<x-app-layout>
    @section('title', 'Team')
<x-slot name="header">

<div class="tk-topnav">


    {{-- 2. Right Side Icons & Profile --}}
    <div class="tk-topnav-right">
    

            {{-- Notifications --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-topnav-icon" id="notif-btn" title="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <div class="tk-dropdown" id="notif-dropdown">
                    <div class="tk-dropdown-header">
                        <span class="tk-dropdown-title">Notifications</span>
                    </div>
                    <div class="tk-dropdown-body">
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-blue">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Task completed</strong> — "Design review" was marked done</div>
                                <div class="tk-notif-time">2 minutes ago</div>
                            </div>
                        </div>
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-amber">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Assigned to you</strong> — "API integration sprint"</div>
                                <div class="tk-notif-time">1 hour ago</div>
                            </div>
                        </div>
                        <div class="tk-notif-item">
                            <div class="tk-notif-icon ni-red">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div class="tk-notif-content">
                                <div class="tk-notif-text"><strong>Overdue</strong> — "Client presentation" is past due</div>
                                <div class="tk-notif-time">Yesterday</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="tk-topnav-divider"></div>

            {{-- Profile Dropdown --}}
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
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('settings.index') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            My Profile & Settings
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
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');

/* Topnav sits outside .db-page so scope it explicitly */
.tk-topnav, .tk-topnav *, .tk-topnav *::before, .tk-topnav *::after {
    box-sizing: border-box;
    font-family: var(--font);
}
/* ================================================================
   TOPNAV
================================================================ */
.tk-topnav {
    display: flex; align-items: center; gap: 1rem;
    padding: 0 2rem; height: 66px;
    background: var(--c-white); border-bottom: 1px solid var(--c-rule);
}

.tk-topnav-search {
    display: flex; align-items: center; gap: .6rem;
    background: var(--c-bg); border: 1.5px solid var(--c-border);
    border-radius: 10px; padding: .6rem 1rem;
    width: 320px; flex-shrink: 0;
    transition: border-color .18s, box-shadow .18s, background .18s;
}
.tk-topnav-search:focus-within {
    border-color: var(--c-blue);
    box-shadow: 0 0 0 3px rgba(45,82,196,.1);
    background: var(--c-white);
}
.tk-topnav-search svg               { color: var(--c-soft); flex-shrink: 0; }
.tk-topnav-search input             { border: none; background: transparent; outline: none; font-family: var(--font); font-size: 14px; font-weight: 500; color: var(--c-text); flex: 1; min-width: 0; }
.tk-topnav-search input::placeholder{ color: var(--c-soft); font-weight: 400; }
.tk-topnav-kbd   { font-size: 11px; font-weight: 600; color: var(--c-soft); background: var(--c-white); border: 1px solid var(--c-border); border-radius: 5px; padding: 2px 7px; flex-shrink: 0; }
.tk-topnav-right { display: flex; align-items: center; gap: .65rem; margin-left: auto; }

.tk-topnav-icon {
    width: 40px; height: 40px; border-radius: 10px;
    border: 1.5px solid var(--c-border); background: var(--c-white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--c-muted); position: relative;
    transition: background .15s, border-color .15s, color .15s, transform .15s;
}
.tk-topnav-icon:hover { background: var(--c-bg); border-color: var(--c-border-2); color: var(--c-navy); transform: translateY(-1px); }

.tk-notif-badge {
    position: absolute; top: 4px; right: 4px;
    width: 17px; height: 17px; border-radius: 50%;
    background: var(--c-red); border: 2px solid var(--c-white);
    color: #fff; font-size: 9px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
}

.tk-topnav-divider { width: 1px; height: 28px; background: var(--c-rule); flex-shrink: 0; margin: 0 .25rem; }

.tk-topnav-user {
    display: flex; align-items: center; gap: .65rem;
    cursor: pointer; padding: .45rem .75rem;
    border-radius: 10px; border: 1.5px solid transparent;
    background: transparent; font-family: var(--font);
    transition: background .18s, border-color .18s, transform .15s;
}
.tk-topnav-user:hover,
.tk-topnav-user.active { background: var(--c-bg); border-color: var(--c-border); transform: translateY(-1px); }

.tk-topnav-avatar   { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--c-blue-md), var(--c-navy)); color: #fff; font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(45,82,196,.32); }
.tk-topnav-userinfo { text-align: left; }
.tk-topnav-username { display: block; font-size: 14px; font-weight: 700; color: var(--c-text); line-height: 1.25; }
.tk-topnav-email    { display: block; font-size: 11.5px; color: var(--c-soft); font-weight: 500; }
.tk-chevron         { color: var(--c-soft); flex-shrink: 0; transition: transform .25s cubic-bezier(.16,1,.3,1); }
.tk-chevron.open    { transform: rotate(180deg); }

/* ================================================================
   DROPDOWNS (notifications + profile)
================================================================ */
.tk-dropdown-wrap { position: relative; }

.tk-dropdown {
    position: absolute; right: 0; top: calc(100% + 10px);
    background: var(--c-white); border: 1px solid var(--c-border);
    border-radius: 14px; box-shadow: var(--shadow-lg);
    z-index: 999; overflow: hidden;
    opacity: 0; transform: translateY(-10px) scale(.96);
    pointer-events: none;
    transition: opacity .22s cubic-bezier(.16,1,.3,1), transform .22s cubic-bezier(.16,1,.3,1);
    transform-origin: top right;
}
.tk-dropdown.open     { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
#notif-dropdown       { width: 340px; }
.tk-profile-dropdown  { width: 268px; }

.tk-dropdown-header   { padding: 1rem 1.25rem; border-bottom: 1px solid var(--c-rule); display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
.tk-dropdown-title    { font-size: 15px; font-weight: 800; color: var(--c-text); }
.tk-badge-pill        { font-size: 11.5px; font-weight: 700; background: var(--c-blue-lt); color: var(--c-blue); padding: 2px 10px; border-radius: 99px; }
.tk-dropdown-body     { max-height: 360px; overflow-y: auto; }

.tk-notif-item            { display: flex; gap: .85rem; align-items: flex-start; padding: .95rem 1.25rem; border-bottom: 1px solid var(--c-rule); transition: background .15s; }
.tk-notif-item:last-child { border-bottom: none; }
.tk-notif-item:hover      { background: var(--c-surface); }
.tk-notif-icon            { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ni-blue                  { background: var(--c-blue-lt);  color: var(--c-blue); }
.ni-amber                 { background: var(--c-amber-lt); color: var(--c-amber); }
.ni-red                   { background: var(--c-red-lt);   color: var(--c-red); }
.tk-notif-content         { flex: 1; min-width: 0; }
.tk-notif-text            { font-size: 13.5px; color: var(--c-muted); line-height: 1.5; font-weight: 500; }
.tk-notif-text strong     { color: var(--c-text); font-weight: 700; }
.tk-notif-time            { font-size: 12px; color: var(--c-soft); font-weight: 600; margin-top: 3px; }

.tk-profile-header    { gap: .9rem; align-items: center; }
.tk-profile-avatar-lg { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, var(--c-blue-md), var(--c-navy)); color: #fff; font-size: 15px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(45,82,196,.3); }
.tk-profile-meta      { font-size: 12.5px; color: var(--c-soft); font-weight: 500; margin-top: 2px; }

.tk-profile-item {
    display: flex; align-items: center; gap: .7rem;
    padding: .75rem 1.25rem; font-size: 14px; font-weight: 600;
    color: var(--c-muted); text-decoration: none;
    cursor: pointer; border: none; background: none;
    width: 100%; font-family: var(--font);
    transition: background .14s, color .14s; text-align: left;
}
.tk-profile-item:hover         { background: var(--c-surface); color: var(--c-text); }
.tk-profile-item--danger       { color: var(--c-red); }
.tk-profile-item--danger:hover { background: var(--c-red-lt); color: var(--c-red); }
.tk-profile-divider            { height: 1px; background: var(--c-rule); margin: .3rem 0; }

.st-topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 66px; gap: 1rem;
    background: var(--white); border-bottom: 1px solid var(--border);
}
.st-topnav-left { display: flex; flex-direction: column; justify-content: center; }
.st-topnav-eyebrow { font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--blue); line-height: 1; margin-bottom: 2px; }
.st-topnav-heading { font-size: 16px; font-weight: 800; color: var(--navy); letter-spacing: -.02em; line-height: 1; }
.st-topnav-right { display: flex; align-items: center; gap: .5rem; }
.tk-dropdown-wrap { position: relative; }
.tk-dropdown {
    position: absolute; right: 0; top: calc(100% + 10px);
    background: var(--c-white); border: 1px solid var(--c-border);
    border-radius: 14px; box-shadow: var(--shadow-lg);
    z-index: 999; overflow: hidden;
    opacity: 0; transform: translateY(-10px) scale(.96);
    pointer-events: none;
    transition: opacity .22s cubic-bezier(.16,1,.3,1), transform .22s cubic-bezier(.16,1,.3,1);
    transform-origin: top right;
}
.tk-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
.tk-profile-dropdown { width: 268px; }
.tk-dropdown-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--c-rule); display: flex; align-items: center; gap: .75rem; }
.tk-dropdown-title  { font-size: 15px; font-weight: 700; color: var(--c-text); }
.tk-badge-pill      { font-size: 11.5px; font-weight: 700; background: var(--c-blue-lt); color: var(--c-blue); padding: 2px 10px; border-radius: 99px; }
.tk-dropdown-body   { max-height: 360px; overflow-y: auto; }
.tk-notif-item      { display: flex; gap: .85rem; align-items: flex-start; padding: .95rem 1.25rem; border-bottom: 1px solid var(--c-border); transition: background .15s; }
.tk-notif-item:last-child { border-bottom: none; }
.tk-notif-item:hover { background: var(--c-surface); }
.tk-notif-icon      { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: var(--c-blue-lt); color: var(--c-blue); }
.tk-notif-content   { flex: 1; min-width: 0; }
.tk-notif-text      { font-size: 13.5px; color: var(--c-muted); line-height: 1.5; font-weight: 500; }
.tk-notif-text strong { color: var(--c-text); font-weight: 700; }
.tk-notif-time      { font-size: 12px; color: var(--c-soft); font-weight: 600; margin-top: 3px; }
.tk-profile-avatar-lg { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #3b63d8, #1b2b5e); color: #fff; font-size: 15px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tk-nav-icon-btn { width: 40px; height: 40px; border-radius: 10px; border: 1.5px solid var(--c-border); background: var(--c-white); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--c-muted); transition: background .15s, transform .15s; }
.tk-nav-icon-btn:hover { background: var(--c-bg); transform: translateY(-1px); }
.tk-nav-profile { display: flex; align-items: center; gap: .65rem; cursor: pointer; padding: .4rem .75rem; border-radius: 10px; border: 1.5px solid transparent; transition: background .18s, border-color .18s; }
.tk-nav-profile:hover { background: var(--c-bg); border-color: var(--c-border); }
.tk-nav-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #3b63d8, #1b2b5e); color: #fff; font-size: 14px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tk-nav-userinfo { display: flex; flex-direction: column; }
.tk-nav-name  { font-size: 14px; font-weight: 700; color: var(--c-text); line-height: 1.25; }
.tk-nav-email { font-size: 12px; color: var(--c-soft); font-weight: 500; }
.tk-nav-chevron { color: var(--c-soft); flex-shrink: 0; transition: transform .22s; }
.tk-profile-item { display: flex; align-items: center; gap: .7rem; padding: .75rem 1.25rem; font-size: 14px; font-weight: 600; color: var(--c-muted); text-decoration: none; cursor: pointer; border: none; background: none; width: 100%; font-family: 'Epilogue', sans-serif; transition: background .14s, color .14s; text-align: left; }
.tk-profile-item:hover { background: var(--c-surface); color: var(--c-text); }
.tk-profile-item--danger { color: var(--c-red); }
.tk-profile-item--danger:hover { background: var(--c-red-lt); color: var(--c-red); }

:root{
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-teal:#0e9f8e;--c-teal-lt:#e6f7f5;
    --c-amber:#c47c0e;--c-amber-lt:#fef5e6;
    --c-red:#c0354a;--c-red-lt:#fdeef1;
    --c-green:#1a8a5a;--c-green-lt:#e8f6f0;
    --c-purple:#7c3aed;--c-purple-lt:#f5f3ff;
    --c-rule:#e8eaf0;--radius:12px;
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

/* ── Page Layout ── */
.tm-page{padding:2rem 0 3.5rem;}
.tm-wrap{max-width:1160px;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:1.75rem;}

/* ── Summary Strip ── */
.tm-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
@media(max-width:600px){.tm-summary{grid-template-columns:1fr;}}
.tm-sum-card{
    background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);
    padding:1.4rem 1.6rem;box-shadow:var(--shadow-sm);
    display:flex;align-items:center;gap:1.1rem;
    animation:fadeUp .4s ease both;
}
.tm-sum-card:nth-child(2){animation-delay:.07s;}
.tm-sum-card:nth-child(3){animation-delay:.14s;}
.tm-sum-icon{
    width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;
    font-size:20px;flex-shrink:0;
}
.tm-sum-icon.blue{background:var(--c-blue-lt);}
.tm-sum-icon.teal{background:var(--c-teal-lt);}
.tm-sum-icon.amber{background:var(--c-amber-lt);}
.tm-sum-val{font-family:'Playfair Display',serif;font-size:2.1rem;font-weight:700;color:var(--c-navy);line-height:1;}
.tm-sum-label{font-size:12px;color:var(--c-soft);text-transform:uppercase;letter-spacing:.07em;font-weight:600;margin-top:.3rem;}

/* ── Toolbar ── */
.tm-toolbar{
    display:flex;align-items:center;gap:1rem;
    background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);
    padding:.85rem 1.2rem;box-shadow:var(--shadow-sm);
}
.tm-search{flex:1;position:relative;max-width:380px;}
.tm-search input{
    width:100%;padding:.6rem .9rem .6rem 2.5rem;
    border:1.5px solid var(--c-border);border-radius:8px;
    background:var(--c-bg);font-family:'Epilogue',sans-serif;
    font-size:13.5px;color:var(--c-text);outline:none;transition:border-color .15s,background .15s;
}
.tm-search input:focus{border-color:var(--c-blue);background:var(--c-white);}
.tm-search input::placeholder{color:var(--c-soft);}
.tm-search-icon{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:var(--c-soft);font-size:14px;pointer-events:none;}
.tm-toolbar-meta{margin-left:auto;font-size:12.5px;color:var(--c-soft);font-weight:500;}
.tm-toolbar-meta strong{color:var(--c-text);}

/* ── Member Grid ── */
.tm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:1.1rem;}

.tm-card{
    background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);
    padding:1.5rem 1.4rem 1.2rem;box-shadow:var(--shadow-sm);
    display:flex;flex-direction:column;gap:.9rem;
    animation:fadeUp .4s ease both;transition:box-shadow .2s,transform .2s,border-color .2s;
}
.tm-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);border-color:var(--c-border-2);}
.tm-card:nth-child(2){animation-delay:.06s;}.tm-card:nth-child(3){animation-delay:.12s;}
.tm-card:nth-child(4){animation-delay:.18s;}.tm-card:nth-child(5){animation-delay:.24s;}
.tm-card:nth-child(6){animation-delay:.30s;}

/* Card top row */
.tm-card-top{position:relative;display:flex;align-items:center;gap:1rem;}
.tm-av{
    width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    font-size:16px;font-weight:700;color:#fff;flex-shrink:0;letter-spacing:.03em;
    box-shadow:0 2px 8px rgba(27,43,94,.18);
}
.tm-name{font-size:14.5px;font-weight:700;color:var(--c-text);line-height:1.3;}
.tm-email{font-size:12px;color:var(--c-soft);margin-top:2px;}
.tm-online-wrap{margin-left:auto;display:flex;align-items:center;gap:.35rem;flex-shrink:0;}
.tm-online{width:8px;height:8px;border-radius:50%;background:#d1d5db;flex-shrink:0;}
.tm-online.active{background:var(--c-green);box-shadow:0 0 0 3px rgba(26,138,90,.15);}
.tm-online-text{font-size:10.5px;color:var(--c-soft);font-weight:500;}
.tm-online.active ~ .tm-online-text{color:var(--c-green);}

/* Divider */
.tm-card-divider{height:1px;background:var(--c-rule);margin:0 -.1rem;}

/* Role + tasks row */
.tm-card-meta{display:flex;align-items:center;justify-content:space-between;}
.tm-role{display:inline-flex;align-items:center;gap:.35rem;font-size:11px;font-weight:700;padding:4px 10px;border-radius:5px;letter-spacing:.04em;text-transform:uppercase;}
.tm-role.admin{background:var(--c-blue-lt);color:var(--c-blue);}
.tm-role.member{background:var(--c-teal-lt);color:var(--c-teal);}
.tm-role.manager{background:var(--c-amber-lt);color:var(--c-amber);}
.tm-tasks-badge{
    display:flex;align-items:center;gap:.35rem;
    font-size:12px;color:var(--c-muted);font-weight:500;
}
.tm-tasks-badge strong{color:var(--c-text);font-weight:700;}

/* Edit button */
.tm-edit-btn{
    position:absolute;top:-2px;right:0;background:none;border:none;cursor:pointer;
    color:var(--c-soft);font-size:14px;padding:4px 6px;border-radius:6px;
    transition:color .15s,background .15s;
}
.tm-edit-btn:hover{color:var(--c-blue);background:var(--c-blue-lt);}

/* Perms button */
.tm-card-actions{margin-top:auto;}
.tm-card-action-btn{
    width:100%;padding:.55rem .8rem;border-radius:8px;
    border:1.5px solid #c4b5fd;background:var(--c-purple-lt);
    font-family:'Epilogue',sans-serif;font-size:12px;font-weight:700;
    color:var(--c-purple);cursor:pointer;transition:all .15s;
    display:flex;align-items:center;justify-content:center;gap:.45rem;
}
.tm-card-action-btn:hover{background:#ede9fe;border-color:#a78bfa;}

/* ── Edit Modal ── */
.tm-modal-bg{display:none;position:fixed;inset:0;background:rgba(27,43,94,.35);backdrop-filter:blur(4px);z-index:999;align-items:center;justify-content:center;}
.tm-modal-bg.open{display:flex;}
.tm-modal{background:var(--c-white);border-radius:16px;padding:2rem;width:100%;max-width:430px;box-shadow:0 8px 40px rgba(27,43,94,.18);animation:fadeUp .25s ease;}
.tm-modal h3{font-size:16px;font-weight:700;color:var(--c-text);margin-bottom:1.3rem;}
.tm-field{display:flex;flex-direction:column;gap:.3rem;margin-bottom:1rem;}
.tm-field label{font-size:11px;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em;}
.tm-field input,.tm-field select{padding:.6rem .85rem;border:1.5px solid var(--c-border);border-radius:9px;font-family:'Epilogue',sans-serif;font-size:13.5px;color:var(--c-text);outline:none;transition:border-color .15s;background:var(--c-white);}
.tm-field input:focus,.tm-field select:focus{border-color:var(--c-blue);}
.tm-modal-actions{display:flex;gap:.6rem;justify-content:flex-end;margin-top:1.5rem;}
.tm-btn{padding:.55rem 1.2rem;border-radius:9px;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:700;cursor:pointer;border:none;transition:opacity .15s;}
.tm-btn.primary{background:var(--c-blue);color:#fff;}.tm-btn.primary:hover{opacity:.88;}
.tm-btn.ghost{background:var(--c-bg);color:var(--c-muted);border:1.5px solid var(--c-border);}
.tm-save-msg{font-size:12px;color:var(--c-green);margin-right:auto;display:none;}

/* ── Permissions Modal ── */
.tm-perm-modal-bg{display:none;position:fixed;inset:0;background:rgba(27,43,94,.4);backdrop-filter:blur(4px);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.tm-perm-modal-bg.open{display:flex;}
.tm-perm-modal{background:var(--c-white);border-radius:18px;width:100%;max-width:490px;box-shadow:var(--shadow-lg);animation:fadeUp .25s ease;overflow:hidden;}
.tm-perm-head{padding:1.4rem 1.6rem;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:1rem;}
.tm-perm-av{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff;flex-shrink:0;box-shadow:0 2px 8px rgba(27,43,94,.18);}
.tm-perm-name{font-size:15.5px;font-weight:700;color:var(--c-text);}
.tm-perm-sub{font-size:12px;color:var(--c-soft);margin-top:2px;}
.tm-perm-close{margin-left:auto;width:32px;height:32px;border:1.5px solid var(--c-border);border-radius:8px;background:var(--c-white);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--c-muted);font-size:16px;transition:background .15s;}
.tm-perm-close:hover{background:var(--c-bg);}
.tm-perm-body{padding:1.4rem 1.6rem;display:flex;flex-direction:column;gap:.55rem;}
.tm-perm-section-label{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--c-soft);margin-bottom:.1rem;margin-top:.5rem;}
.tm-toggle-row{
    display:flex;align-items:center;justify-content:space-between;
    padding:.75rem 1rem;border:1.5px solid var(--c-border);border-radius:10px;
    background:var(--c-surface);transition:border-color .15s,background .15s;
}
.tm-toggle-row:hover{border-color:var(--c-border-2);background:#f7f8fa;}
.tm-toggle-label{display:flex;align-items:center;gap:.7rem;}
.tm-toggle-icon{font-size:17px;width:24px;text-align:center;}
.tm-toggle-text{font-size:13.5px;font-weight:600;color:var(--c-text);}
.tm-toggle-desc{font-size:11px;color:var(--c-soft);margin-top:1px;}
.tm-toggle{position:relative;display:inline-block;width:44px;height:25px;flex-shrink:0;}
.tm-toggle input{opacity:0;width:0;height:0;}
.tm-toggle-slider{position:absolute;cursor:pointer;inset:0;background:#d1d5db;border-radius:99px;transition:.25s;}
.tm-toggle-slider:before{content:'';position:absolute;height:19px;width:19px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.25s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.tm-toggle input:checked + .tm-toggle-slider{background:var(--c-blue);}
.tm-toggle input:checked + .tm-toggle-slider:before{transform:translateX(19px);}
.tm-perm-footer{padding:1.1rem 1.6rem;border-top:1px solid var(--c-border);display:flex;justify-content:flex-end;gap:.6rem;background:var(--c-surface);}
.tm-perm-save-msg{font-size:12px;color:var(--c-green);margin-right:auto;display:none;align-items:center;gap:.3rem;}

/* Avatar colors */
.av-1{background:#2d52c4;}.av-2{background:#0e9f8e;}.av-3{background:#c47c0e;}
.av-4{background:#c0354a;}.av-5{background:#6d52c4;}.av-6{background:#1a8a5a;}
.av-7{background:#db2777;}.av-8{background:#0d9488;}

.st-topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 66px; gap: 1rem;
    background: var(--white); border-bottom: 1px solid var(--border);
}
.st-topnav-left { display: flex; flex-direction: column; justify-content: center; }
.st-topnav-eyebrow { font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--blue); line-height: 1; margin-bottom: 2px; }
.st-topnav-heading { font-size: 16px; font-weight: 800; color: var(--navy); letter-spacing: -.02em; line-height: 1; }
.st-topnav-right { display: flex; align-items: center; gap: .5rem; }

@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
</style>

<div class="tm-page">
<div class="tm-wrap">

    {{-- Summary --}}
    <div class="tm-summary">
        <div class="tm-sum-card">
            <div class="tm-sum-icon blue">👥</div>
            <div>
                <div class="tm-sum-val">{{ $teamCount }}</div>
                <div class="tm-sum-label">Team Members</div>
            </div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-icon teal">🟢</div>
            <div>
                <div class="tm-sum-val">{{ $activeCount }}</div>
                <div class="tm-sum-label">Active Today</div>
            </div>
        </div>
        <div class="tm-sum-card">
            <div class="tm-sum-icon amber">📋</div>
            <div>
                <div class="tm-sum-val">{{ $openTasks }}</div>
                <div class="tm-sum-label">Open Tasks</div>
            </div>
        </div>
    </div>

 {{-- Toolbar with search --}}
    <div class="tm-toolbar">
        <div class="tm-search">
            <span class="tm-search-icon">🔍</span>
            <input type="text" placeholder="Search members…" id="memberSearch" oninput="filterMembers()">
        </div>
        <div class="tm-toolbar-meta" id="memberCount">
            Showing <strong>{{ count($members ?? []) }}</strong> members
        </div>
    </div>

    {{-- Member Grid --}}
    <div class="tm-grid" id="memberGrid">
        @forelse($members ?? [] as $member)
        @php
            $roleClass = match(strtolower($member->role ?? 'team_member')) { 'admin'=>'admin','manager'=>'manager',default=>'member' };
            $avIdx     = ($loop->index % 8) + 1;
            $initials  = strtoupper(substr($member->name,0,1) . (strpos($member->name,' ')!==false ? substr($member->name,strpos($member->name,' ')+1,1) : ''));
            $isOnline  = $member->last_active && \Carbon\Carbon::parse($member->last_active)->diffInMinutes() < 30;
            $perms     = $member->getPermissions();
        @endphp

        <div class="tm-card" data-name="{{ strtolower($member->name) }}" data-id="{{ $member->id }}">

            <div class="tm-card-top">
                <div class="tm-av av-{{ $avIdx }}">{{ $initials }}</div>
                <div style="min-width:0;">
                    <div class="tm-name">{{ $member->name }}</div>
                    <div class="tm-email">{{ $member->email }}</div>
                </div>
                <div class="tm-online-wrap">
                
                </div>
                @if(auth()->user()->role === 'admin')
                <button class="tm-edit-btn"
                    onclick="openEdit(this)"
                    data-id="{{ $member->id }}"
                    data-name="{{ $member->name }}"
                    data-email="{{ $member->email }}"
                    data-role="{{ $member->role ?? 'team_member' }}"
                    title="Edit member">✏️</button>
                @endif
            </div>{{-- end .tm-card-top --}}

            <div class="tm-card-divider"></div>

            <div class="tm-card-meta">
                <span class="tm-role {{ $roleClass }}">{{ ucfirst(str_replace('_',' ', $member->role ?? 'Member')) }}</span>
                <div class="tm-tasks-badge">
                    📌 <strong>{{ $member->tasks_count ?? 0 }}</strong> tasks
                </div>
            </div>

            @if(auth()->user()->role === 'admin' && $member->role !== 'admin')
            <div class="tm-card-actions">
                <button class="tm-card-action-btn" onclick="openPerms(
                    {{ $member->id }},
                    '{{ addslashes($member->name) }}',
                    'av-{{ $avIdx }}',
                    {{ $perms->can_view_calendar  ? 'true' : 'false' }},
                    {{ $perms->can_view_analytics ? 'true' : 'false' }},
                    {{ $perms->can_view_team      ? 'true' : 'false' }},
                    {{ $perms->can_view_reports   ? 'true' : 'false' }},
                    {{ $perms->can_create_tasks   ? 'true' : 'false' }},
                    {{ $perms->can_delete_tasks   ? 'true' : 'false' }},
                    {{ $perms->can_edit_tasks     ? 'true' : 'false' }}, 
                    {{ $perms->can_add_column     ? 'true' : 'false' }}
                )">
                    🔐 Manage Permissions
                </button>
            </div>
            @endif

        </div>{{-- end .tm-card --}}
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--c-soft);font-size:14px;">
            No team members found.
        </div>
        @endforelse
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
            <label>New Password <span style="font-weight:400;color:var(--c-soft)">(leave blank to keep)</span></label>
            <input type="password" id="editPassword" placeholder="••••••••">
        </div>
        <div class="tm-field">
            <label>Role</label>
            <select id="editRole">
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
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

            <div class="tm-perm-section-label" style="margin-top:.5rem;">⚙️ Task Actions</div>

            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">➕</span>
                    <div>
                        <div class="tm-toggle-text">Create Tasks</div>
                        <div class="tm-toggle-desc">Add new tasks to any column and add column</div>
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
                
            <div class="tm-toggle-row">
                <div class="tm-toggle-label">
                    <span class="tm-toggle-icon">✏️</span>
                    <div>
                        <div class="tm-toggle-text">Edit Tasks</div>
                        <div class="tm-toggle-desc">Modify task details, title, due date, etc.</div>
                    </div>
                </div>
                <label class="tm-toggle">
                    <input type="checkbox" id="perm_edit_tasks" onchange="savePerms()">
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
    let visible = 0;
    document.querySelectorAll('.tm-card').forEach(c => {
        const show = c.dataset.name.includes(q);
        c.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    const mc = document.getElementById('memberCount');
    if (mc) mc.innerHTML = `Showing <strong>${visible}</strong> member${visible !== 1 ? 's' : ''}`;
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
function closeEdit() { document.getElementById('editModalBg').classList.remove('open'); }
document.getElementById('editModalBg').addEventListener('click', function(e) { if (e.target === this) closeEdit(); });
async function saveEdit() {
    const id       = document.getElementById('editId').value;
    const name     = document.getElementById('editName').value;
    const email    = document.getElementById('editEmail').value;
    const password = document.getElementById('editPassword').value;
    const role     = document.getElementById('editRole').value;
    const payload  = { name, email, role, _method: 'PUT' };
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
            const roleLabel = role === 'team_member' ? 'Team Member' : role === 'manager' ? 'Manager' : 'Admin';
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
function openPerms(userId, name, avClass, calendar, analytics, team, reports, createTasks, deleteTasks, editTasks, addColumn) {
    document.getElementById('permUserId').value          = userId;
    document.getElementById('perm-name').textContent     = name;
    document.getElementById('perm-av').textContent       = name.split(' ').map(n=>n[0]).join('').toUpperCase().substr(0,2);
    document.getElementById('perm-av').className         = 'tm-perm-av ' + avClass;
    document.getElementById('perm_calendar').checked     = calendar;
    document.getElementById('perm_analytics').checked    = analytics;
    document.getElementById('perm_team').checked         = team;
    document.getElementById('perm_create_tasks').checked = createTasks;
    document.getElementById('perm_delete_tasks').checked = deleteTasks;
    document.getElementById('perm_edit_tasks').checked  = editTasks;   // ← add
    document.getElementById('permSaveMsg').style.display = 'none';
    document.getElementById('permModalBg').classList.add('open');
}
function closePerms() { document.getElementById('permModalBg').classList.remove('open'); }
document.getElementById('permModalBg').addEventListener('click', function(e) { if (e.target === this) closePerms(); });

async function savePerms() {
    const userId = document.getElementById('permUserId').value;
    if (!userId) return;
    const payload = {
        can_view_calendar:  document.getElementById('perm_calendar').checked,
        can_view_analytics: document.getElementById('perm_analytics').checked,
        can_view_team:      document.getElementById('perm_team').checked,
        can_view_reports:   false,
        can_create_tasks:   document.getElementById('perm_create_tasks').checked,
        can_delete_tasks:   document.getElementById('perm_delete_tasks').checked,
        can_edit_tasks:     document.getElementById('perm_edit_tasks').checked,   // ← add
          
    };
    const res = await fetch(`/team/members/${userId}/permissions`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    });
    if (res.ok) {
        const msg = document.getElementById('permSaveMsg');
        msg.style.display = 'flex';
        setTimeout(() => msg.style.display = 'none', 2000);
        const card = document.querySelector(`.tm-card[data-id="${userId}"]`);
        if (card) {
            const btn = card.querySelector('.tm-card-action-btn');
            if (btn) {
                const calendar    = document.getElementById('perm_calendar').checked;
                const analytics   = document.getElementById('perm_analytics').checked;
                const team        = document.getElementById('perm_team').checked;
                const createTasks = document.getElementById('perm_create_tasks').checked;
                const deleteTasks = document.getElementById('perm_delete_tasks').checked;
                const name        = document.getElementById('perm-name').textContent;
                const avClass     = document.getElementById('perm-av').className.replace('tm-perm-av ', '');
                const editTasks = document.getElementById('perm_edit_tasks').checked;

                btn.setAttribute('onclick', `openPerms(${userId},'${name}','${avClass}',${calendar},${analytics},${team},false,${createTasks},${deleteTasks},${editTasks},${addColumn})`);
            }
        }
    } else {
        alert('Failed to save permissions.');
    }
}
</script>
<script>
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
 
    // Load notifications
    const notifBtn = document.getElementById('notif-btn');
    if (notifBtn) {
        notifBtn.addEventListener('click', async () => {
            const list = document.querySelector('#notif-dropdown .tk-dropdown-body');
            if (!list) return;
            list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--c-soft);">Loading…</div>';
            try {
                const res  = await fetch('/notifications', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();
                const count = document.getElementById('notif-count');
                if (count) count.textContent = data.length ? data.length + ' new' : '';
                if (!data.length) {
                    list.innerHTML = '<div style="padding:1.5rem;text-align:center;font-size:13.5px;color:var(--c-soft);">You\'re all caught up! 🎉</div>';
                    return;
                }
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