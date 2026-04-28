<x-app-layout>
    @section('title', 'System Data')

    <x-slot name="header">
        <div class="tk-topnav">
            <div class="tk-topnav-right">

                {{-- Notifications --}}
                <div class="tk-dropdown-wrap">
                    <button class="tk-topnav-icon" id="notif-btn" title="Notifications">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    <div class="tk-dropdown" id="notif-dropdown">
                        <div class="tk-dropdown-header"><span class="tk-dropdown-title">Notifications</span></div>
                        <div class="tk-dropdown-body"><div class="tk-notif-item"><div class="tk-notif-content"><div class="tk-notif-text text-gray-500 text-sm py-2 text-center">No new notifications.</div></div></div></div>
                    </div>
                </div>

                <div class="tk-topnav-divider"></div>

                {{-- Profile --}}
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
                            @if(true)
                            <a href="{{ route('settings.index') }}" class="tk-profile-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                Settings
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

    @push('styles') @vite('resources/css/dashboard.css') @endpush
    @push('scripts') @vite('resources/js/dashboard.js') @endpush

    {{-- ── SCHEDULE MODAL ─────────────────────────────────────────────── --}}
    <div id="scheduleModal" style="
        display:none; position:fixed; inset:0; z-index:1000;
        background:rgba(15,23,42,.45); backdrop-filter:blur(3px);
        align-items:center; justify-content:center;">
        <div style="
            background:#fff; border-radius:14px; width:100%; max-width:480px;
            margin:1rem; box-shadow:0 20px 60px rgba(0,0,0,.18);
            font-family:'Epilogue',sans-serif; overflow:hidden;">

            {{-- Modal Header --}}
            <div style="padding:1.4rem 1.6rem 1rem; border-bottom:1px solid #f0f2f6; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:15px; font-weight:700; color:#0f172a;">Edit Schedule</div>
                    <div id="schedModalSubtitle" style="font-size:12.5px; color:#94a3b8; margin-top:2px;"></div>
                </div>
                <button onclick="closeScheduleModal()" style="background:none; border:none; cursor:pointer; padding:.3rem; border-radius:6px; color:#94a3b8; line-height:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div style="padding:1.4rem 1.6rem; display:flex; flex-direction:column; gap:1.1rem;">

                {{-- Shift Times --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div>
                        <label style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:.4rem;">Shift Start</label>
                        <input type="time" id="sched-start" style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:.55rem .8rem; font-family:inherit; font-size:13.5px; color:#0f172a; outline:none; transition:.15s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:.4rem;">Shift End</label>
                        <input type="time" id="sched-end" style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:.55rem .8rem; font-family:inherit; font-size:13.5px; color:#0f172a; outline:none; transition:.15s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Timezone --}}
                <div>
                    <label style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:.4rem;">Client Timezone</label>
                    <div style="position:relative;" id="tzDropdownWrap">
                        <input type="hidden" id="sched-timezone" value="">
                        <button type="button" id="tzDropdownBtn" onclick="toggleTzDropdown()" style="
                            width:100%; border:1.5px solid #e2e8f0; border-radius:8px;
                            padding:.55rem .8rem; font-family:inherit; font-size:13.5px;
                            color:#94a3b8; background:#fff; cursor:pointer; text-align:left;
                            display:flex; align-items:center; justify-content:space-between;
                            transition:.15s; outline:none;">
                            <span id="tzDropdownLabel">Select timezone…</span>
                            <svg id="tzChevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0; color:#94a3b8; transition:transform .2s;"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div id="tzDropdownList" style="
                            display:none; position:absolute; left:0; right:0; top:calc(100% + 4px);
                            background:#fff; border:1.5px solid #e2e8f0; border-radius:8px;
                            box-shadow:0 8px 24px rgba(15,23,42,.12); z-index:9999;
                            max-height:200px; overflow-y:auto;">
                            <div class="tz-group-label">United States</div>
                            <div class="tz-option" data-value="America/New_York">US Eastern (ET)</div>
                            <div class="tz-option" data-value="America/Chicago">US Central (CT)</div>
                            <div class="tz-option" data-value="America/Denver">US Mountain (MT)</div>
                            <div class="tz-option" data-value="America/Los_Angeles">US Pacific (PT)</div>
                            <div class="tz-option" data-value="America/Anchorage">US Alaska (AKT)</div>
                            <div class="tz-option" data-value="Pacific/Honolulu">US Hawaii (HST)</div>
                            <div class="tz-group-label">Australia</div>
                            <div class="tz-option" data-value="Australia/Sydney">Australia Eastern (AEST)</div>
                            <div class="tz-option" data-value="Australia/Adelaide">Australia Central (ACST)</div>
                            <div class="tz-option" data-value="Australia/Perth">Australia Western (AWST)</div>
                            <div class="tz-group-label">United Kingdom / Europe</div>
                            <div class="tz-option" data-value="Europe/London">UK (GMT/BST)</div>
                            <div class="tz-option" data-value="Europe/Paris">Central Europe (CET)</div>
                        </div>
                    </div>
                </div>

                <style>
                .tz-group-label {
                    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
                    letter-spacing: .07em; color: #94a3b8; padding: .5rem .85rem .25rem;
                    pointer-events: none;
                }
                .tz-option {
                    font-size: 13.5px; font-weight: 500; color: #0f172a;
                    padding: .5rem .85rem; cursor: pointer; transition: background .1s;
                }
                .tz-option:hover { background: #f1f5f9; }
                .tz-option.selected { background: #0f172a; color: #fff; }
                </style>

                {{-- Operating Days --}}
                <div>
                    <label style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:.6rem;">Operating Days</label>
                    <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                        <label style="cursor:pointer; user-select:none;">
                            <input type="checkbox" class="sched-day-cb" value="{{ $day }}" style="display:none;">
                            <span class="sched-day-pill" data-day="{{ $day }}" style="
                                display:inline-block; padding:.35rem .75rem; border-radius:20px;
                                font-size:12.5px; font-weight:600; border:1.5px solid #e2e8f0;
                                color:#64748b; background:#f8fafc; cursor:pointer; transition:.15s;
                                user-select:none;">{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Status message --}}
                <div id="schedStatusMsg" style="display:none; font-size:13px; font-weight:500; padding:.6rem .9rem; border-radius:8px;"></div>

            </div>

            {{-- Modal Footer --}}
            <div style="padding:1rem 1.6rem 1.4rem; display:flex; justify-content:flex-end; gap:.75rem; border-top:1px solid #f0f2f6;">
                <button onclick="closeScheduleModal()" style="background:#f1f5f9; color:#64748b; border:none; border-radius:8px; padding:.6rem 1.3rem; font-family:inherit; font-size:13.5px; font-weight:600; cursor:pointer;">Cancel</button>
                <button onclick="saveSchedule()" id="schedSaveBtn" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:.6rem 1.4rem; font-family:inherit; font-size:13.5px; font-weight:600; cursor:pointer; transition:.15s;">Save Schedule</button>
            </div>
        </div>
    </div>

    <div class="py-10" style="font-family: 'Epilogue', sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Page heading --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem;">
                <div>
                    <h1 style="font-size:26px; font-weight:800; color:#0f172a; margin:0 0 .25rem; font-family:'Epilogue',sans-serif;">
                        System Lookups
                    </h1>
                    <p style="font-size:13.5px; color:#94a3b8; margin:0;">
                        Manage system-wide data like campaigns, roles, and configurations
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div style="background:rgba(45,204,112,.1); border-left:4px solid #2dcc70; padding:1rem; border-radius:4px; color:#1e824c; font-size:14px; font-weight:500;">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div style="background:rgba(231,76,60,.1); border-left:4px solid #e74c3c; padding:1rem; border-radius:4px; color:#c0392b; font-size:14px; font-weight:500;">
                    <ul style="margin:0; padding-left:1rem;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="section-eyebrow mb-6">Database Configuration</div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:2rem;">

{{-- ── CAMPAIGNS CARD ── --}}
<div class="card" style="display:flex; flex-direction:column; grid-column: 1 / -1;">
                    <div class="card-header">
                        <div class="card-title">Campaigns</div>
                        <div style="font-size:13px; color:var(--c-soft);">Manage assignments &amp; schedules</div>
                    </div>

                    <div style="flex:1; max-height:800px; overflow-y:auto;">
                        <table class="task-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Shift</th>
                                    <th>Users</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $camp)
                                    <tr class="task-row">
                                        <td style="font-weight:600; color:var(--c-navy);">{{ $camp->name }}</td>
                                        <td>
                                            @if($camp->hasSchedule())
                                                <span style="font-size:12px; color:#475569; font-weight:500; white-space:nowrap;">
                                                    {{ $camp->shift_label }}
                                                    @if($camp->timezone)
                                                        <span style="color:#94a3b8; font-weight:400;">· {{ $camp->timezone }}</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span style="font-size:12px; color:#cbd5e1; font-style:italic;">Not set</span>
                                            @endif
                                        </td>
                                        <td><span class="pill" style="border:1px solid var(--c-border);">{{ $camp->users_count }} assigned</span></td>
                                        <td style="text-align:right;">
                                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:.75rem;">
                                                @if(true)
                                                <button
                                                    onclick="openScheduleModal({{ $camp->id }}, '{{ addslashes($camp->name) }}', '{{ $camp->shift_start ?? '' }}', '{{ $camp->shift_end ?? '' }}', '{{ $camp->timezone ?? '' }}', {{ json_encode($camp->operating_days ?? []) }})"
                                                    style="color:var(--c-blue); border:none; background:transparent; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; display:flex; align-items:center; gap:.3rem; white-space:nowrap;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    Schedule
                                                </button>
                                                @endif
                                                <form method="POST" action="{{ route('admin.campaigns.destroy', $camp) }}" onsubmit="return confirm('Delete this campaign?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="color:var(--c-red); border:none; background:transparent; cursor:pointer; font-family:inherit; font-size:13px; font-weight:500;">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row"><td colspan="4">No campaigns created yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(true)
                    <div style="padding:1.25rem; border-top:1px solid var(--c-border-2); background:#f8fafc;">
                        <form method="POST" action="{{ route('admin.campaigns.store') }}" style="display:flex; gap:.75rem;">
                            @csrf
                            <input type="text" name="name" placeholder="e.g. TORT" required style="flex:1; border:1px solid var(--c-border); border-radius:6px; padding:.5rem .8rem; font-family:inherit; font-size:13px;">
                            <button type="submit" style="background:var(--c-navy); color:#fff; border:none; border-radius:6px; padding:.5rem 1.25rem; font-family:inherit; font-size:13px; font-weight:600; cursor:pointer;">Add Campaign</button>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- ── ROLES CARD ── --}}
                <div class="card" style="display:flex; flex-direction:column; grid-column: 1 / -1;">
                    <div class="card-header">
                        <div class="card-title">Custom Roles</div>
                        <div style="font-size:13px; color:var(--c-soft);">Manage job titles</div>
                    </div>

                    <div style="flex:1; max-height:400px; overflow-y:auto;">
                        <table class="task-table">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Users</th>
                                    <th style="text-align:right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr class="task-row">
                                        <td>
                                            <div style="font-weight:600; color:var(--c-navy); display:flex; align-items:center; gap:.5rem;">
                                                {{ $role->name }}
                                                @if($role->is_system)
                                                    <span style="font-size:10px; font-weight:700; background:var(--c-blue); color:#fff; padding:2px 6px; border-radius:4px; text-transform:uppercase;">Core</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><span class="pill" style="border:1px solid var(--c-border);">{{ $role->users_count }} assigned</span></td>
                                        <td style="text-align:right;">
                                            @if(!$role->is_system)
                                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Permanently delete this role?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="color:var(--c-red); border:none; background:transparent; cursor:pointer; font-family:inherit; font-size:13px; font-weight:500;">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="padding:1.25rem; border-top:1px solid var(--c-border-2); background:#f8fafc;">
                        <form method="POST" action="{{ route('admin.roles.store') }}" style="display:flex; gap:.75rem;">
                            @csrf
                            <input type="text" name="name" placeholder="e.g. HR, OM" required style="flex:1; border:1px solid var(--c-border); border-radius:6px; padding:.5rem .8rem; font-family:inherit; font-size:13px;">
                            <button type="submit" style="background:var(--c-navy); color:#fff; border:none; border-radius:6px; padding:.5rem 1.25rem; font-family:inherit; font-size:13px; font-weight:600; cursor:pointer;">Add Role</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── SCHEDULE MODAL JS ── --}}
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let _schedCampaignId = null;

        function openScheduleModal(id, name, shiftStart, shiftEnd, timezone, operatingDays) {
            _schedCampaignId = id;

            document.getElementById('schedModalSubtitle').textContent = name;
            document.getElementById('sched-start').value    = shiftStart || '';
            document.getElementById('sched-end').value      = shiftEnd   || '';

            if (timezone) {
                const opt = document.querySelector(`.tz-option[data-value="${timezone}"]`);
                const label = opt ? opt.textContent.trim() : timezone;
                setTimezone(timezone, label);
            } else {
                document.getElementById('sched-timezone').value = '';
                document.getElementById('tzDropdownLabel').textContent = 'Select timezone…';
                document.getElementById('tzDropdownLabel').style.color = '#94a3b8';
                document.querySelectorAll('.tz-option').forEach(o => o.classList.remove('selected'));
            }

            // Reset all day pills first
            document.querySelectorAll('.sched-day-pill').forEach(pill => {
                pill.dataset.active = '0';
                deactivatePill(pill);
            });

            // Activate saved days
            const days = Array.isArray(operatingDays) ? operatingDays : [];
            days.forEach(day => {
                const pill = document.querySelector(`.sched-day-pill[data-day="${day}"]`);
                if (pill) activatePill(pill);
            });

            document.getElementById('schedStatusMsg').style.display = 'none';
            document.getElementById('schedSaveBtn').textContent     = 'Save Schedule';
            document.getElementById('schedSaveBtn').disabled        = false;
            document.getElementById('scheduleModal').style.display  = 'flex';
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').style.display = 'none';
            _schedCampaignId = null;
        }

        // ── Day Pill Logic ──────────────────────────────────────────
        document.querySelectorAll('.sched-day-cb').forEach(cb => {
            cb.addEventListener('change', function () {
                const pill = this.closest('label').querySelector('.sched-day-pill');
                if (!pill) return;
                if (this.checked) {
                    pill.style.background  = '#0f172a';
                    pill.style.color       = '#fff';
                    pill.style.borderColor = '#0f172a';
                    pill.dataset.active    = '1';
                } else {
                    pill.style.background  = '#f8fafc';
                    pill.style.color       = '#64748b';
                    pill.style.borderColor = '#e2e8f0';
                    pill.dataset.active    = '0';
                }
            });
        });

        function activatePill(pill) {
            const cb = pill.closest('label')?.querySelector('.sched-day-cb');
            if (cb) { cb.checked = true; cb.dispatchEvent(new Event('change')); }
        }

        function deactivatePill(pill) {
            const cb = pill.closest('label')?.querySelector('.sched-day-cb');
            if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change')); }
        }

        function toggleDayPill(pill) {
            const cb = pill.closest('label')?.querySelector('.sched-day-cb');
            if (cb) { cb.checked = !cb.checked; cb.dispatchEvent(new Event('change')); }
        }

        async function saveSchedule() {
            if (!_schedCampaignId) return;

            const start    = document.getElementById('sched-start').value;
            const end      = document.getElementById('sched-end').value;
            const timezone = document.getElementById('sched-timezone').value;
            const days     = [...document.querySelectorAll('.sched-day-cb:checked')].map(cb => cb.value);
            const saveBtn  = document.getElementById('schedSaveBtn');

            if (!start || !end) { showSchedMsg('Shift start and end time are required.', 'error'); return; }
            if (!timezone)      { showSchedMsg('Please select a client timezone.', 'error'); return; }

            saveBtn.textContent = 'Saving…';
            saveBtn.disabled    = true;

            try {
                const res = await fetch(`/admin/system-data/campaigns/${_schedCampaignId}/schedule`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ shift_start: start, shift_end: end, timezone, operating_days: days }),
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    showSchedMsg('Schedule saved successfully!', 'success');
                    saveBtn.textContent = 'Saved ✓';
                    setTimeout(() => { window.location.reload(); }, 900);
                } else {
                    throw new Error(data.message || 'Save failed.');
                }
            } catch (e) {
                showSchedMsg(e.message || 'Something went wrong. Please try again.', 'error');
                saveBtn.textContent = 'Save Schedule';
                saveBtn.disabled    = false;
            }
        }

        function showSchedMsg(text, type) {
            const msg = document.getElementById('schedStatusMsg');
            msg.textContent      = text;
            msg.style.display    = 'block';
            msg.style.background = type === 'success' ? 'rgba(45,204,112,.12)' : 'rgba(231,76,60,.1)';
            msg.style.color      = type === 'success' ? '#1e824c' : '#c0392b';
        }

        document.getElementById('scheduleModal').addEventListener('click', function(e) {
            if (e.target === this) closeScheduleModal();
        });

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeScheduleModal(); });

        // ── Timezone Custom Dropdown ─────────────────────────────────
        function toggleTzDropdown() {
            const list   = document.getElementById('tzDropdownList');
            const chev   = document.getElementById('tzChevron');
            const isOpen = list.style.display === 'block';
            list.style.display   = isOpen ? 'none' : 'block';
            chev.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        function setTimezone(value, label) {
            document.getElementById('sched-timezone').value        = value;
            document.getElementById('tzDropdownLabel').textContent = label;
            document.getElementById('tzDropdownLabel').style.color = '#0f172a';
            document.querySelectorAll('.tz-option').forEach(opt => {
                opt.classList.toggle('selected', opt.dataset.value === value);
            });
            document.getElementById('tzDropdownList').style.display = 'none';
            document.getElementById('tzChevron').style.transform    = 'rotate(0deg)';
            document.getElementById('tzDropdownBtn').style.borderColor = '#e2e8f0';
        }

        document.querySelectorAll('.tz-option').forEach(opt => {
            opt.addEventListener('click', () => setTimezone(opt.dataset.value, opt.textContent.trim()));
        });

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('tzDropdownWrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('tzDropdownList').style.display = 'none';
                document.getElementById('tzChevron').style.transform    = 'rotate(0deg)';
            }
        });
    </script>

</x-app-layout>