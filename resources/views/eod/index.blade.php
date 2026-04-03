<x-app-layout>
    @section('title', 'EOD Reports')

    <x-slot name="header">
        <div class="tk-topnav" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="flex items-center gap-6 pl-2 md:pl-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0" style="font-family: 'Epilogue', sans-serif;">
                    {{ __('EOD & Time Logs') }}
                </h2>
            </div>
            
            <div class="tk-topnav-right" style="display: flex; align-items: center; gap: .65rem; padding-right: 1.5rem;">
                <div class="tk-dropdown-wrap" style="position: relative;">
                    <div class="tk-nav-profile" id="profile-btn" role="button" tabindex="0" style="display: flex; align-items: center; gap: .65rem; cursor: pointer; padding: .4rem .75rem; border-radius: 10px; border: 1.5px solid transparent;">
                        <div class="tk-nav-avatar" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #3b63d8, #1b2b5e); color: #fff; font-size: 14px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="tk-nav-userinfo" style="display: flex; flex-direction: column;">
                            <span class="tk-nav-name" style="font-size: 14px; font-weight: 700; color: #1a1e2e; line-height: 1.25;">{{ Auth::user()->name }}</span>
                        </div>
                        <svg id="profile-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: #8b94b3;"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>

                    <div id="profile-dropdown" class="tk-dropdown tk-profile-dropdown" style="width:268px; position: absolute; right: 0; top: calc(100% + 10px); background: #fff; border: 1px solid #e2e5eb; border-radius: 14px; box-shadow: 0 12px 32px rgba(27,43,94,0.14); z-index: 999; opacity: 0; pointer-events: none; transition: opacity .22s, transform .22s; transform: translateY(-10px) scale(.96);">
                        <div class="tk-dropdown-header tk-profile-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e8eaf0; display: flex; align-items: center; gap: .9rem;">
                            <div class="tk-profile-avatar-lg" style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #3b63d8, #1b2b5e); color: #fff; font-size: 15px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:14px;font-weight:700;color:#1a1e2e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                                <div style="font-size: 12.5px; color: #8b94b3; margin-top: 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="tk-dropdown-body" style="padding:.3rem 0;">
                            <a href="{{ route('settings.index') }}" style="display: flex; align-items: center; gap: .7rem; padding: .75rem 1.25rem; font-size: 14px; font-weight: 600; color: #6b7491; text-decoration: none;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                My Profile & Settings
                            </a>
                            <div style="height: 1px; background: #e8eaf0; margin: .3rem 0;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display: flex; align-items: center; gap: .7rem; padding: .75rem 1.25rem; font-size: 14px; font-weight: 600; color: #c0354a; background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
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

    @push('styles') @vite('resources/css/dashboard.css') @endpush
    @push('scripts') 
        @vite('resources/js/dashboard.js') 
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('profile-btn');
                const drop = document.getElementById('profile-dropdown');
                if(btn && drop) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if(drop.style.opacity == '0') {
                            drop.style.opacity = '1';
                            drop.style.transform = 'translateY(0) scale(1)';
                            drop.style.pointerEvents = 'auto';
                        } else {
                            drop.style.opacity = '0';
                            drop.style.transform = 'translateY(-10px) scale(.96)';
                            drop.style.pointerEvents = 'none';
                        }
                    });
                    document.addEventListener('click', function() {
                        drop.style.opacity = '0';
                        drop.style.transform = 'translateY(-10px) scale(.96)';
                        drop.style.pointerEvents = 'none';
                    });
                    drop.addEventListener('click', e => e.stopPropagation());
                }
            });
        </script>
    @endpush

    <div class="py-10" style="font-family: 'Epilogue', sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="section-eyebrow mb-6">Attendance & Reporting</div>

            <div class="card" style="padding: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 1.5rem; align-items: flex-end;">
                
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

                <form method="GET" action="{{ route('eod.export') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="campaign" value="{{ request('campaign') }}">
                    <input type="hidden" name="leader" value="{{ request('leader') }}">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    
                    <button type="submit" style="background: #10b981; color: white; border: none; border-radius: 6px; padding: 0.5rem 1.25rem; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Export Excel
                    </button>
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
</x-app-layout>