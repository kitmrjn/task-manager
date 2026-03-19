<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <p class="db-greeting">{{ now()->format('l, F j') }}</p>
                <h2 class="db-title">Welcome back, {{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="db-header-right">
            <a href="{{ route('tasks.index') }}" class="db-board-btn">View Board →</a>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');

:root {
    --c-bg:       #f4f5f7;
    --c-white:    #ffffff;
    --c-surface:  #fafbfc;
    --c-border:   #e2e5eb;
    --c-border-2: #d0d4dd;
    --c-text:     #1a1e2e;
    --c-muted:    #6b7491;
    --c-soft:     #9ba3be;
    --c-navy:     #1b2b5e;
    --c-blue:     #2d52c4;
    --c-blue-lt:  #ebeffa;
    --c-teal:     #0e9f8e;
    --c-teal-lt:  #e6f7f5;
    --c-amber:    #c47c0e;
    --c-amber-lt: #fef5e6;
    --c-red:      #c0354a;
    --c-red-lt:   #fdeef1;
    --c-green:    #1a8a5a;
    --c-green-lt: #e8f6f0;
    --c-rule:     #e8eaf0;
    --radius:     10px;
    --radius-sm:  7px;
    --shadow-sm:  0 1px 4px rgba(27,43,94,0.07), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(27,43,94,0.10), 0 1px 4px rgba(0,0,0,0.04);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--c-bg); color: var(--c-text); font-family: 'Epilogue', sans-serif; }

/* Header */
.db-header-inner { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
.db-header-left  { display: flex; align-items: center; gap: .9rem; }
.db-avatar {
    width: 44px; height: 44px; border-radius: 10px;
    background: var(--c-navy); color: #fff;
    font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-greeting { font-size: 11px; color: var(--c-soft); letter-spacing: .05em; text-transform: uppercase; font-weight: 500; }
.db-title    { font-size: 17px; font-weight: 600; color: var(--c-text); }
.db-board-btn {
    display: inline-block; padding: .48rem 1.1rem;
    border: 1.5px solid var(--c-border-2); border-radius: 7px;
    font-size: 12px; font-weight: 600; color: var(--c-navy);
    text-decoration: none;
    transition: background .18s, border-color .18s;
    background: var(--c-white);
}
.db-board-btn:hover { background: var(--c-navy); color: #fff; border-color: var(--c-navy); }

/* Page shell */
.db-page { padding: 2rem 0 3rem; }
.db-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }

/* Section eyebrow */
.section-eyebrow {
    font-size: 10px; font-weight: 600; text-transform: uppercase;
    letter-spacing: .14em; color: var(--c-soft); margin-bottom: .75rem;
    display: flex; align-items: center; gap: .5rem;
}
.section-eyebrow::after { content: ''; flex: 1; height: 1px; background: var(--c-rule); }

/* Stat Cards */
.stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; }
@media (max-width: 900px) { .stat-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 520px) { .stat-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: var(--c-white); border: 1px solid var(--c-border);
    border-radius: var(--radius); padding: 1.35rem 1.5rem 1.2rem;
    position: relative; overflow: hidden;
    box-shadow: var(--shadow-sm);
    animation: fadeUp .45s ease both;
    transition: box-shadow .2s, transform .2s;
}
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-card:nth-child(2) { animation-delay: .07s; }
.stat-card:nth-child(3) { animation-delay: .14s; }
.stat-card:nth-child(4) { animation-delay: .21s; }
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: var(--radius) var(--radius) 0 0;
}
.stat-card.s-blue::before  { background: var(--c-blue); }
.stat-card.s-teal::before  { background: var(--c-teal); }
.stat-card.s-amber::before { background: var(--c-amber); }
.stat-card.s-red::before   { background: var(--c-red); }

.stat-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: .9rem; }
.s-blue  .stat-icon { background: var(--c-blue-lt); }
.s-teal  .stat-icon { background: var(--c-teal-lt); }
.s-amber .stat-icon { background: var(--c-amber-lt); }
.s-red   .stat-icon { background: var(--c-red-lt); }
.stat-label { font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: var(--c-muted); }
.stat-value { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; color: var(--c-text); line-height: 1.1; margin: .2rem 0 .25rem; }
.stat-sub   { font-size: 11px; color: var(--c-soft); }

/* Progress card */
.prog-card {
    background: var(--c-white); border: 1px solid var(--c-border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    padding: 1.25rem 1.4rem;
    animation: fadeUp .45s .1s ease both;
}
.prog-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.prog-title  { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--c-navy); }
.prog-pct    { font-size: 22px; font-family: 'Playfair Display', serif; font-weight: 700; color: var(--c-blue); }
.prog-track  { height: 8px; background: var(--c-bg); border-radius: 99px; overflow: hidden; border: 1px solid var(--c-rule); }
.prog-fill   { height: 100%; width: 0%; border-radius: 99px; background: linear-gradient(90deg, var(--c-blue), var(--c-teal)); transition: width 1.1s cubic-bezier(.16,1,.3,1); }
.prog-legend { display: flex; gap: 1.5rem; margin-top: .75rem; flex-wrap: wrap; }
.prog-legend-item { display: flex; align-items: center; gap: .4rem; font-size: 11.5px; color: var(--c-muted); font-weight: 500; }
.prog-legend-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

/* Two-column layout */
.body-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; align-items: start; }
@media (max-width: 900px) { .body-grid { grid-template-columns: 1fr; } }

/* Card shell */
.card { background: var(--c-white); border: 1px solid var(--c-border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; animation: fadeUp .45s .22s ease both; }
.card-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--c-rule); }
.card-title  { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--c-navy); }
.card-link   { font-size: 11px; font-weight: 600; color: var(--c-blue); text-decoration: none; letter-spacing: .03em; }
.card-link:hover { text-decoration: underline; }

/* Task table */
.task-table { width: 100%; border-collapse: collapse; }
.task-table thead th {
    font-size: 10px; text-transform: uppercase; letter-spacing: .1em;
    font-weight: 600; color: var(--c-soft);
    padding: .6rem 1.4rem; text-align: left;
    background: var(--c-surface); border-bottom: 1px solid var(--c-rule);
}
.task-row { border-bottom: 1px solid var(--c-rule); transition: background .15s; animation: fadeIn .35s ease both; }
.task-row:last-child { border-bottom: none; }
.task-row:hover { background: #f6f8ff; }
.task-row:nth-child(1) { animation-delay: .28s; }
.task-row:nth-child(2) { animation-delay: .34s; }
.task-row:nth-child(3) { animation-delay: .40s; }
.task-row:nth-child(4) { animation-delay: .46s; }
.task-row:nth-child(5) { animation-delay: .52s; }
.task-row td { padding: .85rem 1.4rem; vertical-align: middle; }
.task-name  { font-size: 13px; font-weight: 500; color: var(--c-text); }
.task-board { font-size: 11px; color: var(--c-soft); margin-top: 2px; }

.pill { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 4px; font-size: 10.5px; font-weight: 600; letter-spacing: .04em; white-space: nowrap; }
.pill-todo   { background: #f0f1f4; color: #6b7491; }
.pill-doing  { background: var(--c-blue-lt); color: var(--c-blue); }
.pill-review { background: var(--c-amber-lt); color: var(--c-amber); }
.pill-done   { background: var(--c-green-lt); color: var(--c-green); }

.prio { display: inline-flex; align-items: center; gap: .35rem; font-size: 11.5px; font-weight: 500; }
.prio-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.prio.high   .prio-dot { background: var(--c-red); }
.prio.medium .prio-dot { background: var(--c-amber); }
.prio.low    .prio-dot { background: var(--c-teal); }
.prio.high   { color: var(--c-red); }
.prio.medium { color: var(--c-amber); }
.prio.low    { color: var(--c-teal); }

.due { font-size: 11.5px; font-weight: 500; display: flex; align-items: center; gap: .3rem; }
.due.overdue { color: var(--c-red); }
.due.soon    { color: var(--c-amber); }
.due.ok      { color: var(--c-soft); }

.empty-row td { padding: 2.5rem 1.4rem; text-align: center; color: var(--c-soft); font-size: 13px; }

/* Activity */
.activity-card { animation-delay: .28s; }
.activity-item {
    display: flex; gap: .85rem; align-items: flex-start;
    padding: .85rem 1.4rem; border-bottom: 1px solid var(--c-rule);
    transition: background .15s;
    animation: fadeIn .35s ease both;
}
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: var(--c-surface); }
.activity-item:nth-child(1) { animation-delay: .35s; }
.activity-item:nth-child(2) { animation-delay: .41s; }
.activity-item:nth-child(3) { animation-delay: .47s; }
.activity-item:nth-child(4) { animation-delay: .53s; }
.activity-item:nth-child(5) { animation-delay: .59s; }
.act-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.act-icon.created  { background: var(--c-blue-lt); }
.act-icon.moved    { background: #f0edfc; }
.act-icon.done     { background: var(--c-green-lt); }
.act-icon.assigned { background: var(--c-amber-lt); }
.act-body  { flex: 1; min-width: 0; }
.act-text  { font-size: 12.5px; color: var(--c-muted); line-height: 1.45; }
.act-text strong { color: var(--c-text); font-weight: 600; }
.act-time  { font-size: 10.5px; color: var(--c-soft); margin-top: 3px; display: block; font-weight: 500; }

/* Animations */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
</style>

<div class="db-page">
<div class="db-wrap">

    {{-- STAT CARDS --}}
    @php
        $total     = $stats['total'] ?? 0;
        $myTasks_c = $stats['my_tasks'] ?? 0;
        $completed = $stats['completed'] ?? 0;
        $highPrio  = $stats['high_priority'] ?? 0;
        $pct       = $total > 0 ? round(($completed / $total) * 100) : 0;
    @endphp

    <div>
        <div class="section-eyebrow">At a Glance</div>
        <div class="stat-grid">
            <div class="stat-card s-blue">
                <div class="stat-icon">📋</div>
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value" data-count="{{ $total }}">0</div>
                <div class="stat-sub">across all boards</div>
            </div>
            <div class="stat-card s-teal">
                <div class="stat-icon">👤</div>
                <div class="stat-label">Assigned to Me</div>
                <div class="stat-value" data-count="{{ $myTasks_c }}">0</div>
                <div class="stat-sub">active tasks</div>
            </div>
            <div class="stat-card s-amber">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Completed</div>
                <div class="stat-value" data-count="{{ $completed }}">0</div>
                <div class="stat-sub">tasks closed</div>
            </div>
            <div class="stat-card s-red">
                <div class="stat-icon">⚑</div>
                <div class="stat-label">High Priority</div>
                <div class="stat-value" data-count="{{ $highPrio }}">0</div>
                <div class="stat-sub">require attention</div>
            </div>
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    <div class="prog-card">
        <div class="prog-header">
            <div class="prog-title">Project Completion</div>
            <div class="prog-pct">{{ $pct }}%</div>
        </div>
        <div class="prog-track">
            <div class="prog-fill" data-width="{{ $pct }}"></div>
        </div>
        <div class="prog-legend">
            <div class="prog-legend-item">
                <div class="prog-legend-dot" style="background:var(--c-blue)"></div>
                {{ $completed }} Completed
            </div>
            <div class="prog-legend-item">
                <div class="prog-legend-dot" style="background:var(--c-rule);border:1px solid var(--c-border-2)"></div>
                {{ $total - $completed }} Remaining
            </div>
            <div class="prog-legend-item">
                <div class="prog-legend-dot" style="background:var(--c-red)"></div>
                {{ $highPrio }} High Priority
            </div>
        </div>
    </div>

    {{-- TASKS + ACTIVITY --}}
    <div class="body-grid">

        {{-- My Tasks --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">My Tasks</div>
                <a href="{{ route('tasks.index') }}" class="card-link">View all →</a>
            </div>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTasks as $task)
                        @php
                            $priority = strtolower($task->priority ?? 'medium');
                            $colTitle = $task->column->title ?? 'To Do';
                            $colSlug  = strtolower(str_replace([' ','-'], '', $colTitle));
                            $pillClass = match(true) {
                                str_contains($colSlug,'done')    => 'pill-done',
                                str_contains($colSlug,'review')  => 'pill-review',
                                str_contains($colSlug,'doing') || str_contains($colSlug,'progress') => 'pill-doing',
                                default => 'pill-todo'
                            };
                            $dueDate  = $task->due_date ?? null;
                            $dueClass = 'ok'; $dueLabel = '—';
                            if ($dueDate) {
                                $diff = now()->diffInDays(\Carbon\Carbon::parse($dueDate), false);
                                if ($diff < 0)      { $dueClass = 'overdue'; $dueLabel = 'Overdue'; }
                                elseif ($diff <= 2) { $dueClass = 'soon';    $dueLabel = 'Due ' . \Carbon\Carbon::parse($dueDate)->format('M j'); }
                                else                { $dueClass = 'ok';      $dueLabel = \Carbon\Carbon::parse($dueDate)->format('M j, Y'); }
                            }
                        @endphp
                        <tr class="task-row">
                            <td>
                                <div class="task-name">{{ $task->title }}</div>
                                <div class="task-board">{{ $task->board->title ?? 'General Board' }}</div>
                            </td>
                            <td><span class="pill {{ $pillClass }}">{{ $colTitle }}</span></td>
                            <td>
                                <span class="prio {{ $priority }}">
                                    <span class="prio-dot"></span>{{ ucfirst($priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="due {{ $dueClass }}">
                                    @if($dueClass === 'overdue') ⚠ @elseif($dueClass === 'soon') ◷ @else ○ @endif
                                    {{ $dueLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="4">No tasks assigned to you at this time.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Activity Feed --}}
        <div class="card activity-card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
            </div>
            @forelse($recentActivity ?? [] as $activity)
@php
    $type    = $activity->type ?? 'created';
    $iconMap = [
        'created'     => '✦', 
        'moved'       => '⇄', 
        'done'        => '✓', 
        'assigned'    => '◈',
        'commented'   => '💬', // Added
        'updated'     => '✎', // Added
        'checklist'   => '📋'  // Added
    ];
    $icon = $iconMap[$type] ?? '·';
@endphp
                <div class="activity-item">
                    <div class="act-icon {{ $type }}">{{ $icon }}</div>
                    <div class="act-body">
                        <div class="act-text">
                            <strong>{{ $activity->user->name ?? 'Someone' }}</strong>
                            {{ $activity->description ?? 'performed an action' }}
                        </div>
                        <span class="act-time">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                @forelse($myTasks->take(5) as $task)
                    <div class="activity-item">
                        <div class="act-icon assigned">◈</div>
                        <div class="act-body">
                            <div class="act-text">You were assigned <strong>{{ Str::limit($task->title, 34) }}</strong></div>
                            <span class="act-time">{{ $task->created_at ? \Carbon\Carbon::parse($task->created_at)->diffForHumans() : 'recently' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="act-body"><div class="act-text" style="text-align:center;padding:.75rem 0;color:var(--c-soft)">No recent activity.</div></div>
                    </div>
                @endforelse
            @endforelse
        </div>

    </div>{{-- /.body-grid --}}
</div>{{-- /.db-wrap --}}
</div>{{-- /.db-page --}}

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Animated counters
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (!target) return;
        let n = 0;
        const step  = Math.max(1, Math.ceil(target / 36));
        const timer = setInterval(() => {
            n = Math.min(n + step, target);
            el.textContent = n;
            if (n >= target) clearInterval(timer);
        }, 28);
    });

    // Progress bar sweep
    const fill = document.querySelector('.prog-fill');
    if (fill) setTimeout(() => { fill.style.width = fill.dataset.width + '%'; }, 250);
});
</script>

</x-app-layout>