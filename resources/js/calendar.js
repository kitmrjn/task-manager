/**
 * calendar.js  →  resources/js/calendar.js
 */

document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    const tasksByDate  = window.CAL_TASKS  || {};
    const eventsByDate = window.CAL_EVENTS || {};

    window.cur                  = new Date();
    let selectedEventColor   = 'blue';
    let selectedEventType    = 'meeting';
    let currentViewEventId   = null;
    let currentViewEventDate = null;
    let currentView          = 'month';

    /* ============================================================
       HOLIDAY COLOR CONFIG
       These map holidayType → CSS class used in cal-event pills.
       The actual colors are defined in calendar.css.
    ============================================================ */
    const HOLIDAY_COLOR_CLASS = {
        'ph-regular':         'ph-regular',         // coral/rose
        'ph-special-nonwork': 'ph-special-nonwork', // deep orange
        'ph-special-work':    'ph-special-work',     // slate teal
        'us-holiday':         'us-holiday',          // neon yellow
    };

    const HOLIDAY_TYPE_LABEL = {
        'ph-regular':         '🇵🇭 Regular Holiday',
        'ph-special-nonwork': '🇵🇭 Special Non-Working',
        'ph-special-work':    '🇵🇭 Special Working',
        'us-holiday':         '🇺🇸 US Holiday',
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
            html += `<div class="list-date-group">
                        <div class="list-date-heading">${new Date(date + 'T00:00:00').toLocaleDateString('default', { weekday:'long', month:'long', day:'numeric', year:'numeric' })}</div>`;

            (window.CAL_EVENTS[date] || []).forEach(ev => {
                const colorClass = ev.holidayType ? HOLIDAY_COLOR_CLASS[ev.holidayType] : (ev.color || 'blue');
                html += `<div class="cal-event ${colorClass}" style="margin-bottom:5px;cursor:pointer;"
                              onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date,holidayType:ev.holidayType}).replace(/"/g,'&quot;')})">
                            <small>${ev.time ? formatTime(ev.time) : 'All Day'}</small> — ${ev.title}
                         </div>`;
            });

            (window.CAL_TASKS[date] || []).forEach(t => {
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

        const events = window.CAL_EVENTS[key] || [];
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
                                 onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date:key,holidayType:ev.holidayType}).replace(/"/g,'&quot;')})">
                                <span class="day-event-time">${formatTime(ev.time)}</span>
                                <span class="day-event-title">${ev.title}</span>
                            </div>`;
        });

        const allDayEvents = events.filter(ev => !ev.time);
        const allDayTasks  = tasks;
        let allDayHtml = '';
        allDayEvents.forEach(ev => {
            const colorClass = ev.holidayType ? HOLIDAY_COLOR_CLASS[ev.holidayType] : (ev.color || 'blue');
            allDayHtml += `<div class="cal-event ${colorClass}" style="cursor:pointer;margin-bottom:3px;"
                                onclick="viewItem(${JSON.stringify({label:ev.title,color:colorClass,type:'event',id:ev.id,time:ev.time,desc:ev.description,etype:ev.type,date:key,holidayType:ev.holidayType}).replace(/"/g,'&quot;')})">${ev.title}</div>`;
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
                const hasItems = (window.CAL_EVENTS[key] && window.CAL_EVENTS[key].length) ||
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
            const events = window.CAL_EVENTS[key] || [];

            const allItems = [
                ...tasks.map(t => {
                    const isOverdue = (key < todayKey) && !t.is_completed;
                    return { label:t.title, color:isOverdue?'red':getPriorityColor(t.priority), done:t.is_completed, type:'task', id:t.id, column:t.column, date:key, isOverdue };
                }),
                ...events.map(e => {
                    // Holiday events get their special color class
                    const colorClass = e.holidayType ? HOLIDAY_COLOR_CLASS[e.holidayType] : (e.color || 'blue');
                    return { label:e.title, color:colorClass, done:false, type:'event', id:e.id, time:e.time, desc:e.description, etype:e.type, date:key, isOverdue:false, holidayType:e.holidayType };
                })
            ];

            const showMax = 3;
            let evtHtml = allItems.slice(0, showMax).map(item => {
                const strike       = item.done ? 'text-decoration:line-through;opacity:.55;' : '';
                const overdueClass = item.isOverdue ? 'overdue' : '';
                return `<div class="cal-event ${item.color} ${overdueClass}"
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
                const events = window.CAL_EVENTS[key] || [];
                const tasks  = window.CAL_TASKS[key]  || [];

                const allItems = [
                    ...tasks.map(t => ({ label: t.title, color: getPriorityColor(t.priority), type: 'task', id: t.id, column: t.column, date: key })),
                    ...events.map(e => {
                        const colorClass = e.holidayType ? HOLIDAY_COLOR_CLASS[e.holidayType] : (e.color || 'blue');
                        return { label: e.title, color: colorClass, type: 'event', id: e.id, time: e.time, desc: e.description, etype: e.type, date: key, holidayType: e.holidayType };
                    })
                ];

                let evtHtml = allItems.slice(0, 2).map(item =>
                    `<div class="cal-event ${item.color}"
                          style="opacity:.7;"
                          onclick="event.stopPropagation();viewItem(${JSON.stringify(item).replace(/"/g,'&quot;')})"
                    >${item.label}</div>`
                ).join('');

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
            deleteBtn.style.display  = 'none';
            currentViewEventId       = null;
            currentViewEventDate     = null;
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
            // Hide delete for holidays (either type=holiday or has a holidayType)
            const isHoliday = item.etype === 'holiday' || !!item.holidayType;
            deleteBtn.style.display = isHoliday ? 'none' : 'flex';

            const typeLabel = item.holidayType
                ? HOLIDAY_TYPE_LABEL[item.holidayType] || 'Holiday'
                : (item.etype === 'meeting' ? 'Meeting' : item.etype === 'reminder' ? 'Reminder' : 'Note');

            bodyHtml = `
                <div class="cal-ev-row">
                    <div class="cal-ev-row-label">Type</div>
                    <div class="cal-ev-row-value">${typeLabel}</div>
                </div>
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
                recurrence, recurrence_until: until || null
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

        /**
         * Fetch from a given endpoint and merge into window.CAL_EVENTS.
         * Each holiday entry will have a `holidayType` field so the
         * renderer knows which CSS class to apply.
         */
        async function fetchAndMerge(endpoint, year) {
            try {
                const res = await fetch(`${endpoint}/${year}`);
                if (!res.ok) return;
                const holidays = await res.json();

                holidays.forEach(h => {
                    if (!window.CAL_EVENTS[h.date]) window.CAL_EVENTS[h.date] = [];

                    // Deduplicate: use holidayType+date as a composite key
                    const uid = `holiday-${h.holidayType}-${h.date}`;
                    const alreadyAdded = window.CAL_EVENTS[h.date].some(e => e.id === uid);
                    if (alreadyAdded) return;

                    window.CAL_EVENTS[h.date].push({
                        id:          uid,
                        title:       h.localName || h.name,
                        color:       HOLIDAY_COLOR_CLASS[h.holidayType] || 'green',
                        holidayType: h.holidayType,
                        type:        'holiday',
                        time:        null,
                        description: h.name + (h.country === 'US' ? ' (US Federal Holiday)' : ''),
                        country:     h.country,
                    });
                });
            } catch (e) {
                console.warn(`Holiday fetch failed for ${endpoint}/${year}:`, e);
            }
        }

        // Philippine holidays (current + next year)
        await fetchAndMerge('/holidays', year);
        await fetchAndMerge('/holidays', nextYear);

        // US holidays (current + next year)
        await fetchAndMerge('/holidays/us', year);
        await fetchAndMerge('/holidays/us', nextYear);

        renderCal();
    }

    startCalendarSync();
    loadHolidays();

}); // end DOMContentLoaded

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