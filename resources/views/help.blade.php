<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">❓</div>
            <div>
                <p class="db-greeting">Find answers & get support</p>
                <h2 class="db-title">Help Center</h2>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root{--c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;--c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;--c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;--c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;--c-amber:#c47c0e;--c-amber-lt:#fef5e6;--c-rule:#e8eaf0;--radius:10px;--shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);--shadow-md:0 4px 16px rgba(27,43,94,0.10);}
body{background:var(--c-bg);color:var(--c-text);font-family:'Epilogue',sans-serif;}
.db-header-inner{display:flex;justify-content:space-between;align-items:center;}
.db-header-left{display:flex;align-items:center;gap:.9rem;}
.db-avatar{width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center;}
.db-greeting{font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500;}
.db-title{font-size:17px;font-weight:600;color:var(--c-text);}

.hp-page{padding:2rem 0 3rem;}
.hp-wrap{max-width:860px;margin:0 auto;padding:0 1.5rem;display:flex;flex-direction:column;gap:1.75rem;}

/* Search hero */
.hp-hero{background:var(--c-navy);border-radius:var(--radius);padding:2.5rem 2rem;text-align:center;animation:fadeUp .4s ease both;}
.hp-hero-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:#fff;margin-bottom:.5rem;}
.hp-hero-sub{font-size:13.5px;color:rgba(255,255,255,.55);margin-bottom:1.5rem;}
.hp-search-box{display:flex;max-width:480px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.2);}
.hp-search-box input{flex:1;border:none;padding:.75rem 1rem;font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);outline:none;}
.hp-search-box button{padding:.75rem 1.2rem;background:var(--c-blue);border:none;color:#fff;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:600;cursor:pointer;}
.hp-search-box button:hover{background:#1e3fa8;}

/* Quick links */
.hp-quick{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:600px){.hp-quick{grid-template-columns:1fr;}}
.hp-quick-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.3rem;box-shadow:var(--shadow-sm);display:flex;align-items:flex-start;gap:.85rem;cursor:pointer;transition:box-shadow .2s,transform .2s;animation:fadeUp .4s ease both;}
.hp-quick-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);}
.hp-quick-card:nth-child(2){animation-delay:.06s;}.hp-quick-card:nth-child(3){animation-delay:.12s;}
.hp-quick-icon{font-size:22px;flex-shrink:0;}
.hp-quick-title{font-size:13.5px;font-weight:600;color:var(--c-text);}
.hp-quick-sub{font-size:11.5px;color:var(--c-soft);margin-top:2px;}

/* FAQ */
.hp-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-header{padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule);}
.card-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--c-navy);}
.faq-item{border-bottom:1px solid var(--c-rule);}
.faq-item:last-child{border-bottom:none;}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.4rem;cursor:pointer;font-size:13.5px;font-weight:500;color:var(--c-text);transition:background .15s;}
.faq-q:hover{background:var(--c-surface);}
.faq-chevron{color:var(--c-soft);font-size:12px;transition:transform .25s;}
.faq-item.open .faq-chevron{transform:rotate(180deg);}
.faq-a{max-height:0;overflow:hidden;transition:max-height .3s ease,padding .3s;}
.faq-item.open .faq-a{max-height:200px;padding:0 1.4rem 1rem;}
.faq-a p{font-size:13px;color:var(--c-muted);line-height:1.6;}

/* Contact */
.hp-contact{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
@media(max-width:600px){.hp-contact{grid-template-columns:1fr;}}
.hp-contact-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.4rem;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:1rem;animation:fadeUp .4s .2s ease both;}
.hp-contact-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.hp-contact-title{font-size:14px;font-weight:600;color:var(--c-text);}
.hp-contact-sub{font-size:11.5px;color:var(--c-soft);margin-top:2px;}
.hp-contact-link{display:inline-block;margin-top:.5rem;font-size:12px;font-weight:600;color:var(--c-blue);text-decoration:none;}
.hp-contact-link:hover{text-decoration:underline;}

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
</style>

<div class="hp-page">
<div class="hp-wrap">

    <div class="hp-hero">
        <div class="hp-hero-title">How can we help you?</div>
        <div class="hp-hero-sub">Search our knowledge base or browse topics below</div>
        <div class="hp-search-box">
            <input type="text" placeholder="Search for answers...">
            <button>Search</button>
        </div>
    </div>

    <div class="hp-quick">
        <div class="hp-quick-card">
            <div class="hp-quick-icon">🚀</div>
            <div>
                <div class="hp-quick-title">Getting Started</div>
                <div class="hp-quick-sub">Set up your workspace in minutes</div>
            </div>
        </div>
        <div class="hp-quick-card">
            <div class="hp-quick-icon">📋</div>
            <div>
                <div class="hp-quick-title">Managing Tasks</div>
                <div class="hp-quick-sub">Create, assign, and track tasks</div>
            </div>
        </div>
        <div class="hp-quick-card">
            <div class="hp-quick-icon">👥</div>
            <div>
                <div class="hp-quick-title">Team Collaboration</div>
                <div class="hp-quick-sub">Invite members and set roles</div>
            </div>
        </div>
    </div>

    <div class="hp-card">
        <div class="card-header"><div class="card-title">Frequently Asked Questions</div></div>
        @foreach([
            ['How do I create a new task?', 'Navigate to the Tasks page and click the "+ New Task" button. Fill in the title, assign a member, set a priority and due date, then click Create.'],
            ['How do I invite a team member?', 'Go to the Team page and click "Invite Member". Enter their email address and select a role. They will receive an email invitation to join your workspace.'],
            ['Can I set due dates on tasks?', 'Yes! When creating or editing a task, you will find a Due Date field. Tasks with approaching deadlines will be highlighted in the dashboard and calendar.'],
            ['How are task priorities determined?', 'You can manually set each task to Low, Medium, or High priority. High priority tasks appear in your dashboard stats and are flagged in red.'],
            ['How do I change my password?', 'Go to Settings → Account and use the Change Password form. Enter your current password and your new password twice to confirm.'],
            ['Where can I see all overdue tasks?', 'The Dashboard shows tasks with overdue indicators. The Calendar view also highlights overdue items in red under Upcoming Deadlines.'],
        ] as [$q, $a])
        <div class="faq-item">
            <div class="faq-q" onclick="faqToggle(this)">
                {{ $q }}
                <span class="faq-chevron">▼</span>
            </div>
            <div class="faq-a"><p>{{ $a }}</p></div>
        </div>
        @endforeach
    </div>

    <div class="hp-contact">
        <div class="hp-contact-card">
            <div class="hp-contact-icon" style="background:var(--c-blue-lt);">📧</div>
            <div>
                <div class="hp-contact-title">Email Support</div>
                <div class="hp-contact-sub">We reply within 24 hours</div>
                <a href="mailto:support@taskflow.app" class="hp-contact-link">support@taskflow.app</a>
            </div>
        </div>
        <div class="hp-contact-card">
            <div class="hp-contact-icon" style="background:var(--c-teal-lt);">💬</div>
            <div>
                <div class="hp-contact-title">Live Chat</div>
                <div class="hp-contact-sub">Available Mon–Fri, 9am–5pm</div>
                <a href="#" class="hp-contact-link">Start a conversation →</a>
            </div>
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

// Make search filter FAQ items
const searchInput = document.querySelector('.hp-search-box input');
const searchBtn   = document.querySelector('.hp-search-box button');

function searchFAQ() {
    const q = searchInput.value.toLowerCase().trim();
    document.querySelectorAll('.faq-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
    // Also close all open items when searching
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
}

searchBtn.addEventListener('click', searchFAQ);
searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') searchFAQ(); });

// Also filter quick cards
searchInput.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase().trim();
    if (!q) {
        document.querySelectorAll('.faq-item').forEach(i => i.style.display = '');
    }
});
</script>
</x-app-layout>