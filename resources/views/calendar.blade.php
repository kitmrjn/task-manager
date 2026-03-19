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

<div class="cal-page">
<div class="cal-wrap">

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
        </div>
        <div class="upcoming-list">
            @forelse($upcomingTasks ?? [] as $task)
                @php
                    $due = \Carbon\Carbon::parse($task->due_date);
                    $diff = now()->diffInDays($due, false);
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
        </div>
    </div>

</div>
</div>

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
</x-app-layout>