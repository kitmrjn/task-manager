<x-app-layout>
    @section('title', 'User Directory')

    <x-slot name="header">
        <div class="tk-topnav" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            
            <div class="flex items-center gap-6 pl-2 md:pl-6">
                <div><h2 class="font-semibold text-xl text-gray-800 leading-tight m-0" style="font-family: 'Epilogue', sans-serif;">{{ __('User Directory') }}</h2></div>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-user-modal')" style="background: var(--c-blue); border: none; padding: 0.5rem 1rem; border-radius: 6px; color: white; font-family: 'Epilogue', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                    Add User
                </button>
            </div>

            <div class="tk-topnav-right">
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
                            @if(auth()->user()->role === 'super_admin')
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
    @push('scripts')
<script>
const ID_TYPES = {
    sss_card:         'SSS Card',
    philhealth_card:  'PhilHealth Card',
    tin_card:         'TIN Card',
    pagibig_card:     'Pag-IBIG Card',
    passport:         'Passport',
    drivers_license:  "Driver's License",
};

// Track which types are already attached (for edit modal)
function getAttachedTypes() {
    const existing = document.querySelectorAll('#edit-existing-ids input[name="id_type"]');
    return [...existing].map(i => i.value);
}

function getUsedSlotTypes(prefix) {
    const selects = document.querySelectorAll(`#${prefix}-id-slots select`);
    return [...selects].map(s => s.value).filter(Boolean);
}

function buildIdTypeOptions(prefix, excludeTypes = [], selectedType = '') {
    return Object.entries(ID_TYPES).map(([val, label]) => {
        if (excludeTypes.includes(val) && val !== selectedType) return '';
        return `<option value="${val}" ${val === selectedType ? 'selected' : ''}>${label}</option>`;
    }).join('');
}

function addIdSlot(prefix) {
    const container = document.getElementById(`${prefix}-id-slots`);
    const attached  = prefix === 'edit' ? getAttachedTypes() : [];
    const used      = getUsedSlotTypes(prefix);
    const excluded  = [...attached, ...used];

    // Check if all types are already used
    const remaining = Object.keys(ID_TYPES).filter(t => !excluded.includes(t));
    if (!remaining.length) {
        alert('All ID types have already been attached.');
        return;
    }

    const slotId = `slot-${prefix}-${Date.now()}`;
    const div    = document.createElement('div');
    div.id       = slotId;
    div.style.cssText = 'display:flex;align-items:center;gap:.5rem;background:#f8fafc;border:1px solid var(--c-border);border-radius:6px;padding:.5rem .75rem;';
    div.innerHTML = `
        <select onchange="refreshSlotOptions('${prefix}')"
                style="border:1px solid var(--c-border);border-radius:6px;padding:.35rem .6rem;font-size:13px;font-family:inherit;flex:0 0 180px;">
            <option value="">Select ID type…</option>
            ${buildIdTypeOptions(prefix, excluded)}
        </select>
        <input type="file" name="_pending_id_file" accept="image/jpg,image/jpeg,image/png"
               style="flex:1;font-size:13px;" />
        <button type="button" onclick="removeIdSlot('${slotId}', '${prefix}')"
                style="color:var(--c-red);background:none;border:none;cursor:pointer;font-size:18px;line-height:1;">&times;</button>
    `;
    container.appendChild(div);
}

function removeIdSlot(slotId, prefix) {
    document.getElementById(slotId)?.remove();
    refreshSlotOptions(prefix);
}

// Refresh all slot dropdowns so selected types in one slot are excluded from others
function refreshSlotOptions(prefix) {
    const container = document.getElementById(`${prefix}-id-slots`);
    const slots     = container.querySelectorAll('div[id^="slot-"]');
    const attached  = prefix === 'edit' ? getAttachedTypes() : [];

    slots.forEach(slot => {
        const select  = slot.querySelector('select');
        const current = select.value;
        const used    = [...container.querySelectorAll('select')]
                          .filter(s => s !== select)
                          .map(s => s.value)
                          .filter(Boolean);
        const excluded = [...attached, ...used];

        select.innerHTML = `<option value="">Select ID type…</option>${buildIdTypeOptions(prefix, excluded, current)}`;
        select.value = current;
    });

    // Wire up the correct file input name based on selected type
    wirePendingInputs(prefix);
}

function wirePendingInputs(prefix) {
    const container = document.getElementById(`${prefix}-id-slots`);
    container.querySelectorAll('div[id^="slot-"]').forEach(slot => {
        const select = slot.querySelector('select');
        const input  = slot.querySelector('input[type="file"]');
        if (select.value) {
            input.name = `valid_id_${select.value}`;
        } else {
            input.name = '_pending_id_file';
        }
    });
}

// Wire names on select change too
document.addEventListener('change', e => {
    if (e.target.matches('#create-id-slots select, #edit-id-slots select')) {
        const prefix = e.target.closest('[id$="-id-slots"]').id.includes('create') ? 'create' : 'edit';
        refreshSlotOptions(prefix);
    }
});

// formatIdType used in Alpine template
window.formatIdType = function(type) {
    return ID_TYPES[type] || type;
};
</script>
@endpush

    <div class="py-10" style="font-family: 'Epilogue', sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div style="background: rgba(45, 204, 112, 0.1); border-left: 4px solid #2dcc70; padding: 1rem; border-radius: 4px; color: #1e824c; font-size: 14px; font-weight: 500;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="section-eyebrow mb-6">Employee Management</div>

            <div class="card" style="padding: 1.5rem;">
                <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                    
                    <div style="flex: 1; min-width: 200px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px;">
                    </div>

                    <div style="min-width: 150px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Role</label>
                        <select name="role" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px; background: #fff;">
                            <option value="">All Roles</option>
                            @if(isset($roles))
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->slug }}" {{ request('role') == $roleOption->slug ? 'selected' : '' }}>{{ $roleOption->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div style="min-width: 150px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Campaign</label>
                        <select name="campaign" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px; background: #fff;">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp->id }}" {{ request('campaign') == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="min-width: 150px;">
                        <label style="font-size: 12px; font-weight: 600; color: var(--c-soft); margin-bottom: 0.25rem; display: block;">Status</label>
                        <select name="status" style="width: 100%; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px; background: #fff;">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Setup</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Deactivated</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" style="background: var(--c-navy); color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1.25rem; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer;">Filter</button>
                        <a href="{{ route('admin.users.index') }}" style="background: #f1f5f9; color: var(--c-navy); border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 1.25rem; font-family: inherit; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block;">Reset</a>
                    </div>
                </form>
            </div>

            <div class="card" style="overflow: visible; contain: none;">
                <table class="task-table" style="width: 100%; overflow: visible;">
                    <thead>
                        <tr>
                            <th>User Profile</th>
                            <th>Role</th>
                            <th>Assignment</th>
                            <th>Status</th>
                            <td style="text-align: right; overflow: visible; position: relative;">
                        </tr>
                    </thead>
                    <tbody x-data="{ userToEdit: null }">
                        @forelse($users as $user)
                            <tr class="task-row">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--c-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--c-navy); font-size: 14px;">{{ $user->name }}</div>
                                            <div style="font-size: 12px; color: var(--c-soft);">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="pill" style="border: 1px solid var(--c-border); background: #f8fafc; color: var(--c-navy);">
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>

                                <td>
                                    <div style="font-weight: 500; font-size: 13px;">{{ $user->campaign ? $user->campaign->name : 'Unassigned' }}</div>
                                    <div style="font-size: 12px; color: var(--c-soft);">Leader: {{ $user->teamLeader ? $user->teamLeader->name : 'None' }}</div>
                                </td>

                                <td>
                                    @if(!$user->is_active)
                                        <span class="pill" style="background:#fee2e2; color:#991b1b; border: 1px solid #fca5a5;">Deactivated</span>
                                    @elseif($user->email_verified_at)
                                        <span class="pill" style="background:#dcfce7; color:#166534; border: 1px solid #86efac;">Active</span>
                                    @else
                                        <span class="pill" style="background:#fef3c7; color:#92400e; border: 1px solid #fcd34d;">Pending Setup</span>
                                    @endif
                                </td>

                                <td style="text-align: right; overflow: visible;">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button style="background: none; border: none; cursor: pointer; color: var(--c-soft);">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <button x-on:click="$dispatch('open-modal', 'edit-user-modal'); $dispatch('set-edit-user', {{ json_encode($user) }})" style="width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 13px; border: none; background: transparent; cursor: pointer;">Edit Profile</button>
                                            
                                            @if($user->is_active && !$user->email_verified_at)
                                                <form method="POST" action="{{ route('admin.users.resend-invite', $user) }}">
                                                    @csrf <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 13px; color: var(--c-blue); border: none; background: transparent; cursor: pointer;">Resend Invite</button>
                                                </form>
                                            @endif

                                            @if(auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 1rem; font-size: 13px; color: {{ $user->is_active ? 'var(--c-red)' : '#166534' }}; border: none; background: transparent; cursor: pointer;">
                                                        {{ $user->is_active ? 'Deactivate Account' : 'Reactivate Account' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row"><td colspan="5">No users match your criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($users->hasPages())
                    <div style="padding: 1rem; border-top: 1px solid var(--c-border-2);">{{ $users->links() }}</div>
                @endif
            </div>

        </div>
    </div>

 {{-- ============================================================
     CREATE USER MODAL
============================================================ --}}
<x-modal name="create-user-modal" focusable>
    <form method="post" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        <h2 class="text-lg font-medium text-gray-900 mb-4" style="font-family:'Epilogue',sans-serif;">Add New User</h2>

        {{-- Basic Info --}}
        <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Basic Information</div>
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <x-input-label value="Full Name" />
                <x-text-input name="name" type="text" x-model="user.name" class="mt-1 block w-full" required />
            </div>
            <div class="col-span-2">
                <x-input-label value="Email Address" /><x-text-input name="email" type="email" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label value="Phone" /><x-text-input name="phone" type="text" class="mt-1 block w-full" placeholder="+63 9xx xxx xxxx" />
            </div>
            <div>
                <x-input-label value="City" /><x-text-input name="city" type="text" class="mt-1 block w-full" />
            </div>
            <div class="col-span-2">
                <x-input-label value="Address" /><x-text-input name="address" type="text" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label value="Country" />
                <select name="country" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                    <option value="Philippines" selected>Philippines</option>
                    <option value="United States">United States</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <x-input-label value="Profile Photo" />
                <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-slate-100 file:text-sm file:font-medium" />
            </div>
        </div></div>

        {{-- Role & Assignment --}}
        <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Role & Assignment</div>
        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-input-label value="Role" />
                <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                    @if(isset($roles)) @foreach($roles as $r) <option value="{{ $r->slug }}">{{ $r->name }}</option> @endforeach @endif
                </select>
            </div>
            <div>
                <x-input-label value="Campaign (Optional)" />
                <select name="campaign_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">-- None --</option>
                    @foreach($campaigns as $camp) <option value="{{ $camp->id }}">{{ $camp->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <x-input-label value="Team Leader (Optional)" />
                <select name="team_leader_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">-- None --</option>
                    @foreach($teamLeaders as $leader) <option value="{{ $leader->id }}">{{ $leader->name }}</option> @endforeach
                </select>
            </div>
        </div></div>

        {{-- Government Numbers --}}
        <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Government Numbers</div>
        <div class="grid grid-cols-2 gap-4">
            <div><x-input-label value="SSS Number" /><x-text-input name="sss_number" type="text" class="mt-1 block w-full" placeholder="xx-xxxxxxx-x" /></div>
            <div><x-input-label value="PhilHealth Number" /><x-text-input name="philhealth_number" type="text" class="mt-1 block w-full" placeholder="xx-xxxxxxxxx-x" /></div>
            <div><x-input-label value="TIN Number" /><x-text-input name="tin_number" type="text" class="mt-1 block w-full" placeholder="xxx-xxx-xxx" /></div>
            <div><x-input-label value="Pag-IBIG Number" /><x-text-input name="pag_ibig_number" type="text" class="mt-1 block w-full" placeholder="xxxx-xxxx-xxxx" /></div>
        </div></div>

        {{-- Valid ID Attachments --}}
        <div class="mb-4">
            <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Valid ID Attachments</div>
            <div id="create-id-slots" style="display:flex;flex-direction:column;gap:.75rem;"></div>
            <button type="button" onclick="addIdSlot('create')" style="margin-top:.5rem;font-size:13px;color:var(--c-blue);background:none;border:1px dashed var(--c-blue);border-radius:6px;padding:.4rem 1rem;cursor:pointer;width:100%;">
                + Attach an ID
            </button>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
            <x-primary-button style="background:var(--c-navy);">Send Invitation</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- ============================================================
     EDIT USER MODAL
============================================================ --}}
<x-modal name="edit-user-modal" focusable>
    <div x-data="{ user: {} }" @set-edit-user.window="user = $event.detail">
        <form method="POST" x-bind:action="`/admin/users/${user.id}`" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <h2 class="text-lg font-medium text-gray-900 mb-4" style="font-family:'Epilogue',sans-serif;">Edit User Profile</h2>

            {{-- Basic Info --}}
            <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Basic Information</div>
            <div class="grid grid-cols-2 gap-4">
                <<div class="col-span-2">
                    <x-input-label value="Full Name" />
                    <x-text-input name="name" type="text" x-model="user.name" class="mt-1 block w-full" required />
                </div>
                <div class="col-span-2"><x-input-label value="Email" /><x-text-input name="email" type="email" x-model="user.email" class="mt-1 block w-full" required /></div>
                <div><x-input-label value="Phone" /><x-text-input name="phone" type="text" x-model="user.phone" class="mt-1 block w-full" /></div>
                <div><x-input-label value="City" /><x-text-input name="city" type="text" x-model="user.city" class="mt-1 block w-full" /></div>
                <div class="col-span-2"><x-input-label value="Address" /><x-text-input name="address" type="text" x-model="user.address" class="mt-1 block w-full" /></div>
                <div>
                    <x-input-label value="Country" />
                    <select name="country" x-model="user.country" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="Philippines">Philippines</option>
                        <option value="United States">United States</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <x-input-label value="Profile Photo" />
                    <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-slate-100 file:text-sm file:font-medium" />
                    <template x-if="user.photo">
                        <img :src="`/storage/${user.photo}`" style="margin-top:.5rem;height:48px;width:48px;object-fit:cover;border-radius:50%;border:2px solid var(--c-border);" />
                    </template>
                </div>
            </div></div>

            {{-- Role & Assignment --}}
            <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Role & Assignment</div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-input-label value="Role" />
                    <select name="role" x-model="user.role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                        @if(isset($roles)) @foreach($roles as $r) <option value="{{ $r->slug }}">{{ $r->name }}</option> @endforeach @endif
                    </select>
                </div>
                <div>
                    <x-input-label value="Campaign (Optional)" />
                    <select name="campaign_id" x-model="user.campaign_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($campaigns as $camp) <option value="{{ $camp->id }}">{{ $camp->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Team Leader (Optional)" />
                    <select name="team_leader_id" x-model="user.team_leader_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($teamLeaders as $leader) <option value="{{ $leader->id }}">{{ $leader->name }}</option> @endforeach
                    </select>
                </div>
            </div></div>

            {{-- Government Numbers --}}
            <div class="mb-4"><div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Government Numbers</div>
            <div class="grid grid-cols-2 gap-4">
                <div><x-input-label value="SSS Number" /><x-text-input name="sss_number" type="text" x-model="user.sss_number" class="mt-1 block w-full" /></div>
                <div><x-input-label value="PhilHealth Number" /><x-text-input name="philhealth_number" type="text" x-model="user.philhealth_number" class="mt-1 block w-full" /></div>
                <div><x-input-label value="TIN Number" /><x-text-input name="tin_number" type="text" x-model="user.tin_number" class="mt-1 block w-full" /></div>
                <div><x-input-label value="Pag-IBIG Number" /><x-text-input name="pag_ibig_number" type="text" x-model="user.pag_ibig_number" class="mt-1 block w-full" /></div>
            </div></div>

            {{-- Valid ID Attachments --}}
            <div class="mb-4">
                <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Valid ID Attachments</div>

                {{-- Existing IDs --}}
                <div id="edit-existing-ids" style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:.75rem;">
                    <template x-if="user.valid_ids && user.valid_ids.length">
                        <template x-for="vid in user.valid_ids" :key="vid.id">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .75rem;background:#f8fafc;border:1px solid var(--c-border);border-radius:6px;font-size:13px;">
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                                    <span x-text="formatIdType(vid.id_type)" style="font-weight:600;color:var(--c-navy);"></span>
                                    <span x-text="vid.original_filename" style="color:var(--c-soft);"></span>
                                </div>
                                <div style="display:flex;gap:.5rem;">
                                    <a :href="`/storage/${vid.file_path}`" target="_blank" style="font-size:12px;color:var(--c-blue);text-decoration:none;font-weight:600;">View</a>
                                    <form method="POST" :action="`/admin/users/${user.id}/valid-ids`" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="id_type" :value="vid.id_type">
                                        <button type="submit" style="font-size:12px;color:var(--c-red);background:none;border:none;cursor:pointer;font-weight:600;" onclick="return confirm('Remove this ID?')">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>

                <div id="edit-id-slots" style="display:flex;flex-direction:column;gap:.75rem;"></div>
                <button type="button" onclick="addIdSlot('edit')" style="margin-top:.5rem;font-size:13px;color:var(--c-blue);background:none;border:1px dashed var(--c-blue);border-radius:6px;padding:.4rem 1rem;cursor:pointer;width:100%;">
                    + Attach another ID
                </button>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button style="background:var(--c-navy);">Save Changes</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
</x-app-layout>