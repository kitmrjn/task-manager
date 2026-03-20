<x-app-layout>
<x-slot name="header">
    <div class="tk-topnav">
        <div class="tk-topnav-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Search tasks, projects…" />
        </div>
        <div class="tk-topnav-right">

            {{-- Mail --}}
            <button class="tk-nav-icon-btn" title="Messages">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </button>

            {{-- Notifications --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-nav-icon-btn" id="notif-btn" title="Notifications">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="tk-notif-dot">3</span>
                </button>
                <div id="notif-dropdown" class="tk-dropdown">
                    <div class="tk-dropdown-header">
                        <span class="tk-dropdown-title">Notifications</span>
                        <span class="tk-badge-pill" id="notif-count"></span>
                    </div>
                    <div class="tk-dropdown-body" id="notif-list">
                        <div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--soft);">Loading…</div>
                    </div>
                </div>
            </div>

            <div class="tk-nav-divider"></div>

            {{-- Profile --}}
            <div class="tk-dropdown-wrap">
                <div class="tk-nav-profile" id="profile-btn" role="button" tabindex="0">
                    <div class="tk-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="tk-nav-userinfo">
                        <span class="tk-nav-name">{{ Auth::user()->name }}</span>
                        <span class="tk-nav-email">{{ Auth::user()->email }}</span>
                    </div>
                    <svg id="profile-chevron" class="tk-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform .22s;"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div id="profile-dropdown" class="tk-dropdown tk-profile-dropdown">
                    <div class="tk-dropdown-header tk-profile-header">
                        <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ Auth::user()->name }}</div>
                            <div class="tk-profile-meta">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="tk-dropdown-body">
                        <a href="{{ route('profile.edit') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            My Profile
                        </a>
                        <a href="{{ route('settings.index') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            Settings
                        </a>
                        <div class="tk-profile-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tk-profile-item tk-profile-item--danger">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-slot>

<<<<<<< Updated upstream
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root {
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;--c-green:#1a8a5a;--c-green-lt:#e8f6f0;
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
=======
{{-- ── Inject page assets via Vite ───────────────────────────── --}}
@push('styles')
    @vite('resources/css/calendar.css')
@endpush
>>>>>>> Stashed changes

@push('scripts')
    {{--
        Pass server-side PHP data to calendar.js via window globals.
        This pattern lets calendar.js live as a pure external file
        with no Blade syntax inside it.
    --}}
    <script>
        window.CAL_TASKS  = {!! json_encode($tasksByDate  ?? []) !!};
        window.CAL_EVENTS = {!! json_encode($eventsByDate ?? []) !!};
    </script>
    @vite('resources/js/calendar.js')
@endpush

<<<<<<< Updated upstream
/* Calendar card */
.cal-card  { background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s ease both; }
.cal-nav   { display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule); }
.cal-month { font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--c-navy); }
.cal-btn   { width:32px;height:32px;border-radius:7px;border:1px solid var(--c-border);background:var(--c-surface);color:var(--c-muted);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px;transition:background .15s,color .15s; }
.cal-btn:hover { background:var(--c-navy);color:#fff;border-color:var(--c-navy); }

.cal-grid  { display:grid;grid-template-columns:repeat(7,1fr); }
.cal-dow   { padding:.6rem;text-align:center;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-soft);border-bottom:1px solid var(--c-rule); }
.cal-cell  { min-height:90px;padding:.5rem;border-right:1px solid var(--c-rule);border-bottom:1px solid var(--c-rule);position:relative;transition:background .15s;cursor:default; }
.cal-cell:nth-child(7n) { border-right:none; }
.cal-cell:hover { background:#f6f8ff; }
.cal-cell.other  { background:var(--c-surface); }
.cal-cell.today  { background:var(--c-blue-lt); }
.cal-cell.today .cal-day { background:var(--c-blue);color:#fff;border-radius:50%; }
.cal-day   { width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--c-text);margin-bottom:.3rem; }
.cal-cell.other .cal-day { color:var(--c-soft); }
.cal-event { font-size:10px;font-weight:600;padding:2px 5px;border-radius:3px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.cal-event.blue  { background:var(--c-blue-lt);color:var(--c-blue); }
.cal-event.green { background:var(--c-green-lt);color:var(--c-green); }
.cal-event.red   { background:var(--c-red-lt);color:var(--c-red); }
.cal-event.amber { background:var(--c-amber-lt);color:var(--c-amber); }

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

=======
{{-- ═══════════════════════════════════════════════════════════
     PAGE
════════════════════════════════════════════════════════════ --}}
>>>>>>> Stashed changes
<div class="cal-page">

<<<<<<< Updated upstream
    <div class="cal-card">
        <div class="cal-nav">
            <button class="cal-btn" onclick="calPrev()">‹</button>
            <div class="cal-month" id="calMonthLabel"></div>
            <button class="cal-btn" onclick="calNext()">›</button>
        </div>
        <div class="cal-grid" id="calDow">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="cal-dow">{{ $d }}</div>
            @endforeach
        </div>
        <div class="cal-grid" id="calCells"></div>
    </div>

    <div class="upcoming-card">
        <div class="card-header">
            <div class="card-title">Upcoming Deadlines</div>
=======
    <div class="cal-page-header">
        <h1 class="cal-page-title">Calendar</h1>
        <p class="cal-page-sub">Stay on top of your schedule and deadlines.</p>
    </div>

    <div class="cal-wrap">

        {{-- ── CALENDAR GRID ──────────────────────────────── --}}
        <div class="cal-card">
            <div class="cal-nav">
                <button class="cal-btn" onclick="calPrev()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <div class="cal-nav-controls">
                    <span class="cal-month" id="calMonthLabel"></span>
                    <button class="cal-today-btn" onclick="cur=new Date();renderCal()">Today</button>
                </div>
                <button class="cal-btn" onclick="calNext()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>

            <div class="cal-grid">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                    <div class="cal-dow">{{ $d }}</div>
                @endforeach
            </div>

            {{-- Cells populated by calendar.js → renderCal() --}}
            <div class="cal-grid" id="calCells"></div>
>>>>>>> Stashed changes
        </div>

        {{-- ── UPCOMING EVENTS SIDEBAR ────────────────────── --}}
        <div class="upcoming-card">
            <div class="card-header">
                <div class="card-title">Upcoming Events</div>
            </div>
            <div class="upcoming-list">
                @php
<<<<<<< Updated upstream
                    $due = \Carbon\Carbon::parse($task->due_date);
                    $diff = now()->diffInDays($due, false);
                    $tagClass = $diff < 0 ? 'red' : ($diff <= 2 ? 'amber' : 'blue');
                    $tagLabel = $diff < 0 ? 'Overdue' : ($diff == 0 ? 'Today' : ($diff == 1 ? 'Tomorrow' : 'In '.$diff.' days'));
=======
                    /*
                     * Merge task deadlines ($upcomingTasks) + calendar events ($eventsByDate)
                     * into one list sorted by date ascending.
                     * Both variables are passed from CalendarController.
                     */
                    $upcomingAll = collect();

                    // ── Tasks with due dates ──────────────────
                    foreach (($upcomingTasks ?? []) as $task) {
                        $due  = \Carbon\Carbon::parse($task->due_date);
                        $diff = now()->startOfDay()->diffInDays($due->copy()->startOfDay(), false);
                        $upcomingAll->push([
                            'title'    => $task->title,
                            'diff'     => $diff,
                            'dotColor' => $diff < 0 ? 'var(--red)' : ($diff <= 2 ? 'var(--amber)' : 'var(--blue)'),
                            'subLabel' => $diff < 0
                                ? 'Overdue'
                                : ($diff == 0 ? 'Today · All Day'
                                    : ($diff == 1 ? 'Tomorrow · All Day'
                                        : $due->format('M j') . ' · All Day')),
                        ]);
                    }

                    // ── Calendar events (added via the modal) ─
                    $dotMap = [
                        'blue'   => 'var(--blue)',
                        'green'  => 'var(--green)',
                        'red'    => 'var(--red)',
                        'amber'  => 'var(--amber)',
                        'purple' => 'var(--purple)',
                    ];
                    foreach (($eventsByDate ?? []) as $dateStr => $evs) {
                        foreach ($evs as $ev) {
                            $due  = \Carbon\Carbon::parse($dateStr);
                            $diff = now()->startOfDay()->diffInDays($due->copy()->startOfDay(), false);
                            if ($diff >= -1) {
                                $dot     = $dotMap[$ev['color'] ?? 'blue'] ?? 'var(--blue)';
                                $timeLbl = !empty($ev['time'])
                                    ? \Carbon\Carbon::createFromFormat('H:i', substr($ev['time'], 0, 5))->format('g:i A')
                                    : 'All Day';
                                $upcomingAll->push([
                                    'title'    => $ev['title'],
                                    'diff'     => $diff,
                                    'dotColor' => $dot,
                                    'subLabel' => $due->format('M j') . ' · ' . $timeLbl,
                                ]);
                            }
                        }
                    }

                    $upcomingAll = $upcomingAll->sortBy('diff')->values();
>>>>>>> Stashed changes
                @endphp

                @forelse($upcomingAll as $item)
                    <div class="upcoming-item">
                        <div class="up-dot" style="background:{{ $item['dotColor'] }};"></div>
                        <div class="up-body">
                            <div class="up-title">{{ $item['title'] }}</div>
                            <div class="up-sub">{{ $item['subLabel'] }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem 1.4rem;text-align:center;color:var(--soft);font-size:14px;font-weight:500;">
                        No upcoming events 🎉
                    </div>
<<<<<<< Updated upstream
                    <span class="upcoming-tag" style="background:var(--c-{{ $tagClass }}-lt);color:var(--c-{{ $tagClass }})">{{ $tagLabel }}</span>
                </div>
            @empty
                {{-- Placeholder rows --}}
                @foreach([['15','Apr','Design Review','In Progress','blue','In 3 days'],['17','Apr','Client Presentation','To Do','amber','In 5 days'],['20','Apr','Sprint Retrospective','To Do','blue','In 8 days'],['25','Apr','Q2 Report Due','To Do','red','In 13 days']] as $r)
                <div class="upcoming-item">
                    <div class="upcoming-date">
                        <div class="ud-day">{{ $r[0] }}</div>
                        <div class="ud-mon">{{ $r[1] }}</div>
                    </div>
                    <div class="upcoming-divider"></div>
                    <div class="upcoming-info">
                        <div class="ui-title">{{ $r[2] }}</div>
                        <div class="ui-sub">{{ $r[3] }}</div>
                    </div>
                    <span class="upcoming-tag" style="background:var(--c-{{ $r[4] }}-lt);color:var(--c-{{ $r[4] }})">{{ $r[5] }}</span>
                </div>
                @endforeach
            @endforelse
=======
                @endforelse
            </div>
>>>>>>> Stashed changes
        </div>

    </div>{{-- /.cal-wrap --}}
</div>{{-- /.cal-page --}}

{{-- ═══════════════════════════════════════════════════════════
     ADD EVENT MODAL
════════════════════════════════════════════════════════════ --}}
<div id="addEventModal" class="cal-modal-overlay">
<div class="cal-modal">
    <div class="cal-modal-head">
        <div class="cal-modal-title">Add Event</div>
        <button class="cal-modal-close" onclick="closeEventModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="cal-modal-body">
        {{-- Title --}}
        <div>
            <div class="cal-field-label">Title</div>
            <input type="text" id="ev-title" class="cal-field-input" placeholder="e.g. Team Meeting">
        </div>

<<<<<<< Updated upstream
<script>
let cur = new Date();
const sampleEvents = {
    3:  [{t:'Team Standup',c:'blue'}],
    7:  [{t:'Design Review',c:'amber'}],
    12: [{t:'Sprint End',c:'red'}],
    15: [{t:'Client Call',c:'green'}],
    20: [{t:'Retrospective',c:'blue'}],
    25: [{t:'Q2 Report',c:'red'},{t:'1:1 Meeting',c:'green'}],
};
function renderCal() {
    const y = cur.getFullYear(), m = cur.getMonth();
    const today = new Date();
    document.getElementById('calMonthLabel').textContent =
        cur.toLocaleString('default',{month:'long',year:'numeric'});
    const first = new Date(y,m,1).getDay();
    const days  = new Date(y,m+1,0).getDate();
    let html = '';
    for (let i=0;i<first;i++) {
        const d = new Date(y,m,0-first+i+2).getDate();
        html += `<div class="cal-cell other"><div class="cal-day">${d}</div></div>`;
    }
    for (let d=1;d<=days;d++) {
        const isToday = y===today.getFullYear()&&m===today.getMonth()&&d===today.getDate();
        const evts = sampleEvents[d]||[];
        let evtHtml = evts.map(e=>`<div class="cal-event ${e.c}">${e.t}</div>`).join('');
        html += `<div class="cal-cell${isToday?' today':''}"><div class="cal-day">${d}</div>${evtHtml}</div>`;
    }
    const rem = 7 - ((first+days)%7); if(rem<7) for(let i=1;i<=rem;i++) html+=`<div class="cal-cell other"><div class="cal-day">${i}</div></div>`;
    document.getElementById('calCells').innerHTML = html;
}
function calPrev(){ cur.setMonth(cur.getMonth()-1); renderCal(); }
function calNext(){ cur.setMonth(cur.getMonth()+1); renderCal(); }
renderCal();
</script>
=======
        {{-- Date + Time --}}
        <div class="cal-two-col">
            <div>
                <div class="cal-field-label">Date</div>
                <input type="date" id="ev-date" class="cal-field-input">
            </div>
            <div>
                <div class="cal-field-label">Time <span class="optional">(optional)</span></div>
                <input type="time" id="ev-time" class="cal-field-input">
            </div>
        </div>

        {{-- Type — SVG icons, no emoji --}}
        <div>
            <div class="cal-field-label">Type</div>
            <div class="cal-type-grid">
                <button type="button" id="ev-type-btn-meeting" class="cal-type-btn active" onclick="selectEventType('meeting')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Meeting
                </button>
                <button type="button" id="ev-type-btn-note" class="cal-type-btn" onclick="selectEventType('note')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Note
                </button>
                <button type="button" id="ev-type-btn-reminder" class="cal-type-btn" onclick="selectEventType('reminder')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Reminder
                </button>
            </div>
        </div>

        {{-- Color swatches — no radio buttons --}}
        <div>
            <div class="cal-field-label">Color</div>
            <div class="cal-color-row">
                @foreach(['blue'=>'#2d52c4','green'=>'#1a8a5a','red'=>'#c0354a','amber'=>'#c47c0e','purple'=>'#7c3aed'] as $k => $c)
                <div id="ev-color-{{ $k }}"
                     class="cal-color-dot {{ $k === 'blue' ? 'selected' : '' }}"
                     onclick="selectEventColor('{{ $k }}')"
                     style="background:{{ $c }};">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Description --}}
        <div>
            <div class="cal-field-label">Description <span class="optional">(optional)</span></div>
            <textarea id="ev-desc" class="cal-field-textarea" placeholder="Add notes…"></textarea>
        </div>
    </div>

    <div class="cal-modal-footer">
        <button class="cal-btn-ghost" onclick="closeEventModal()">Cancel</button>
        <button class="cal-btn-primary" onclick="saveEvent()">Save Event</button>
    </div>
</div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     VIEW EVENT MODAL
════════════════════════════════════════════════════════════ --}}
<div id="viewEventModal" class="cal-modal-overlay" style="z-index:600;">
<div class="cal-modal cal-modal-sm">
    <div class="cal-modal-head">
        <div class="cal-modal-title" id="view-ev-title">Event</div>
        <button class="cal-modal-close" onclick="closeViewModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="cal-modal-body" id="view-ev-body" style="gap:1.1rem;"></div>
    <div class="cal-modal-footer" style="justify-content:space-between;">
        <button id="view-ev-delete" class="cal-btn-danger" onclick="deleteEvent()" style="display:none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
            Delete
        </button>
        <button class="cal-btn-primary" onclick="closeViewModal()" style="margin-left:auto;">Close</button>
    </div>
</div>
</div>

>>>>>>> Stashed changes
</x-app-layout>