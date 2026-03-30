<x-app-layout>
    @section('title', 'Settings')
<x-slot name="header">
    <div class="st-topnav">
        <div class="st-topnav-left">

        </div>
        <div class="st-topnav-right">
            <button class="st-nav-icon-btn" title="Messages">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </button>
            <button class="st-nav-icon-btn" title="Notifications">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            <div class="st-nav-divider"></div>
            <div class="st-nav-profile">
                <div class="st-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="st-nav-userinfo">
                    <span class="st-nav-name">{{ Auth::user()->name }}</span>
                    <span class="st-nav-email">{{ Auth::user()->email }}</span>
                </div>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:#9ca3af;flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

:root {
    --bg:        #eef0f6;
    --white:     #ffffff;
    --surface:   #f8f9fc;
    --border:    #e2e5eb;
    --border-2:  #cdd2de;
    --text:      #0d1424;
    --muted:     #4a5270;
    --soft:      #8b94b3;
    --navy:      #1b2b5e;
    --blue:      #2d52c4;
    --blue-lt:   #eaeffc;
    --blue-md:   #3b63d8;
    --teal:      #0e9f8e;
    --red:       #c0354a;
    --red-lt:    #fdeef1;
    --green:     #1a8a5a;
    --green-lt:  #e7f6ef;
    --amber:     #c47c0e;
    --amber-lt:  #fef4e6;
    --rule:      #e5e8f0;
    --radius:    14px;
    --shadow-sm: 0 1px 4px rgba(27,43,94,0.07), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 24px rgba(27,43,94,0.13), 0 2px 8px rgba(0,0,0,0.04);
    --shadow-lg: 0 10px 40px rgba(27,43,94,0.18);
    --font:      'Plus Jakarta Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); font-family: var(--font); color: var(--text); -webkit-font-smoothing: antialiased; font-size: 14px; }

/* ── TOPNAV ─────────────────────────────────────────────────── */
.st-topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 66px; gap: 1rem;
    background: var(--white); border-bottom: 1px solid var(--border);
}
.st-topnav-left { display: flex; flex-direction: column; justify-content: center; }
.st-topnav-eyebrow { font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--blue); line-height: 1; margin-bottom: 2px; }
.st-topnav-heading { font-size: 16px; font-weight: 800; color: var(--navy); letter-spacing: -.02em; line-height: 1; }
.st-topnav-right { display: flex; align-items: center; gap: .5rem; }
.st-nav-icon-btn { width: 36px; height: 36px; border-radius: 9px; border: 1.5px solid var(--border); background: var(--white); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); transition: background .15s, transform .15s; }
.st-nav-icon-btn:hover { background: var(--bg); transform: translateY(-1px); }
.st-nav-divider { width: 1px; height: 24px; background: var(--border); flex-shrink: 0; margin: 0 .25rem; }
.st-nav-profile { display: flex; align-items: center; gap: .55rem; cursor: pointer; padding: .35rem .65rem; border-radius: 10px; border: 1.5px solid transparent; transition: background .18s, border-color .18s; }
.st-nav-profile:hover { background: var(--bg); border-color: var(--border); }
.st-nav-avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--blue-md), var(--navy)); color: #fff; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(45,82,196,.3); }
.st-nav-name  { display: block; font-size: 13.5px; font-weight: 700; color: var(--text); line-height: 1.2; }
.st-nav-email { display: block; font-size: 11px; color: var(--soft); }

/* ── PAGE ───────────────────────────────────────────────────── */
.st-page { padding: 2rem 2rem 3rem; max-width: 1060px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }

/* ── PAGE HEADER BANNER ─────────────────────────────────────── */
.st-banner {
    background: linear-gradient(125deg, #1b2b5e 0%, #253d8a 52%, #1e4fa3 100%);
    border-radius: var(--radius); padding: 2rem 2.5rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; position: relative; overflow: hidden;
    box-shadow: 0 10px 40px rgba(27,43,94,.24);
    animation: fadeUp .5s cubic-bezier(.16,1,.3,1) both;
}
.st-banner::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.st-banner::after {
    content: ''; position: absolute; right: -60px; top: -60px;
    width: 220px; height: 220px; border-radius: 50%;
    background: rgba(255,255,255,.05); pointer-events: none;
}
.st-banner-left { position: relative; z-index: 1; display: flex; align-items: center; gap: 1.25rem; }
.st-banner-av {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(255,255,255,.18); border: 2px solid rgba(255,255,255,.3);
    color: #fff; font-size: 20px; font-weight: 800;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    backdrop-filter: blur(8px);
}
.st-banner-name { font-size: 1.5rem; font-weight: 800; color: #fff; letter-spacing: -.02em; line-height: 1.1; }
.st-banner-role { font-size: 13px; color: rgba(255,255,255,.6); font-weight: 500; margin-top: 3px; }
.st-banner-right { position: relative; z-index: 1; display: flex; gap: .75rem; flex-wrap: wrap; }
.st-banner-pill {
    display: flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.2);
    border-radius: 99px; padding: .55rem 1.1rem;
    color: rgba(255,255,255,.9); font-size: 12.5px; font-weight: 700;
    backdrop-filter: blur(8px); white-space: nowrap;
}
.st-banner-pill svg { opacity: .7; }

/* ── LAYOUT ─────────────────────────────────────────────────── */
.st-layout { display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; align-items: start; animation: fadeUp .5s .1s cubic-bezier(.16,1,.3,1) both; }
@media (max-width: 760px) { .st-layout { grid-template-columns: 1fr; } }

/* ── SIDEBAR TABS ───────────────────────────────────────────── */
.st-tabs {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.st-tab {
    display: flex; align-items: center; gap: .7rem;
    padding: .85rem 1.1rem; font-size: 13.5px; font-weight: 600;
    color: var(--muted); cursor: pointer;
    border-left: 3px solid transparent;
    transition: background .14s, color .14s, border-color .14s;
    user-select: none;
}
.st-tab:hover { background: var(--surface); color: var(--text); }
.st-tab.active { background: var(--blue-lt); color: var(--blue); border-left-color: var(--blue); }
.st-tab-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: background .14s;
}
.st-tab .st-tab-icon         { background: var(--surface); color: var(--soft); }
.st-tab:hover .st-tab-icon   { background: var(--border); color: var(--muted); }
.st-tab.active .st-tab-icon  { background: var(--blue-lt); color: var(--blue); }
.st-tab + .st-tab { border-top: 1px solid var(--rule); }

/* ── PANELS ─────────────────────────────────────────────────── */
.st-panels { display: flex; flex-direction: column; gap: 1rem; }
.st-panel { display: none; flex-direction: column; gap: 1rem; animation: fadeUp .28s ease both; }
.st-panel.active { display: flex; }

/* ── CARDS ──────────────────────────────────────────────────── */
.st-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    overflow: hidden; transition: box-shadow .2s;
}
.st-card:hover { box-shadow: var(--shadow-md); }
.st-card-header {
    padding: 1.2rem 1.6rem; border-bottom: 1px solid var(--rule);
    display: flex; align-items: center; gap: .85rem;
}
.st-card-header-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.st-card-header-icon.blue   { background: var(--blue-lt); color: var(--blue); }
.st-card-header-icon.red    { background: var(--red-lt);  color: var(--red); }
.st-card-header-icon.amber  { background: var(--amber-lt); color: var(--amber); }
.st-card-header-icon.teal   { background: #e5f7f5; color: var(--teal); }
.st-card-title { font-size: 15px; font-weight: 800; color: var(--navy); letter-spacing: -.01em; }
.st-card-sub   { font-size: 12px; color: var(--soft); font-weight: 500; margin-top: 2px; }
.st-card-body  { padding: 1.5rem 1.6rem; display: flex; flex-direction: column; gap: 1.15rem; }

/* ── AVATAR ROW ─────────────────────────────────────────────── */
.st-avatar-row { display: flex; align-items: center; gap: 1.1rem; }
.st-big-av {
    width: 58px; height: 58px; border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-md), var(--navy));
    color: #fff; font-size: 20px; font-weight: 800;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(45,82,196,.3);
}
.st-av-name  { font-size: 15px; font-weight: 700; color: var(--text); }
.st-av-role  { font-size: 12px; color: var(--soft); margin-top: 3px; font-weight: 500; }

/* ── FORM ───────────────────────────────────────────────────── */
.st-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 600px) { .st-row { grid-template-columns: 1fr; } }
.st-field     { display: flex; flex-direction: column; gap: .4rem; }
.st-field.full { grid-column: 1 / -1; }
.st-label     { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--soft); }
.st-input {
    padding: .65rem 1rem; border: 1.5px solid var(--border); border-radius: 9px;
    font-family: var(--font); font-size: 13.5px; font-weight: 500;
    color: var(--text); background: var(--white); outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.st-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(45,82,196,.1); }
.st-input.error { border-color: var(--red); }
select.st-input {
    cursor: pointer; appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b94b3' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right .85rem center;
    padding-right: 2.2rem;
}
.st-error { font-size: 11.5px; color: var(--red); font-weight: 500; }

/* ── BUTTONS ────────────────────────────────────────────────── */
.st-btn-row { display: flex; align-items: center; gap: .65rem; margin-top: .25rem; }
.st-save {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .6rem 1.4rem; background: var(--navy); color: #fff;
    border: none; border-radius: 9px; font-family: var(--font);
    font-size: 13.5px; font-weight: 700; cursor: pointer;
    transition: background .15s, transform .15s;
}
.st-save:hover { background: var(--blue-md); transform: translateY(-1px); }
.st-cancel {
    padding: .6rem 1.1rem; border: 1.5px solid var(--border); border-radius: 9px;
    background: var(--white); font-family: var(--font); font-size: 13.5px;
    font-weight: 600; color: var(--muted); cursor: pointer; transition: background .15s;
}
.st-cancel:hover { background: var(--bg); }

/* ── TOGGLE ─────────────────────────────────────────────────── */
.st-toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 0; border-bottom: 1px solid var(--rule);
}
.st-toggle-row:last-of-type { border-bottom: none; padding-bottom: 0; }
.st-toggle-label { font-size: 13.5px; font-weight: 700; color: var(--text); }
.st-toggle-sub   { font-size: 12px; color: var(--soft); font-weight: 500; margin-top: 3px; }

.toggle { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-track {
    position: absolute; inset: 0; background: var(--border-2);
    border-radius: 99px; cursor: pointer; transition: background .2s;
}
.toggle input:checked + .toggle-track { background: var(--blue); }
.toggle-thumb {
    position: absolute; top: 4px; left: 4px;
    width: 16px; height: 16px; background: #fff; border-radius: 50%;
    transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.2);
    pointer-events: none;
}
.toggle input:checked ~ .toggle-thumb { transform: translateX(18px); }

/* ── DANGER ZONE ────────────────────────────────────────────── */
.st-card.danger { border-color: #f5c8d0; }
.st-card.danger .st-card-title { color: var(--red); }
.st-danger-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.st-btn-danger {
    padding: .55rem 1.1rem; border-radius: 9px;
    border: 1.5px solid var(--red); background: var(--red-lt);
    color: var(--red); font-family: var(--font); font-size: 13px;
    font-weight: 700; cursor: pointer; transition: background .15s, transform .15s;
    white-space: nowrap; flex-shrink: 0;
}
.st-btn-danger:hover { background: var(--red); color: #fff; transform: translateY(-1px); }

/* ── ALERTS ─────────────────────────────────────────────────── */
.st-alert {
    padding: .85rem 1.1rem; border-radius: 10px;
    font-size: 13.5px; font-weight: 600;
    display: flex; align-items: center; gap: .6rem;
    animation: fadeUp .25s ease both;
}
.st-alert.success { background: var(--green-lt); color: var(--green); border: 1px solid #a7f3d0; }
.st-alert.error   { background: var(--red-lt);   color: var(--red);   border: 1px solid #fca5a5; }
.st-alert svg { flex-shrink: 0; }

/* ── SECTION LABEL ──────────────────────────────────────────── */
.st-section-label {
    font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .13em; color: var(--soft);
    display: flex; align-items: center; gap: .6rem;
}
.st-section-label::after { content: ''; flex: 1; height: 1px; background: var(--rule); }

/* ── DELETE MODAL ───────────────────────────────────────────── */
.del-overlay { position: fixed; inset: 0; background: rgba(13,20,36,.52); backdrop-filter: blur(5px); z-index: 500; display: none; align-items: center; justify-content: center; padding: 1rem; }
.del-overlay.open { display: flex; }
.del-modal { background: var(--white); border-radius: 18px; width: 100%; max-width: 420px; box-shadow: var(--shadow-lg); animation: modalIn .25s cubic-bezier(.16,1,.3,1) both; overflow: hidden; }
.del-modal-head { padding: 1.4rem 1.6rem; border-bottom: 1px solid var(--rule); display: flex; align-items: center; gap: .75rem; }
.del-modal-icon { width: 38px; height: 38px; border-radius: 9px; background: var(--red-lt); color: var(--red); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.del-modal-title { font-size: 17px; font-weight: 800; color: var(--red); letter-spacing: -.01em; }
.del-modal-body { padding: 1.4rem 1.6rem; display: flex; flex-direction: column; gap: 1rem; }
.del-modal-body p { font-size: 13.5px; color: var(--muted); line-height: 1.6; font-weight: 500; }
.del-modal-footer { padding: 1.1rem 1.6rem; border-top: 1px solid var(--rule); display: flex; justify-content: flex-end; gap: .65rem; }

@keyframes fadeUp  { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
@keyframes modalIn { from { opacity: 0; transform: translateY(16px) scale(.97); } to { opacity: 1; transform: none; } }

.eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3b63d8;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.eyebrow-dot {
    width: 6px;
    height: 6px;
    background: currentColor;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.eyebrow-text { display: inline; }
.eyebrow-separator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 10px;
    color: transparent;
    position: relative;
}
.eyebrow-separator::after {
    content: '';
    position: absolute;
    width: 6px;
    height: 6px;
    background: #3b63d8;
    border-radius: 50%;
}
</style>

<div class="st-page">

    {{-- Banner --}}
    <div class="st-banner">
        <div class="st-banner-left">
            <div class="st-banner-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="st-banner-name">{{ Auth::user()->name }}</div>
                <div class="st-banner-role">{{ ucfirst(Auth::user()->role ?? 'Member') }} · {{ Auth::user()->email }}</div>
            </div>
        </div>
        <div class="st-banner-right">
            <div class="st-banner-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Member since {{ Auth::user()->created_at->format('M Y') }}
            </div>
            <div class="st-banner-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                {{ ucfirst(Auth::user()->role ?? 'Member') }}
            </div>
        </div>
    </div>

    {{-- Layout --}}
    <div class="st-layout">

        {{-- Sidebar --}}
        <div class="st-tabs">

            <div class="st-tab active" onclick="stTab('notifications', this)">
                <div class="st-tab-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </div>
                Notifications
            </div>

            <div class="st-tab" onclick="stTab('appearance', this)">
                <div class="st-tab-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                </div>
                Appearance
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="st-tab" onclick="stTab('branding', this)">
                <div class="st-tab-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                </div>
                System Branding
            </div>
            @endif

        </div>

        {{-- Panels --}}
        <div class="st-panels">

            {{-- ── NOTIFICATIONS ── --}}
            <div class="st-panel active" id="panel-notifications">
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-icon teal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                        </div>
                        <div>
                            <div class="st-card-title">Notification Preferences</div>
                            <div class="st-card-sub">Choose what you want to be notified about</div>
                        </div>
                    </div>
                    <div class="st-card-body">
                        @foreach([
                            ['task_assigned',  'Task assigned to you',   'Get notified when someone assigns you a task',   true],
                            ['task_due',       'Task due soon',           'Reminder 24 hours before a task deadline',       true],
                            ['task_completed', 'Task completed',          'When a task you created is marked done',          false],
                            ['team_mention',   'Team mentions',           'When someone mentions you in a comment',          true],
                            ['weekly_summary', 'Weekly summary',          "A weekly digest of your team's activity",        false],
                        ] as [$key, $label, $sub, $checked])
                        <div class="st-toggle-row">
                            <div>
                                <div class="st-toggle-label">{{ $label }}</div>
                                <div class="st-toggle-sub">{{ $sub }}</div>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" {{ $checked ? 'checked' : '' }}>
                                <div class="toggle-track"></div>
                                <div class="toggle-thumb"></div>
                            </label>
                        </div>
                        @endforeach
                        <div style="font-size:12px;color:var(--soft);padding-top:.25rem;">
                            Notification preferences will be saved in a future update.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── APPEARANCE ── --}}
            <div class="st-panel" id="panel-appearance">
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-icon blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        </div>
                        <div>
                            <div class="st-card-title">Appearance</div>
                            <div class="st-card-sub">Customize how the app looks for you</div>
                        </div>
                    </div>
                    <div class="st-card-body">
                        <div class="st-row">
                            <div class="st-field">
                                <label class="st-label">Language</label>
                                <select class="st-input">
                                    <option>English</option>
                                    <option>Filipino</option>
                                    <option>Spanish</option>
                                    <option>Japanese</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Timezone</label>
                                <select class="st-input">
                                    <option>Asia/Manila (GMT+8)</option>
                                    <option>UTC</option>
                                    <option>America/New_York</option>
                                    <option>Europe/London</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Date Format</label>
                                <select class="st-input">
                                    <option>MM/DD/YYYY</option>
                                    <option>DD/MM/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                        <div style="font-size:12px;color:var(--soft);">
                            Appearance preferences will be saved in a future update.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SYSTEM BRANDING ── --}}
            @if(auth()->user()->role === 'admin')
            <div class="st-panel" id="panel-branding">

                @if(session('success_branding'))
                <div class="st-alert success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success_branding') }}
                </div>
                @endif

                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-icon blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
                        </div>
                        <div>
                            <div class="st-card-title">System Branding</div>
                            <div class="st-card-sub">Customize the application name, logo, colors, and landing page copy</div>
                        </div>
                    </div>
                    <div class="st-card-body">
                        <form method="POST" action="{{ route('settings.branding') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="st-section-label" style="margin-bottom:1rem;">🏷️ Identity</div>
                            <div class="st-row" style="margin-bottom:1.5rem;">
                                <div class="st-field">
                                    <label class="st-label">Company / App Name</label>
                                    <input class="st-input" type="text" name="app_name"
                                           value="{{ $siteSettings['app_name'] ?? 'ProductivityDaily' }}"
                                           placeholder="e.g. Trello">
                                </div>
                            </div>

                            <div class="st-section-label" style="margin-bottom:1rem;">📝 Landing Page Copy</div>
                            <div style="display:flex;flex-direction:column;gap:1rem;margin-bottom:1.5rem;">
                                <div class="st-field">
                                    <label class="st-label">Eyebrow Tag <span style="font-weight:400;color:var(--soft);text-transform:none;letter-spacing:0;">(small text above headline, use - to add bullet)</span></label>
                                    <input class="st-input" type="text" name="app_eyebrow"
                                           value="{{ $siteSettings['app_eyebrow'] ?? 'Productivity · Simplified' }}"
                                           placeholder="e.g. Productivity · Simplified"
                                           maxlength="60">
                                    <span style="font-size:11px;color:var(--soft);margin-top:2px;">Max 60 characters.</span>
                                </div>
                                <div class="st-field">
                                    <label class="st-label">Headline</label>
                                    <input class="st-input" type="text" name="app_headline"
                                           value="{{ $siteSettings['app_headline'] ?? 'Streamline your Workflows.' }}"
                                           placeholder="e.g. Manage your Task. Effortlessly."
                                           maxlength="80">
                                    <span style="font-size:11px;color:var(--soft);margin-top:2px;">Max 80 characters.</span>
                                </div>
                                <div class="st-field">
                                    <label class="st-label">Short Description</label>
                                    <textarea class="st-input" name="app_description"
                                              rows="3" maxlength="200"
                                              placeholder="e.g. Manage your cases, collaborate with your team..."
                                              style="resize:vertical;line-height:1.6;">{{ $siteSettings['app_description'] ?? 'Organize your work, collaborate with your team, and hit every deadline — all from one clean, focused workspace.' }}</textarea>
                                    <span style="font-size:11px;color:var(--soft);margin-top:2px;">Max 200 characters.</span>
                                </div>
                            </div>

                            <div class="st-section-label" style="margin-bottom:1rem;">🖼️ Logo & Favicon</div>
                            <div class="st-row" style="margin-bottom:1.5rem;">
                                <div class="st-field">
                                    <label class="st-label">Company Logo</label>
                                    <input class="st-input" type="file" name="app_logo" accept="image/*">
                                    @if(!empty($siteSettings['app_logo']))
                                    <div style="margin-top:.5rem;padding:.5rem .75rem;background:var(--surface);border-radius:8px;border:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;">
                                        <img src="{{ asset('storage/' . $siteSettings['app_logo']) }}" height="24" style="display:block;">
                                        <a href="{{ route('settings.branding.clear', 'app_logo') }}"
                                           style="font-size:11px;color:var(--red);text-decoration:none;font-weight:700;flex-shrink:0;">✕ Remove</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="st-field">
                                    <label class="st-label">Browser Favicon <span style="font-weight:400;color:var(--soft);text-transform:none;letter-spacing:0;">(.png or .ico)</span></label>
                                    <input class="st-input" type="file" name="app_favicon" accept="image/x-icon,image/png">
                                    @if(!empty($siteSettings['app_favicon']))
                                    <div style="margin-top:.5rem;padding:.5rem .75rem;background:var(--surface);border-radius:8px;border:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;">
                                        <img src="{{ asset('storage/' . $siteSettings['app_favicon']) }}" height="20" style="display:block;">
                                        <a href="{{ route('settings.branding.clear', 'app_favicon') }}"
                                           style="font-size:11px;color:var(--red);text-decoration:none;font-weight:700;flex-shrink:0;">✕ Remove</a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="st-btn-row">
                                <button type="submit" class="st-save">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Branding
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- /.st-panels --}}
    </div>{{-- /.st-layout --}}
</div>{{-- /.st-page --}}

{{-- Delete Modal --}}
<div class="del-overlay" id="deleteModal">
<div class="del-modal">
    <div class="del-modal-head">
        <div class="del-modal-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="del-modal-title">Delete Account</div>
    </div>
    <div class="del-modal-body">
        <p>This will permanently delete your account, tasks, and all associated data. This action <strong>cannot be undone</strong>.</p>
        <form method="POST" action="{{ route('settings.delete') }}">
            @csrf @method('DELETE')
            <div class="st-field" style="margin-bottom:.25rem;">
                <label class="st-label">Confirm your password</label>
                <input class="st-input" type="password" name="password"
                       placeholder="Enter your password to confirm" required>
                @error('password')<div class="st-error">{{ $message }}</div>@enderror
            </div>
        </form>
    </div>
    <div class="del-modal-footer">
        <button type="button" class="st-cancel"
                onclick="document.getElementById('deleteModal').classList.remove('open')">
            Cancel
        </button>
        <button type="submit" class="st-btn-danger"
                onclick="this.closest('.del-modal').querySelector('form').submit()">
            Yes, Delete My Account
        </button>
    </div>
</div>
</div>

<script>
const eyebrowInput = document.querySelector('input[name="app_eyebrow"]');
if (eyebrowInput) {
    eyebrowInput.addEventListener('input', function(e) {
        const start = this.selectionStart;
        const newValue = this.value.replace(/-/g, '|');
        if (this.value !== newValue) {
            this.value = newValue;
            this.setSelectionRange(start, start);
        }
    });
}

function stTab(id, el) {
    document.querySelectorAll('.st-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.st-panel').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('panel-' + id).classList.add('active');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('deleteModal').classList.remove('open');
});

@if(session('active_tab'))
(function() {
    const tab = document.querySelector('[onclick="stTab(\'{{ session("active_tab") }}\', this)"]');
    if (tab) stTab('{{ session("active_tab") }}', tab);
})();
@endif
</script>
</x-app-layout>