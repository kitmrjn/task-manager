<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">📊</div>
            <div>
                <p class="db-greeting">Performance Overview</p>
                <h2 class="db-title">Analytics</h2>
            </div>
        </div>
        <div>
            <select class="period-select" id="periodSelect">
                <option>Last 7 days</option>
                <option selected>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root {
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;
    --c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;
    --c-green:#1a8a5a;--c-green-lt:#e8f6f0;
    --c-rule:#e8eaf0;--radius:10px;
    --shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);
}
body{background:var(--c-bg);color:var(--c-text);font-family:'Epilogue',sans-serif;}
.db-header-inner{display:flex;justify-content:space-between;align-items:center;}
.db-header-left{display:flex;align-items:center;gap:.9rem;}
.db-avatar{width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center;}
.db-greeting{font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500;}
.db-title{font-size:17px;font-weight:600;color:var(--c-text);}
.period-select{padding:.45rem .9rem;border:1.5px solid var(--c-border-2);border-radius:7px;background:var(--c-white);color:var(--c-navy);font-family:'Epilogue',sans-serif;font-size:12px;font-weight:600;cursor:pointer;outline:none;}

.an-page{padding:2rem 0 3rem;}
.an-wrap{max-width:1100px;margin:0 auto;padding:0 1.5rem;display:flex;flex-direction:column;gap:1.5rem;}

/* Stat row */
.an-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;}
@media(max-width:800px){.an-stats{grid-template-columns:repeat(2,1fr);}}
.an-stat{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.2rem 1.4rem;box-shadow:var(--shadow-sm);animation:fadeUp .4s ease both;position:relative;overflow:hidden;}
.an-stat:nth-child(2){animation-delay:.06s;}.an-stat:nth-child(3){animation-delay:.12s;}.an-stat:nth-child(4){animation-delay:.18s;}
.an-stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;border-radius:var(--radius) var(--radius) 0 0;}
.an-stat.s1::before{background:var(--c-blue);}.an-stat.s2::before{background:var(--c-teal);}
.an-stat.s3::before{background:var(--c-amber);}.an-stat.s4::before{background:var(--c-green);}
.an-stat-label{font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);}
.an-stat-value{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--c-text);line-height:1.1;margin:.2rem 0 .2rem;}
.an-stat-delta{font-size:11px;font-weight:600;}
.an-stat-delta.up{color:var(--c-green);}.an-stat-delta.down{color:var(--c-red);}

/* Charts row */
.an-charts{display:grid;grid-template-columns:2fr 1fr;gap:1rem;}
@media(max-width:800px){.an-charts{grid-template-columns:1fr;}}
.an-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s .2s ease both;}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule);}
.card-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--c-navy);}
.card-sub{font-size:11px;color:var(--c-soft);}
.chart-wrap{padding:1.2rem 1.4rem;}
canvas{width:100%!important;}

/* Bar breakdown */
.breakdown{padding:.5rem 0;}
.breakdown-item{display:flex;align-items:center;gap:1rem;padding:.7rem 1.4rem;border-bottom:1px solid var(--c-rule);}
.breakdown-item:last-child{border-bottom:none;}
.bd-label{font-size:12.5px;font-weight:500;color:var(--c-text);width:110px;flex-shrink:0;}
.bd-track{flex:1;height:7px;background:var(--c-rule);border-radius:99px;overflow:hidden;}
.bd-fill{height:100%;border-radius:99px;transition:width 1s cubic-bezier(.16,1,.3,1);}
.bd-count{font-size:12px;font-weight:600;color:var(--c-muted);width:28px;text-align:right;flex-shrink:0;}

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
</style>

<div class="an-page">
<div class="an-wrap">

    <div class="an-stats">
        <div class="an-stat s1">
            <div class="an-stat-label">Tasks Completed</div>
            <div class="an-stat-value" data-count="{{ $stats['completed'] ?? 24 }}">0</div>
            <div class="an-stat-delta up">↑ 12% vs last period</div>
        </div>
        <div class="an-stat s2">
            <div class="an-stat-label">On-Time Rate</div>
            <div class="an-stat-value">{{ $stats['on_time_rate'] ?? 87 }}<span style="font-size:1rem">%</span></div>
            <div class="an-stat-delta up">↑ 4% vs last period</div>
        </div>
        <div class="an-stat s3">
            <div class="an-stat-label">Avg. Completion</div>
            <div class="an-stat-value">{{ $stats['avg_days'] ?? 3 }}<span style="font-size:1rem">d</span></div>
            <div class="an-stat-delta down">↓ 1d slower</div>
        </div>
        <div class="an-stat s4">
            <div class="an-stat-label">Active Members</div>
            <div class="an-stat-value" data-count="{{ $stats['active_members'] ?? 6 }}">0</div>
            <div class="an-stat-delta up">↑ 2 this period</div>
        </div>
    </div>

    <div class="an-charts">
        <div class="an-card">
            <div class="card-header">
                <div class="card-title">Tasks Completed Over Time</div>
                <div class="card-sub">Last 30 days</div>
            </div>
            <div class="chart-wrap">
                <canvas id="lineChart" height="200"></canvas>
            </div>
        </div>
        <div class="an-card">
            <div class="card-header">
                <div class="card-title">By Priority</div>
                <div class="card-sub">Distribution</div>
            </div>
            <div class="chart-wrap" style="display:flex;justify-content:center;align-items:center;min-height:200px;">
                <canvas id="doughnutChart" height="200" style="max-width:200px;"></canvas>
            </div>
        </div>
    </div>

    <div class="an-card">
        <div class="card-header">
            <div class="card-title">Tasks by Status</div>
        </div>
        <div class="breakdown">
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
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Counters
    document.querySelectorAll('[data-count]').forEach(el => {
        const t = parseInt(el.dataset.count);
        let n = 0; const s = Math.max(1,Math.ceil(t/30));
        const ti = setInterval(()=>{ n=Math.min(n+s,t); el.textContent=n; if(n>=t)clearInterval(ti); },30);
    });

    // Bar fill
    document.querySelectorAll('.bd-fill').forEach(el => {
        setTimeout(()=>{ el.style.width = el.dataset.w + '%'; }, 300);
    });

    // Line chart
    const labels = Array.from({length:30},(_,i)=>{const d=new Date();d.setDate(d.getDate()-29+i);return d.getDate()+'/'+(d.getMonth()+1);});
    const data   = Array.from({length:30},()=>Math.floor(Math.random()*5)+1);
    new Chart(document.getElementById('lineChart'), {
        type:'line',
        data:{ labels, datasets:[{ label:'Completed', data, borderColor:'#2d52c4', backgroundColor:'rgba(45,82,196,0.08)', tension:.4, fill:true, pointRadius:2, pointHoverRadius:5 }] },
        options:{ responsive:true, plugins:{ legend:{display:false}, tooltip:{mode:'index'} }, scales:{ x:{grid:{display:false},ticks:{maxTicksLimit:8,font:{size:10}}}, y:{grid:{color:'#e8eaf0'},ticks:{stepSize:1,font:{size:10}}} } }
    });

    // Doughnut
    new Chart(document.getElementById('doughnutChart'), {
        type:'doughnut',
        data:{
            labels:['High','Medium','Low'],
            datasets:[{ data:[{{ $stats['high_priority'] ?? 5 }},{{ $stats['medium_priority'] ?? 10 }},{{ $stats['low_priority'] ?? 7 }}], backgroundColor:['#c0354a','#c47c0e','#0e9f8e'], borderWidth:0, hoverOffset:6 }]
        },
        options:{ responsive:true, cutout:'70%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:11}, padding:12 } } } }
    });
});
</script>
</x-app-layout>