<x-app-layout>
    @section('title', 'Help')
<x-slot name="header">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:66px;">

        {{-- Left: page identity --}}
        <div class="db-header-left">
     
        </div>

        {{-- Right: notifications + profile --}}
        <div style="display:flex;align-items:center;gap:.65rem;">

            {{-- Notifications --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-nav-icon-btn" id="notif-btn" title="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <div class="tk-dropdown" id="notif-dropdown" style="width:340px;">
                    <div class="tk-dropdown-header">
                        <span class="tk-dropdown-title">Notifications</span>
                        <span class="tk-badge-pill" id="notif-count"></span>
                    </div>
                    <div class="tk-dropdown-body" id="notif-list">
                        <div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--c-soft);">Loading…</div>
                    </div>
                </div>
            </div>

            <div style="width:1px;height:28px;background:var(--c-border);flex-shrink:0;margin:0 .15rem;"></div>

            {{-- Profile --}}
            <div class="tk-dropdown-wrap">
                <div class="tk-nav-profile" id="profile-btn">
                    <div class="tk-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="tk-nav-userinfo">
                        <span class="tk-nav-name">{{ Auth::user()->name }}</span>
                        <span class="tk-nav-email">{{ Auth::user()->email }}</span>
                    </div>
                    <svg id="profile-chevron" class="tk-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="tk-dropdown tk-profile-dropdown" id="profile-dropdown">
                    <div class="tk-dropdown-header">
                        <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:14px;font-weight:700;color:var(--c-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                            <div style="font-size:12px;color:var(--c-soft);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div style="padding:.3rem 0;">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('settings.index') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            My Profile & Settings
                        </a>
                        @endif
                        <div style="height:1px;background:var(--c-border);margin:.3rem 0;"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tk-profile-item tk-profile-item--danger">
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

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root{
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;
    --c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;
    --c-rule:#e8eaf0;--radius:12px;
    --shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:0 4px 16px rgba(27,43,94,0.10);
}
body{background:var(--c-bg);color:var(--c-text);font-family:'Epilogue',sans-serif;}
.db-header-left{display:flex;align-items:center;gap:.9rem;}
.db-avatar{width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center;}
.db-greeting{font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500;}
.db-title{font-size:17px;font-weight:600;color:var(--c-text);}

/* ── Dropdown styles ── */
.tk-dropdown-wrap{position:relative;}
.tk-dropdown{position:absolute;right:0;top:calc(100% + 10px);background:var(--c-white);border:1px solid var(--c-border);border-radius:14px;box-shadow:0 12px 32px rgba(27,43,94,0.14);z-index:999;overflow:hidden;opacity:0;transform:translateY(-10px) scale(.96);pointer-events:none;transition:opacity .22s cubic-bezier(.16,1,.3,1),transform .22s cubic-bezier(.16,1,.3,1);transform-origin:top right;}
.tk-dropdown.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto;}
.tk-profile-dropdown{width:268px;}
.tk-dropdown-header{padding:1rem 1.25rem;border-bottom:1px solid var(--c-rule);display:flex;align-items:center;gap:.75rem;}
.tk-dropdown-title{font-size:15px;font-weight:700;color:var(--c-text);}
.tk-badge-pill{font-size:11.5px;font-weight:700;background:var(--c-blue-lt);color:var(--c-blue);padding:2px 10px;border-radius:99px;}
.tk-dropdown-body{max-height:360px;overflow-y:auto;}
.tk-notif-item{display:flex;gap:.85rem;align-items:flex-start;padding:.95rem 1.25rem;border-bottom:1px solid var(--c-border);transition:background .15s;}
.tk-notif-item:last-child{border-bottom:none;}
.tk-notif-item:hover{background:var(--c-surface);}
.tk-notif-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--c-blue-lt);color:var(--c-blue);}
.tk-notif-content{flex:1;min-width:0;}
.tk-notif-text{font-size:13.5px;color:var(--c-muted);line-height:1.5;font-weight:500;}
.tk-notif-text strong{color:var(--c-text);font-weight:700;}
.tk-notif-time{font-size:12px;color:var(--c-soft);font-weight:600;margin-top:3px;}
.tk-profile-avatar-lg{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#3b63d8,#1b2b5e);color:#fff;font-size:15px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.tk-nav-icon-btn{width:40px;height:40px;border-radius:10px;border:1.5px solid var(--c-border);background:var(--c-white);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--c-muted);transition:background .15s,transform .15s;}
.tk-nav-icon-btn:hover{background:var(--c-bg);transform:translateY(-1px);}
.tk-nav-profile{display:flex;align-items:center;gap:.65rem;cursor:pointer;padding:.4rem .75rem;border-radius:10px;border:1.5px solid transparent;transition:background .18s,border-color .18s;}
.tk-nav-profile:hover{background:var(--c-bg);border-color:var(--c-border);}
.tk-nav-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#3b63d8,#1b2b5e);color:#fff;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.tk-nav-userinfo{display:flex;flex-direction:column;}
.tk-nav-name{font-size:14px;font-weight:700;color:var(--c-text);line-height:1.25;}
.tk-nav-email{font-size:12px;color:var(--c-soft);font-weight:500;}
.tk-nav-chevron{color:var(--c-soft);flex-shrink:0;transition:transform .22s;}
.tk-profile-item{display:flex;align-items:center;gap:.7rem;padding:.75rem 1.25rem;font-size:14px;font-weight:600;color:var(--c-muted);text-decoration:none;cursor:pointer;border:none;background:none;width:100%;font-family:'Epilogue',sans-serif;transition:background .14s,color .14s;text-align:left;}
.tk-profile-item:hover{background:var(--c-surface);color:var(--c-text);}
.tk-profile-item--danger{color:var(--c-red);}
.tk-profile-item--danger:hover{background:var(--c-red-lt);color:var(--c-red);}

/* ── Layout ── */
.hp-page{padding:2rem 0 3.5rem;}
.hp-wrap{max-width:900px;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:1.75rem;}
.hp-hero{background:var(--c-navy);border-radius:var(--radius);padding:2.8rem 2rem 2.5rem;text-align:center;animation:fadeUp .4s ease both;position:relative;overflow:hidden;}
.hp-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 70% 0%,rgba(45,82,196,.55) 0%,transparent 65%),radial-gradient(ellipse at 20% 100%,rgba(14,159,142,.3) 0%,transparent 55%);pointer-events:none;}
.hp-hero-eyebrow{font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:.6rem;}
.hp-hero-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:#fff;margin-bottom:.5rem;position:relative;}
.hp-hero-sub{font-size:13.5px;color:rgba(255,255,255,.5);margin-bottom:1.8rem;position:relative;}
.hp-search-wrap{max-width:500px;margin:0 auto;position:relative;}
.hp-search-wrap input{width:100%;padding:.8rem 1rem .8rem 3rem;border:none;border-radius:9px;font-family:'Epilogue',sans-serif;font-size:13.5px;color:var(--c-text);outline:none;box-shadow:0 4px 24px rgba(0,0,0,.22);background:#fff;box-sizing:border-box;}
.hp-search-wrap input::placeholder{color:var(--c-soft);}
.hp-search-ico{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:15px;pointer-events:none;}
.hp-search-btn{position:absolute;right:5px;top:50%;transform:translateY(-50%);background:var(--c-blue);color:#fff;border:none;border-radius:7px;padding:.5rem 1rem;font-family:'Epilogue',sans-serif;font-size:12.5px;font-weight:700;cursor:pointer;transition:background .15s;}
.hp-search-btn:hover{background:#1e3fa8;}
.hp-quick{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:620px){.hp-quick{grid-template-columns:1fr;}}
.hp-quick-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.3rem 1.25rem;box-shadow:var(--shadow-sm);display:flex;align-items:flex-start;gap:.9rem;cursor:pointer;transition:box-shadow .2s,transform .2s,border-color .2s;animation:fadeUp .4s ease both;}
.hp-quick-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);border-color:var(--c-border-2);}
.hp-quick-card:nth-child(2){animation-delay:.07s;}.hp-quick-card:nth-child(3){animation-delay:.14s;}
.hp-quick-icon{width:42px;height:42px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:19px;}
.hp-quick-icon.blue{background:var(--c-blue-lt);}.hp-quick-icon.teal{background:var(--c-teal-lt);}.hp-quick-icon.amber{background:var(--c-amber-lt);}
.hp-quick-title{font-size:13.5px;font-weight:700;color:var(--c-text);}
.hp-quick-sub{font-size:11.5px;color:var(--c-soft);margin-top:3px;line-height:1.4;}
.hp-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.hp-card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--c-rule);display:flex;align-items:center;justify-content:space-between;}
.hp-card-title{font-family:'Playfair Display',serif;font-size:15.5px;font-weight:700;color:var(--c-navy);}
.hp-faq-count{font-size:11.5px;color:var(--c-soft);font-weight:500;}
.faq-item{border-bottom:1px solid var(--c-rule);}
.faq-item:last-child{border-bottom:none;}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:1.05rem 1.5rem;cursor:pointer;font-size:13.5px;font-weight:600;color:var(--c-text);transition:background .15s;gap:1rem;}
.faq-q:hover{background:var(--c-surface);}
.faq-item.open .faq-q{color:var(--c-blue);background:var(--c-blue-lt);}
.faq-chevron{width:22px;height:22px;border-radius:50%;background:var(--c-rule);display:flex;align-items:center;justify-content:center;color:var(--c-soft);font-size:10px;flex-shrink:0;transition:transform .25s,background .2s,color .2s;}
.faq-item.open .faq-chevron{transform:rotate(180deg);background:var(--c-blue);color:#fff;}
.faq-a{max-height:0;overflow:hidden;transition:max-height .3s ease;}
.faq-item.open .faq-a{max-height:200px;}
.faq-a-inner{padding:.7rem 1rem;font-size:13px;color:var(--c-muted);line-height:1.65;border-left:3px solid var(--c-blue);margin:0 1.5rem .9rem;border-radius:0 6px 6px 0;background:var(--c-blue-lt);}
.hp-contact-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.4rem 1.5rem;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:1.1rem;animation:fadeUp .4s .2s ease both;}
.hp-contact-icon{width:48px;height:48px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:22px;background:var(--c-blue-lt);}
.hp-contact-title{font-size:14.5px;font-weight:700;color:var(--c-text);}
.hp-contact-sub{font-size:12px;color:var(--c-soft);margin-top:2px;}
.hp-contact-link{display:inline-block;margin-top:.5rem;font-size:12.5px;font-weight:700;color:var(--c-blue);text-decoration:none;}
.hp-contact-link:hover{text-decoration:underline;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
</style>

<div class="hp-page">
<div class="hp-wrap">

    <div class="hp-hero">
        <div class="hp-hero-eyebrow">Support</div>
        <div class="hp-hero-title">How can we help you?</div>
        <div class="hp-hero-sub">Search our knowledge base or browse topics below</div>
        <div class="hp-search-wrap">
            <span class="hp-search-ico">🔍</span>
            <input type="text" id="helpSearch" placeholder="Search for answers…">
            <button class="hp-search-btn" onclick="searchFAQ()">Search</button>
        </div>
    </div>

    <div class="hp-quick">
        <div class="hp-quick-card">
            <div class="hp-quick-icon blue">🚀</div>
            <div><div class="hp-quick-title">Getting Started</div><div class="hp-quick-sub">Set up your workspace in minutes</div></div>
        </div>
        <div class="hp-quick-card">
            <div class="hp-quick-icon teal">📋</div>
            <div><div class="hp-quick-title">Managing Tasks</div><div class="hp-quick-sub">Create, assign, and track tasks</div></div>
        </div>
        <div class="hp-quick-card">
            <div class="hp-quick-icon amber">👥</div>
            <div><div class="hp-quick-title">Team Collaboration</div><div class="hp-quick-sub">Invite members and set roles</div></div>
        </div>
    </div>

    <div class="hp-card">
        <div class="hp-card-header">
            <div class="hp-card-title">Frequently Asked Questions</div>
            <span class="hp-faq-count" id="faqCount">6 articles</span>
        </div>
        @foreach([
            ['How do I create a new task?', 'Navigate to the Tasks page and click the "+ New Task" button. Fill in the title, assign a member, set a priority and due date, then click Create.'],
            ['How do I invite a team member?', 'Go to the Team page and click "Invite Member". Enter their email address and select a role. They will receive an email invitation to join your workspace.'],
            ['Can I set due dates on tasks?', 'Yes! When creating or editing a task, you will find a Due Date field. Tasks with approaching deadlines will be highlighted in the dashboard and calendar.'],
            ['How are task priorities determined?', 'You can manually set each task to Low, Medium, or High priority. High priority tasks appear in your dashboard stats and are flagged in red.'],
            ['How do I change my password?', 'Go to Settings → Account and use the Change Password form. Enter your current password and your new password twice to confirm.'],
            ['Where can I see all overdue tasks?', 'The Analytics page shows tasks with overdue indicators. The Calendar view also highlights overdue items in red under Upcoming Deadlines.'],
        ] as [$q, $a])
        <div class="faq-item">
            <div class="faq-q" onclick="faqToggle(this)">
                <span>{{ $q }}</span>
                <span class="faq-chevron">▼</span>
            </div>
            <div class="faq-a">
                <div class="faq-a-inner">{{ $a }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="hp-contact-card">
        <div class="hp-contact-icon">📧</div>
        <div>
            <div class="hp-contact-title">Email Support</div>
            <div class="hp-contact-sub">We reply within 24 hours on business days</div>
            <a href="mailto:support@taskflow.app" class="hp-contact-link">support@taskflow.app</a>
        </div>
    </div>

</div>
</div>

<script>
function faqToggle(el) {
    const item = el.closest('.faq-item');
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
}

function searchFAQ() {
    const q = document.getElementById('helpSearch').value.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('.faq-item').forEach(item => {
        const show = !q || item.textContent.toLowerCase().includes(q);
        item.style.display = show ? '' : 'none';
        item.classList.remove('open');
        if (show) visible++;
    });
    const countEl = document.getElementById('faqCount');
    if (countEl) countEl.textContent = visible + ' article' + (visible !== 1 ? 's' : '');
}

const searchInput = document.getElementById('helpSearch');
searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') searchFAQ(); });
searchInput.addEventListener('input', () => {
    if (!searchInput.value.trim()) {
        document.querySelectorAll('.faq-item').forEach(i => { i.style.display = ''; });
        const countEl = document.getElementById('faqCount');
        if (countEl) countEl.textContent = '6 articles';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function bindDropdown(btnId, dropId) {
        const btn  = document.getElementById(btnId);
        const drop = document.getElementById(dropId);
        if (!btn || !drop) return;
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = drop.classList.contains('open');
            closeAll();
            if (!isOpen) drop.classList.add('open');
        });
        drop.addEventListener('click', e => e.stopPropagation());
    }

    function closeAll() {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
    }

    document.addEventListener('click', closeAll);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });

    bindDropdown('notif-btn',   'notif-dropdown');
    bindDropdown('profile-btn', 'profile-dropdown');

    const notifBtn = document.getElementById('notif-btn');
    if (notifBtn) {
        notifBtn.addEventListener('click', async () => {
            const list = document.querySelector('#notif-dropdown .tk-dropdown-body');
            if (!list) return; 
            list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--c-soft);">Loading…</div>';
            try {
                const res  = await fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
                const data = await res.json();
                const count = document.getElementById('notif-count');
                if (count) count.textContent = data.length ? data.length + ' new' : '';
                if (!data.length) { list.innerHTML = '<div style="padding:1.5rem;text-align:center;font-size:13.5px;color:var(--c-soft);">You\'re all caught up! 🎉</div>'; return; }
                const iconMap = { comment:'💬', created:'✅', priority_change:'🔥', lead_change:'👤', column_change:'📋', completed:'🎉', checklist_added:'☑️' };
                list.innerHTML = data.map(n => `
                    <div class="tk-notif-item">
                        <div class="tk-notif-icon">${iconMap[n.action] || '🔔'}</div>
                        <div class="tk-notif-content">
                            <div class="tk-notif-text"><strong>${n.user || 'Someone'}</strong> ${n.description}${n.task ? ` on <em>${n.task}</em>` : ''}</div>
                            <div class="tk-notif-time">${n.time}</div>
                        </div>
                    </div>`).join('');
            } catch {
                list.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--c-red);">Failed to load.</div>';
            }
        });
    }
});
</script>
</x-app-layout>