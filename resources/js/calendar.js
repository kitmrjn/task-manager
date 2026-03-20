/**
 * calendar.js  →  resources/js/calendar.js
 *
 * NOTE: tasksByDate and eventsByDate are injected by the blade as
 * window.CAL_TASKS and window.CAL_EVENTS before this script runs.
 * See calendar.blade.php @push('scripts') block.
 *
 * Handles:
 *   - renderCal()      — build the month grid
 *   - calPrev/calNext  — month navigation
 *   - viewItem()       — open view modal for event or task
 *   - openAddEvent()   — open add event modal pre-filled with date
 *   - saveEvent()      — POST to /calendar-events
 *   - deleteEvent()    — DELETE /calendar-events/{id}
 *   - selectEventType / selectEventColor — modal UI state
 */

document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // Data injected from blade via window globals
    const tasksByDate  = window.CAL_TASKS  || {};
    const eventsByDate = window.CAL_EVENTS || {};

    let cur                = new Date();
    let selectedEventColor = 'blue';
    let selectedEventType  = 'meeting';
    let currentViewEventId = null;

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

    /* ============================================================
       RENDER CALENDAR GRID
    ============================================================ */
    window.renderCal = function() {
        const y     = cur.getFullYear();
        const m     = cur.getMonth();
        const today = new Date();

        document.getElementById('calMonthLabel').textContent =
            cur.toLocaleString('default', { month: 'long', year: 'numeric' });

        const first = new Date(y, m, 1).getDay();
        const days  = new Date(y, m + 1, 0).getDate();
        let html = '';

        // Trailing days from previous month
        for (let i = 0; i < first; i++) {
            const d = new Date(y, m, 0 - first + i + 2).getDate();
            html += `<div class="cal-cell other"><div class="cal-day">${d}</div></div>`;
        }

        // Current month days
        for (let d = 1; d <= days; d++) {
            const isToday = y === today.getFullYear() && m === today.getMonth() && d === today.getDate();
            const mm  = String(m + 1).padStart(2, '0');
            const dd  = String(d).padStart(2, '0');
            const key = `${y}-${mm}-${dd}`;

            const tasks  = tasksByDate[key]  || [];
            const events = eventsByDate[key] || [];

            const allItems = [
                ...tasks.map(t => ({
                    label: t.title,
                    color: getPriorityColor(t.priority),
                    done:  t.is_completed,
                    type:  'task',
                    id:    t.id,
                    column: t.column,
                })),
                ...events.map(e => ({
                    label: e.title,
                    color: e.color,
                    done:  false,
                    type:  'event',
                    id:    e.id,
                    time:  e.time,
                    desc:  e.description,
                    etype: e.type,
                }))
            ];

            const showMax = 3;
            let evtHtml = allItems.slice(0, showMax).map(item => {
                const strike = item.done ? 'text-decoration:line-through;opacity:.55;' : '';
                return `<div class="cal-event ${item.color}" style="${strike}"
                             title="${item.label}"
                             onclick="event.stopPropagation(); viewItem(${JSON.stringify(item).replace(/"/g, '&quot;')})"
                        >${item.label}</div>`;
            }).join('');

            if (allItems.length > showMax) {
                evtHtml += `<div class="cal-event" style="background:#f0f2f6;color:var(--soft);cursor:pointer;"
                                 onclick="event.stopPropagation(); openAddEvent('${key}')">
                                 +${allItems.length - showMax} more
                            </div>`;
            }

            html += `<div class="cal-cell${isToday ? ' today' : ''}" onclick="openAddEvent('${key}')">
                        ${evtHtml}
                        <div class="cal-day">${d}</div>
                     </div>`;
        }

        // Leading days of next month
        const rem = 7 - ((first + days) % 7);
        if (rem < 7) {
            for (let i = 1; i <= rem; i++) {
                html += `<div class="cal-cell other"><div class="cal-day">${i}</div></div>`;
            }
        }

        document.getElementById('calCells').innerHTML = html;
    };

    /* ============================================================
       NAVIGATION
    ============================================================ */
    window.calPrev = function() { cur.setMonth(cur.getMonth() - 1); renderCal(); };
    window.calNext = function() { cur.setMonth(cur.getMonth() + 1); renderCal(); };

    /* ============================================================
       VIEW ITEM MODAL
    ============================================================ */
    window.viewItem = function(item) {
        document.getElementById('view-ev-title').textContent = item.label;
        const deleteBtn = document.getElementById('view-ev-delete');
        let bodyHtml = '';

        if (item.type === 'task') {
            deleteBtn.style.display = 'none';
            currentViewEventId = null;
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
            currentViewEventId = item.id;
            deleteBtn.style.display = 'flex';
            const typeLabel = item.etype === 'meeting' ? 'Meeting' : item.etype === 'reminder' ? 'Reminder' : 'Note';
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
        currentViewEventId = null;
    };

    /* ============================================================
       DELETE EVENT
    ============================================================ */
    window.deleteEvent = function() {
        if (!currentViewEventId || !confirm('Delete this event?')) return;
        fetch(`/calendar-events/${currentViewEventId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(data => { if (data.success) { closeViewModal(); window.location.reload(); } });
    };

    /* ============================================================
       ADD EVENT MODAL
    ============================================================ */
    window.openAddEvent = function(dateKey) {
        document.getElementById('ev-title').value = '';
        document.getElementById('ev-date').value  = dateKey;
        document.getElementById('ev-time').value  = '';
        document.getElementById('ev-desc').value  = '';
        selectEventType('meeting');
        selectEventColor('blue');
        document.getElementById('addEventModal').classList.add('open');
    };

    window.closeEventModal = function() {
        document.getElementById('addEventModal').classList.remove('open');
    };

    window.selectEventType = function(type) {
        selectedEventType = type;
        ['meeting', 'note', 'reminder'].forEach(t => {
            const btn = document.getElementById(`ev-type-btn-${t}`);
            if (btn) btn.classList.toggle('active', t === type);
        });
    };

    window.selectEventColor = function(key) {
        selectedEventColor = key;
        ['blue', 'green', 'red', 'amber', 'purple'].forEach(k => {
            const dot = document.getElementById(`ev-color-${k}`);
            if (dot) dot.classList.toggle('selected', k === key);
        });
    };

    window.saveEvent = function() {
        const title = document.getElementById('ev-title').value.trim();
        const date  = document.getElementById('ev-date').value;
        const time  = document.getElementById('ev-time').value;
        const desc  = document.getElementById('ev-desc').value.trim();

        if (!title || !date) { alert('Title and date are required!'); return; }

        const saveBtn = document.querySelector('#addEventModal .cal-btn-primary');
        saveBtn.textContent = 'Saving…'; saveBtn.disabled = true;

        fetch('/calendar-events', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                title,
                date,
                time:        time || null,
                description: desc || null,
                type:        selectedEventType,
                color:       selectedEventColor
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { closeEventModal(); window.location.reload(); }
        })
        .catch(() => {
            saveBtn.textContent = 'Save Event';
            saveBtn.disabled    = false;
        });
    };

    /* ============================================================
       MODAL BACKDROP + KEYBOARD CLOSE
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
    renderCal();

}); // end DOMContentLoaded

/* ============================================================
   TOPNAV DROPDOWNS — profile + notifications
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

            // close all
            document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
            document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');

            if (!isOpen) {
                drop.classList.add('open');
                if (chevId) {
                    const chev = document.getElementById(chevId);
                    if (chev) chev.style.transform = 'rotate(180deg)';
                }
                if (btnId === 'profile-btn') {
                    btn.closest('.tk-nav-profile')?.classList.add('active');
                }
                if (btnId === 'notif-btn') loadNotifications(drop);
            }
        });
    }

    // Close on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
        document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');
    });

    // Bind both
    bindDropdown('notif-btn',   'notif-dropdown');
    bindDropdown('profile-btn', 'profile-dropdown', 'profile-chevron');

    async function loadNotifications(drop) {
        const list  = drop.querySelector('#notif-list');
        const count = drop.querySelector('#notif-count');
        if (!list) return;
        list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13px;color:var(--soft);">Loading…</div>';
        try {
            const res  = await fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
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