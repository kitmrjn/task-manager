<x-app-layout>
<x-slot name="header">
    <div class="an-topnav">
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
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');
:root{--bg:#f0f2f6;--white:#fff;--border:#e4e7ec;--text:#0d1117;--muted:#4b5563;--soft:#9ca3af;--blue:#2563eb;--green:#16a34a;--amber:#d97706;--red:#dc2626;--teal:#0e9f8e;--radius:12px;--shadow:0 1px 3px rgba(13,17,23,.06),0 1px 2px rgba(13,17,23,.04);--shadow-md:0 4px 16px rgba(13,17,23,.08);--font:'DM Sans',sans-serif;--font-display:'Syne',sans-serif;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg);font-family:var(--font);color:var(--text);-webkit-font-smoothing:antialiased;font-size:14px;}

.an-topnav{display:flex;align-items:center;padding:0 1.5rem;height:58px;gap:1rem;background:var(--white);border-bottom:1px solid var(--border);}
.an-topnav-right{display:flex;align-items:center;gap:.5rem;margin-left:auto;}
.an-nav-icon-btn{width:34px;height:34px;border-radius:8px;border:1.5px solid var(--border);background:var(--white);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:background .15s;}
.an-nav-icon-btn:hover{background:var(--bg);}
.an-nav-divider{width:1px;height:22px;background:var(--border);flex-shrink:0;}
.an-nav-profile{display:flex;align-items:center;gap:.5rem;cursor:pointer;padding:.3rem .55rem;border-radius:8px;border:1.5px solid transparent;transition:background .18s,border-color .18s;}
.an-nav-profile:hover{background:var(--bg);border-color:var(--border);}
.an-nav-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1b2b5e);color:#fff;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.an-nav-name{font-size:13px;font-weight:600;color:var(--text);line-height:1.2;}
.an-nav-email{font-size:11px;color:var(--soft);}

.an-page{padding:1.75rem 2rem 3rem;max-width:1080px;margin:0 auto;display:flex;flex-direction:column;gap:1.4rem;}

.an-page-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.an-page-eyebrow{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--blue);margin-bottom:.3rem;}
.an-page-title{font-family:var(--font-display);font-size:25px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.1;}
.an-page-sub{font-size:13.5px;color:var(--soft);margin-top:.25rem;}
.an-page-actions{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;}
.an-period-select{padding:.42rem .8rem;border:1.5px solid var(--border);border-radius:8px;background:var(--white);color:var(--text);font-family:var(--font);font-size:12.5px;font-weight:600;cursor:pointer;outline:none;transition:border-color .15s;}
.an-period-select:focus{border-color:var(--blue);}
.an-custom-range{display:flex;align-items:center;gap:.4rem;animation:fadeUp .2s ease;}
.an-date-input{padding:.4rem .65rem;border:1.5px solid var(--border);border-radius:8px;background:var(--white);color:var(--text);font-family:var(--font);font-size:12px;font-weight:500;outline:none;transition:border-color .15s;width:130px;}
.an-date-input:focus{border-color:var(--blue);}
.an-range-sep{font-size:11px;color:var(--soft);font-weight:600;}
.an-apply-btn{padding:.4rem .8rem;border-radius:8px;background:var(--blue);color:#fff;font-family:var(--font);font-size:12px;font-weight:700;border:none;cursor:pointer;transition:opacity .15s;white-space:nowrap;}
.an-apply-btn:hover{opacity:.88;}
.an-export-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.42rem .85rem;border:1.5px solid var(--border);border-radius:8px;background:var(--white);color:var(--muted);font-family:var(--font);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .16s;text-decoration:none;white-space:nowrap;}
.an-export-btn:hover{background:var(--text);color:#fff;border-color:var(--text);}

.an-stats-top{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
.an-stats-bottom{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;max-width:calc(66.66% - .33rem);}
@media(max-width:700px){.an-stats-top{grid-template-columns:repeat(2,1fr);}.an-stats-bottom{grid-template-columns:1fr;max-width:100%;}}

.an-stat{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.35rem 1.4rem 1.2rem;box-shadow:var(--shadow);position:relative;overflow:hidden;animation:fadeUp .35s ease both;transition:box-shadow .18s,transform .18s;}
.an-stat:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);}
.an-stat-accent{position:absolute;top:0;left:0;right:0;height:3px;border-radius:var(--radius) var(--radius) 0 0;}
.an-stat-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--soft);margin-bottom:.55rem;margin-top:.1rem;}
.an-stat-value{font-family:var(--font-display);font-size:2.2rem;font-weight:800;color:var(--text);letter-spacing:-.04em;line-height:1;}
.an-stat-value sup{font-size:1rem;font-weight:700;color:var(--muted);letter-spacing:0;font-family:var(--font);vertical-align:super;}
.an-stat-delta{font-size:12px;font-weight:600;display:flex;align-items:center;gap:.25rem;margin-top:.55rem;}
.an-stat-delta.up{color:var(--green);}
.an-stat-delta.down{color:var(--red);}

.an-charts{display:grid;grid-template-columns:1fr 290px;gap:1rem;align-items:start;}
@media(max-width:768px){.an-charts{grid-template-columns:1fr;}}

.an-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;animation:fadeUp .35s .1s ease both;}
.an-card-header{display:flex;align-items:flex-start;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--border);}
.an-card-title{font-family:var(--font-display);font-size:14.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.an-card-sub{font-size:12px;color:var(--soft);font-weight:400;margin-top:3px;}
.an-chart-wrap{padding:1.1rem 1.4rem 1.3rem;}
#lineChart{display:block;width:100%!important;height:190px!important;}
.an-doughnut-wrap{display:flex;flex-direction:column;align-items:center;padding:1.5rem 1rem 1.3rem;gap:.8rem;}
#doughnutChart{width:195px!important;height:195px!important;}

.breakdown{padding:.2rem 0;}
.breakdown-item{display:flex;align-items:center;gap:.85rem;padding:.75rem 1.4rem;border-bottom:1px solid var(--border);transition:background .14s;}
.breakdown-item:last-child{border-bottom:none;}
.breakdown-item:hover{background:#fafbfc;}
.bd-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.bd-label{font-size:13px;font-weight:600;color:var(--text);width:100px;flex-shrink:0;}
.bd-track{flex:1;height:5px;background:#f0f2f5;border-radius:99px;overflow:hidden;}
.bd-fill{height:100%;border-radius:99px;transition:width 1.1s cubic-bezier(.16,1,.3,1);width:0;}
.bd-count{font-size:12.5px;font-weight:700;color:var(--muted);width:24px;text-align:right;flex-shrink:0;}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;}}
</style>

<div class="an-page">

    {{-- Header --}}
    <div class="an-page-header">
        <div>
            <div class="an-page-eyebrow">Performance Overview</div>
            <div class="an-page-title">Analytics</div>
            <div class="an-page-sub">Insights into your team's performance and project health.</div>
        </div>
        <div class="an-page-actions">
            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                <select class="an-period-select" id="periodSelect" onchange="handlePeriodChange(this.value)">
                    <option value="7"     {{ ($period ?? '30') === '7'      ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30"    {{ ($period ?? '30') === '30'     ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90"    {{ ($period ?? '30') === '90'     ? 'selected' : '' }}>Last 90 days</option>
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

    {{-- Stats row 1: Completed, On-Time, Avg Completion --}}
    <div class="an-stats-top">
        <div class="an-stat" style="animation-delay:0s">
            <div class="an-stat-accent" style="background:var(--blue)"></div>
            <div class="an-stat-label">Tasks Completed</div>
            <div class="an-stat-value" data-count="{{ $stats['completed'] ?? 0 }}">0</div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                this period
            </div>
        </div>
        <div class="an-stat" style="animation-delay:.06s">
            <div class="an-stat-accent" style="background:var(--teal)"></div>
            <div class="an-stat-label">On-Time Rate</div>
            <div class="an-stat-value">{{ $stats['on_time_rate'] ?? 0 }}<sup>%</sup></div>
            <div class="an-stat-delta {{ ($stats['on_time_rate'] ?? 0) >= 70 ? 'up' : 'down' }}">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                of tasks on schedule
            </div>
        </div>
        <div class="an-stat" style="animation-delay:.12s">
            <div class="an-stat-accent" style="background:var(--amber)"></div>
            <div class="an-stat-label">Avg. Completion</div>
            <div class="an-stat-value">{{ $stats['avg_days'] ?? 0 }}<sup>d</sup></div>
            <div class="an-stat-delta {{ ($stats['avg_days'] ?? 0) <= 3 ? 'up' : 'down' }}">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                avg days per task
            </div>
        </div>
    </div>

    {{-- Stats row 2: Active Members, Overdue --}}
    <div class="an-stats-bottom">
        <div class="an-stat" style="animation-delay:.18s">
            <div class="an-stat-accent" style="background:var(--green)"></div>
            <div class="an-stat-label">Active Members</div>
            <div class="an-stat-value" data-count="{{ $stats['active_members'] ?? 0 }}">0</div>
            <div class="an-stat-delta up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                with assigned tasks
            </div>
        </div>
        <div class="an-stat" style="animation-delay:.22s">
            <div class="an-stat-accent" style="background:var(--red)"></div>
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
    <div class="an-card">
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
    if (!from || !to)  { alert('Please select both dates.'); return; }
    if (from > to)     { alert('Start date must be before end date.'); return; }
    window.location.href = '{{ route("analytics.index") }}?period=custom&from=' + from + '&to=' + to;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-count]').forEach(el => {
        const t = parseInt(el.dataset.count), s = Math.max(1, Math.ceil(t / 40));
        let n = 0;
        const ti = setInterval(() => { n = Math.min(n + s, t); el.textContent = n; if (n >= t) clearInterval(ti); }, 22);
    });

    document.querySelectorAll('.bd-fill').forEach((el, i) => {
        setTimeout(() => { el.style.width = el.dataset.w + '%'; }, 350 + i * 50);
    });

    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#9ca3af';

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($last30->pluck('date')) !!},
            datasets: [{
                label: 'Completed',
                data: {!! json_encode($last30->pluck('count')) !!},
                borderColor: '#2563eb',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 170);
                    g.addColorStop(0, 'rgba(37,99,235,.1)');
                    g.addColorStop(1, 'rgba(37,99,235,0)');
                    return g;
                },
                borderWidth: 2, tension: .4, fill: true,
                pointRadius: 2.5, pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff', pointBorderWidth: 1.5, pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false, backgroundColor: '#0d1117', titleColor: '#fff', bodyColor: '#9ca3af', padding: 10, cornerRadius: 8, titleFont: { size: 11, weight: '700' }, bodyFont: { size: 11 } }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 8, font: { size: 10 }, color: '#9ca3af' } },
                y: { grid: { color: '#f0f2f5' }, border: { display: false }, beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 }, color: '#9ca3af', padding: 6 } }
            }
        }
    });

    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                data: [{{ $chartHigh }}, {{ $chartMedium }}, {{ $chartLow }}],
                backgroundColor: ['#dc2626', '#d97706', '#0e9f8e'],
                borderWidth: 0, hoverOffset: 6,
            }]
        },
        options: {
            responsive: false, maintainAspectRatio: false, cutout: '72%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11, weight: '600' }, padding: 12, usePointStyle: true, pointStyleWidth: 7 } },
                tooltip: { backgroundColor: '#0d1117', titleColor: '#fff', bodyColor: '#9ca3af', padding: 10, cornerRadius: 8 }
            }
        }
    });
});
</script>
</x-app-layout>