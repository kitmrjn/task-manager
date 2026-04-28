<x-app-layout>
    @section('title', 'EOD Reports')

    <x-slot name="header">
        <div class="tk-topnav">
            <div class="tk-topnav-right">

                {{-- Notifications --}}
                <div class="tk-dropdown-wrap">
                    <button class="tk-nav-icon-btn" id="notif-btn" title="Notifications">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    <div id="notif-dropdown" class="tk-dropdown" style="width:340px;">
                        <div class="tk-dropdown-header">
                            <span class="tk-dropdown-title">Notifications</span>
                            <span id="notif-count" class="tk-badge-pill"></span>
                        </div>
                        <div class="tk-dropdown-body" id="notif-list">
                            <div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--soft);">Loading…</div>
                        </div>
                    </div>
                </div>

                <div class="tk-nav-divider"></div>

                {{-- User profile --}}
                <div class="tk-dropdown-wrap" style="flex-shrink:0;">
                    <div class="tk-nav-profile" id="profile-btn">
                        <div class="tk-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div class="tk-nav-userinfo">
                            <span class="tk-nav-name">{{ Auth::user()->name }}</span>
                            <span class="tk-nav-email">{{ Auth::user()->email }}</span>
                        </div>
                        <svg id="profile-chevron" class="tk-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div id="profile-dropdown" class="tk-dropdown tk-profile-dropdown">
                        <div class="tk-dropdown-header">
                            <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:14px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                                <div class="tk-profile-meta" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div style="padding:.3rem 0;">
                            <a href="{{ route('settings.index') }}" class="tk-profile-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                My Profile & Settings
                            </a>
                            <div class="tk-profile-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="tk-profile-item tk-profile-item--danger">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-slot>

    @push('styles') @vite(['resources/css/dashboard.css', 'resources/css/tasks.css']) @endpush

    <div class="py-10" style="font-family: 'Geist', sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Page heading --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem;">
                <div>
                    <h1 style="font-size:26px; font-weight:800; color:#0f172a; margin:0 0 .25rem; font-family:'Geist',sans-serif;">EOD & Time Logs</h1>
                    <p style="font-size:13.5px; color:#94a3b8; margin:0;">Track attendance, hours worked, and end-of-day reports</p>
                </div>
                <form method="GET" action="{{ route('eod.export') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="campaign" value="{{ request('campaign') }}">
                    <input type="hidden" name="leader" value="{{ request('leader') }}">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit" style="background:#10b981; color:white; border:none; border-radius:8px; padding:.55rem 1.25rem; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:.5rem; font-family:'Geist',sans-serif;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </button>
                </form>
            </div>

            <div class="card" style="padding: 1.5rem; display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end;">
                <form method="GET" action="{{ route('eod.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; flex: 1;">
                    <div style="flex: 1; min-width: 180px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Employee</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-size: 13px;">
                    </div>

                    @if(auth()->user()->isAtLeastManager() || \App\Models\User::where('team_leader_id', auth()->id())->exists())
                    <div style="min-width: 140px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Campaign</label>
                        <select name="campaign" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-size: 13px; background: #fff;">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $camp) <option value="{{ $camp->id }}" {{ request('campaign') == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option> @endforeach
                        </select>
                    </div>

                    <div style="min-width: 140px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Team Leader</label>
                        <select name="leader" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-size: 13px; background: #fff;">
                            <option value="">All Leaders</option>
                            @foreach($teamLeaders as $ldr) <option value="{{ $ldr->id }}" {{ request('leader') == $ldr->id ? 'selected' : '' }}>{{ $ldr->name }}</option> @endforeach
                        </select>
                    </div>
                    @endif

                    <div style="min-width: 130px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem; font-size: 13px;">
                    </div>
                    <div style="min-width: 130px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem; font-size: 13px;">
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" style="background: var(--c-navy); color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1rem; font-size: 13px; font-weight: 600; cursor: pointer;">Filter</button>
                        <a href="{{ route('eod.index') }}" style="background: #f1f5f9; color: var(--c-navy); border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 1rem; font-size: 13px; font-weight: 600; text-decoration: none;">Reset</a>
                    </div>
                </form>
            </div>

            <div class="card" style="overflow: visible;">
                <table class="task-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Assignment</th>
                            <th>Time Record</th>
                            <th>Status / Hours</th>
                            <th>EOD Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="task-row">
                                <td style="font-weight: 600; color: var(--c-navy); font-size: 13px;">{{ $log->log_date->format('M j, Y') }}</td>
                                <td>
                                    <div style="font-weight: 600; color: var(--c-navy); font-size: 13px;">{{ $log->user->name }}</div>
                                    <div style="font-size: 11px; color: var(--c-soft);">{{ ucwords(str_replace('_', ' ', $log->user->role)) }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 500; font-size: 13px;">{{ $log->user->campaign->name ?? 'Unassigned' }}</div>
                                    <div style="font-size: 11px; color: var(--c-soft);">LDR: {{ $log->user->teamLeader->name ?? 'None' }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: var(--c-navy);">In: <strong>{{ $log->time_in ? $log->time_in->format('h:i A') : '--' }}</strong></div>
                                    <div style="font-size: 13px; color: var(--c-soft);">Out: <strong>{{ $log->time_out ? $log->time_out->format('h:i A') : '--' }}</strong></div>
                                </td>
                                <td>
                                    @if($log->status === 'Complete')
                                        <span class="pill" style="background:#dcfce7; color:#166534; border: 1px solid #86efac; margin-bottom: 4px;">Complete</span>
                                    @elseif($log->status === 'Partial')
                                        <span class="pill" style="background:#fef3c7; color:#92400e; border: 1px solid #fcd34d; margin-bottom: 4px;">Partial</span>
                                    @else
                                        <span class="pill" style="background:#fee2e2; color:#991b1b; border: 1px solid #fca5a5; margin-bottom: 4px;">Incomplete</span>
                                    @endif
                                    <div style="font-size: 12px; color: var(--c-navy); font-weight: 600; margin-top: 4px;">{{ $log->total_hours }} Hours</div>
                                </td>
                                <td style="max-width: 250px;">
                                    <div style="font-size: 12px; color: var(--c-soft); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $log->eod_notes }}">
                                        {{ $log->eod_notes ?? 'No notes submitted.' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row"><td colspan="6">No EOD logs found for the selected criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if($logs->hasPages())
                    <div style="padding: 1rem; border-top: 1px solid var(--c-border-2);">{{ $logs->links() }}</div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts') @vite('resources/js/tasks.js') @endpush

</x-app-layout>