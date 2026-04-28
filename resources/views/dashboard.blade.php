<x-app-layout>
    @section('title', 'Dashboard')
<x-slot name="header">

    <div class="tk-topnav">

      <x-shift-pill />

        {{-- ── RIGHT SIDE ── --}}
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

@push('styles')
    @vite('resources/css/tasks.css')
    @vite('resources/css/dashboard.css')
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite('resources/js/dashboard.js')
@endpush

{{-- Stat Task List Modal --}}
<div id="statTaskModal" style="display:none;position:fixed;inset:0;background:rgba(16,24,40,.5);backdrop-filter:blur(4px);z-index:400;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:620px;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 12px 40px rgba(16,24,40,.18);">
        <div style="padding:1.4rem 1.6rem;border-bottom:1px solid #e4e7ec;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div>
                <div id="statModal-title" style="font-size:18px;font-weight:800;color:#0d1117;letter-spacing:-.02em;"></div>
                <div id="statModal-sub" style="font-size:13px;color:#6b7280;margin-top:2px;font-weight:500;"></div>
            </div>
            <button onclick="closeStatModal()" style="width:34px;height:34px;border:1.5px solid #e4e7ec;border-radius:8px;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#4b5563;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="statModal-list" style="flex:1;overflow-y:auto;padding:.75rem;display:flex;flex-direction:column;gap:.5rem;"></div>
    </div>
</div>

@include('tasks.partials.detail-modal')

@push('styles')
    @vite('resources/css/dashboard.css')
@endpush

@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush

<div class="db-page">
<div class="db-wrap">

    {{-- ── GREETING BANNER ── --}}
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

    {{-- ── TIME TRACKING WIDGET ── --}}
    <div class="card" style="margin-bottom: 2rem; border-left: 4px solid var(--c-navy);">
        <div style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-weight: 600; font-size: 16px; color: var(--c-navy); margin: 0 0 0.25rem 0;">Daily Time Record</h3>
                @if(!$todaysLog)
                    <p style="margin: 0; font-size: 13px; color: var(--c-soft);">You have not clocked in for today's shift yet.</p>
                @elseif(!$todaysLog->time_out)
                    <p style="margin: 0; font-size: 13px; color: var(--c-soft);">
                        Clocked in at <strong>{{ $todaysLog->time_in->format('h:i A') }}</strong>. Don't forget to submit your EOD notes when you finish!
                    </p>
                @else
                    <p style="margin: 0; font-size: 13px; color: #166534;">
                        Shift completed! Clocked in at {{ $todaysLog->time_in->format('h:i A') }} and out at {{ $todaysLog->time_out->format('h:i A') }}.
                    </p>
                @endif
            </div>
            <div>
                @if(!$todaysLog)
                    <form method="POST" action="{{ route('time-logs.in') }}">
                        @csrf
                        <button type="submit" style="background:#16a34a;color:#fff;border:none;padding:.6rem 1.5rem;border-radius:6px;font-weight:600;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:.5rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Time In
                        </button>
                    </form>
                @elseif(!$todaysLog->time_out)
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'eod-modal')" style="background:var(--c-red);color:#fff;border:none;padding:.6rem 1.5rem;border-radius:6px;font-weight:600;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:.5rem;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Time Out & Submit EOD
                    </button>
                @else
                    <span style="background:#dcfce7;color:#166534;padding:.5rem 1rem;border-radius:6px;font-weight:600;font-size:13px;border:1px solid #bbf7d0;">
                        EOD Submitted
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div>
        <div class="section-eyebrow">At a Glance</div>
        <div class="stat-grid">
            <div class="stat-card s-blue" onclick="toggleStatPanel('total', this)">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="8" y1="9" x2="16" y2="9"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/></svg>
                </div>
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value" data-count="{{ $stats['total'] }}">0</div>
                <div class="stat-sub">across all boards</div>
            </div>
            <div class="stat-card s-teal" onclick="toggleStatPanel('mine', this)">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
                <div class="stat-label">Assigned to Me</div>
                <div class="stat-value" data-count="{{ $stats['my_tasks'] }}">0</div>
                <div class="stat-sub">active tasks</div>
            </div>
            <div class="stat-card s-amber" onclick="toggleStatPanel('completed', this)">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="9 12 11 14 15 10"/></svg>
                </div>
                <div class="stat-label">Completed</div>
                <div class="stat-value" data-count="{{ $stats['completed'] }}">0</div>
                <div class="stat-sub">tasks closed</div>
            </div>
            <div class="stat-card s-red" onclick="toggleStatPanel('high', this)">
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div class="stat-label">High Priority</div>
                <div class="stat-value" data-count="{{ $stats['high_priority'] }}">0</div>
                <div class="stat-sub">require attention</div>
            </div>
        </div>

        {{-- Expand Panel --}}
        <div id="statPanel" style="display:grid;grid-template-rows:0fr;transition:grid-template-rows .35s ease,margin .35s ease;margin-top:0;">
            <div style="overflow:hidden;">
                <div style="margin-top:.5rem;border-radius:14px;background:#fff;box-shadow:0 4px 20px rgba(16,24,40,.08);border:1.5px solid #e4e7ec;">
                    <div id="statPanel-inner" style="border-top:3px solid #e4e7ec;border-radius:14px 14px 0 0;">
                        <div style="padding:.85rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
                            <div>
                                <div id="statPanel-title" style="font-size:15px;font-weight:800;color:#0d1117;letter-spacing:-.02em;"></div>
                                <div id="statPanel-sub" style="font-size:12px;color:#6b7280;margin-top:1px;font-weight:500;"></div>
                            </div>
                            <button onclick="closeStatPanel()" style="width:30px;height:30px;border:1.5px solid #e4e7ec;border-radius:8px;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#4b5563;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div style="height:1px;background:#e4e7ec;"></div>
                    </div>
                    <div id="statPanel-list" style="padding:.75rem;display:flex;flex-direction:column;gap:.5rem;max-height:320px;overflow-y:auto;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── PROGRESS BAR ── --}}
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

    {{-- ── COMPLETION CHART ── --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title">Tasks Completed Over Time</div>
            <div style="font-size:12px;color:#6b7280;font-weight:500;">Last 30 days</div>
        </div>
        <div style="padding:1rem 1.5rem 1.4rem;">
            <canvas id="dashLineChart" style="width:100%!important;height:180px!important;display:block;"></canvas>
        </div>
    </div>

    {{-- ── TASKS + ACTIVITY ── --}}
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
                            <td><span class="pill {{ $task->pill_class }}">{{ $task->col_title }}</span></td>
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
                            <span class="act-time">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    @forelse($myTasks->take(5) as $task)
                        <div class="activity-item">
                            <div class="act-icon assigned">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            </div>
                            <div class="act-body">
                                <div class="act-text">You were assigned <strong>{{ Str::limit($task->title, 34) }}</strong></div>
                                <span class="act-time">{{ $task->created_at ? \Carbon\Carbon::parse($task->created_at)->diffForHumans() : 'recently' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="act-body">
                                <div class="act-text" style="text-align:center;padding:1rem 0;color:var(--c-soft)">No recent activity.</div>
                            </div>
                        </div>
                    @endforelse
                @endforelse
            </div>
        </div>

    </div>{{-- /.body-grid --}}
</div>{{-- /.db-wrap --}}
</div>{{-- /.db-page --}}

{{-- ── EOD MODAL ── --}}
<x-modal name="eod-modal" focusable>
    <form method="post" action="{{ route('time-logs.out') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-medium text-gray-900 mb-2" style="font-family:'Epilogue',sans-serif;">End of Day Report</h2>
        <p class="text-sm text-gray-500 mb-4">Please provide a brief summary of what you accomplished today before clocking out.</p>
        <div>
            <x-input-label for="eod_notes" value="EOD Notes" />
            <textarea id="eod_notes" name="eod_notes" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required placeholder="1. Completed user authentication...&#10;2. Attended sync meeting...&#10;3. Fixed bug on dashboard..."></textarea>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
            <x-primary-button style="background:var(--c-red);">Submit & Time Out</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- ── SHIFT PILL STYLES ── --}}
<style>
.tk-shift-pill-wrap {
    position: relative;
}

.tk-shift-pill {
    display: flex; align-items: center; gap: .55rem;
    background: #f1f5f9; border: 1.5px solid #e2e8f0;
    border-radius: 10px; padding: .45rem 1rem;
    cursor: pointer; font-family: inherit;
    transition: background .15s, border-color .15s, box-shadow .15s;
    white-space: nowrap;
}
.tk-shift-pill:hover {
    background: #e8edf5; border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(27,43,94,.08);
}
.tk-shift-pill svg { color: #64748b; flex-shrink: 0; }

.tk-shift-pill-text {
    font-size: 13px; font-weight: 600; color: #334155;
}
.tk-shift-pill-text strong { color: #1b2b5e; font-weight: 700; }
.tk-shift-pill-tz { color: #94a3b8; font-weight: 500; }
.tk-shift-pill-empty { color: #94a3b8; font-style: italic; }
.tk-shift-pill-empty strong { color: #64748b; }

.tk-shift-chevron {
    color: #94a3b8; flex-shrink: 0;
    transition: transform .22s cubic-bezier(.16,1,.3,1);
}
.tk-shift-chevron.open { transform: rotate(180deg); }

/* Popover */
.tk-shift-popover {
    position: absolute; left: 0; top: calc(100% + 10px);
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 14px; box-shadow: 0 10px 40px rgba(27,43,94,.14);
    width: 300px; z-index: 999; overflow: hidden;
    opacity: 0; transform: translateY(-8px) scale(.97);
    pointer-events: none;
    transition: opacity .2s cubic-bezier(.16,1,.3,1), transform .2s cubic-bezier(.16,1,.3,1);
    transform-origin: top left;
}
.tk-shift-popover.open {
    opacity: 1; transform: translateY(0) scale(1);
    pointer-events: auto;
}

.tk-shift-pop-header {
    padding: 1rem 1.25rem .85rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(135deg, #1b2b5e, #2d52c4);
}
.tk-shift-pop-title { font-size: 14px; font-weight: 800; color: #fff; }
.tk-shift-pop-sub   { font-size: 11px; font-weight: 600; color: rgba(255,255,255,.6); margin-top: 2px; text-transform: uppercase; letter-spacing: .08em; }

.tk-shift-pop-body  { padding: .85rem 1.25rem; display: flex; flex-direction: column; gap: .85rem; }

.tk-shift-pop-row {
    display: flex; align-items: flex-start; gap: .75rem;
}
.tk-shift-pop-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: #f1f5f9; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0; color: #64748b;
}
.tk-shift-pop-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: 2px; }
.tk-shift-pop-value { font-size: 13.5px; font-weight: 700; color: #1b2b5e; }

.tk-shift-pop-days  { display: flex; flex-wrap: wrap; gap: .3rem; margin-top: .25rem; }
.tk-shift-day {
    font-size: 11px; font-weight: 700; padding: 2px 8px;
    border-radius: 5px; background: #f1f5f9; color: #94a3b8;
    border: 1px solid #e2e8f0;
}
.tk-shift-day.active {
    background: #1b2b5e; color: #fff; border-color: #1b2b5e;
}
</style>

{{-- ── SHIFT PILL + STAT SCRIPTS ── --}}
@push('scripts')
<script>
/* ── Shift Popover ── */
function toggleShiftPopover() {
    const pop  = document.getElementById('shiftPopover');
    const chev = document.getElementById('shiftChevron');
    const isOpen = pop.classList.contains('open');

    // Close other dropdowns first
    document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));

    pop.classList.toggle('open', !isOpen);
    chev.classList.toggle('open', !isOpen);
}

// Close popover on outside click
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('shiftPillWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('shiftPopover')?.classList.remove('open');
        document.getElementById('shiftChevron')?.classList.remove('open');
    }
});

/* ── Stat Panel ── */
let activeStatType = null;
let activeStatCard = null;

const statColors = {
    total:     { border: '#3b82f6', bg: '#eff6ff' },
    mine:      { border: '#14b8a6', bg: '#f0fdfa' },
    completed: { border: '#f59e0b', bg: '#fffbeb' },
    high:      { border: '#ef4444', bg: '#fef2f2' },
};

async function toggleStatPanel(type, card) {
    const panel = document.getElementById('statPanel');

    if (activeStatType === type && panel.style.gridTemplateRows === '1fr') {
        closeStatPanel(); return;
    }

    document.querySelectorAll('.stat-card').forEach(c => { c.style.outline = 'none'; c.style.transform = 'none'; });

    const color = statColors[type];
    card.style.outline   = `2.5px solid ${color.border}`;
    card.style.transform = 'translateY(-3px)';
    activeStatCard = card;
    activeStatType = type;

    document.getElementById('statPanel-inner').style.borderTop  = `3px solid ${color.border}`;
    document.getElementById('statPanel-inner').style.background = color.bg;
    document.getElementById('statPanel-list').style.background  = color.bg;

    const CSRF  = document.querySelector('meta[name="csrf-token"]').content;
    const titles = {
        total:     { title: 'All Tasks',          sub: 'Every task across all boards' },
        mine:      { title: 'Assigned to Me',      sub: 'Tasks currently assigned to you' },
        completed: { title: 'Completed Tasks',     sub: 'Tasks that have been closed' },
        high:      { title: 'High Priority Tasks', sub: 'Tasks that require urgent attention' },
    };

    document.getElementById('statPanel-title').textContent = titles[type].title;
    document.getElementById('statPanel-sub').textContent   = titles[type].sub;
    document.getElementById('statPanel-list').innerHTML    = '<div style="padding:1.5rem;text-align:center;color:#6b7280;font-size:14px;">Loading…</div>';

    panel.style.gridTemplateRows = '1fr';
    panel.style.marginTop        = '0';

    try {
        const res   = await fetch(`/dashboard/tasks?type=${type}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        const tasks = await res.json();

        if (!tasks.length) {
            document.getElementById('statPanel-list').innerHTML = '<div style="padding:1.5rem;text-align:center;color:#6b7280;font-size:14px;">No tasks found.</div>';
            return;
        }

        const priorityColor = { high: '#dc2626', medium: '#d97706', low: '#16a34a' };
        const priorityBg    = { high: '#fef2f2', medium: '#fffbeb', low: '#f0fdf4' };

        document.getElementById('statPanel-list').innerHTML = tasks.map(task => `
            <div onclick="openDetail(${task.id})"
                 style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 1rem;border:1.5px solid ${color.border}30;border-radius:10px;cursor:pointer;transition:border-color .15s,box-shadow .15s,transform .15s;background:#fff;"
                 onmouseover="this.style.borderColor='${color.border}';this.style.boxShadow='0 4px 12px ${color.border}22';this.style.transform='translateY(-1px)'"
                 onmouseout="this.style.borderColor='${color.border}30';this.style.boxShadow='none';this.style.transform='none'">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:700;color:#0d1117;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${task.title}</div>
                    <div style="font-size:12px;color:#6b7280;font-weight:500;">${task.column ?? 'No column'}${task.assignee ? ' · ' + task.assignee : ''}</div>
                </div>
                <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;margin-left:.75rem;">
                    ${task.due_date ? `<span style="font-size:12px;color:#6b7280;font-weight:600;">${task.due_date}</span>` : ''}
                    <span style="font-size:11px;font-weight:700;padding:2px 9px;border-radius:5px;text-transform:uppercase;background:${priorityBg[task.priority]};color:${priorityColor[task.priority]};">${task.priority}</span>
                </div>
            </div>`).join('');
    } catch(e) {
        document.getElementById('statPanel-list').innerHTML = '<div style="padding:1.5rem;text-align:center;color:#dc2626;font-size:14px;">Failed to load tasks.</div>';
    }
}

function closeStatPanel() {
    const panel = document.getElementById('statPanel');
    panel.style.gridTemplateRows = '0fr';
    activeStatType = null;
    if (activeStatCard) { activeStatCard.style.outline = 'none'; activeStatCard.style.transform = 'none'; activeStatCard = null; }
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('dashLineChart');
    if (!ctx) return;

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#8b94b3';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Completed',
                data: {!! json_encode($chartData->pluck('count')) !!},
                borderColor: '#2d52c4',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 160);
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
                tooltip: { mode: 'index', intersect: false, backgroundColor: '#0d1424', titleColor: '#fff', bodyColor: '#8b94b3', padding: 11, cornerRadius: 9 }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 8, font: { size: 10.5 } } },
                y: { grid: { color: '#f0f2f5' }, border: { display: false }, beginAtZero: true, ticks: { stepSize: 1, font: { size: 10.5 }, padding: 6 } }
            }
        }
    });
});
</script>
@endpush

</x-app-layout>