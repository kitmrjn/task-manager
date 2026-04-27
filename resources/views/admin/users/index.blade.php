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
    sss_card:        'SSS ID',
    philhealth_card: 'PhilHealth ID',
    tin_card:        'TIN ID',
    pagibig_card:    'Pag-IBIG ID',
    passport:        'Passport ID',
    drivers_license: "Driver's License",
};

window.formatIdType = function(type) {
    return ID_TYPES[type] || type;
};

function getUsedTypes(prefix) {
    // Types already saved on the user (edit modal only)
    const attached = prefix === 'edit'
        ? [...document.querySelectorAll('#edit-existing-ids input[name="id_type"]')].map(i => i.value)
        : [];
    // Types selected in the current new slots
    const slotTypes = [...document.querySelectorAll(`#${prefix}-id-slots .id-slot`)]
        .map(s => s.querySelector('select')?.value)
        .filter(Boolean);
    return [...new Set([...attached, ...slotTypes])];
}

function buildOptions(prefix, selectedType = '') {
    const used = getUsedTypes(prefix);
    let html = '<option value="">Select ID type…</option>';
    for (const [val, label] of Object.entries(ID_TYPES)) {
        // Include the currently-selected type of this slot even if "used"
        if (!used.includes(val) || val === selectedType) {
            html += `<option value="${val}" ${val === selectedType ? 'selected' : ''}>${label}</option>`;
        }
    }
    return html;
}

function refreshAllDropdowns(prefix) {
    document.querySelectorAll(`#${prefix}-id-slots .id-slot`).forEach(slot => {
        const select  = slot.querySelector('select');
        const current = select.value;
        select.innerHTML = buildOptions(prefix, current);
        select.value = current;
    });
}

function onTypeSelected(selectEl, prefix) {
    const slot     = selectEl.closest('.id-slot');
    const fileWrap = slot.querySelector('.file-wrap');
    const val      = selectEl.value;

    if (val) {
        fileWrap.style.display = 'flex';
        fileWrap.querySelector('input[type="file"]').name = `valid_id_${val}`;
    } else {
        fileWrap.style.display = 'none';
        const fi = fileWrap.querySelector('input[type="file"]');
        fi.name  = '_pending_id_file';
        fi.value = '';
    }

    refreshAllDropdowns(prefix);
    maybeAddNextSlot(prefix);
}

function onFileAttached(fileInput, prefix) {
    if (fileInput.files.length > 0) {
        maybeAddNextSlot(prefix);
    }
}

function maybeAddNextSlot(prefix) {
    const container = document.getElementById(`${prefix}-id-slots`);
    const slots     = [...container.querySelectorAll('.id-slot')];
    const usedCount = getUsedTypes(prefix).length;
    const totalTypes = Object.keys(ID_TYPES).length;

    if (usedCount >= totalTypes) return; // all types used, no more slots needed

    if (slots.length === 0) {
        addIdSlot(prefix);
        return;
    }

    // Only add a new slot if the LAST slot has both a type selected AND a file attached
    const lastSlot   = slots[slots.length - 1];
    const lastSelect = lastSlot.querySelector('select');
    const lastFile   = lastSlot.querySelector('input[type="file"]');

    if (lastSelect?.value && lastFile?.files?.length > 0) {
        addIdSlot(prefix);
    }
}

function addIdSlot(prefix) {
    const container  = document.getElementById(`${prefix}-id-slots`);
    const usedCount  = getUsedTypes(prefix).length;
    const totalTypes = Object.keys(ID_TYPES).length;
    if (usedCount >= totalTypes) return;

    const slotId = `slot-${prefix}-${Date.now()}`;
    const div    = document.createElement('div');
    div.className = 'id-slot';
    div.id        = slotId;
    div.style.cssText = 'display:flex;flex-direction:column;gap:.4rem;';

    div.innerHTML = `
        <div style="display:flex;align-items:center;gap:.5rem;">
            <select onchange="onTypeSelected(this, '${prefix}')"
                    style="flex:1;border:1px solid var(--c-border);border-radius:6px;padding:.4rem .6rem;font-size:13px;font-family:inherit;background:#fff;">
                ${buildOptions(prefix)}
            </select>
            <button type="button" onclick="removeIdSlot('${slotId}', '${prefix}')"
                    style="color:var(--c-soft);background:none;border:none;cursor:pointer;font-size:20px;line-height:1;padding:0 .25rem;">&times;</button>
        </div>
        <div class="file-wrap" style="display:none;align-items:center;gap:.5rem;padding:.4rem .75rem;background:#f8fafc;border:1px solid var(--c-border);border-radius:6px;">
            <svg width="13" height="13" fill="none" stroke="var(--c-soft)" stroke-width="2" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
            <input type="file" name="_pending_id_file" accept="image/jpg,image/jpeg,image/png"
                   onchange="onFileAttached(this, '${prefix}')"
                   style="flex:1;font-size:13px;" />
        </div>
    `;

    container.appendChild(div);
}

function removeIdSlot(slotId, prefix) {
    document.getElementById(slotId)?.remove();
    refreshAllDropdowns(prefix);
    maybeAddNextSlot(prefix);
}

// Initialise first slot when each modal opens
document.addEventListener('open-modal', e => {
    if (e.detail === 'create-user-modal') {
        document.getElementById('create-id-slots').innerHTML = '';
        addIdSlot('create');
    }
    if (e.detail === 'edit-user-modal') {
        document.getElementById('edit-id-slots').innerHTML = '';
        // Small delay so Alpine can render existing IDs first (they affect used-type list)
        setTimeout(() => addIdSlot('edit'), 120);
    }
});
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
                            <td style="text-align: right; overflow: visible; position: relative;"></td>
                        </tr>
                    </thead>
                    <tbody x-data="{ userToEdit: null }">
                        @forelse($users as $user)
                            <tr class="task-row">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--c-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
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
        <div class="mb-4">
            <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Basic Information</div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label value="Full Name" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="col-span-2">
                    <x-input-label value="Email Address" />
                    <x-text-input name="email" type="email" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label value="Phone" />
                    <x-text-input name="phone" type="text" class="mt-1 block w-full" placeholder="+63 9xx xxx xxxx" />
                </div>
                <div>
                    <x-input-label value="City" />
                    <x-text-input name="city" type="text" class="mt-1 block w-full" />
                </div>
                <div class="col-span-2">
                    <x-input-label value="Address" />
                    <x-text-input name="address" type="text" class="mt-1 block w-full" />
                </div>
                <div class="col-span-2">
                    <x-input-label value="Country" />
                    <select name="country" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="Philippines" selected>Philippines</option>
                        <option value="United States">United States</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Role & Assignment --}}
        <div class="mb-4">
            <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Role & Assignment</div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-input-label value="Role" />
                    <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                        @if(isset($roles)) @foreach($roles as $r) <option value="{{ $r->slug }}">{{ $r->name }}</option> @endforeach @endif
                    </select>
                </div>
                <div>
                    <x-input-label value="Campaign" />
                    <select name="campaign_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($campaigns as $camp) <option value="{{ $camp->id }}">{{ $camp->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Team Leader" />
                    <select name="team_leader_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($teamLeaders as $leader) <option value="{{ $leader->id }}">{{ $leader->name }}</option> @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Valid ID Attachments --}}
        <div class="mb-4">
            <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Valid ID Attachments</div>
            <div id="create-id-slots" style="display:flex;flex-direction:column;gap:.5rem;"></div>
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
            <div class="mb-4">
                <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Basic Information</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <x-input-label value="Full Name" />
                        <x-text-input name="name" type="text" x-model="user.name" class="mt-1 block w-full" required />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Email" />
                        <x-text-input name="email" type="email" x-model="user.email" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label value="Phone" />
                        <x-text-input name="phone" type="text" x-model="user.phone" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label value="City" />
                        <x-text-input name="city" type="text" x-model="user.city" class="mt-1 block w-full" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Address" />
                        <x-text-input name="address" type="text" x-model="user.address" class="mt-1 block w-full" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label value="Country" />
                        <select name="country" x-model="user.country" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="Philippines">Philippines</option>
                            <option value="United States">United States</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Role & Assignment --}}
            <div class="mb-4">
                <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Role & Assignment</div>
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
                </div>
            </div>

            {{-- Valid ID Attachments --}}
            <div class="mb-4">
                <div class="section-eyebrow" style="font-size:11px;font-weight:700;color:var(--c-soft);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Valid ID Attachments</div>

                {{-- Existing IDs (already saved) --}}
                <div id="edit-existing-ids" style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:.75rem;">
                    <template x-if="user.valid_ids && user.valid_ids.length">
                        <template x-for="vid in user.valid_ids" :key="vid.id">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .75rem;background:#f8fafc;border:1px solid var(--c-border);border-radius:6px;font-size:13px;">
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                                    {{-- Hidden input so getUsedTypes() can detect attached types --}}
                                    <input type="hidden" name="id_type" :value="vid.id_type">
                                    <span x-text="formatIdType(vid.id_type)" style="font-weight:600;color:var(--c-navy);"></span>
                                    <span x-text="vid.original_filename" style="color:var(--c-soft);"></span>
                                </div>
                                <div style="display:flex;gap:.5rem;align-items:center;">
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

                {{-- New ID slots --}}
                <div id="edit-id-slots" style="display:flex;flex-direction:column;gap:.5rem;"></div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button style="background:var(--c-navy);">Save Changes</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>

</x-app-layout>