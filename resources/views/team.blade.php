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
:root{--c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;--c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;--c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;--c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;--c-amber:#c47c0e;--c-amber-lt:#fef5e6;--c-red:#c0354a;--c-red-lt:#fdeef1;--c-green:#1a8a5a;--c-green-lt:#e8f6f0;--c-rule:#e8eaf0;--radius:10px;--shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);--shadow-md:0 4px 16px rgba(27,43,94,0.10);}
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
.tm-tasks-label{font-size:11px;color:var(--c-soft);}
.tm-tasks-label span{font-weight:600;color:var(--c-text);}
.tm-online{width:8px;height:8px;border-radius:50%;background:#ccc;flex-shrink:0;}
.tm-online.active{background:var(--c-green);}

.tm-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:600px){.tm-summary{grid-template-columns:1fr;}}
.tm-sum-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.1rem 1.3rem;box-shadow:var(--shadow-sm);text-align:center;animation:fadeUp .4s .1s ease both;}
.tm-sum-val{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--c-navy);}
.tm-sum-label{font-size:11px;color:var(--c-soft);text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin-top:.2rem;}

/* ── Edit button ── */
.tm-edit-btn{position:absolute;top:0;right:0;background:none;border:none;cursor:pointer;color:var(--c-soft);font-size:15px;padding:2px 4px;border-radius:6px;transition:color .15s,background .15s;}
.tm-edit-btn:hover{color:var(--c-blue);background:var(--c-blue-lt);}

/* ── Modal ── */
.tm-modal-bg{display:none;position:fixed;inset:0;background:rgba(27,43,94,.35);z-index:999;align-items:center;justify-content:center;}
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

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.av-1{background:#2d52c4;}.av-2{background:#0e9f8e;}.av-3{background:#c47c0e;}
.av-4{background:#c0354a;}.av-5{background:#6d52c4;}.av-6{background:#1a8a5a;}
</style>

<div class="tm-page">
<div class="tm-wrap">

    <div class="tm-summary">
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $teamCount ?? 6 }}</div>
            <div class="tm-sum-label">Team Members</div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $activeCount ?? 4 }}</div>
            <div class="tm-sum-label">Active Today</div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-val">{{ $openTasks ?? 18 }}</div>
            <div class="tm-sum-label">Open Tasks</div>
        </div>
    </div>

    <div class="tm-toolbar">
        <div class="tm-search">
            <span class="tm-search-icon">🔍</span>
            <input type="text" placeholder="Search team members..." id="memberSearch" oninput="filterMembers()">
        </div>
    </div>

    <div class="tm-grid" id="memberGrid">
        @forelse($members ?? [] as $member)
            @php
                $roleClass = match(strtolower($member->role ?? 'team_member')) { 'admin'=>'admin','manager'=>'manager',default=>'member' };
                $avIdx = ($loop->index % 6) + 1;
                $initials = strtoupper(substr($member->name,0,1) . (strpos($member->name,' ')!==false ? substr($member->name,strpos($member->name,' ')+1,1) : ''));
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
                    <span class="tm-role {{ $roleClass }}">{{ ucfirst($member->role ?? 'Member') }}</span>
                    <span class="tm-tasks-label"><span>{{ $member->tasks_count ?? 0 }}</span> tasks</span>
                </div>
            </div>
        @empty
            {{-- Placeholder cards (no edit btn since no real IDs) --}}
            @foreach([
                ['Anna Reyes','anna@company.com','Admin','AR','av-1',5,true],
                ['Marco Santos','marco@company.com','Manager','MS','av-2',8,true],
                ['Lena Cruz','lena@company.com','Member','LC','av-3',3,false],
                ['James Uy','james@company.com','Member','JU','av-4',6,true],
                ['Sofia Lim','sofia@company.com','Member','SL','av-5',2,false],
                ['Totok Michael','totok@company.com','Manager','TM','av-6',4,true],
            ] as [$n,$e,$r,$i,$av,$tc,$on])
            <div class="tm-card" data-name="{{ strtolower($n) }}">
                <div class="tm-card-top">
                    <div class="tm-av {{ $av }}">{{ $i }}</div>
                    <div>
                        <div class="tm-name">{{ $n }}</div>
                        <div class="tm-email">{{ $e }}</div>
                    </div>
                    <div class="tm-online {{ $on ? 'active' : '' }}" style="margin-left:auto"></div>
                </div>
                <div class="tm-role-row">
                    <span class="tm-role {{ strtolower($r) }}">{{ $r }}</span>
                    <span class="tm-tasks-label"><span>{{ $tc }}</span> tasks</span>
                </div>
            </div>
            @endforeach
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

<script>
function filterMembers() {
    const q = document.getElementById('memberSearch').value.toLowerCase();
    document.querySelectorAll('.tm-card').forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
}

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
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
    });

    if (res.ok) {
        const card = document.querySelector(`.tm-card[data-id="${id}"]`);
        if (card) {
            card.dataset.name = name.toLowerCase();
            card.querySelector('.tm-name').textContent  = name;
            card.querySelector('.tm-email').textContent = email;
            const roleSpan = card.querySelector('.tm-role');
            const roleLabel = role === 'team_member' ? 'Team Member' : role.charAt(0).toUpperCase() + role.slice(1);
            const roleClass = role === 'team_member' ? 'member' : role;
            roleSpan.textContent = roleLabel;
            roleSpan.className   = `tm-role ${roleClass}`;
            const editBtn = card.querySelector('.tm-edit-btn');
            if (editBtn) {
                editBtn.dataset.name  = name;
                editBtn.dataset.email = email;
                editBtn.dataset.role  = role;
            }
        }
        document.getElementById('saveMsg').style.display = 'inline';
        setTimeout(closeEdit, 1200);
    } else {
        const err = await res.json();
        alert('Failed to save: ' + (err.message ?? JSON.stringify(err)));
    }
}
</script>
</x-app-layout>