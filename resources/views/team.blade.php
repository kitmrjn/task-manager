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

/* Summary cards */
.tm-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:600px){.tm-summary{grid-template-columns:1fr;}}
.tm-sum-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.1rem 1.3rem;box-shadow:var(--shadow-sm);text-align:center;animation:fadeUp .4s .1s ease both;}
.tm-sum-val{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--c-navy);}
.tm-sum-label{font-size:11px;color:var(--c-soft);text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin-top:.2rem;}

/* Search bar */
.tm-toolbar{display:flex;gap:.75rem;align-items:center;}
.tm-search{flex:1;position:relative;}
.tm-search input{width:100%;padding:.6rem .9rem .6rem 2.4rem;border:1.5px solid var(--c-border);border-radius:8px;background:var(--c-white);font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);outline:none;transition:border-color .15s;}
.tm-search input:focus{border-color:var(--c-blue);}
.tm-search-icon{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--c-soft);font-size:14px;}

/* Member grid */
.tm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;}
.tm-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.4rem;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:.75rem;animation:fadeUp .4s ease both;transition:box-shadow .2s,transform .2s;cursor:pointer;}
.tm-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);}
.tm-card-top{display:flex;align-items:center;gap:.85rem;}
.tm-av{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;letter-spacing:.03em;}
.tm-name{font-size:14px;font-weight:600;color:var(--c-text);}
.tm-email{font-size:11.5px;color:var(--c-soft);margin-top:1px;}
.tm-role-row{display:flex;align-items:center;justify-content:space-between;}
.tm-role{display:inline-block;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:4px;letter-spacing:.04em;}
.tm-role.admin{background:var(--c-blue-lt);color:var(--c-blue);}
.tm-role.manager{background:var(--c-amber-lt);color:var(--c-amber);}
.tm-role.member{background:var(--c-teal-lt);color:var(--c-teal);}
.tm-tasks-label{font-size:11px;color:var(--c-soft);}
.tm-tasks-label span{font-weight:600;color:var(--c-text);}
.tm-online{width:8px;height:8px;border-radius:50%;background:#ccc;flex-shrink:0;}
.tm-online.active{background:var(--c-green);}

/* Avatar colors */
.av-1{background:#2d52c4;}.av-2{background:#0e9f8e;}.av-3{background:#c47c0e;}
.av-4{background:#c0354a;}.av-5{background:#6d52c4;}.av-6{background:#1a8a5a;}
.av-7{background:#db2777;}.av-8{background:#0d9488;}

/* ── MEMBER DETAIL MODAL ── */
.tm-modal-overlay{position:fixed;inset:0;background:rgba(16,24,40,.5);backdrop-filter:blur(4px);z-index:500;display:none;align-items:center;justify-content:center;padding:1rem;}
.tm-modal-overlay.open{display:flex;}
.tm-modal{background:var(--c-white);border-radius:16px;width:100%;max-width:560px;max-height:88vh;display:flex;flex-direction:column;box-shadow:var(--shadow-lg);animation:fadeUp .25s ease both;overflow:hidden;}
.tm-modal-head{padding:1.3rem 1.5rem;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:1rem;flex-shrink:0;}
.tm-modal-av{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;}
.tm-modal-name{font-size:17px;font-weight:700;color:var(--c-text);}
.tm-modal-email{font-size:12px;color:var(--c-soft);margin-top:2px;}
.tm-modal-close{margin-left:auto;width:32px;height:32px;border:1.5px solid var(--c-border);border-radius:8px;background:var(--c-white);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--c-muted);font-size:16px;flex-shrink:0;}
.tm-modal-close:hover{background:var(--c-bg);}
.tm-modal-body{flex:1;overflow-y:auto;padding:1.3rem 1.5rem;display:flex;flex-direction:column;gap:1.25rem;}

/* Role management */
.tm-role-section{background:var(--c-surface);border:1px solid var(--c-border);border-radius:10px;padding:1rem 1.2rem;}
.tm-role-section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--c-soft);margin-bottom:.75rem;}
.tm-role-options{display:flex;gap:.5rem;}
.tm-role-opt{flex:1;padding:.5rem;border:1.5px solid var(--c-border);border-radius:8px;text-align:center;font-size:12px;font-weight:600;cursor:pointer;transition:all .15s;background:var(--c-white);color:var(--c-muted);}
.tm-role-opt:hover{border-color:var(--c-blue);color:var(--c-blue);}
.tm-role-opt.selected.admin{border-color:var(--c-blue);background:var(--c-blue-lt);color:var(--c-blue);}
.tm-role-opt.selected.manager{border-color:var(--c-amber);background:var(--c-amber-lt);color:var(--c-amber);}
.tm-role-opt.selected.member{border-color:var(--c-teal);background:var(--c-teal-lt);color:var(--c-teal);}
.tm-save-role{margin-top:.75rem;width:100%;padding:.55rem;background:var(--c-navy);color:#fff;border:none;border-radius:8px;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:background .15s;}
.tm-save-role:hover{background:var(--c-blue);}

/* Task list in modal */
.tm-task-section-title{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--c-soft);margin-bottom:.5rem;display:flex;align-items:center;justify-content:space-between;}
.tm-task-item{display:flex;align-items:center;gap:.75rem;padding:.65rem .85rem;border:1px solid var(--c-border);border-radius:8px;margin-bottom:.4rem;background:var(--c-white);transition:border-color .15s;}
.tm-task-item:hover{border-color:#93c5fd;}
.tm-task-title{flex:1;font-size:13px;font-weight:500;color:var(--c-text);}
.tm-task-col{font-size:11px;color:var(--c-soft);}
.tm-task-due{font-size:11px;color:var(--c-soft);}
.tm-priority{font-size:10px;font-weight:700;padding:2px 7px;border-radius:4px;text-transform:uppercase;}
.tm-priority.high{background:var(--c-red-lt);color:var(--c-red);}
.tm-priority.medium{background:var(--c-amber-lt);color:var(--c-amber);}
.tm-priority.low{background:var(--c-green-lt);color:var(--c-green);}
.tm-collab-badge{font-size:10px;font-weight:600;padding:2px 7px;border-radius:4px;background:var(--c-purple-lt);color:var(--c-purple);}

.tm-empty{font-size:13px;color:var(--c-soft);text-align:center;padding:1rem 0;font-style:italic;}

.tm-stats-row{display:flex;gap:.75rem;}
.tm-mini-stat{flex:1;background:var(--c-surface);border:1px solid var(--c-border);border-radius:8px;padding:.7rem 1rem;text-align:center;}
.tm-mini-stat-val{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--c-navy);}
.tm-mini-stat-label{font-size:10px;color:var(--c-soft);text-transform:uppercase;letter-spacing:.07em;font-weight:600;margin-top:2px;}

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
        @forelse($members as $member)
        @php
            $roleClass = match(strtolower($member->role ?? 'member')) {
                'admin'   => 'admin',
                'manager' => 'manager',
                default   => 'member'
            };
            $avIdx    = ($loop->index % 8) + 1;
            $initials = strtoupper(substr($member->name, 0, 1) . (strpos($member->name, ' ') !== false ? substr($member->name, strpos($member->name, ' ') + 1, 1) : ''));
            $isActive = in_array($member->id, $activeUserIds ?? []);
        @endphp
        <div class="tm-card" data-name="{{ strtolower($member->name) }}"
             onclick="openMember({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->email }}', '{{ $member->role ?? 'member' }}', {{ $avIdx }}, '{{ $initials }}')">
            <div class="tm-card-top">
                <div class="tm-av av-{{ $avIdx }}">{{ $initials }}</div>
                <div>
                    <div class="tm-name">{{ $member->name }}</div>
                    <div class="tm-email">{{ $member->email }}</div>
                </div>
                <div class="tm-online {{ $isActive ? 'active' : '' }}" style="margin-left:auto" title="{{ $isActive ? 'Online' : 'Offline' }}"></div>
            </div>
            <div class="tm-role-row">
                <span class="tm-role {{ $roleClass }}">{{ ucfirst($member->role ?? 'Member') }}</span>
                <span class="tm-tasks-label"><span>{{ $member->tasks_count }}</span> open tasks</span>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--c-soft);font-size:13px;">
            No team members found.
        </div>
        @endforelse
    </div>

</div>
</div>

{{-- ── MEMBER DETAIL MODAL ── --}}
<div class="tm-modal-overlay" id="memberModal">
<div class="tm-modal">

    {{-- Header --}}
    <div class="tm-modal-head">
        <div class="tm-modal-av" id="modal-av"></div>
        <div>
            <div class="tm-modal-name" id="modal-name"></div>
            <div class="tm-modal-email" id="modal-email"></div>
        </div>
        <button class="tm-modal-close" onclick="closeModal()">×</button>
    </div>

    {{-- Body --}}
    <div class="tm-modal-body">

        {{-- Mini stats --}}
        <div class="tm-stats-row">
            <div class="tm-mini-stat">
                <div class="tm-mini-stat-val" id="modal-assigned-count">0</div>
                <div class="tm-mini-stat-label">Assigned</div>
            </div>
            <div class="tm-mini-stat">
                <div class="tm-mini-stat-val" id="modal-collab-count">0</div>
                <div class="tm-mini-stat-label">Collaborating</div>
            </div>
            <div class="tm-mini-stat">
                <div class="tm-mini-stat-val" id="modal-completed-count">0</div>
                <div class="tm-mini-stat-label">Completed</div>
            </div>
        </div>

        {{-- Role Management — admin only --}}
        @if(auth()->user()->role === 'admin')
        <div class="tm-role-section" id="role-section">
            <div class="tm-role-section-title">Change Role</div>
            <div class="tm-role-options">
                <div class="tm-role-opt admin" id="role-opt-admin" onclick="selectRole('admin')">👑 Admin</div>
                <div class="tm-role-opt manager" id="role-opt-manager" onclick="selectRole('manager')">🎯 Manager</div>
                <div class="tm-role-opt member" id="role-opt-member" onclick="selectRole('member')">👤 Member</div>
            </div>
            <button class="tm-save-role" onclick="saveRole()">Save Role</button>
        </div>
        @endif

        {{-- Assigned Tasks --}}
        <div>
            <div class="tm-task-section-title">
                <span>Assigned Tasks</span>
                <span id="assigned-badge" class="tm-section-badge" style="font-size:11px;background:#f0f2f6;padding:2px 8px;border-radius:99px;color:#6b7491;"></span>
            </div>
            <div id="modal-assigned-tasks">
                <div class="tm-empty">Loading…</div>
            </div>
        </div>

        {{-- Collaborating Tasks --}}
        <div>
            <div class="tm-task-section-title">
                <span>Collaborating On</span>
                <span id="collab-badge" class="tm-section-badge" style="font-size:11px;background:#f0f2f6;padding:2px 8px;border-radius:99px;color:#6b7491;"></span>
            </div>
            <div id="modal-collab-tasks">
                <div class="tm-empty">Loading…</div>
            </div>
        </div>

    </div>
</div>
</div>

<script>
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const IS_ADMIN   = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};
let currentMemberId   = null;
let selectedRole      = null;
let avColors = ['#2d52c4','#0e9f8e','#c47c0e','#c0354a','#6d52c4','#1a8a5a','#db2777','#0d9488'];

// ── Search ────────────────────────────────────────────────────
function filterMembers() {
    const q = document.getElementById('memberSearch').value.toLowerCase();
    document.querySelectorAll('.tm-card').forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
}

// ── Open member modal ─────────────────────────────────────────
async function openMember(userId, name, email, role, avIdx, initials) {
    currentMemberId = userId;
    selectedRole    = role;

    // Set header
    const av = document.getElementById('modal-av');
    av.textContent       = initials;
    av.style.background  = avColors[(avIdx - 1) % avColors.length];
    document.getElementById('modal-name').textContent  = name;
    document.getElementById('modal-email').textContent = email;

    // Reset task lists
    document.getElementById('modal-assigned-tasks').innerHTML = '<div class="tm-empty">Loading…</div>';
    document.getElementById('modal-collab-tasks').innerHTML   = '<div class="tm-empty">Loading…</div>';

    // Highlight current role
    if (IS_ADMIN) highlightRole(role);

    // Open modal
    document.getElementById('memberModal').classList.add('open');

    // Fetch tasks
    try {
        const res  = await fetch(`/team/${userId}/tasks`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();

        // Mini stats
        document.getElementById('modal-assigned-count').textContent  = data.assigned.length;
        document.getElementById('modal-collab-count').textContent    = data.collaborating.length;
        document.getElementById('modal-completed-count').textContent = data.completed_count;

        // Badges
        document.getElementById('assigned-badge').textContent = data.assigned.length;
        document.getElementById('collab-badge').textContent   = data.collaborating.length;

        // Render assigned
        document.getElementById('modal-assigned-tasks').innerHTML =
            data.assigned.length > 0
                ? data.assigned.map(t => renderTaskItem(t)).join('')
                : '<div class="tm-empty">No assigned tasks.</div>';

        // Render collaborating
        document.getElementById('modal-collab-tasks').innerHTML =
            data.collaborating.length > 0
                ? data.collaborating.map(t => renderTaskItem(t, true)).join('')
                : '<div class="tm-empty">Not collaborating on any tasks.</div>';

    } catch(err) {
        console.error('Error fetching member tasks:', err);
    }
}

function renderTaskItem(task, isCollab = false) {
    const due = task.due_date ? `<span class="tm-task-due">📅 ${task.due_date}</span>` : '';
    const col = task.column   ? `<span class="tm-task-col">${task.column}</span>` : '';
    const collab = isCollab   ? `<span class="tm-collab-badge">Collab</span>` : '';
    return `
        <div class="tm-task-item">
            <div style="flex:1;min-width:0;">
                <div class="tm-task-title">${task.title}</div>
                <div style="display:flex;gap:.5rem;margin-top:3px;flex-wrap:wrap;">${col}${due}</div>
            </div>
            ${collab}
            <span class="tm-priority ${task.priority}">${task.priority}</span>
        </div>`;
}

function closeModal() {
    document.getElementById('memberModal').classList.remove('open');
    currentMemberId = null;
    selectedRole    = null;
}

// ── Role Management ───────────────────────────────────────────
function highlightRole(role) {
    ['admin', 'manager', 'member'].forEach(r => {
        const el = document.getElementById(`role-opt-${r}`);
        if (!el) return;
        if (r === role) {
            el.classList.add('selected', r);
        } else {
            el.classList.remove('selected', 'admin', 'manager', 'member');
        }
    });
    selectedRole = role;
}

function selectRole(role) {
    highlightRole(role);
}

function saveRole() {
    if (!currentMemberId || !selectedRole) return;

    const btn = document.querySelector('.tm-save-role');
    btn.textContent = 'Saving…'; btn.disabled = true;

    fetch(`/team/${currentMemberId}/role`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ role: selectedRole })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.textContent = '✓ Saved!';
            btn.style.background = '#1a8a5a';
            setTimeout(() => {
                btn.textContent      = 'Save Role';
                btn.style.background = '';
                btn.disabled         = false;
                // Reload to reflect new role on cards
                window.location.reload();
            }, 1000);
        } else {
            alert(data.error || 'Failed to update role.');
            btn.textContent = 'Save Role'; btn.disabled = false;
        }
    })
    .catch(() => { btn.textContent = 'Save Role'; btn.disabled = false; });
}

// ── Close on backdrop / Escape ────────────────────────────────
document.getElementById('memberModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
</x-app-layout>