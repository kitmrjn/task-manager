<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">👥</div>
            <div>
                <p class="db-greeting">Manage your workspace</p>
                <h2 class="db-title">Team</h2>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root{
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;
    --c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;
    --c-green:#1a8a5a;--c-green-lt:#e8f6f0;
    --c-purple:#7c3aed;--c-purple-lt:#f5f3ff;
    --c-rule:#e8eaf0;--radius:10px;
    --shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:0 4px 16px rgba(27,43,94,0.10);
    --shadow-lg:0 12px 32px rgba(27,43,94,0.14);
}
body{background:var(--c-bg);color:var(--c-text);font-family:'Epilogue',sans-serif;}
.db-header-inner{display:flex;justify-content:space-between;align-items:center;}
.db-header-left{display:flex;align-items:center;gap:.9rem;}
.db-avatar{width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center;}
.db-greeting{font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500;}
.db-title{font-size:17px;font-weight:600;color:var(--c-text);}

.tm-page{padding:2rem 0 3rem;}
.tm-wrap{max-width:1100px;margin:0 auto;padding:0 1.5rem;display:flex;flex-direction:column;gap:1.5rem;}
.tm-toolbar{display:flex;gap:.75rem;align-items:center;}
.tm-search{flex:1;position:relative;}
.tm-search input{width:100%;padding:.6rem .9rem .6rem 2.4rem;border:1.5px solid var(--c-border);border-radius:8px;background:var(--c-white);font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);outline:none;transition:border-color .15s;}
.tm-search input:focus{border-color:var(--c-blue);}
.tm-search-icon{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--c-soft);font-size:14px;}

.tm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;}
.tm-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.4rem;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:.75rem;animation:fadeUp .4s ease both;transition:box-shadow .2s,transform .2s;}
.tm-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);}
.tm-card:nth-child(2){animation-delay:.06s;}.tm-card:nth-child(3){animation-delay:.12s;}
.tm-card:nth-child(4){animation-delay:.18s;}.tm-card:nth-child(5){animation-delay:.24s;}
.tm-card:nth-child(6){animation-delay:.30s;}
.tm-card-top{position:relative;display:flex;align-items:center;gap:.85rem;}
.tm-av{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;letter-spacing:.03em;}
.tm-name{font-size:14px;font-weight:600;color:var(--c-text);}
.tm-email{font-size:11.5px;color:var(--c-soft);margin-top:1px;}
.tm-role-row{display:flex;align-items:center;justify-content:space-between;}
.tm-role{display:inline-block;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:4px;letter-spacing:.04em;}
.tm-role.admin{background:var(--c-blue-lt);color:var(--c-blue);}
.tm-role.member{background:var(--c-teal-lt);color:var(--c-teal);}
.tm-role.manager{background:var(--c-amber-lt);color:var(--c-amber);}
.tm-tasks-label{font-size:11px;color:var(--c-soft);}
.tm-tasks-label span{font-weight:600;color:var(--c-text);}
.tm-online{width:8px;height:8px;border-radius:50%;background:#ccc;flex-shrink:0;}
.tm-online.active{background:var(--c-green);}

.tm-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:600px){.tm-summary{grid-template-columns:1fr;}}
.tm-sum-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.1rem 1.3rem;box-shadow:var(--shadow-sm);text-align:center;animation:fadeUp .4s .1s ease both;}
.tm-sum-val{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--c-navy);}
.tm-sum-label{font-size:11px;color:var(--c-soft);text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin-top:.2rem;}

/* Edit button */
.tm-edit-btn{position:absolute;top:0;right:0;background:none;border:none;cursor:pointer;color:var(--c-soft);font-size:15px;padding:2px 4px;border-radius:6px;transition:color .15s,background .15s;}
.tm-edit-btn:hover{color:var(--c-blue);background:var(--c-blue-lt);}

/* Card action buttons row */
.tm-card-actions{display:flex;gap:.45rem;margin-top:.25rem;}
.tm-card-action-btn{flex:1;padding:.4rem .6rem;border-radius:7px;border:1.5px solid var(--c-border);background:var(--c-white);font-family:'Epilogue',sans-serif;font-size:11.5px;font-weight:600;color:var(--c-muted);cursor:pointer;transition:all .15s;text-align:center;}
.tm-card-action-btn:hover{border-color:var(--c-blue);color:var(--c-blue);background:var(--c-blue-lt);}
.tm-card-action-btn.perms{border-color:#c4b5fd;color:var(--c-purple);background:var(--c-purple-lt);}
.tm-card-action-btn.perms:hover{background:#ede9fe;}

/* ── Edit Modal ── */
.tm-modal-bg{display:none;position:fixed;inset:0;background:rgba(27,43,94,.35);backdrop-filter:blur(4px);z-index:999;align-items:center;justify-content:center;}
.tm-modal-bg.open{display:flex;}
.tm-modal{background:var(--c-white);border-radius:14px;padding:2rem;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(27,43,94,.18);animation:fadeUp .25s ease;}
.tm-modal h3{font-size:16px;font-weight:700;color:var(--c-text);margin-bottom:1.2rem;}
.tm-field{display:flex;flex-direction:column;gap:.3rem;margin-bottom:1rem;}
.tm-field label{font-size:11.5px;font-weight:600;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em;}
.tm-field input,.tm-field select{padding:.55rem .8rem;border:1.5px solid var(--c-border);border-radius:8px;font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);outline:none;transition:border-color .15s;}
.tm-field input:focus,.tm-field select:focus{border-color:var(--c-blue);}
.tm-modal-actions{display:flex;gap:.6rem;justify-content:flex-end;margin-top:1.4rem;}
.tm-btn{padding:.5rem 1.1rem;border-radius:8px;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:opacity .15s;}
.tm-btn.primary{background:var(--c-blue);color:#fff;}.tm-btn.primary:hover{opacity:.88;}
.tm-btn.ghost{background:var(--c-bg);color:var(--c-muted);border:1.5px solid var(--c-border);}
.tm-save-msg{font-size:12px;color:var(--c-green);margin-right:auto;display:none;}

/* ── Permissions Modal ── */
.tm-perm-modal-bg{display:none;position:fixed;inset:0;background:rgba(27,43,94,.4);backdrop-filter:blur(4px);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.tm-perm-modal-bg.open{display:flex;}
.tm-perm-modal{background:var(--c-white);border-radius:16px;width:100%;max-width:480px;box-shadow:var(--shadow-lg);animation:fadeUp .25s ease;overflow:hidden;}
.tm-perm-head{padding:1.3rem 1.5rem;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:.85rem;}
.tm-perm-av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;}
.tm-perm-name{font-size:15px;font-weight:700;color:var(--c-text);}
.tm-perm-sub{font-size:11.5px;color:var(--c-soft);margin-top:1px;}
.tm-perm-close{margin-left:auto;width:30px;height:30px;border:1.5px solid var(--c-border);border-radius:7px;background:var(--c-white);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--c-muted);font-size:15px;}
.tm-perm-close:hover{background:var(--c-bg);}
.tm-perm-body{padding:1.3rem 1.5rem;display:flex;flex-direction:column;gap:.6rem;}
.tm-perm-section-label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--c-soft);margin-bottom:.2rem;margin-top:.4rem;}

/* Toggle row */
.tm-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:.7rem 1rem;border:1.5px solid var(--c-border);border-radius:9px;background:var(--c-surface);transition:border-color .15s;}
.tm-toggle-row:hover{border-color:var(--c-border-2);}
.tm-toggle-label{display:flex;align-items:center;gap:.6rem;}
.tm-toggle-icon{font-size:16px;width:22px;text-align:center;}
.tm-toggle-text{font-size:13px;font-weight:600;color:var(--c-text);}
.tm-toggle-desc{font-size:11px;color:var(--c-soft);margin-top:1px;}

/* The actual toggle switch */
.tm-toggle{position:relative;display:inline-block;width:42px;height:24px;flex-shrink:0;}
.tm-toggle input{opacity:0;width:0;height:0;}
.tm-toggle-slider{position:absolute;cursor:pointer;inset:0;background:#d1d5db;border-radius:99px;transition:.25s;}
.tm-toggle-slider:before{content:'';position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.25s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.tm-toggle input:checked + .tm-toggle-slider{background:var(--c-blue);}
.tm-toggle input:checked + .tm-toggle-slider:before{transform:translateX(18px);}

.tm-perm-footer{padding:1rem 1.5rem;border-top:1px solid var(--c-border);display:flex;justify-content:flex-end;gap:.6rem;background:var(--c-surface);}
.tm-perm-save-msg{font-size:12px;color:var(--c-green);margin-right:auto;display:none;align-items:center;gap:.3rem;}

.av-1{background:#2d52c4;}.av-2{background:#0e9f8e;}.av-3{background:#c47c0e;}
.av-4{background:#c0354a;}.av-5{background:#6d52c4;}.av-6{background:#1a8a5a;}
.av-7{background:#db2777;}.av-8{background:#0d9488;}

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
</style>

<div class="tm-page">
<div class="tm-wrap">

    {{-- Summary --}}
    <div class="tm-summary">
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $teamCount }}</div>
            <div class="tm-sum-label">Team Members</div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $activeCount }}</div>
            <div class="tm-sum-label">Active Today</div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $openTasks }}</div>
            <div class="tm-sum-label">Open Tasks</div>
        </div>
    </div>

    {{-- Search --}}
    <div class="tm-toolbar">
        <div class="tm-search">
            <span class="tm-search-icon">🔍</span>
            <input type="text" placeholder="Search team members..." id="memberSearch" oninput="filterMembers()">
        </div>
    </div>

    {{-- Member Grid --}}
    <div class="tm-grid" id="memberGrid">
        @forelse($members ?? [] as $member)
        @php
            $roleClass = match(strtolower($member->role ?? 'team_member')) { 'admin'=>'admin','manager'=>'manager',default=>'member' };
            $avIdx     = ($loop->index % 8) + 1;
            $initials  = strtoupper(substr($member->name,0,1) . (strpos($member->name,' ')!==false ? substr($member->name,strpos($member->name,' ')+1,1) : ''));
            $perms     = $member->getPermissions();
        @endphp
        <div class="tm-card" data-name="{{ strtolower($member->name) }}" data-id="{{ $member->id }}">
            <div class="tm-card-top">
                <div class="tm-av av-{{ $avIdx }}">{{ $initials }}</div>
                <div>
                    <div class="tm-name">{{ $member->name }}</div>
                    <div class="tm-email">{{ $member->email }}</div>
                </div>
                <div class="tm-online {{ $member->last_active && \Carbon\Carbon::parse($member->last_active)->diffInMinutes() < 30 ? 'active' : '' }}" style="margin-left:auto" title="Online status"></div>
                @if(auth()->user()->role === 'admin')
                <button class="tm-edit-btn"
                    onclick="openEdit(this)"
                    data-id="{{ $member->id }}"
                    data-name="{{ $member->name }}"
                    data-email="{{ $member->email }}"
                    data-role="{{ $member->role ?? 'team_member' }}"
                    title="Edit member">✏️</button>
                @endif
            </div>
            <div class="tm-role-row">
                <span class="tm-role {{ $roleClass }}">{{ ucfirst(str_replace('_',' ', $member->role ?? 'Member')) }}</span>
                <span class="tm-tasks-label"><span>{{ $member->tasks_count ?? 0 }}</span> tasks</span>
            </div>

            {{-- Permission toggles — only shown to admin, and only for non-admin members --}}
            @if(auth()->user()->role === 'admin' && $member->role !== 'admin')
            <div class="tm-card-actions">
                <button class="tm-card-action-btn perms" onclick="openPerms(
                    {{ $member->id }},
                    '{{ addslashes($member->name) }}',
                    'av-{{ $avIdx }}',
                    {{ $perms->can_view_calendar  ? 'true' : 'false' }},
                    {{ $perms->can_view_analytics ? 'true' : 'false' }},
                    {{ $perms->can_view_team      ? 'true' : 'false' }},
                    {{ $perms->can_view_reports   ? 'true' : 'false' }},
                    {{ $perms->can_create_tasks   ? 'true' : 'false' }},
                    {{ $perms->can_delete_tasks   ? 'true' : 'false' }}
                )">
                    🔐 Manage Permissions
                </button>
            </div>
            @endif
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--c-soft);font-size:14px;">No team members found.</div>
        @endforelse
    </div>

</div>
</div>

{{-- ── Edit Member Modal ── --}}
<div class="tm-modal-bg" id="editModalBg">
    <div class="tm-modal">
        <h3>Edit Member</h3>
        <input type="hidden" id="editId">
        <div class="tm-field">
            <label>Name</label>
            <input type="text" id="editName" placeholder="Full name">
        </div>
        <div class="tm-field">
            <label>Email</label>
            <input type="email" id="editEmail" placeholder="Email address">
        </div>
        <div class="tm-field">
            <label>New Password <span style="font-weight:400;color:var(--c-soft)">(leave blank to keep current)</span></label>
            <input type="password" id="editPassword" placeholder="••••••••">
        </div>
        <div class="tm-field">
            <label>Role</label>
            <select id="editRole">
                <option value="admin">Admin</option>
                <option value="team_member">Team Member</option>
            </select>
        </div>
        <div class="tm-modal-actions">
            <span class="tm-save-msg" id="saveMsg">✔ Saved!</span>
            <button class="tm-btn ghost" onclick="closeEdit()">Cancel</button>
            <button class="tm-btn primary" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>
</div>

{{-- ── Permissions Modal ── --}}
<div class="tm-perm-modal-bg" id="permModalBg">
    <div class="tm-perm-modal">
        <div class="tm-perm-head">
            <div class="tm-perm-av" id="perm-av">AB</div>
            <div>
                <div class="tm-perm-name" id="perm-name">Member Name</div>
                <div class="tm-perm-sub">Feature access permissions</div>
            </div>
            <button class="tm-perm-close" onclick="closePerms()">×</button>
        </div>

        <input type="hidden" id="permUserId">

        <div class="tm-perm-body">
            <div class="tm-perm-section-label">📄 Pages</div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">📅</span>
                    <div>
                        <div class="tm-toggle-text">Calendar</div>
                        <div class="tm-toggle-desc">View and manage calendar events</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_calendar" onchange="savePerms()">
                    <span class="tm-toggle-slider"></span>
                </label>
            </div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">📊</span>
                    <div>
                        <div class="tm-toggle-text">Analytics</div>
                        <div class="tm-toggle-desc">View performance reports and charts</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_analytics" onchange="savePerms()">
                    <span class="tm-toggle-slider"></span>
                </label>
            </div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">👥</span>
                    <div>
                        <div class="tm-toggle-text">Team Page</div>
                        <div class="tm-toggle-desc">View team members</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_team" onchange="savePerms()">
                    <span class="tm-toggle-slider"></span>
                </label>
            </div>

            <div class="tm-perm-section-label" style="margin-top:.4rem;">⚙️ Task Actions</div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">➕</span>
                    <div>
                        <div class="tm-toggle-text">Create Tasks</div>
                        <div class="tm-toggle-desc">Add new tasks to any column</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_create_tasks" onchange="savePerms()">
                    <span class="tm-toggle-slider"></span>
                </label>
            </div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">🗑️</span>
                    <div>
                        <div class="tm-toggle-text">Delete Tasks</div>
                        <div class="tm-toggle-desc">Remove tasks from the board</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_delete_tasks" onchange="savePerms()">
                    <span class="tm-toggle-slider"></span>
                </label>
            </div>
            
        </div>

        <div class="tm-perm-footer">
            <span class="tm-perm-save-msg" id="permSaveMsg">✔ Saved!</span>
            <button class="tm-btn ghost" onclick="closePerms()">Close</button>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ── Search ── */
function filterMembers() {
    const q = document.getElementById('memberSearch').value.toLowerCase();
    document.querySelectorAll('.tm-card').forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
}

/* ── Edit Modal ── */
function openEdit(btn) {
    document.getElementById('editId').value       = btn.dataset.id;
    document.getElementById('editName').value     = btn.dataset.name;
    document.getElementById('editEmail').value    = btn.dataset.email;
    document.getElementById('editRole').value     = btn.dataset.role;
    document.getElementById('editPassword').value = '';
    document.getElementById('saveMsg').style.display = 'none';
    document.getElementById('editModalBg').classList.add('open');
}
function closeEdit() {
    document.getElementById('editModalBg').classList.remove('open');
}
document.getElementById('editModalBg').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
async function saveEdit() {
    const id       = document.getElementById('editId').value;
    const name     = document.getElementById('editName').value;
    const email    = document.getElementById('editEmail').value;
    const password = document.getElementById('editPassword').value;
    const role     = document.getElementById('editRole').value;

    const payload = { name, email, role, _method: 'PUT' };
    if (password) payload.password = password;

    const res = await fetch(`/team/members/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    });

    if (res.ok) {
        const card = document.querySelector(`.tm-card[data-id="${id}"]`);
        if (card) {
            card.dataset.name = name.toLowerCase();
            card.querySelector('.tm-name').textContent  = name;
            card.querySelector('.tm-email').textContent = email;
            const roleSpan  = card.querySelector('.tm-role');
            const roleLabel = role === 'team_member' ? 'Team Member' : role.charAt(0).toUpperCase() + role.slice(1);
            const roleClass = role === 'team_member' ? 'member' : role;
            roleSpan.textContent = roleLabel;
            roleSpan.className   = `tm-role ${roleClass}`;
            const editBtn = card.querySelector('.tm-edit-btn');
            if (editBtn) { editBtn.dataset.name = name; editBtn.dataset.email = email; editBtn.dataset.role = role; }
        }
        document.getElementById('saveMsg').style.display = 'inline';
        setTimeout(closeEdit, 1200);
    } else {
        const err = await res.json();
        alert('Failed to save: ' + (err.message ?? JSON.stringify(err)));
    }
}

/* ── Permissions Modal ── */
function openPerms(userId, name, avClass, calendar, analytics, team, reports, createTasks, deleteTasks) {
    document.getElementById('permUserId').value          = userId;
    document.getElementById('perm-name').textContent     = name;
    document.getElementById('perm-av').textContent       = name.split(' ').map(n=>n[0]).join('').toUpperCase().substr(0,2);
    document.getElementById('perm-av').className         = 'tm-perm-av ' + avClass;
    document.getElementById('perm_calendar').checked     = calendar;
    document.getElementById('perm_analytics').checked    = analytics;
    document.getElementById('perm_team').checked         = team;
    document.getElementById('perm_create_tasks').checked = createTasks;
    document.getElementById('perm_delete_tasks').checked = deleteTasks;
    document.getElementById('permSaveMsg').style.display = 'none';
    document.getElementById('permModalBg').classList.add('open');
}
function closePerms() {
    document.getElementById('permModalBg').classList.remove('open');
}
document.getElementById('permModalBg').addEventListener('click', function(e) {
    if (e.target === this) closePerms();
});

async function savePerms() {
    const userId = document.getElementById('permUserId').value;
    if (!userId) return;

    const payload = {
        can_view_calendar:  document.getElementById('perm_calendar').checked,
        can_view_analytics: document.getElementById('perm_analytics').checked,
        can_view_team:      document.getElementById('perm_team').checked,
        can_view_reports:   false, // reserved for future
        can_create_tasks:   document.getElementById('perm_create_tasks').checked,
        can_delete_tasks:   document.getElementById('perm_delete_tasks').checked,
    };

    const res = await fetch(`/team/members/${userId}/permissions`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    });

if (res.ok) {
    // Show saved message
    const msg = document.getElementById('permSaveMsg');
    msg.style.display = 'flex';
    setTimeout(() => msg.style.display = 'none', 2000);

    // Update the button's data attributes so reopening the modal shows correct state
    const userId = document.getElementById('permUserId').value;
    const card   = document.querySelector(`.tm-card[data-id="${userId}"]`);
    if (card) {
        const btn = card.querySelector('.tm-card-action-btn.perms');
        if (btn) {
            // Rebuild the onclick with updated values
            const calendar    = document.getElementById('perm_calendar').checked;
            const analytics   = document.getElementById('perm_analytics').checked;
            const team        = document.getElementById('perm_team').checked;
            const createTasks = document.getElementById('perm_create_tasks').checked;
            const deleteTasks = document.getElementById('perm_delete_tasks').checked;
            const name        = document.getElementById('perm-name').textContent;
            const avClass     = document.getElementById('perm-av').className.replace('tm-perm-av ', '');

            btn.setAttribute('onclick', `openPerms(${userId},'${name}','${avClass}',${calendar},${analytics},${team},false,${createTasks},${deleteTasks})`);
        }
    }
} else {
    alert('Failed to save permissions.');
}
}
</script>
</x-app-layout>