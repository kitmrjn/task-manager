<x-app-layout>
    @section('title', 'Dashboard')
<x-slot name="header">

    <div class="tk-topnav">
        {{-- Right side --}}

        <div class="tk-topnav-right">

            {{-- Notifications --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-topnav-icon" id="notif-btn" title="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <div class="tk-dropdown" id="notif-dropdown">
                    <div class="tk-dropdown-header">
                        <span class="tk-dropdown-title">Notifications</span>
                    </div>
                    <div class="tk-dropdown-body">
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-blue">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Task completed</strong> — "Design review" was marked done</div>
                                <div class="tk-notif-time">2 minutes ago</div>
                            </div>
                        </div>
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-amber">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Assigned to you</strong> — "API integration sprint"</div>
                                <div class="tk-notif-time">1 hour ago</div>
                            </div>
                        </div>
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-red">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Overdue</strong> — "Client presentation" is past due</div>
                                <div class="tk-notif-time">Yesterday</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="tk-topnav-divider"></div>

            {{-- Profile Dropdown --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-topnav-user" id="profile-btn">
                    <div class="tk-topnav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="tk-topnav-userinfo">
                        <span class="tk-topnav-username">{{ Auth::user()->name }}</span>
                        <span class="tk-topnav-email">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="tk-chevron" id="profile-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="tk-dropdown tk-profile-dropdown" id="profile-dropdown">
                    <div class="tk-dropdown-header tk-profile-header">
                        <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <div class="tk-dropdown-title">{{ Auth::user()->name }}</div>
                            <div class="tk-profile-meta">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="tk-dropdown-body" style="padding:.4rem 0;">
                        <a href="{{ route('settings.index') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            My Profile & Settings
                        </a>
                        
                        {{-- RBAC Check for Team Logs --}}
                        @if(Auth::user()->isAtLeastManager())
                            <a href="{{ route('team-logs.index') }}" class="tk-profile-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                Team Time Logs
                            </a>
                        @endif

                        <div class="tk-profile-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tk-profile-item tk-profile-item--danger">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-slot>

{{-- ── Inject dashboard-specific CSS & JS via Vite ── --}}
@push('styles')
    @vite('resources/css/dashboard.css')
@endpush

@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush

<div class="db-page">
<div class="db-wrap">

    {{-- ── GREETING BANNER ────────────────────────────────────── --}}
    <div class="db-greeting-banner">
        <div class="db-greeting-left">
            <div class="db-greeting-eyebrow">{{ now()->format('l, F j') }}</div>
            <div class="db-greeting-name">{{ $greeting }}, <em>{{ $firstName }}.</em></div>
            <div class="db-greeting-sub">Here's what's happening across your projects today.</div>
        </div>
        <div class="db-greeting-right">
            <div class="db-greeting-pill">
                <span>{{ $stats['my_tasks'] }}</span> tasks assigned
            </div>
            <div class="db-greeting-pill">
                <span>{{ $stats['high_priority'] }}</span> high priority
            </div>
        </div>
    </div>

    {{-- ── ADVANCED TIME TRACKING (EOD) WIDGET ── --}}
    @php
        $activeBreak = $todaysLog ? $todaysLog->breaks()->whereNull('end_time')->first() : null;
        $totalBreakSecs = $todaysLog ? $todaysLog->breaks()->whereNotNull('end_time')->sum('duration_minutes') * 60 : 0;
        
        $stateColor = 'var(--c-navy)';
        $statusText = 'Not Started';
        
        if($todaysLog && !$todaysLog->time_out && !$activeBreak) { $stateColor = '#16a34a'; $statusText = 'Active Shift'; }
        if($activeBreak) { $stateColor = '#d97706'; $statusText = 'On Break'; }
        if($todaysLog && $todaysLog->time_out) { $stateColor = '#dc2626'; $statusText = 'Timed Out'; }
    @endphp

    <div class="card" style="margin-bottom: 2rem; border-left: 4px solid {{ $stateColor }};"
         x-data="{ 
            timeIn: '{{ $todaysLog ? $todaysLog->time_in->toIso8601String() : '' }}',
            activeBreakStart: '{{ $activeBreak ? $activeBreak->start_time->toIso8601String() : '' }}',
            accumulatedBreakSecs: {{ $totalBreakSecs }},
            liveTimer: '00:00:00',
            init() {
                if(!this.timeIn) return;
                setInterval(() => {
                    let now = new Date();
                    let start = new Date(this.timeIn);
                    let diffSecs = Math.floor((now - start) / 1000);
                    diffSecs -= this.accumulatedBreakSecs;
                    
                    if(this.activeBreakStart) {
                        let bStart = new Date(this.activeBreakStart);
                        diffSecs -= Math.floor((now - bStart) / 1000);
                    }
                    
                    if(diffSecs < 0) diffSecs = 0;
                    let h = Math.floor(diffSecs / 3600).toString().padStart(2, '0');
                    let m = Math.floor((diffSecs % 3600) / 60).toString().padStart(2, '0');
                    let s = (diffSecs % 60).toString().padStart(2, '0');
                    this.liveTimer = `${h}:${m}:${s}`;
                }, 1000);
            }
         }">
         
        <div style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                    <h3 style="font-weight: 600; font-size: 16px; color: var(--c-navy); margin:0;">Daily Time Record</h3>
                    <span style="background: {{ $stateColor }}20; color: {{ $stateColor }}; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;">
                        {{ $statusText }}
                    </span>
                </div>
                
                @if(!$todaysLog)
                    <p style="margin: 0; font-size: 13px; color: var(--c-soft);">You have not clocked in for today's shift yet.</p>
                @elseif(!$todaysLog->time_out)
                    <p style="margin: 0; font-size: 13px; color: var(--c-soft);">
                        Clocked in at <strong>{{ $todaysLog->time_in->format('h:i A') }}</strong>.
                        @if($activeBreak)
                            Taking a break since {{ $activeBreak->start_time->format('h:i A') }}.
                        @else
                            Current active hours: <strong x-text="liveTimer">--:--:--</strong>
                        @endif
                    </p>
                @else
                    <p style="margin: 0; font-size: 13px; color: #166534;">
                        Shift completed! Clocked in at {{ $todaysLog->time_in->format('h:i A') }} and out at {{ $todaysLog->time_out->format('h:i A') }}.
                        Total hours: <strong>{{ $todaysLog->total_hours }}</strong> hrs.
                    </p>
                @endif
            </div>

            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @if(!$todaysLog)
                    <form method="POST" action="{{ route('time-logs.in') }}">
                        @csrf
                        <button type="submit" style="background: #16a34a; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
                            Time In
                        </button>
                    </form>
                @elseif(!$todaysLog->time_out)
                    
                    @if(!$activeBreak)
                        {{-- Start Break Dropdown --}}
                        <div x-data="{ open: false }" style="position: relative;">
                            <button @click="open = !open" style="background: #f59e0b; color: #fff; border: none; padding: 0.6rem 1rem; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
                                Start Break ▼
                            </button>
                            <div x-show="open" @click.away="open = false" style="position: absolute; right: 0; top: 110%; background: #fff; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 160px; z-index: 50; display: none;" :style="{ display: open ? 'block' : 'none' }">
                                <form method="POST" action="{{ route('time-logs.break-start') }}">
                                    @csrf
                                    <input type="hidden" name="break_type" value="first">
                                    <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; background: none; border: none; font-size: 13px; cursor: pointer; border-bottom: 1px solid #eee;">First Break (15m)</button>
                                </form>
                                <form method="POST" action="{{ route('time-logs.break-start') }}">
                                    @csrf
                                    <input type="hidden" name="break_type" value="lunch">
                                    <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; background: none; border: none; font-size: 13px; cursor: pointer; border-bottom: 1px solid #eee;">Lunch (1h)</button>
                                </form>
                                <form method="POST" action="{{ route('time-logs.break-start') }}">
                                    @csrf
                                    <input type="hidden" name="break_type" value="last">
                                    <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; background: none; border: none; font-size: 13px; cursor: pointer;">Last Break (15m)</button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- End Break Button --}}
                        <form method="POST" action="{{ route('time-logs.break-end') }}">
                            @csrf
                            <button type="submit" style="background: #d97706; color: #fff; border: none; padding: 0.6rem 1rem; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
                                End Break
                            </button>
                        </form>
                    @endif

                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'eod-modal')" style="background: #dc2626; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
                        Time Out
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────── --}}
    <div>
        <div class="section-eyebrow">At a Glance</div>
        <div class="stat-grid">

            <div class="stat-card s-blue">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <line x1="8" y1="9" x2="16" y2="9"/>
                        <line x1="8" y1="13" x2="16" y2="13"/>
                        <line x1="8" y1="17" x2="13" y2="17"/>
                    </svg>
                </div>
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value" data-count="{{ $stats['total'] }}">0</div>
                <div class="stat-sub">across all boards</div>
            </div>

            <div class="stat-card s-teal">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                <div class="stat-label">Assigned to Me</div>
                <div class="stat-value" data-count="{{ $stats['my_tasks'] }}">0</div>
                <div class="stat-sub">active tasks</div>
            </div>

            <div class="stat-card s-amber">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
                <div class="stat-label">Completed</div>
                <div class="stat-value" data-count="{{ $stats['completed'] }}">0</div>
                <div class="stat-sub">tasks closed</div>
            </div>

            <div class="stat-card s-red">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="stat-label">High Priority</div>
                <div class="stat-value" data-count="{{ $stats['high_priority'] }}">0</div>
                <div class="stat-sub">require attention</div>
            </div>

        </div>
    </div>

    {{-- ── PROGRESS BAR ────────────────────────────────────────── --}}
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
                {{ $stats['completed'] }} Completed
            </div>
            <div class="prog-legend-item">
                <div class="prog-legend-dot" style="background:var(--c-rule);border:1px solid var(--c-border-2)"></div>
                {{ $stats['total'] - $stats['completed'] }} Remaining
            </div>
            <div class="prog-legend-item">
                <div class="prog-legend-dot" style="background:var(--c-red)"></div>
                {{ $stats['high_priority'] }} High Priority
            </div>
        </div>
    </div>

    {{-- ── TASKS + ACTIVITY ────────────────────────────────────── --}}
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
                        <tr class="task-row">
                            <td>
                                <div class="task-name">{{ $task->title }}</div>
                                <div class="task-board">{{ $task->col_title }}</div>
                            </td>
                            <td>
                                <span class="pill {{ $task->pill_class }}">{{ $task->col_title }}</span>
                            </td>
                            <td>
                                <span class="prio {{ $task->priority_class }}">
                                    <span class="prio-dot"></span>
                                    {{ ucfirst($task->priority_class) }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="due {{ $task->due_class }}">
                                    @if($task->due_class === 'overdue')
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    @elseif($task->due_class === 'soon')
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
                                    @else
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    @endif
                                    {{ $task->due_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="4">No tasks assigned to you at this time.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Activity Feed --}}
        <div class="card activity-card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
            </div>
            <div class="activity-card-body">
      @forelse($recentActivity ?? [] as $activity)
    @php
        $type    = $activity->type ?? 'created';
        $iconMap = ['created' => '✦', 'moved' => '⇄', 'done' => '✓', 'assigned' => '◈'];
        $icon    = $iconMap[$type] ?? '·';
    @endphp
                <div class="activity-item">
                    <div class="act-icon {{ $type }}">{{ $icon }}</div>
                    <div class="act-body">
                        <div class="act-text">
                            <strong>{{ $activity->user->name ?? 'Someone' }}</strong>
                            {{ $activity->description ?? 'performed an action' }}
                        </div>
                        <span class="act-time">
                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                @forelse($myTasks->take(5) as $task)
                    <div class="activity-item">
                        <div class="act-icon assigned">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        </div>
                        <div class="act-body">
                            <div class="act-text">
                                You were assigned <strong>{{ Str::limit($task->title, 34) }}</strong>
                            </div>
                            <span class="act-time">
                                {{ $task->created_at
                                    ? \Carbon\Carbon::parse($task->created_at)->diffForHumans()
                                    : 'recently' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="act-body">
                            <div class="act-text" style="text-align:center;padding:1rem 0;color:var(--c-soft)">
                                No recent activity.
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            @endforelse
        </div>

    </div>{{-- /.body-grid --}}
</div>{{-- /.db-wrap --}}
</div>{{-- /.db-page --}}

{{-- ── TIME OUT / EOD MODAL ── --}}
    <x-modal name="eod-modal" focusable>
        <form method="post" action="{{ route('time-logs.out') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-2" style="font-family: 'Epilogue', sans-serif;">End of Day Report</h2>
            <p class="text-sm text-gray-500 mb-4">Please provide a brief summary of what you accomplished today before clocking out.</p>
            
            <div>
                <x-input-label for="eod_notes" value="EOD Notes" />
                <textarea id="eod_notes" name="eod_notes" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required placeholder="1. Completed user authentication...&#10;2. Attended sync meeting...&#10;3. Fixed bug on dashboard..."></textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button style="background: var(--c-red);">Submit & Time Out</x-primary-button>
            </div>
        </form>
    </x-modal>

</x-app-layout>