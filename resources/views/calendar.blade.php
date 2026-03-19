<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">📅</div>
            <div>
                <p class="db-greeting">{{ now()->format('F Y') }}</p>
                <h2 class="db-title">Calendar</h2>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root {
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;--c-green:#1a8a5a;--c-green-lt:#e8f6f0;
    --c-purple:#7c3aed;--c-purple-lt:#f5f3ff;
    --c-rule:#e8eaf0;--radius:10px;
    --shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:0 4px 16px rgba(27,43,94,0.10);
}
body { background:var(--c-bg); color:var(--c-text); font-family:'Epilogue',sans-serif; }
.db-header-inner { display:flex; justify-content:space-between; align-items:center; }
.db-header-left  { display:flex; align-items:center; gap:.9rem; }
.db-avatar { width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center; }
.db-greeting { font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500; }
.db-title { font-size:17px;font-weight:600;color:var(--c-text); }

.cal-page  { padding:2rem 0 3rem; }
.cal-wrap  { max-width:1100px;margin:0 auto;padding:0 1.5rem;display:flex;flex-direction:column;gap:1.5rem; }

/* Calendar card */
.cal-card  { background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s ease both; }
.cal-nav   { display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule); }
.cal-month { font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--c-navy); }
.cal-btn   { width:32px;height:32px;border-radius:7px;border:1px solid var(--c-border);background:var(--c-surface);color:var(--c-muted);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px;transition:background .15s,color .15s; }
.cal-btn:hover { background:var(--c-navy);color:#fff;border-color:var(--c-navy); }
.cal-grid  { display:grid;grid-template-columns:repeat(7,1fr); }
.cal-dow   { padding:.6rem;text-align:center;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-soft);border-bottom:1px solid var(--c-rule); }
.cal-cell  { min-height:90px;padding:.5rem;border-right:1px solid var(--c-rule);border-bottom:1px solid var(--c-rule);position:relative;transition:background .15s;cursor:pointer; }
.cal-cell:nth-child(7n) { border-right:none; }
.cal-cell:hover { background:#f6f8ff; }
.cal-cell.other  { background:var(--c-surface); }
.cal-cell.today  { background:var(--c-blue-lt); }
.cal-cell.today .cal-day { background:var(--c-blue);color:#fff;border-radius:50%; }
.cal-day   { width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--c-text);margin-bottom:.3rem; }
.cal-cell.other .cal-day { color:var(--c-soft); }
.cal-event { font-size:10px;font-weight:600;padding:2px 5px;border-radius:3px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.cal-event.blue   { background:var(--c-blue-lt);color:var(--c-blue); }
.cal-event.green  { background:var(--c-green-lt);color:var(--c-green); }
.cal-event.red    { background:var(--c-red-lt);color:var(--c-red); }
.cal-event.amber  { background:var(--c-amber-lt);color:var(--c-amber); }
.cal-event.purple { background:var(--c-purple-lt);color:var(--c-purple); }

/* Upcoming */
.upcoming-card { background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s .1s ease both; }
.card-header   { display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule); }
.card-title    { font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--c-navy); }
.upcoming-list { padding:.5rem 0; }
.upcoming-item { display:flex;align-items:center;gap:1rem;padding:.8rem 1.4rem;border-bottom:1px solid var(--c-rule);transition:background .15s; }
.upcoming-item:last-child { border-bottom:none; }
.upcoming-item:hover { background:var(--c-surface); }
.upcoming-date { width:42px;text-align:center;flex-shrink:0; }
.upcoming-date .ud-day  { font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--c-navy);line-height:1; }
.upcoming-date .ud-mon  { font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--c-soft);font-weight:600; }
.upcoming-divider { width:1px;height:36px;background:var(--c-rule);flex-shrink:0; }
.upcoming-info .ui-title { font-size:13px;font-weight:500;color:var(--c-text); }
.upcoming-info .ui-sub   { font-size:11px;color:var(--c-soft);margin-top:2px; }
.upcoming-tag  { margin-left:auto;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:4px; }

@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:none} }
</style>

{{-- ── PAGE ── --}}
<div class="cal-page">
<div class="cal-wrap">

    {{-- Calendar --}}
    <div class="cal-card">
        <div class="cal-nav">
            <button class="cal-btn" onclick="calPrev()">‹</button>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div class="cal-month" id="calMonthLabel"></div>
                <button onclick="openAddEvent(new Date().toISOString().split('T')[0])"
                        style="font-size:12px;font-weight:600;padding:4px 12px;background:#1b2b5e;color:#fff;border:none;border-radius:6px;cursor:pointer;">
                    + Add Event
                </button>
                <button onclick="cur=new Date();renderCal()"
                        style="font-size:12px;font-weight:600;padding:4px 12px;background:#f4f5f7;color:#6b7491;border:1px solid #e2e5eb;border-radius:6px;cursor:pointer;">
                    Today
                </button>
            </div>
            <button class="cal-btn" onclick="calNext()">›</button>
        </div>
                <div class="cal-grid">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="cal-dow">{{ $d }}</div>
            @endforeach
        </div>
        <div class="cal-grid" id="calCells"></div>
    </div>

    {{-- Upcoming Deadlines --}}
    <div class="upcoming-card">
        <div class="card-header">
            <div class="card-title">Upcoming Deadlines</div>
        </div>
        <div class="upcoming-list">
            @forelse($upcomingTasks ?? [] as $task)
                @php
                    $due      = \Carbon\Carbon::parse($task->due_date);
                    $diff     = now()->diffInDays($due, false);
                    $tagClass = $diff < 0 ? 'red' : ($diff <= 2 ? 'amber' : 'blue');
                    $tagLabel = $diff < 0 ? 'Overdue' : ($diff == 0 ? 'Today' : ($diff == 1 ? 'Tomorrow' : 'In '.$diff.' days'));
                @endphp
                <div class="upcoming-item">
                    <div class="upcoming-date">
                        <div class="ud-day">{{ $due->format('j') }}</div>
                        <div class="ud-mon">{{ $due->format('M') }}</div>
                    </div>
                    <div class="upcoming-divider"></div>
                    <div class="upcoming-info">
                        <div class="ui-title">{{ $task->title }}</div>
                        <div class="ui-sub">{{ $task->column->title ?? 'No status' }}</div>
                    </div>
                    <span class="upcoming-tag" style="background:var(--c-{{ $tagClass }}-lt);color:var(--c-{{ $tagClass }})">{{ $tagLabel }}</span>
                </div>
            @empty
                <div style="padding:1.5rem;text-align:center;color:var(--c-soft);font-size:13px;">
                    No upcoming deadlines 🎉
                </div>
            @endforelse
        </div>
    </div>

</div>
</div>

{{-- ── ADD EVENT MODAL ── --}}
<div id="addEventModal" style="position:fixed;inset:0;background:rgba(16,24,40,.5);backdrop-filter:blur(4px);z-index:500;display:none;align-items:center;justify-content:center;padding:1rem;">
<div style="background:#fff;border-radius:16px;width:100%;max-width:460px;box-shadow:0 12px 32px rgba(16,24,40,.14);animation:fadeUp .25s ease both;">
    <div style="padding:1.3rem 1.5rem;border-bottom:1px solid #e2e5eb;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:17px;font-weight:700;color:#1a1e2e;" id="ev-modal-title">Add Event</div>
        <button onclick="closeEventModal()" style="width:32px;height:32px;border:1.5px solid #e2e5eb;border-radius:8px;background:#fff;cursor:pointer;font-size:18px;color:#6b7491;display:flex;align-items:center;justify-content:center;">×</button>
    </div>
    <div style="padding:1.3rem 1.5rem;display:flex;flex-direction:column;gap:1rem;">
        <div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Title</div>
            <input type="text" id="ev-title" placeholder="e.g. Team Meeting"
                   style="width:100%;padding:.6rem .85rem;border:1.5px solid #e2e5eb;border-radius:8px;font-size:13.5px;outline:none;font-family:'Epilogue',sans-serif;color:#1a1e2e;">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Date</div>
                <input type="date" id="ev-date"
                       style="width:100%;padding:.6rem .85rem;border:1.5px solid #e2e5eb;border-radius:8px;font-size:13.5px;outline:none;font-family:'Epilogue',sans-serif;color:#1a1e2e;">
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Time <span style="font-weight:400;text-transform:none;">(optional)</span></div>
                <input type="time" id="ev-time"
                       style="width:100%;padding:.6rem .85rem;border:1.5px solid #e2e5eb;border-radius:8px;font-size:13.5px;outline:none;font-family:'Epilogue',sans-serif;color:#1a1e2e;">
            </div>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Type</div>
            <div style="display:flex;gap:.5rem;">
                @foreach(['meeting' => '🤝 Meeting', 'note' => '📝 Note', 'reminder' => '⏰ Reminder'] as $val => $label)
                <label style="cursor:pointer;flex:1;">
                    <input type="radio" name="ev-type" value="{{ $val }}" id="ev-type-{{ $val }}" class="hidden" {{ $val === 'meeting' ? 'checked' : '' }}>
                    <div id="ev-type-btn-{{ $val }}" onclick="selectEventType('{{ $val }}')"
                         style="padding:.5rem;border:1.5px solid {{ $val === 'meeting' ? '#2d52c4' : '#e2e5eb' }};border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:{{ $val === 'meeting' ? '#2d52c4' : '#6b7491' }};background:{{ $val === 'meeting' ? '#ebeffa' : '#fff' }};cursor:pointer;transition:all .15s;">
                        {{ $label }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Color</div>
            <div style="display:flex;gap:.5rem;">
                @foreach(['blue' => '#2d52c4', 'green' => '#1a8a5a', 'red' => '#c0354a', 'amber' => '#c47c0e', 'purple' => '#7c3aed'] as $k => $c)
                <div id="ev-color-{{ $k }}" onclick="selectEventColor('{{ $k }}')"
                     style="width:32px;height:32px;border-radius:50%;background:{{ $c }};cursor:pointer;border:3px solid {{ $k === 'blue' ? '#1b2b5e' : 'transparent' }};display:flex;align-items:center;justify-content:center;transition:all .15s;">
                    <svg id="ev-color-check-{{ $k }}" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:{{ $k === 'blue' ? 'block' : 'none' }}">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                @endforeach
            </div>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9ba3be;margin-bottom:.4rem;">Description <span style="font-weight:400;text-transform:none;">(optional)</span></div>
            <textarea id="ev-desc" rows="2" placeholder="Add notes…"
                      style="width:100%;padding:.6rem .85rem;border:1.5px solid #e2e5eb;border-radius:8px;font-size:13.5px;outline:none;resize:none;font-family:'Epilogue',sans-serif;color:#1a1e2e;"></textarea>
        </div>
    </div>
    <div style="padding:1rem 1.5rem;border-top:1px solid #e2e5eb;display:flex;justify-content:flex-end;gap:.65rem;">
        <button onclick="closeEventModal()" style="padding:.55rem 1.1rem;border:1.5px solid #e2e5eb;border-radius:7px;background:#fff;font-size:13.5px;font-weight:500;color:#6b7491;cursor:pointer;font-family:'Epilogue',sans-serif;">Cancel</button>
        <button onclick="saveEvent()" style="padding:.55rem 1.2rem;background:#2d52c4;color:#fff;border:none;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Epilogue',sans-serif;">Save Event</button>
    </div>
</div>
</div>
{{-- VIEW EVENT MODAL --}}
<div id="viewEventModal" style="position:fixed;inset:0;background:rgba(16,24,40,.5);backdrop-filter:blur(4px);z-index:600;display:none;align-items:center;justify-content:center;padding:1rem;">
<div style="background:#fff;border-radius:16px;width:100%;max-width:420px;box-shadow:0 12px 32px rgba(16,24,40,.14);animation:fadeUp .25s ease both;">
    <div style="padding:1.3rem 1.5rem;border-bottom:1px solid #e2e5eb;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:17px;font-weight:700;color:#1a1e2e;" id="view-ev-title">Event</div>
        <button onclick="closeViewModal()" style="width:32px;height:32px;border:1.5px solid #e2e5eb;border-radius:8px;background:#fff;cursor:pointer;font-size:18px;color:#6b7491;display:flex;align-items:center;justify-content:center;">×</button>
    </div>
    <div style="padding:1.3rem 1.5rem;display:flex;flex-direction:column;gap:.85rem;" id="view-ev-body">
    </div>
    <div style="padding:1rem 1.5rem;border-top:1px solid #e2e5eb;display:flex;justify-content:space-between;align-items:center;">
        <button id="view-ev-delete" onclick="deleteEvent()"
                style="font-size:12.5px;font-weight:600;color:#c0354a;background:transparent;border:1.5px solid #e2e5eb;border-radius:7px;padding:.5rem 1rem;cursor:pointer;font-family:'Epilogue',sans-serif;display:none;">
            🗑 Delete
        </button>
        <button onclick="closeViewModal()"
                style="margin-left:auto;padding:.55rem 1.2rem;background:#2d52c4;color:#fff;border:none;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Epilogue',sans-serif;">
            Close
        </button>
    </div>
</div>
</div>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let cur = new Date();
let selectedEventColor = 'blue';
let selectedEventType  = 'meeting';
let currentViewEventId = null;

const tasksByDate  = {!! json_encode($tasksByDate  ?? []) !!};
const eventsByDate = {!! json_encode($eventsByDate ?? []) !!};

function getPriorityColor(priority) {
    if (priority === 'high')   return 'red';
    if (priority === 'medium') return 'amber';
    return 'blue';
}

function renderCal() {
    const y = cur.getFullYear(), m = cur.getMonth();
    const today = new Date();

    document.getElementById('calMonthLabel').textContent =
        cur.toLocaleString('default', { month: 'long', year: 'numeric' });

    const first = new Date(y, m, 1).getDay();
    const days  = new Date(y, m + 1, 0).getDate();
    let html = '';

    for (let i = 0; i < first; i++) {
        const d = new Date(y, m, 0 - first + i + 2).getDate();
        html += `<div class="cal-cell other"><div class="cal-day">${d}</div></div>`;
    }

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
                icon:  '📋',
                done:  t.is_completed,
                type:  'task',
                id:    t.id,
                column: t.column,
            })),
            ...events.map(e => ({
                label: e.title,
                color: e.color,
                icon:  e.type === 'meeting' ? '🤝' : e.type === 'reminder' ? '⏰' : '📝',
                done:  false,
                type:  'event',
                id:    e.id,
                time:  e.time,
                desc:  e.description,
                etype: e.type,
            }))
        ];

        const showMax = 2;
        let evtHtml = allItems.slice(0, showMax).map(item => {
            const strike = item.done ? 'text-decoration:line-through;opacity:.55;' : '';
            // Each event pill is clickable — stopPropagation so cell click doesn't also fire
            return `<div class="cal-event ${item.color}" style="${strike}cursor:pointer;"
                         title="${item.label}"
                         onclick="event.stopPropagation(); viewItem(${JSON.stringify(item).replace(/"/g, '&quot;')})"
                    >${item.icon} ${item.label}</div>`;
        }).join('');

        if (allItems.length > showMax) {
            evtHtml += `<div class="cal-event" style="background:#f0f2f6;color:#6b7280;cursor:pointer;"
                             onclick="event.stopPropagation(); openAddEvent('${key}')">
                             +${allItems.length - showMax} more
                        </div>`;
        }

        // Clicking the cell itself (empty area) opens Add Event
        html += `<div class="cal-cell${isToday ? ' today' : ''}" onclick="openAddEvent('${key}')">
                    <div class="cal-day">${d}</div>
                    ${evtHtml}
                 </div>`;
    }

    const rem = 7 - ((first + days) % 7);
    if (rem < 7) {
        for (let i = 1; i <= rem; i++) {
            html += `<div class="cal-cell other"><div class="cal-day">${i}</div></div>`;
        }
    }

    document.getElementById('calCells').innerHTML = html;
}

function calPrev() { cur.setMonth(cur.getMonth() - 1); renderCal(); }
function calNext() { cur.setMonth(cur.getMonth() + 1); renderCal(); }

// ── View existing item ────────────────────────────────────────
function viewItem(item) {
    document.getElementById('view-ev-title').textContent = item.label;
    const deleteBtn = document.getElementById('view-ev-delete');

    let bodyHtml = '';

    if (item.type === 'task') {
        // Task — read only, no delete
        deleteBtn.style.display = 'none';
        currentViewEventId = null;
        bodyHtml = `
            <div style="display:flex;align-items:center;gap:.5rem;">
                <span style="font-size:20px;">📋</span>
                <span style="font-size:13px;color:#6b7491;">Task Board
            </div>
            ${item.column ? `<div style="font-size:13px;color:#1a1e2e;">
                <span style="color:#9ba3be;font-weight:600;text-transform:uppercase;font-size:11px;letter-spacing:.06em;">Status</span><br>
                ${item.column}
            </div>` : ''}
            <div style="font-size:13px;color:#6b7491;background:#f4f5f7;padding:.65rem .85rem;border-radius:8px;">
                This task comes from your Task board. Open the Tasks page to edit it.
            </div>`;
    } else {
        // Custom event — can delete
        currentViewEventId = item.id;
        deleteBtn.style.display = 'flex';

        const typeLabel = item.etype === 'meeting' ? '🤝 Meeting' : item.etype === 'reminder' ? '⏰ Reminder' : '📝 Note';
        const timeStr   = item.time ? `<div style="font-size:13px;color:#1a1e2e;">
                <span style="color:#9ba3be;font-weight:600;text-transform:uppercase;font-size:11px;letter-spacing:.06em;">Time</span><br>
                ${formatTime(item.time)}
            </div>` : '';
        const descStr   = item.desc ? `<div style="font-size:13px;color:#1a1e2e;">
                <span style="color:#9ba3be;font-weight:600;text-transform:uppercase;font-size:11px;letter-spacing:.06em;">Notes</span><br>
                ${item.desc}
            </div>` : '';

        bodyHtml = `
            <div style="display:flex;align-items:center;gap:.5rem;">
                <span style="font-size:13px;color:#6b7491;">${typeLabel}</span>
            </div>
            ${timeStr}
            ${descStr}`;
    }

    document.getElementById('view-ev-body').innerHTML = bodyHtml;
    document.getElementById('viewEventModal').style.display = 'flex';
}

function formatTime(timeStr) {
    if (!timeStr) return '';
    const [h, m] = timeStr.split(':');
    const hour   = parseInt(h);
    const ampm   = hour >= 12 ? 'PM' : 'AM';
    const h12    = hour % 12 || 12;
    return `${h12}:${m} ${ampm}`;
}

function closeViewModal() {
    document.getElementById('viewEventModal').style.display = 'none';
    currentViewEventId = null;
}

function deleteEvent() {
    if (!currentViewEventId || !confirm('Delete this event?')) return;
    fetch(`/calendar-events/${currentViewEventId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { closeViewModal(); window.location.reload(); }
    });
}

// ── Add Event Modal ───────────────────────────────────────────
function openAddEvent(dateKey) {
    document.getElementById('ev-title').value = '';
    document.getElementById('ev-date').value  = dateKey;
    document.getElementById('ev-time').value  = '';
    document.getElementById('ev-desc').value  = '';
    selectEventType('meeting');
    selectEventColor('blue');
    document.getElementById('addEventModal').style.display = 'flex';
}

function closeEventModal() {
    document.getElementById('addEventModal').style.display = 'none';
}

function selectEventType(type) {
    selectedEventType = type;
    ['meeting', 'note', 'reminder'].forEach(t => {
        const btn = document.getElementById(`ev-type-btn-${t}`);
        if (!btn) return;
        if (t === type) {
            btn.style.borderColor = '#2d52c4';
            btn.style.background  = '#ebeffa';
            btn.style.color       = '#2d52c4';
        } else {
            btn.style.borderColor = '#e2e5eb';
            btn.style.background  = '#fff';
            btn.style.color       = '#6b7491';
        }
    });
}

function selectEventColor(key) {
    selectedEventColor = key;
    ['blue', 'green', 'red', 'amber', 'purple'].forEach(k => {
        const s = document.getElementById(`ev-color-${k}`);
        const c = document.getElementById(`ev-color-check-${k}`);
        if (s) s.style.border = k === key ? '3px solid #1b2b5e' : '3px solid transparent';
        if (c) c.style.display = k === key ? 'block' : 'none';
    });
}

function saveEvent() {
    const title = document.getElementById('ev-title').value.trim();
    const date  = document.getElementById('ev-date').value;
    const time  = document.getElementById('ev-time').value;
    const desc  = document.getElementById('ev-desc').value.trim();

    if (!title || !date) { alert('Title and date are required!'); return; }

    const saveBtn = document.querySelector('#addEventModal button[onclick="saveEvent()"]');
    saveBtn.textContent = 'Saving…'; saveBtn.disabled = true;

    fetch('/calendar-events', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ title, date, time: time || null, description: desc || null, type: selectedEventType, color: selectedEventColor })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { closeEventModal(); window.location.reload(); }
    })
    .catch(() => { saveBtn.textContent = 'Save Event'; saveBtn.disabled = false; });
}

// Backdrop clicks
document.getElementById('addEventModal').addEventListener('click', function(e) { if (e.target === this) closeEventModal(); });
document.getElementById('viewEventModal').addEventListener('click', function(e) { if (e.target === this) closeViewModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeEventModal(); closeViewModal(); } });

renderCal();
</script>
</x-app-layout>