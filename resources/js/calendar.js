/**
 * calendar.js  →  resources/js/calendar.js
 */

// ── MODULE-LEVEL: must be outside DOMContentLoaded so the ?cal= block
//    at the bottom and any inline onclick handlers can reach it ──────
const activeSubCals = { personal: true, team: true, general: true };

document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    const tasksByDate  = window.CAL_TASKS  || {};
    const eventsByDate = window.CAL_EVENTS || {};

    window.cur                = new Date();
    let selectedEventColor    = 'blue';
    let selectedEventType     = 'meeting';
    let selectedCalType       = 'personal';
    let currentViewEventId    = null;
    let currentViewEventDate  = null;
    let currentView           = 'month';

    /* ============================================================
       SUB-CALENDAR TOGGLE
    ============================================================ */
    window.toggleSubCal = function(type, btn) {
        activeSubCals[type] = !activeSubCals[type];
        btn.classList.toggle('active', activeSubCals[type]);
        updateActiveIndicator();
        renderCal();
    };

    function updateActiveIndicator() {
        const dot   = document.getElementById('calActiveDot');
        const label = document.getElementById('calActiveLabel');
        if (!dot || !label) return;

        const colors = { personal: '#7c3aed', team: '#1a8a5a', general: '#2d52c4' };
        const names  = { personal: 'Personal Calendar', team: 'Team Calendar', general: 'General Calendar' };

        const active = Object.entries(activeSubCals).filter(([, v]) => v).map(([k]) => k);

        if (active.length === 0) {
            dot.style.background = 'var(--soft)';
            label.textContent = 'No calendars shown';
        } else if (active.length === 1) {
            dot.style.background = colors[active[0]];
            label.textContent = names[active[0]];
        } else if (active.length === 2) {
            dot.style.background = colors[active[0]];
            label.textContent = active.map(k => names[k].replace(' Calendar', '')).join(' & ') + ' Calendars';
        } else {
            dot.style.background = 'var(--soft)';
            label.textContent = 'Personal · Team · General';
        }
    }

    /* ============================================================
       CALENDAR TYPE PICKER (in Add Event modal)
    ============================================================ */
    window.selectCalType = function(type, btn) {
        selectedCalType = type;
        document.querySelectorAll('.cal-subcal-pick-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    };

    /* ============================================================
       FILTER HELPER — should we show this event?
    ============================================================ */
    function shouldShowEvent(ev) {
        if (ev.type === 'holiday') return true;
        const calType = ev.calendar_type || 'general';
        return activeSubCals[calType] !== false;
    }

    /* ============================================================
       HOLIDAY COLOR CONFIG
    ============================================================ */
    const HOLIDAY_COLOR_CLASS = {
        'ph-regular':         'ph-regular',
        'ph-special-nonwork': 'ph-special-nonwork',
        'ph-special-work':    'ph-special-work',
        'us-holiday':         'us-holiday',
    };

    const HOLIDAY_TYPE_LABEL = {
        'ph-regular':         '🇵🇭 Regular Holiday',
        'ph-special-nonwork': '🇵🇭 Special Non-Working',
        'ph-special-work':    '🇵🇭 Special Working',
        'us-holiday':         '🇺🇸 US Holiday',
    };

    const CAL_TYPE_LABEL = {
        personal: '🔵 Personal',
        team:     '🟢 Team',
        general:  '🟣 General',
    };

    /* ============================================================
       VIEW SWITCHER
    ============================================================ */
    window.changeView = function(view, btn) {
        currentView = view;
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderCal();
    };

    window.toggleRecurrenceEnd = function() {
        const val = document.getElementById('ev-recurrence').value;
        document.getElementById('recurrence-end-wrap').style.display = (val === 'none') ? 'none' : 'block';
    };

    window.updateRecurrenceOptions = function() {
        const dateVal = document.getElementById('ev-date').value;
        if (!dateVal) return;

        const date     = new Date(dateVal + 'T00:00:00');
        const DAYS     = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const MONTHS   = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const ORDINALS = ['first','second','third','fourth','fifth'];

        const dayName    = DAYS[date.getDay()];
        const monthName  = MONTHS[date.getMonth()];
        const dayOfMonth = date.getDate();
        const weekNum    = Math.ceil(dayOfMonth / 7);
        const ordinal    = ORDINALS[weekNum - 1] || 'last';

        const weeklyOpt  = document.getElementById('opt-weekly');
        const monthlyOpt = document.getElementById('opt-monthly');
        const yearlyOpt  = document.getElementById('opt-yearly');

        if (weeklyOpt)  weeklyOpt.textContent  = `Weekly on ${dayName}`;
        if (monthlyOpt) monthlyOpt.textContent = `Monthly on the ${ordinal} ${dayName}`;
        if (yearlyOpt)  yearlyOpt.textContent  = `Annually on ${monthName} ${dayOfMonth}`;
    };

    /* ============================================================
       HELPERS
    ============================================================ */
    function getPriorityColor(priority) {
        if (priority === 'high')   return 'red';
        if (priority === 'medium') return 'amber';
        return 'blue';
    }

    function formatTime(timeStr) {
        if (!timeStr) return '';
        const [h, m] = timeStr.split(':');
        const hour   = parseInt(h);
        const ampm   = hour >= 12 ? 'PM' : 'AM';
        const h12    = hour % 12 || 12;
        return `${h12}:${m} ${ampm}`;
    }

    function isoDate(y, m, d) {
        return `${y}-${String(m + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    }

    function calTypeClass(ev) {
        if (ev.type === 'holiday' || ev.holidayType) return '';
        return `cal-${ev.calendar_type || 'general'}`;
    }

    /* ============================================================
       LIST VIEW
    ============================================================ */
    function renderListView() {
        const container = document.getElementById('calCells');
        container.style.display = 'block';
        let html = '<div class="cal-list-view">';

        const allDates = [...new Set([...Object.keys(window.CAL_EVENTS), ...Object.keys(window.CAL_TASKS)])].sort();

        if (allDates.length === 0) {
            html += '<div style="text-align:center;padding:2rem;color:var(--soft);">No events scheduled</div>';
        }

        allDates.forEach(date => {
            const filteredEvents = (window.CAL_EVENTS[date] || []).filter(shouldShowEvent);
            const tasks = window.CAL_TASKS[date] || [];
            if (!filteredEvents.length && !tasks.length) return;

            html += `<div class="list-date-group">
                        <div class="list-date-heading">${new Date(date + 'T00:00:00').toLocaleDateString('default', { weekday:'long', month:'long', day:'numeric', year:'numeric' })}</div>`;

            filteredEvents.forEach(ev => {
                const colorClass = ev.holidayType ? HOLIDAY_COLOR_CLASS[ev.holidayType] : (ev.color || 'blue');
                const ctClass    = calTypeClass(ev);
                html += `<div class="cal-event ${colorClass} ${ctClass}" style="margin-bottom:5px;cursor:pointer;"
                              onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date,holidayType:ev.holidayType,calendarType:ev.calendar_type,isMine:ev.is_mine}).replace(/"/g,'&quot;')})">
                            <small>${ev.time ? formatTime(ev.time) : 'All Day'}</small> — ${ev.title}
                         </div>`;
            });

            tasks.forEach(t => {
                html += `<div class="cal-event ${getPriorityColor(t.priority)}" style="margin-bottom:5px;opacity:.8;">
                            <small>Task</small> — ${t.title} ${t.is_completed ? '✓' : ''}
                         </div>`;
            });

            html += '</div>';
        });

        container.innerHTML = html + '</div>';
    }

    /* ============================================================
       DAY VIEW
    ============================================================ */
    function renderDayView() {
        const container = document.getElementById('calCells');
        container.style.display = 'block';

        const y   = cur.getFullYear();
        const m   = cur.getMonth();
        const d   = cur.getDate();
        const key = isoDate(y, m, d);

        const events = (window.CAL_EVENTS[key] || []).filter(shouldShowEvent);
        const tasks  = window.CAL_TASKS[key]  || [];

        const HOUR_H = 56;

        let timeRows = '';
        for (let h = 0; h < 24; h++) {
            const label = h === 0 ? '12 AM' : h < 12 ? `${h} AM` : h === 12 ? '12 PM' : `${h - 12} PM`;
            timeRows += `<div class="day-row" style="height:${HOUR_H}px;">
                            <div class="day-hour-label">${label}</div>
                            <div class="day-hour-col"></div>
                         </div>`;
        }

        let eventBlocks = '';
        events.forEach(ev => {
            if (!ev.time) return;
            const [eh, em] = ev.time.split(':').map(Number);
            const top        = (eh + em / 60) * HOUR_H;
            const colorClass = ev.holidayType ? HOLIDAY_COLOR_CLASS[ev.holidayType] : (ev.color || 'blue');
            eventBlocks += `<div class="day-event-block ${colorClass}"
                                 style="top:${top}px;"
                                 onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date:key,holidayType:ev.holidayType,calendarType:ev.calendar_type,isMine:ev.is_mine}).replace(/"/g,'&quot;')})">
                                <span class="day-event-time">${formatTime(ev.time)}</span>
                                <span class="day-event-title">${ev.title}</span>
                            </div>`;
        });

        const allDayEvents = events.filter(ev => !ev.time);
        const allDayTasks  = tasks;
        let allDayHtml = '';
        allDayEvents.forEach(ev => {
            const colorClass = ev.holidayType ? HOLIDAY_COLOR_CLASS[ev.holidayType] : (ev.color || 'blue');
            const ctClass    = calTypeClass(ev);
            allDayHtml += `<div class="cal-event ${colorClass} ${ctClass}" style="cursor:pointer;margin-bottom:3px;"
                                onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date:key,holidayType:ev.holidayType,calendarType:ev.calendar_type,isMine:ev.is_mine}).replace(/"/g,'&quot;')})">${ev.title}</div>`;
        });
        allDayTasks.forEach(t => {
            const c = getPriorityColor(t.priority);
            allDayHtml += `<div class="cal-event ${c}" style="margin-bottom:3px;opacity:.85;">${t.title}${t.is_completed ? ' ✓' : ''}</div>`;
        });

        container.innerHTML = `
            <div class="day-view-wrap">
                ${allDayHtml ? `<div class="day-allday-strip"><div class="day-allday-label">All day</div><div class="day-allday-events">${allDayHtml}</div></div>` : ''}
                <div class="day-scroll-area">
                    <div class="day-grid">${timeRows}</div>
                    <div class="day-events-layer">${eventBlocks}</div>
                </div>
            </div>`;

        const scrollArea = container.querySelector('.day-scroll-area');
        if (scrollArea) scrollArea.scrollTop = 7 * HOUR_H;
    }

    /* ============================================================
       YEAR VIEW
    ============================================================ */
    function renderYearView() {
        const container = document.getElementById('calCells');
        container.style.display = 'block';

        const y     = cur.getFullYear();
        const today = new Date();
        const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const DAYS   = ['S','M','T','W','T','F','S'];

        let html = '<div class="year-grid">';

        for (let mo = 0; mo < 12; mo++) {
            const firstDay    = new Date(y, mo, 1).getDay();
            const daysInMonth = new Date(y, mo + 1, 0).getDate();

            let miniCells = '';
            DAYS.forEach(d => { miniCells += `<div class="mini-dow">${d}</div>`; });
            for (let i = 0; i < firstDay; i++) miniCells += '<div class="mini-cell"></div>';
            for (let d = 1; d <= daysInMonth; d++) {
                const key      = isoDate(y, mo, d);
                const hasItems = ((window.CAL_EVENTS[key] || []).filter(shouldShowEvent).length) ||
                                 (window.CAL_TASKS[key]  && window.CAL_TASKS[key].length);
                const isToday  = y === today.getFullYear() && mo === today.getMonth() && d === today.getDate();
                miniCells += `<div class="mini-cell${isToday ? ' mini-today' : ''}${hasItems ? ' mini-has-event' : ''}"
                                   onclick="jumpToDay(${y},${mo},${d})">${d}</div>`;
            }

            html += `<div class="mini-month">
                        <div class="mini-month-label">${MONTHS[mo]}</div>
                        <div class="mini-grid">${miniCells}</div>
                     </div>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    window.jumpToDay = function(y, m, d) {
        cur = new Date(y, m, d);
        currentView = 'day';
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        const dayBtn = document.querySelector('.view-btn[data-view="day"]');
        if (dayBtn) dayBtn.classList.add('active');
        renderCal();
    };

    /* ============================================================
       MONTH VIEW
    ============================================================ */
    function renderMonthView() {
        const y     = cur.getFullYear();
        const m     = cur.getMonth();
        const today = new Date();
        const todayKey = today.toISOString().split('T')[0];

        document.getElementById('calMonthLabel').textContent =
            cur.toLocaleString('default', { month: 'long', year: 'numeric' });

        const first = new Date(y, m, 1).getDay();
        const days  = new Date(y, m + 1, 0).getDate();
        let html    = '';

        for (let i = 0; i < first; i++) {
            const d = new Date(y, m, 0 - first + i + 2).getDate();
            html += `<div class="cal-cell other"><div class="cal-day">${d}</div></div>`;
        }

        for (let d = 1; d <= days; d++) {
            const isToday = y === today.getFullYear() && m === today.getMonth() && d === today.getDate();
            const key     = isoDate(y, m, d);

            const tasks  = window.CAL_TASKS[key]  || [];
            const events = (window.CAL_EVENTS[key] || []).filter(shouldShowEvent);

            const allItems = [
                ...tasks.map(t => {
                    const isOverdue = (key < todayKey) && !t.is_completed;
                    return { label:t.title, color:isOverdue?'red':getPriorityColor(t.priority), done:t.is_completed, type:'task', id:t.id, column:t.column, date:key, isOverdue };
                }),
                ...events.map(e => {
                    const colorClass = e.holidayType ? HOLIDAY_COLOR_CLASS[e.holidayType] : (e.color || 'blue');
                    return { label:e.title, color:colorClass, done:false, type:'event', id:e.id, time:e.time, desc:e.description, etype:e.type, date:key, isOverdue:false, holidayType:e.holidayType, calendarType:e.calendar_type, isMine:e.is_mine };
                })
            ];

            const showMax = 3;
            let evtHtml = allItems.slice(0, showMax).map(item => {
                const strike       = item.done ? 'text-decoration:line-through;opacity:.55;' : '';
                const overdueClass = item.isOverdue ? 'overdue' : '';
                const ctClass      = item.type === 'event' ? `cal-${item.calendarType || 'general'}` : '';
                return `<div class="cal-event ${item.color} ${overdueClass} ${ctClass}"
                             style="${strike}"
                             title="${item.label}${item.isOverdue ? ' (Overdue)' : ''}"
                             onclick="event.stopPropagation();viewItem(${JSON.stringify(item).replace(/"/g,'&quot;')})"
                        >${item.label}</div>`;
            }).join('');

            if (allItems.length > showMax) {
                evtHtml += `<div class="cal-event" style="background:#f0f2f6;color:var(--soft);cursor:pointer;"
                                 onclick="event.stopPropagation();openAddEvent('${key}')">+${allItems.length - showMax} more</div>`;
            }

            html += `<div class="cal-cell${isToday ? ' today' : ''}" onclick="openAddEvent('${key}')">
                        ${evtHtml}
                        <div class="cal-day">${d}</div>
                     </div>`;
        }

        const rem = 7 - ((first + days) % 7);
        if (rem < 7) {
            const nextMonth = m === 11 ? 0 : m + 1;
            const nextYear  = m === 11 ? y + 1 : y;

            for (let i = 1; i <= rem; i++) {
                const key    = isoDate(nextYear, nextMonth, i);
                const events = (window.CAL_EVENTS[key] || []).filter(shouldShowEvent);
                const tasks  = window.CAL_TASKS[key]  || [];

                const allItems = [
                    ...tasks.map(t => ({ label: t.title, color: getPriorityColor(t.priority), type: 'task', id: t.id, column: t.column, date: key })),
                    ...events.map(e => {
                        const colorClass = e.holidayType ? HOLIDAY_COLOR_CLASS[e.holidayType] : (e.color || 'blue');
                        return { label: e.title, color: colorClass, type: 'event', id: e.id, time: e.time, desc: e.description, etype: e.type, date: key, holidayType: e.holidayType, calendarType: e.calendar_type, isMine: e.is_mine };
                    })
                ];

                let evtHtml = allItems.slice(0, 2).map(item => {
                    const ctClass = item.type === 'event' ? `cal-${item.calendarType || 'general'}` : '';
                    return `<div class="cal-event ${item.color} ${ctClass}"
                                  style="opacity:.7;"
                                  onclick="event.stopPropagation();viewItem(${JSON.stringify(item).replace(/"/g,'&quot;')})"
                            >${item.label}</div>`;
                }).join('');

                html += `<div class="cal-cell other" onclick="openAddEvent('${key}')">
                            ${evtHtml}
                            <div class="cal-day">${i}</div>
                         </div>`;
            }
        }

        const container = document.getElementById('calCells');
        container.style.display = 'grid';
        container.innerHTML = html;
    }

    /* ============================================================
       MASTER RENDER
    ============================================================ */
    window.renderCal = function() {
        const dowRow = document.getElementById('calDowRow');
        if (dowRow) dowRow.style.display = currentView === 'month' ? '' : 'none';

        const labelMap = {
            month: cur.toLocaleString('default', { month:'long', year:'numeric' }),
            list:  cur.toLocaleString('default', { month:'long', year:'numeric' }),
            day:   cur.toLocaleDateString('default', { weekday:'long', month:'long', day:'numeric', year:'numeric' }),
            year:  String(cur.getFullYear()),
        };
        document.getElementById('calMonthLabel').textContent = labelMap[currentView] || labelMap.month;

        if (currentView === 'list') { renderListView(); return; }
        if (currentView === 'day')  { renderDayView();  return; }
        if (currentView === 'year') { renderYearView(); return; }
        renderMonthView();
    };

    /* ============================================================
       NAVIGATION
    ============================================================ */
    window.calPrev = function() {
        if (currentView === 'day')        { cur.setDate(cur.getDate() - 1); }
        else if (currentView === 'year')  { cur.setFullYear(cur.getFullYear() - 1); }
        else                              { cur.setMonth(cur.getMonth() - 1); }
        renderCal();
    };

    window.calNext = function() {
        if (currentView === 'day')        { cur.setDate(cur.getDate() + 1); }
        else if (currentView === 'year')  { cur.setFullYear(cur.getFullYear() + 1); }
        else                              { cur.setMonth(cur.getMonth() + 1); }
        renderCal();
    };

    /* ============================================================
       VIEW ITEM MODAL
    ============================================================ */
    window.viewItem = function(item) {
        document.getElementById('view-ev-title').textContent = item.label;
        const deleteBtn = document.getElementById('view-ev-delete');
        let bodyHtml = '';

        if (item.type === 'task') {
            deleteBtn.style.display = 'none';
            currentViewEventId      = null;
            currentViewEventDate    = null;
            bodyHtml = `
                <div class="cal-ev-row">
                    <div class="cal-ev-row-label">Type</div>
                    <div class="cal-ev-row-value">Task</div>
                </div>
                ${item.column ? `<div class="cal-ev-row"><div class="cal-ev-row-label">Status</div><div class="cal-ev-row-value">${item.column}</div></div>` : ''}
                <div style="font-size:14px;color:var(--muted);background:var(--surface);padding:.9rem 1.1rem;border-radius:10px;font-weight:500;border:1px solid var(--border);">
                    This task comes from your Task board. Open Tasks to edit it.
                </div>`;
        } else {
            currentViewEventId   = item.id;
            currentViewEventDate = item.date;
            const isHoliday = item.etype === 'holiday' || !!item.holidayType;
            deleteBtn.style.display = (isHoliday || !item.isMine) ? 'none' : 'flex';

            const typeLabel = item.holidayType
                ? HOLIDAY_TYPE_LABEL[item.holidayType] || 'Holiday'
                : (item.etype === 'meeting' ? 'Meeting' : item.etype === 'reminder' ? 'Reminder' : 'Note');

            const calLabel = !isHoliday && item.calendarType
                ? CAL_TYPE_LABEL[item.calendarType] || ''
                : '';

            bodyHtml = `
                <div class="cal-ev-row">
                    <div class="cal-ev-row-label">Type</div>
                    <div class="cal-ev-row-value">${typeLabel}</div>
                </div>
                ${calLabel ? `<div class="cal-ev-row"><div class="cal-ev-row-label">Calendar</div><div class="cal-ev-row-value">${calLabel}</div></div>` : ''}
                ${item.time ? `<div class="cal-ev-row"><div class="cal-ev-row-label">Time</div><div class="cal-ev-row-value">${formatTime(item.time)}</div></div>` : ''}
                ${item.desc ? `<div class="cal-ev-row"><div class="cal-ev-row-label">Notes</div><div class="cal-ev-row-value" style="font-weight:500;color:var(--muted);">${item.desc}</div></div>` : ''}`;
        }

        document.getElementById('view-ev-body').innerHTML = bodyHtml;
        document.getElementById('viewEventModal').classList.add('open');
    };

    window.closeViewModal = function() {
        document.getElementById('viewEventModal').classList.remove('open');
        currentViewEventId   = null;
        currentViewEventDate = null;
    };

    /* ============================================================
       DELETE EVENT
    ============================================================ */
    window.deleteEvent = function() {
        if (!currentViewEventId) return;

        Swal.fire({
            title: 'Delete this event?',
            text: "This action cannot be undone!",
            icon: 'warning',
            width: '380px',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            background: '#ffffff',
            customClass: { popup:'tk-rounded-modal', confirmButton:'tk-swal-btn', cancelButton:'tk-swal-btn' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/calendar/events', {
                    method: 'DELETE',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
                    body: JSON.stringify({ id: currentViewEventId }),
                })
                .then(r => r.json())
                .then(data => { if (data.success) window.location.reload(); });
            }
        });
    };

    /* ============================================================
       ADD EVENT MODAL
    ============================================================ */
    window.openAddEvent = function(dateKey) {
        document.getElementById('ev-title').value = '';
        document.getElementById('ev-date').value  = dateKey;
        document.getElementById('ev-time').value  = '';
        document.getElementById('ev-desc').value  = '';
        if (document.getElementById('ev-recurrence')) document.getElementById('ev-recurrence').value = 'none';
        if (typeof toggleRecurrenceEnd === 'function') toggleRecurrenceEnd();
        updateRecurrenceOptions();
        selectEventType('meeting');
        selectEventColor('blue');
        selectedCalType = 'personal';
        document.querySelectorAll('.cal-subcal-pick-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.type === 'personal');
        });
        document.getElementById('addEventModal').classList.add('open');
    };

    window.closeEventModal = function() {
        document.getElementById('addEventModal').classList.remove('open');
    };

    window.selectEventType = function(type) {
        selectedEventType = type;
        ['meeting','note','reminder'].forEach(t => {
            const btn = document.getElementById(`ev-type-btn-${t}`);
            if (btn) btn.classList.toggle('active', t === type);
        });
    };

    window.selectEventColor = function(key) {
        selectedEventColor = key;
        ['blue','green','red','amber','purple'].forEach(k => {
            const dot = document.getElementById(`ev-color-${k}`);
            if (dot) dot.classList.toggle('selected', k === key);
        });
    };

    window.saveEvent = function() {
        const title      = document.getElementById('ev-title').value.trim();
        const date       = document.getElementById('ev-date').value;
        const time       = document.getElementById('ev-time').value;
        const desc       = document.getElementById('ev-desc').value.trim();
        const recurrence = document.getElementById('ev-recurrence')?.value || 'none';
        const until      = document.getElementById('ev-until')?.value;

        if (!title || !date) { alert('Title and date are required!'); return; }
        if (recurrence !== 'none' && !until) { alert('Please select an "Until" date.'); return; }

        const saveBtn = document.querySelector('#addEventModal .cal-btn-primary');
        saveBtn.textContent = 'Saving…'; saveBtn.disabled = true;

        fetch('/calendar/events', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
            body: JSON.stringify({
                title, date, time: time || null, description: desc || null,
                type: selectedEventType, color: selectedEventColor,
                recurrence, recurrence_until: until || null,
                calendar_type: selectedCalType,
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { closeEventModal(); window.location.reload(); }
            else { throw new Error('Save failed'); }
        })
        .catch(() => { saveBtn.textContent = 'Save Event'; saveBtn.disabled = false; });
    };

    /* ============================================================
       MODAL BACKDROP + KEYBOARD
    ============================================================ */
    document.getElementById('addEventModal').addEventListener('click', e => {
        if (e.target === document.getElementById('addEventModal')) closeEventModal();
    });
    document.getElementById('viewEventModal').addEventListener('click', e => {
        if (e.target === document.getElementById('viewEventModal')) closeViewModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeEventModal(); closeViewModal(); }
    });

    /* ============================================================
       BOOT
    ============================================================ */
    async function loadHolidays() {
        const year     = cur.getFullYear();
        const nextYear = year + 1;

        async function fetchAndMerge(endpoint, year) {
            try {
                const res = await fetch(`${endpoint}/${year}`);
                if (!res.ok) return;
                const holidays = await res.json();

                holidays.forEach(h => {
                    if (!window.CAL_EVENTS[h.date]) window.CAL_EVENTS[h.date] = [];
                    const uid = `holiday-${h.holidayType}-${h.date}`;
                    const alreadyAdded = window.CAL_EVENTS[h.date].some(e => e.id === uid);
                    if (alreadyAdded) return;

                    window.CAL_EVENTS[h.date].push({
                        id:            uid,
                        title:         h.localName || h.name,
                        color:         HOLIDAY_COLOR_CLASS[h.holidayType] || 'green',
                        holidayType:   h.holidayType,
                        type:          'holiday',
                        time:          null,
                        description:   h.name + (h.country === 'US' ? ' (US Federal Holiday)' : ''),
                        country:       h.country,
                        calendar_type: null,
                    });
                });
            } catch (e) {
                console.warn(`Holiday fetch failed for ${endpoint}/${year}:`, e);
            }
        }

        await fetchAndMerge('/holidays', year);
        await fetchAndMerge('/holidays', nextYear);
        await fetchAndMerge('/holidays/us', year);
        await fetchAndMerge('/holidays/us', nextYear);

        renderCal();
    }

    startCalendarSync();
    loadHolidays();

}); // end DOMContentLoaded


/* ============================================================
   ?cal= URL PARAM — wrapped in DOMContentLoaded so the DOM
   and activeSubCals helpers are ready
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    const calParam = new URLSearchParams(window.location.search).get('cal');
    if (calParam && activeSubCals.hasOwnProperty(calParam)) {
        Object.keys(activeSubCals).forEach(k => activeSubCals[k] = false);
        activeSubCals[calParam] = true;
        document.querySelectorAll('.cal-subcal-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.cal === calParam);
        });
        // updateActiveIndicator is defined inside the first DOMContentLoaded,
        // so we inline the dot update here instead
        const dot = document.getElementById('calActiveDot');
        if (dot) {
            const colors = { personal: '#7c3aed', team: '#1a8a5a', general: '#2d52c4' };
            dot.style.background = colors[calParam] || 'var(--soft)';
        }
    }
});


/* ============================================================
   TOPNAV DROPDOWNS
============================================================ */
(function initTopnavDropdowns() {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function bindDropdown(btnId, dropId, chevId = null) {
        const btn  = document.getElementById(btnId);
        const drop = document.getElementById(dropId);
        if (!btn || !drop) return;

        btn.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = drop.classList.contains('open');
            document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
            document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');

            if (!isOpen) {
                drop.classList.add('open');
                if (chevId) {
                    const chev = document.getElementById(chevId);
                    if (chev) chev.style.transform = 'rotate(180deg)';
                }
                if (btnId === 'profile-btn') btn.closest('.tk-nav-profile')?.classList.add('active');
                if (btnId === 'notif-btn') loadNotifications(drop);
            }
        });
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
        document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');
    });

    bindDropdown('notif-btn',   'notif-dropdown');
    bindDropdown('profile-btn', 'profile-dropdown', 'profile-chevron');

    async function loadNotifications(drop) {
        const list  = drop.querySelector('#notif-list');
        const count = drop.querySelector('#notif-count');
        if (!list) return;
        list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13px;color:var(--soft);">Loading…</div>';
        try {
            const res  = await fetch('/notifications', { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
            const data = await res.json();
            if (count) count.textContent = data.length ? data.length + ' new' : '';
            if (!data.length) {
                list.innerHTML = `<div style="padding:1.5rem;text-align:center;font-size:13.5px;color:var(--soft);">You're all caught up! 🎉</div>`;
                return;
            }
            const iconMap = { comment:'💬', created:'✅', priority_change:'🔥', lead_change:'👤', column_change:'📋', completed:'🎉', checklist_added:'☑️' };
            list.innerHTML = data.map(n => `
                <div class="tk-notif-item">
                    <div class="tk-notif-icon">${iconMap[n.action] || '🔔'}</div>
                    <div class="tk-notif-content">
                        <div class="tk-notif-text"><strong>${n.user || 'Someone'}</strong> ${n.description}${n.task ? ` on <em>${n.task}</em>` : ''}</div>
                        <div class="tk-notif-time">${n.time}</div>
                    </div>
                </div>`).join('');
        } catch(e) {
            list.innerHTML = '<div style="padding:1rem;text-align:center;font-size:13px;color:var(--red);">Failed to load.</div>';
        }
    }
})();


/* ================================================================
   CALENDAR AUTO-SYNC
================================================================ */
function startCalendarSync() {
    setInterval(async () => {
        if (document.getElementById('addEventModal').classList.contains('open') ||
            document.getElementById('viewEventModal').classList.contains('open')) return;

        try {
            const res = await fetch(window.location.href, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const html = await res.text();
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            newDoc.querySelectorAll('script').forEach(script => {
                if (script.innerText.includes('window.CAL_EVENTS')) {
                    const eventMatch = script.innerText.match(/window\.CAL_EVENTS\s*=\s*({.*?});/);
                    const taskMatch  = script.innerText.match(/window\.CAL_TASKS\s*=\s*({.*?});/);
                    if (eventMatch) {
                        const freshEvents = JSON.parse(eventMatch[1]);

                        // Preserve holidays
                        const holidays = {};
                        Object.entries(window.CAL_EVENTS).forEach(([date, evts]) => {
                            const h = evts.filter(e => e.type === 'holiday');
                            if (h.length) holidays[date] = h;
                        });

                        window.CAL_EVENTS = freshEvents;

                        Object.entries(holidays).forEach(([date, evts]) => {
                            if (!window.CAL_EVENTS[date]) window.CAL_EVENTS[date] = [];
                            evts.forEach(h => {
                                const alreadyThere = window.CAL_EVENTS[date].some(e => e.id === h.id);
                                if (!alreadyThere) window.CAL_EVENTS[date].push(h);
                            });
                        });
                    }
                    if (taskMatch) window.CAL_TASKS = JSON.parse(taskMatch[1]);
                }
            });
            renderCal();
        } catch (e) {
            console.error('Calendar sync error:', e);
        }
    }, 10000);
}


/* ================================================================
   MEMO FUNCTIONALITY
================================================================ */
document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    let memoAudienceType = 'all';
    let selectedUserId   = null;
    let allUsers         = [];
    let allCampaigns     = [];

    /* ============================================================
       SIDEBAR TAB SWITCHING
    ============================================================ */
    window.switchSidebarTab = function(tab) {
        document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.sidebar-panel').forEach(p => p.style.display = 'none');

        document.getElementById(`tab-${tab}`).classList.add('active');
        document.getElementById(`panel-${tab}`).style.display = 'flex';
        document.getElementById(`panel-${tab}`).style.flexDirection = 'column';

        if (tab === 'memos') loadMemos();
    };

    /* ============================================================
       LOAD MEMOS
    ============================================================ */
    async function loadMemos() {
        const list = document.getElementById('memoList');
        if (!list) return;

        try {
            const res   = await fetch('/memos', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
            const memos = await res.json();

            updateUnreadBadge(memos);

            if (!memos.length) {
                list.innerHTML = `<div style="padding:2rem 1.4rem;text-align:center;color:var(--soft);font-size:14px;font-weight:500;">No memos yet 📋</div>`;
                return;
            }

            list.innerHTML = memos.map(m => renderMemoItem(m)).join('');

        } catch (e) {
            list.innerHTML = `<div style="padding:1rem;text-align:center;color:var(--red);font-size:13px;">Failed to load memos.</div>`;
        }
    }

    function renderMemoItem(m) {
        const readBtn = !m.is_read
            ? `<button class="memo-read-btn" onclick="markMemoRead(${m.id}, this)">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                Mark as read
               </button>`
            : `<span style="font-size:11px;color:var(--soft);font-weight:600;display:flex;align-items:center;gap:.3rem;">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                Read
               </span>`;

        const deleteBtn = m.can_delete
            ? `<button class="memo-delete-btn" onclick="deleteMemo(${m.id}, this)" title="Delete">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
               </button>`
            : '';

        return `
            <div class="memo-item ${m.is_read ? 'is-read' : ''}" id="memo-item-${m.id}">
                <div class="memo-item-header">
                    <div class="memo-item-title">${escHtml(m.title)}</div>
                    ${!m.is_read ? '<div class="memo-unread-dot"></div>' : ''}
                </div>
                <div class="memo-item-content">${escHtml(m.content)}</div>
                <div class="memo-item-meta">
                    <span class="memo-item-info">By ${escHtml(m.creator)} · ${m.created_at}</span>
                    <span class="memo-item-audience">${escHtml(m.audience)}</span>
                </div>
                <div class="memo-item-actions">
                    ${readBtn}
                    ${deleteBtn}
                </div>
            </div>`;
    }

    function updateUnreadBadge(memos) {
        const badge = document.getElementById('memoUnreadBadge');
        if (!badge) return;
        const unread = memos.filter(m => !m.is_read).length;
        if (unread > 0) {
            badge.textContent = unread;
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    }

    /* ============================================================
       MARK AS READ
    ============================================================ */
    window.markMemoRead = async function(memoId, btn) {
        btn.disabled = true;
        try {
            await fetch(`/memos/${memoId}/read`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });

            const item = document.getElementById(`memo-item-${memoId}`);
            if (item) {
                item.classList.add('is-read');
                const readSpan = document.createElement('span');
                readSpan.style.cssText = 'font-size:11px;color:var(--soft);font-weight:600;display:flex;align-items:center;gap:.3rem;';
                readSpan.innerHTML = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Read`;
                btn.replaceWith(readSpan);
                const dot = item.querySelector('.memo-unread-dot');
                if (dot) dot.remove();
            }

            const badge = document.getElementById('memoUnreadBadge');
            if (badge) {
                const newCount = (parseInt(badge.textContent) || 0) - 1;
                if (newCount <= 0) { badge.style.display = 'none'; }
                else { badge.textContent = newCount; }
            }
        } catch (e) {
            btn.disabled = false;
        }
    };

    /* ============================================================
       DELETE MEMO
    ============================================================ */
    window.deleteMemo = async function(memoId, btn) {
        if (!confirm('Delete this memo?')) return;
        btn.disabled = true;
        try {
            await fetch(`/memos/${memoId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });

            const item = document.getElementById(`memo-item-${memoId}`);
            if (item) {
                item.style.transition = 'opacity .25s, transform .25s';
                item.style.opacity    = '0';
                item.style.transform  = 'translateX(20px)';
                setTimeout(() => item.remove(), 260);
            }
        } catch (e) {
            btn.disabled = false;
        }
    };

    /* ============================================================
       CREATE MEMO MODAL
    ============================================================ */
    window.openMemoModal = async function() {
        document.getElementById('memo-title').value   = '';
        document.getElementById('memo-content').value = '';
        selectedUserId   = null;
        memoAudienceType = 'all';
        document.querySelectorAll('.memo-aud-btn').forEach(b => b.classList.toggle('active', b.dataset.aud === 'all'));
        document.getElementById('memo-campaign-wrap').style.display = 'none';
        document.getElementById('memo-user-wrap').style.display     = 'none';

        if (!allCampaigns.length) {
            try {
                const res  = await fetch('/memos/audience-options', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                allCampaigns = data.campaigns || [];
                allUsers     = data.users     || [];

                const sel = document.getElementById('memo-campaign-id');
                sel.innerHTML = '<option value="">Select campaign…</option>';
                allCampaigns.forEach(c => {
                    sel.innerHTML += `<option value="${c.id}">${escHtml(c.name)}</option>`;
                });
            } catch (e) {
                console.error('Failed to load audience options', e);
            }
        }

        document.getElementById('memoModal').classList.add('open');
    };

    window.closeMemoModal = function() {
        document.getElementById('memoModal').classList.remove('open');
    };

    window.selectAudience = function(type, btn) {
        memoAudienceType = type;
        document.querySelectorAll('.memo-aud-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.getElementById('memo-campaign-wrap').style.display = type === 'campaign' ? 'block' : 'none';
        document.getElementById('memo-user-wrap').style.display     = type === 'user'     ? 'block' : 'none';

        if (type === 'user') renderUserList('');
    };

    function renderUserList(filter) {
        const list = document.getElementById('memo-user-list');
        if (!list) return;

        const filtered = filter
            ? allUsers.filter(u => u.name.toLowerCase().includes(filter.toLowerCase()))
            : allUsers;

        if (!filtered.length) {
            list.innerHTML = `<div style="padding:.75rem 1rem;font-size:13px;color:var(--soft);">No users found</div>`;
            return;
        }

        const campaignMap = {};
        filtered.forEach(u => {
            const campName = allCampaigns.find(c => c.id === u.campaign_id)?.name || 'No Campaign';
            if (!campaignMap[campName]) campaignMap[campName] = [];
            campaignMap[campName].push(u);
        });

        let html = '';
        Object.entries(campaignMap).sort().forEach(([campName, users]) => {
            html += `<div style="padding:.3rem .85rem .1rem;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--soft);background:var(--surface);border-bottom:1px solid var(--rule);">${escHtml(campName)}</div>`;
            users.forEach(u => {
                const initials = u.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                html += `<div class="memo-user-option ${selectedUserId === u.id ? 'selected' : ''}" onclick="selectMemoUser(${u.id}, this)">
                    <div class="memo-user-avatar">${initials}</div>
                    ${escHtml(u.name)}
                </div>`;
            });
        });

        list.innerHTML = html;
    }

    window.filterMemoUsers = function(val) {
        renderUserList(val);
    };

    window.selectMemoUser = function(userId, el) {
        selectedUserId = userId;
        document.querySelectorAll('.memo-user-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
    };

    window.saveMemo = async function() {
        const title   = document.getElementById('memo-title').value.trim();
        const content = document.getElementById('memo-content').value.trim();

        if (!title || !content) { alert('Title and message are required!'); return; }

        let targetId = null;
        if (memoAudienceType === 'campaign') {
            targetId = document.getElementById('memo-campaign-id').value;
            if (!targetId) { alert('Please select a campaign.'); return; }
        } else if (memoAudienceType === 'user') {
            if (!selectedUserId) { alert('Please select a person.'); return; }
            targetId = selectedUserId;
        }

        const saveBtn = document.querySelector('#memoModal .cal-btn-primary');
        saveBtn.textContent = 'Sending…';
        saveBtn.disabled    = true;

        try {
            const res = await fetch('/memos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ title, content, target_type: memoAudienceType, target_id: targetId })
            });
            const data = await res.json();
            if (data.success) {
                closeMemoModal();
                loadMemos();
            } else {
                throw new Error('Failed');
            }
        } catch (e) {
            saveBtn.textContent = 'Send Memo';
            saveBtn.disabled    = false;
        }
    };

    /* ============================================================
       HELPERS
    ============================================================ */
    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    const memoModal = document.getElementById('memoModal');
    if (memoModal) {
        memoModal.addEventListener('click', e => {
            if (e.target === memoModal) closeMemoModal();
        });
    }

    // Load unread badge on page load
    (async function initMemoBadge() {
        try {
            const res   = await fetch('/memos', { headers: { 'Accept': 'application/json' } });
            const memos = await res.json();
            updateUnreadBadge(memos);
        } catch (e) {}
    })();

}); // end memo DOMContentLoaded