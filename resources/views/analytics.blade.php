<x-app-layout>
<x-slot name="header">
    <div class="an-topnav">
        <div class="an-topnav-search">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Search tasks, projects…" />
        </div>
        <div class="an-topnav-right">
            <button class="an-nav-icon-btn" title="Messages">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </button>
            <button class="an-nav-icon-btn" title="Notifications">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            <div class="an-nav-divider"></div>
            <div class="an-nav-profile">
                <div class="an-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="an-nav-userinfo">
                    <span class="an-nav-name">{{ Auth::user()->name }}</span>
                    <span class="an-nav-email">{{ Auth::user()->email }}</span>
                </div>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:#9ca3af;flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

:root {
    --bg:     #f0f2f6;
    --white:  #ffffff;
    --border: #e4e7ec;
    --text:   #0d1117;
    --muted:  #4b5563;
    --soft:   #9ca3af;
    --blue:   #2563eb;
    --green:  #16a34a;
    --amber:  #d97706;
    --red:    #dc2626;
    --teal:   #0e9f8e;
    --radius: 10px;
    --shadow: 0 1px 3px rgba(13,17,23,.07),0 1px 2px rgba(13,17,23,.04);
    --font:   'Inter',sans-serif;
}
*,*::before,*::after { box-sizing:border-box; }
body { background:var(--bg); font-family:var(--font); color:var(--text); -webkit-font-smoothing:antialiased; }

/* ── Topnav ── */
.an-topnav { display:flex; align-items:center; padding:0 1.5rem; height:58px; gap:1rem; background:var(--white); }
.an-topnav-search { display:flex; align-items:center; gap:.5rem; background:var(--bg); border:1.5px solid var(--border); border-radius:8px; padding:.45rem .8rem; width:240px; }
.an-topnav-search:focus-within { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.08); background:var(--white); }
.an-topnav-search svg { color:var(--soft); flex-shrink:0; }
.an-topnav-search input { border:none; background:transparent; outline:none; font-family:var(--font); font-size:13px; font-weight:500; color:var(--text); flex:1; min-width:0; }
.an-topnav-search input::placeholder { color:var(--soft); }
.an-topnav-right { display:flex; align-items:center; gap:.5rem; margin-left:auto; }
.an-nav-icon-btn { width:34px; height:34px; border-radius:8px; border:1.5px solid var(--border); background:var(--white); display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); transition:background .15s; }
.an-nav-icon-btn:hover { background:var(--bg); }
.an-nav-divider { width:1px; height:22px; background:var(--border); flex-shrink:0; }
.an-nav-profile { display:flex; align-items:center; gap:.5rem; cursor:pointer; padding:.3rem .55rem; border-radius:8px; border:1.5px solid transparent; transition:background .18s,border-color .18s; }
.an-nav-profile:hover { background:var(--bg); border-color:var(--border); }
.an-nav-avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#1b2b5e); color:#fff; font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.an-nav-userinfo { display:flex; flex-direction:column; }
.an-nav-name  { font-size:13px; font-weight:700; color:var(--text); line-height:1.2; }
.an-nav-email { font-size:11px; color:var(--soft); font-weight:500; }

/* ── Page: centered, max-width ── */
.an-page {
    padding: 1.25rem 1.5rem 2rem;
    max-width: 1100px;       /* ← THIS is what stops full-width stretching */
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: .9rem;
}

/* ── Banner ── */
.an-banner {
    background: linear-gradient(115deg,#0d1117 0%,#1b2b5e 52%,#2563eb 100%);
    border-radius: 12px;
    padding: 1.25rem 1.75rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
    position: relative; overflow: hidden;
    box-shadow: 0 4px 18px rgba(37,99,235,.2);
    animation: fadeUp .4s ease both;
}
.an-banner::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 45% 100% at 90% 50%,rgba(37,99,235,.32) 0%,transparent 65%); pointer-events:none; }
.an-banner-text { position:relative; z-index:1; }
.an-banner-eyebrow { font-size:10px; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(255,255,255,.45); margin-bottom:.3rem; }
.an-banner-title { font-size:20px; font-weight:900; color:#fff; letter-spacing:-.03em; line-height:1.15; margin-bottom:.2rem; }
.an-banner-sub { font-size:12.5px; color:rgba(255,255,255,.52); line-height:1.5; }
.an-banner-actions { display:flex; align-items:center; gap:.6rem; position:relative; z-index:1; flex-shrink:0; }
.an-export-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; border:1.5px solid rgba(255,255,255,.25); border-radius:8px; background:rgba(255,255,255,.12); color:#fff; font-family:var(--font); font-size:12.5px; font-weight:700; cursor:pointer; transition:all .18s; backdrop-filter:blur(8px); white-space:nowrap; }
.an-export-btn:hover { background:rgba(255,255,255,.2); }
.an-period-select { padding:.48rem .8rem; border:1.5px solid rgba(255,255,255,.2); border-radius:8px; background:rgba(255,255,255,.1); color:#fff; font-family:var(--font); font-size:12px; font-weight:600; cursor:pointer; outline:none; }
.an-period-select option { background:#1b2b5e; }

/* ── Stat cards ── */
.an-stats-row1 { display:grid; grid-template-columns:repeat(4,1fr); gap:.85rem; }

/* overdue: only as wide as one stat card, left-aligned */
.an-stats-row2 { display:grid; grid-template-columns:repeat(4,1fr); gap:.85rem; }
.an-stat-ghost { visibility:hidden; pointer-events:none; }

.an-stat {
    background:var(--white); border:1px solid var(--border);
    border-radius:var(--radius); padding:1rem 1.1rem;
    box-shadow:var(--shadow); position:relative; overflow:hidden;
    animation:fadeUp .38s ease both;
    transition:box-shadow .18s,transform .18s;
}
.an-stat:hover { box-shadow:0 4px 14px rgba(13,17,23,.09); transform:translateY(-1px); }
.an-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:var(--radius) var(--radius) 0 0; }
.an-stat.s1::before{background:var(--blue);}
.an-stat.s2::before{background:var(--teal);}
.an-stat.s3::before{background:var(--amber);}
.an-stat.s4::before{background:var(--green);}
.an-stat.s5::before{background:var(--red);}

.an-stat-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:var(--soft); margin-bottom:.2rem; }
.an-stat-value { font-size:1.75rem; font-weight:900; color:var(--text); letter-spacing:-.04em; line-height:1.1; margin:.1rem 0; }
.an-stat-value span { font-size:.95rem; font-weight:700; color:var(--muted); letter-spacing:0; }
.an-stat-delta { font-size:11px; font-weight:600; display:flex; align-items:center; gap:.2rem; margin-top:.15rem; }
.an-stat-delta.up   { color:var(--green); }
.an-stat-delta.down { color:var(--red); }

/* ── Charts row ── */
.an-charts { display:grid; grid-template-columns:1fr 320px; gap:.85rem; align-items:start; }

/* ── Cards ── */
.an-card { background:var(--white); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; animation:fadeUp .38s .15s ease both; }
.card-header { display:flex; align-items:flex-start; justify-content:space-between; padding:.9rem 1.1rem; border-bottom:1px solid var(--border); }
.card-title { font-size:13.5px; font-weight:800; color:var(--text); letter-spacing:-.02em; }
.card-sub   { font-size:11px; color:var(--soft); font-weight:500; margin-top:1px; }
.chart-wrap { padding:.9rem 1.1rem 1.1rem; }

/* line chart — fixed height, no excess space */
#lineChart { display:block; width:100%!important; height:170px!important; }

/* doughnut — fixed, centered */
.doughnut-wrap { display:flex; flex-direction:column; align-items:center; padding:1.4rem 1rem 1.2rem; gap:.85rem; }
#doughnutChart { width:210px!important; height:210px!important; }

/* ── Breakdown ── */
.breakdown { padding:.15rem 0; }
.breakdown-item { display:flex; align-items:center; gap:.75rem; padding:.65rem 1.1rem; border-bottom:1px solid var(--border); }
.breakdown-item:last-child { border-bottom:none; }
.bd-label { font-size:12.5px; font-weight:600; color:var(--text); width:95px; flex-shrink:0; }
.bd-track { flex:1; height:6px; background:#f0f2f5; border-radius:99px; overflow:hidden; }
.bd-fill  { height:100%; border-radius:99px; transition:width 1.1s cubic-bezier(.16,1,.3,1); width:0; }
.bd-count { font-size:12px; font-weight:700; color:var(--muted); width:22px; text-align:right; flex-shrink:0; }

@keyframes fadeUp { from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;} }
</style>

<div class="an-page">

    {{-- Banner --}}
    <div class="an-banner">
        <div class="an-banner-text">
            <div class="an-banner-eyebrow">Performance Overview</div>
            <div class="an-banner-title">Analytics</div>
            <div class="an-banner-sub">Insights into your team's performance and project health.</div>
        </div>
        <div class="an-banner-actions">
            <select class="an-period-select" id="periodSelect">
                <option>Last 7 days</option>
                <option selected>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
            <button class="an-export-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export Report
            </button>
        </div>
    </div>

    {{-- Row 1: 4 stat cards --}}
    <div class="an-stats-row1">
        <div class="an-stat s1" style="animation-delay:0s">
            <div class="an-stat-label">Tasks Completed</div>
            <div class="an-stat-value" data-count="{{ $stats['completed'] ?? 24 }}">0</div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                12% vs last period
            </div>
        </div>
        <div class="an-stat s2" style="animation-delay:.06s">
            <div class="an-stat-label">On-Time Rate</div>
            <div class="an-stat-value">{{ $stats['on_time_rate'] ?? 87 }}<span>%</span></div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                4% vs last period
            </div>
        </div>
        <div class="an-stat s3" style="animation-delay:.12s">
            <div class="an-stat-label">Avg. Completion</div>
            <div class="an-stat-value">{{ $stats['avg_days'] ?? 3 }}<span>d</span></div>
            <div class="an-stat-delta down">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                1d slower
            </div>
        </div>
        <div class="an-stat s4" style="animation-delay:.18s">
            <div class="an-stat-label">Active Members</div>
            <div class="an-stat-value" data-count="{{ $stats['active_members'] ?? 6 }}">0</div>
<<<<<<< Updated upstream
            <div class="an-stat-delta up">↑ 2 this period</div>
=======
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                2 this period
            </div>
>>>>>>> Stashed changes
        </div>
    </div>

    {{-- Row 2: overdue only, same column width as row 1 --}}
    <div class="an-stats-row2">
        <div class="an-stat s5" style="animation-delay:.24s">
            <div class="an-stat-label">Overdue Tasks</div>
            <div class="an-stat-value" style="color:var(--red);" data-count="{{ $stats['overdue'] ?? 0 }}">0</div>
            <div class="an-stat-delta {{ ($stats['overdue'] ?? 0) > 0 ? 'down' : 'up' }}">
                @if(($stats['overdue'] ?? 0) > 0)
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                    Need attention
                @else
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    All on track ✓
                @endif
            </div>
        </div>
        <div class="an-stat-ghost"></div>
        <div class="an-stat-ghost"></div>
        <div class="an-stat-ghost"></div>
    </div>

    {{-- Charts --}}
    <div class="an-charts">
        <div class="an-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Tasks Completed Over Time</div>
                    <div class="card-sub">Last 30 days</div>
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
        <div class="an-card">
            <div class="card-header">
                <div>
                    <div class="card-title">By Priority</div>
                    <div class="card-sub">Distribution</div>
                </div>
            </div>
            <div class="doughnut-wrap">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tasks by Status --}}
    <div class="an-card">
        <div class="card-header">
            <div class="card-title">Tasks by Status</div>
        </div>
        <div class="breakdown">
<<<<<<< Updated upstream
            @php
                $breakdown = [
                    ['To Do',      $stats['todo']     ?? 8,  '#2d52c4', 40],
                    ['In Progress',$stats['doing']    ?? 5,  '#0e9f8e', 25],
                    ['In Review',  $stats['review']   ?? 3,  '#c47c0e', 15],
                    ['Done',       $stats['completed']?? 12, '#1a8a5a', 60],
                ];
                $max = max(array_column($breakdown, 1));
            @endphp
            @foreach($breakdown as [$label, $count, $color, $pct])
            <div class="breakdown-item">
                <div class="bd-label">{{ $label }}</div>
                <div class="bd-track">
                    <div class="bd-fill" data-w="{{ $max > 0 ? round(($count/$max)*100) : $pct }}" style="background:{{ $color }};width:0%"></div>
                </div>
                <div class="bd-count">{{ $count }}</div>
=======
            @php $max = $columnBreakdown->max('count') ?: 1; @endphp
            @foreach($columnBreakdown as $col)
            <div class="breakdown-item">
                <div class="bd-label">{{ $col['title'] }}</div>
                <div class="bd-track">
                    <div class="bd-fill" data-w="{{ $max > 0 ? round(($col['count']/$max)*100) : 0 }}" style="background:{{ $col['color'] }};width:0%"></div>
                </div>
                <div class="bd-count">{{ $col['count'] }}</div>
>>>>>>> Stashed changes
            </div>
            @endforeach
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-count]').forEach(el => {
        const t = parseInt(el.dataset.count), s = Math.max(1,Math.ceil(t/40));
        let n = 0;
        const ti = setInterval(()=>{ n=Math.min(n+s,t); el.textContent=n; if(n>=t) clearInterval(ti); },22);
    });

    document.querySelectorAll('.bd-fill').forEach((el,i) => {
        setTimeout(()=>{ el.style.width = el.dataset.w+'%'; }, 350+i*50);
    });

<<<<<<< Updated upstream
    // Line chart
    const labels = Array.from({length:30},(_,i)=>{const d=new Date();d.setDate(d.getDate()-29+i);return d.getDate()+'/'+(d.getMonth()+1);});
    const data   = Array.from({length:30},()=>Math.floor(Math.random()*5)+1);
    new Chart(document.getElementById('lineChart'), {
=======
    Chart.defaults.font.family = "'Inter',sans-serif";
    Chart.defaults.color = '#9ca3af';

    new Chart(document.getElementById('lineChart'),{
>>>>>>> Stashed changes
        type:'line',
        data:{
            labels:{!! json_encode($last30->pluck('date')) !!},
            datasets:[{
                label:'Completed',
                data:{!! json_encode($last30->pluck('count')) !!},
                borderColor:'#2563eb',
                backgroundColor:(ctx)=>{
                    const g=ctx.chart.ctx.createLinearGradient(0,0,0,170);
                    g.addColorStop(0,'rgba(37,99,235,.1)');
                    g.addColorStop(1,'rgba(37,99,235,0)');
                    return g;
                },
                borderWidth:2,tension:.4,fill:true,
                pointRadius:2.5,pointBackgroundColor:'#2563eb',
                pointBorderColor:'#fff',pointBorderWidth:1.5,pointHoverRadius:5,
            }]
        },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{
                legend:{display:false},
                tooltip:{mode:'index',intersect:false,backgroundColor:'#0d1117',titleColor:'#fff',bodyColor:'#9ca3af',padding:9,cornerRadius:7,titleFont:{size:11,weight:'700'},bodyFont:{size:11}}
            },
            scales:{
                x:{grid:{display:false},border:{display:false},ticks:{maxTicksLimit:8,font:{size:10},color:'#9ca3af'}},
                y:{grid:{color:'#f0f2f5'},border:{display:false},ticks:{stepSize:1,font:{size:10},color:'#9ca3af',padding:6}}
            }
        }
    });

    new Chart(document.getElementById('doughnutChart'),{
        type:'doughnut',
        data:{
            labels:['High','Medium','Low'],
            datasets:[{
                data:[{{ $stats['high_priority']??5 }},{{ $stats['medium_priority']??10 }},{{ $stats['low_priority']??7 }}],
                backgroundColor:['#dc2626','#d97706','#0e9f8e'],
                borderWidth:0,hoverOffset:6,
            }]
        },
        options:{
            responsive:false,
            maintainAspectRatio:false,
            cutout:'70%',
            plugins:{
                legend:{position:'bottom',labels:{font:{size:11,weight:'600'},padding:10,usePointStyle:true,pointStyleWidth:7}},
                tooltip:{backgroundColor:'#0d1117',titleColor:'#fff',bodyColor:'#9ca3af',padding:9,cornerRadius:7}
            }
        }
    });

});
</script>
</x-app-layout>