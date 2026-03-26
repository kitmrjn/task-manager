<x-app-layout>
<x-slot name="header">
    <div class="an-topnav">
        <div class="an-topnav-left">

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
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

:root {
    --bg:        #eef0f6;
    --white:     #ffffff;
    --surface:   #f8f9fc;
    --border:    #e2e5eb;
    --border-2:  #cdd2de;
    --text:      #0d1424;
    --muted:     #4a5270;
    --soft:      #8b94b3;
    --navy:      #1b2b5e;
    --blue:      #2d52c4;
    --blue-lt:   #eaeffc;
    --blue-md:   #3b63d8;
    --teal:      #0e9f8e;
    --teal-lt:   #e5f7f5;
    --amber:     #c47c0e;
    --amber-lt:  #fef4e6;
    --red:       #c0354a;
    --red-lt:    #fdeef1;
    --green:     #1a8a5a;
    --green-lt:  #e7f6ef;
    --rule:      #e5e8f0;
    --radius:    14px;
    --shadow-sm: 0 1px 4px rgba(27,43,94,0.07), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 24px rgba(27,43,94,0.13), 0 2px 8px rgba(0,0,0,0.04);
    --shadow-lg: 0 10px 40px rgba(27,43,94,0.18);
    --font:      'Plus Jakarta Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); font-family: var(--font); color: var(--text); -webkit-font-smoothing: antialiased; font-size: 14px; }

/* ── TOPNAV ─────────────────────────────────────────────────── */
.an-topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 66px; gap: 1rem;
    background: var(--white); border-bottom: 1px solid var(--border);
}
.an-topnav-left { display: flex; flex-direction: column; justify-content: center; }
.an-topnav-eyebrow { font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--blue); line-height: 1; margin-bottom: 2px; }
.an-topnav-heading { font-size: 16px; font-weight: 800; color: var(--navy); letter-spacing: -.02em; line-height: 1; }
.an-topnav-right { display: flex; align-items: center; gap: .5rem; }
.an-nav-icon-btn { width: 36px; height: 36px; border-radius: 9px; border: 1.5px solid var(--border); background: var(--white); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); transition: background .15s, transform .15s; }
.an-nav-icon-btn:hover { background: var(--bg); transform: translateY(-1px); }
.an-nav-divider { width: 1px; height: 24px; background: var(--border); flex-shrink: 0; margin: 0 .25rem; }
.an-nav-profile { display: flex; align-items: center; gap: .55rem; cursor: pointer; padding: .35rem .65rem; border-radius: 10px; border: 1.5px solid transparent; transition: background .18s, border-color .18s; }
.an-nav-profile:hover { background: var(--bg); border-color: var(--border); }
.an-nav-avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--blue-md), var(--navy)); color: #fff; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(45,82,196,.3); }
.an-nav-name  { display: block; font-size: 13.5px; font-weight: 700; color: var(--text); line-height: 1.2; }
.an-nav-email { display: block; font-size: 11px; color: var(--soft); }

/* ── PAGE SHELL ─────────────────────────────────────────────── */
.an-page { padding: 2rem 2rem 3rem; max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }

/* ── PAGE HEADER ────────────────────────────────────────────── */
.an-page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; animation: fadeUp .4s ease both; }
.an-page-title  { font-size: 26px; font-weight: 800; color: var(--navy); letter-spacing: -.03em; line-height: 1.1; }
.an-page-sub    { font-size: 13.5px; color: var(--soft); margin-top: .3rem; font-weight: 500; }
.an-page-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }

.an-period-select {
    padding: .5rem .9rem; border: 1.5px solid var(--border); border-radius: 9px;
    background: var(--white); color: var(--text); font-family: var(--font);
    font-size: 13px; font-weight: 600; cursor: pointer; outline: none;
    transition: border-color .15s, box-shadow .15s;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b94b3' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right .7rem center;
    padding-right: 2rem;
}
.an-period-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(45,82,196,.1); }

.an-custom-range { display: flex; align-items: center; gap: .4rem; animation: fadeUp .2s ease; }
.an-date-input { padding: .48rem .7rem; border: 1.5px solid var(--border); border-radius: 9px; background: var(--white); color: var(--text); font-family: var(--font); font-size: 12.5px; font-weight: 500; outline: none; transition: border-color .15s; width: 135px; }
.an-date-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(45,82,196,.1); }
.an-range-sep { font-size: 11px; color: var(--soft); font-weight: 700; }
.an-apply-btn { padding: .48rem .9rem; border-radius: 9px; background: var(--blue); color: #fff; font-family: var(--font); font-size: 12.5px; font-weight: 700; border: none; cursor: pointer; transition: background .15s, transform .15s; white-space: nowrap; }
.an-apply-btn:hover { background: var(--blue-md); transform: translateY(-1px); }

.an-export-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem .9rem; border: 1.5px solid var(--border);
    border-radius: 9px; background: var(--white); color: var(--muted);
    font-family: var(--font); font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .16s; text-decoration: none; white-space: nowrap;
}
.an-export-btn:hover { background: var(--navy); color: #fff; border-color: var(--navy); transform: translateY(-1px); }

/* ── STAT GRID ──────────────────────────────────────────────── */
.an-stat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}
@media (max-width: 1100px) { .an-stat-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 700px)  { .an-stat-grid { grid-template-columns: repeat(2, 1fr); } }

.an-stat {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 1.4rem 1.5rem 1.25rem;
    box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
    animation: fadeUp .4s ease both;
    transition: box-shadow .2s, transform .2s;
}
.an-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }
.an-stat:nth-child(1) { animation-delay: .04s; }
.an-stat:nth-child(2) { animation-delay: .08s; }
.an-stat:nth-child(3) { animation-delay: .12s; }
.an-stat:nth-child(4) { animation-delay: .16s; }
.an-stat:nth-child(5) { animation-delay: .20s; }

/* Colored left border accent */
.an-stat::before {
    content: ''; position: absolute; top: 0; left: 0; bottom: 0;
    width: 3px; border-radius: var(--radius) 0 0 var(--radius);
}
.an-stat.ac-blue::before   { background: var(--blue); }
.an-stat.ac-teal::before   { background: var(--teal); }
.an-stat.ac-amber::before  { background: var(--amber); }
.an-stat.ac-green::before  { background: var(--green); }
.an-stat.ac-red::before    { background: var(--red); }

/* Subtle background circle decoration */
.an-stat::after {
    content: ''; position: absolute; right: -20px; top: -20px;
    width: 80px; height: 80px; border-radius: 50%;
    opacity: .06; pointer-events: none;
}
.an-stat.ac-blue::after   { background: var(--blue); }
.an-stat.ac-teal::after   { background: var(--teal); }
.an-stat.ac-amber::after  { background: var(--amber); }
.an-stat.ac-green::after  { background: var(--green); }
.an-stat.ac-red::after    { background: var(--red); }

.an-stat-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: .9rem; flex-shrink: 0;
}
.an-stat.ac-blue  .an-stat-icon { background: var(--blue-lt);  color: var(--blue); }
.an-stat.ac-teal  .an-stat-icon { background: var(--teal-lt);  color: var(--teal); }
.an-stat.ac-amber .an-stat-icon { background: var(--amber-lt); color: var(--amber); }
.an-stat.ac-green .an-stat-icon { background: var(--green-lt); color: var(--green); }
.an-stat.ac-red   .an-stat-icon { background: var(--red-lt);   color: var(--red); }

.an-stat-label { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .11em; color: var(--soft); margin-bottom: .5rem; }
.an-stat-value { font-size: 2rem; font-weight: 800; color: var(--navy); letter-spacing: -.04em; line-height: 1; margin-bottom: .5rem; }
.an-stat-value sup { font-size: .9rem; font-weight: 700; color: var(--muted); letter-spacing: 0; font-family: var(--font); vertical-align: super; }
.an-stat-delta { font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: .25rem; }
.an-stat-delta.up   { color: var(--green); }
.an-stat-delta.down { color: var(--red); }

/* ── CHARTS ROW ─────────────────────────────────────────────── */
.an-charts { display: grid; grid-template-columns: 1fr 300px; gap: 1rem; align-items: start; }
@media (max-width: 860px) { .an-charts { grid-template-columns: 1fr; } }

.an-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    overflow: hidden; animation: fadeUp .4s .15s ease both;
    transition: box-shadow .2s, transform .2s;
}
.an-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

.an-card-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--rule);
}
.an-card-title { font-size: 15px; font-weight: 800; color: var(--navy); letter-spacing: -.01em; }
.an-card-sub   { font-size: 12px; color: var(--soft); font-weight: 500; margin-top: 3px; }
.an-chart-wrap { padding: 1.1rem 1.5rem 1.4rem; }
#lineChart { display: block; width: 100% !important; height: 200px !important; }

.an-doughnut-wrap { display: flex; flex-direction: column; align-items: center; padding: 1.5rem 1rem 1.2rem; gap: .85rem; }
#doughnutChart { width: 180px !important; height: 180px !important; }

/* ── BREAKDOWN ──────────────────────────────────────────────── */
.an-card.breakdown-card { animation-delay: .22s; }
.breakdown { padding: .3rem 0; }
.breakdown-item {
    display: flex; align-items: center; gap: 1rem;
    padding: .85rem 1.5rem; border-bottom: 1px solid var(--rule);
    transition: background .14s;
}
.breakdown-item:last-child { border-bottom: none; }
.breakdown-item:hover { background: var(--surface); }
.bd-dot   { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.bd-label { font-size: 13.5px; font-weight: 700; color: var(--text); width: 110px; flex-shrink: 0; }
.bd-track { flex: 1; height: 6px; background: var(--bg); border-radius: 99px; overflow: hidden; }
.bd-fill  { height: 100%; border-radius: 99px; transition: width 1.1s cubic-bezier(.16,1,.3,1); width: 0; }
.bd-count { font-size: 13px; font-weight: 800; color: var(--muted); width: 28px; text-align: right; flex-shrink: 0; }

/* ── SECTION LABEL ──────────────────────────────────────────── */
.an-section-label {
    font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .14em; color: var(--soft);
    display: flex; align-items: center; gap: .65rem;
    animation: fadeUp .4s .1s ease both;
}
.an-section-label::after { content: ''; flex: 1; height: 1px; background: var(--rule); }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
</style>

<div class="an-page">

    {{-- Header --}}
    <div class="an-page-header">
        <div>
            <div class="an-page-title">Analytics</div>
            <div class="an-page-sub">Insights into your team's performance and project health.</div>
        </div>
        <div class="an-page-actions">
            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                <select class="an-period-select" id="periodSelect" onchange="handlePeriodChange(this.value)">
                    <option value="7"      {{ ($period ?? '30') === '7'      ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30"     {{ ($period ?? '30') === '30'     ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90"     {{ ($period ?? '30') === '90'     ? 'selected' : '' }}>Last 90 days</option>
                    <option value="custom" {{ ($period ?? '30') === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
                <div class="an-custom-range" id="customRange" style="{{ ($period ?? '30') === 'custom' ? '' : 'display:none' }}">
                    <input type="date" class="an-date-input" id="fromDate"
                        value="{{ ($period ?? '30') === 'custom' && isset($from) ? $from->format('Y-m-d') : '' }}">
                    <span class="an-range-sep">→</span>
                    <input type="date" class="an-date-input" id="toDate"
                        value="{{ ($period ?? '30') === 'custom' && isset($to) ? $to->format('Y-m-d') : '' }}">
                    <button class="an-apply-btn" onclick="applyCustomRange()">Apply</button>
                </div>
            </div>
            <a class="an-export-btn" href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- Section label --}}

    {{-- Stats — all 5 in one row --}}
    <div class="an-stat-grid">

        <div class="an-stat ac-blue">
            <div class="an-stat-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
            <div class="an-stat-label">Tasks Completed</div>
            <div class="an-stat-value" data-count="{{ $stats['completed'] ?? 0 }}">0</div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                this period
            </div>
        </div>

        <div class="an-stat ac-teal">
            <div class="an-stat-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="an-stat-label">On-Time Rate</div>
            <div class="an-stat-value">{{ $stats['on_time_rate'] ?? 0 }}<sup>%</sup></div>
            <div class="an-stat-delta {{ ($stats['on_time_rate'] ?? 0) >= 70 ? 'up' : 'down' }}">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                of tasks on schedule
            </div>
        </div>

        <div class="an-stat ac-amber">
            <div class="an-stat-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="an-stat-label">Avg. Completion</div>
            <div class="an-stat-value">{{ $stats['avg_days'] ?? 0 }}<sup>d</sup></div>
            <div class="an-stat-delta {{ ($stats['avg_days'] ?? 0) <= 3 ? 'up' : 'down' }}">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                avg days per task
            </div>
        </div>

        <div class="an-stat ac-green">
            <div class="an-stat-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="an-stat-label">Active Members</div>
            <div class="an-stat-value" data-count="{{ $stats['active_members'] ?? 0 }}">0</div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                with assigned tasks
            </div>
        </div>

        <div class="an-stat ac-red">
            <div class="an-stat-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="an-stat-label">Overdue Tasks</div>
            <div class="an-stat-value" style="{{ ($stats['overdue'] ?? 0) > 0 ? 'color:var(--red)' : '' }}" data-count="{{ $stats['overdue'] ?? 0 }}">0</div>
            <div class="an-stat-delta {{ ($stats['overdue'] ?? 0) > 0 ? 'down' : 'up' }}">
                @if(($stats['overdue'] ?? 0) > 0)
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                    need attention
                @else
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    all on track ✓
                @endif
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <div class="an-section-label">Charts</div>
    <div class="an-charts">
        <div class="an-card">
            <div class="an-card-header">
                <div>
                    <div class="an-card-title">Tasks Completed Over Time</div>
                    <div class="an-card-sub">{{ isset($from) ? $from->format('M d') . ' – ' . $to->format('M d, Y') : 'Last 30 days' }}</div>
                </div>
            </div>
            <div class="an-chart-wrap">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
        <div class="an-card">
            <div class="an-card-header">
                <div>
                    <div class="an-card-title">By Priority</div>
                    <div class="an-card-sub">Distribution</div>
                </div>
            </div>
            <div class="an-doughnut-wrap">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tasks by Status --}}
    <div class="an-section-label">By Status</div>
    <div class="an-card breakdown-card">
        <div class="an-card-header">
            <div>
                <div class="an-card-title">Tasks by Status</div>
                <div class="an-card-sub">Breakdown across all columns</div>
            </div>
        </div>
        <div class="breakdown">
            @php $max = $columnBreakdown->max('count') ?: 1; @endphp
            @foreach($columnBreakdown as $col)
            <div class="breakdown-item">
                <div class="bd-dot" style="background:{{ $col['color'] }}"></div>
                <div class="bd-label">{{ $col['title'] }}</div>
                <div class="bd-track">
                    <div class="bd-fill" data-w="{{ $max > 0 ? round(($col['count']/$max)*100) : 0 }}" style="background:{{ $col['color'] }};width:0%"></div>
                </div>
                <div class="bd-count">{{ $col['count'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@php
    $chartHigh   = $stats['high_priority']   ?? 0;
    $chartMedium = $stats['medium_priority'] ?? 0;
    $chartLow    = $stats['low_priority']    ?? 0;
@endphp

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
function handlePeriodChange(val) {
    if (val === 'custom') {
        document.getElementById('customRange').style.display = 'flex';
    } else {
        document.getElementById('customRange').style.display = 'none';
        window.location.href = '{{ route("analytics.index") }}?period=' + val;
    }
}
function applyCustomRange() {
    const from = document.getElementById('fromDate').value;
    const to   = document.getElementById('toDate').value;
    if (!from || !to) { alert('Please select both dates.'); return; }
    if (from > to)    { alert('Start date must be before end date.'); return; }
    window.location.href = '{{ route("analytics.index") }}?period=custom&from=' + from + '&to=' + to;
}

document.addEventListener('DOMContentLoaded', () => {

    // Animated counters
    document.querySelectorAll('[data-count]').forEach(el => {
        const t = parseInt(el.dataset.count);
        if (!t) return;
        const s = Math.max(1, Math.ceil(t / 45));
        let n = 0;
        const ti = setInterval(() => { n = Math.min(n + s, t); el.textContent = n; if (n >= t) clearInterval(ti); }, 20);
    });

    // Progress bars
    document.querySelectorAll('.bd-fill').forEach((el, i) => {
        setTimeout(() => { el.style.width = el.dataset.w + '%'; }, 400 + i * 60);
    });

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#8b94b3';

    // Line chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($last30->pluck('date')) !!},
            datasets: [{
                label: 'Completed',
                data: {!! json_encode($last30->pluck('count')) !!},
                borderColor: '#2d52c4',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 180);
                    g.addColorStop(0, 'rgba(45,82,196,.12)');
                    g.addColorStop(1, 'rgba(45,82,196,0)');
                    return g;
                },
                borderWidth: 2.5, tension: .4, fill: true,
                pointRadius: 3, pointBackgroundColor: '#2d52c4',
                pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 5.5,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index', intersect: false,
                    backgroundColor: '#0d1424', titleColor: '#fff', bodyColor: '#8b94b3',
                    padding: 11, cornerRadius: 9,
                    titleFont: { size: 11, weight: '700' }, bodyFont: { size: 11 }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 8, font: { size: 10.5 }, color: '#8b94b3' } },
                y: { grid: { color: '#f0f2f5' }, border: { display: false }, beginAtZero: true, ticks: { stepSize: 1, font: { size: 10.5 }, color: '#8b94b3', padding: 6 } }
            }
        }
    });

    // Doughnut chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                data: [{{ $chartHigh }}, {{ $chartMedium }}, {{ $chartLow }}],
                backgroundColor: ['#c0354a', '#c47c0e', '#0e9f8e'],
                borderWidth: 0, hoverOffset: 7,
            }]
        },
        options: {
            responsive: false, maintainAspectRatio: false, cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11.5, weight: '600' }, padding: 14, usePointStyle: true, pointStyleWidth: 8 } },
                tooltip: { backgroundColor: '#0d1424', titleColor: '#fff', bodyColor: '#8b94b3', padding: 11, cornerRadius: 9 }
            }
        }
    });
});
</script>
</x-app-layout>