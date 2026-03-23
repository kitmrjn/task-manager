{{-- resources/views/components/sidebar.blade.php --}}
<aside class="tf-sidebar" id="tfSidebar">

    {{-- Logo --}}
    <div class="tf-logo">
        <div class="tf-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity=".9"/>
                <path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".6"/>
                <path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".8"/>
            </svg>
        </div>
        <span class="tf-logo-text">ProductivityDaily</span>
    </div>

    {{-- Nav --}}
    <nav class="tf-nav">
        <div class="tf-nav-section">
            <p class="tf-nav-label">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="tf-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Dashboard</span>
            </a>

            <a href="{{ Route::has('tasks.index') ? route('tasks.index') : '#' }}"
               class="tf-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Tasks</span>
                @php
                    try {
                        $taskCount = \App\Models\Task::where('assigned_to', Auth::id())
                            ->whereDoesntHave('column', fn($q) => $q->where('title','Done'))
                            ->count();
                    } catch (\Exception $e) {
                        $taskCount = 0;
                    }
                @endphp
                @if($taskCount > 0)
                    <span class="tf-badge">{{ $taskCount }}</span>
                @endif
            </a>

            <a href="{{ Route::has('calendar.index') ? route('calendar.index') : '#' }}"
               class="tf-nav-item {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Calendar</span>
            </a>

            <a href="{{ Route::has('analytics.index') ? route('analytics.index') : '#' }}"
               class="tf-nav-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Analytics</span>
            </a>

            <a href="{{ Route::has('team.index') ? route('team.index') : '#' }}"
               class="tf-nav-item {{ request()->routeIs('team.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Team</span>
            </a>
        </div>

        <div class="tf-nav-section">
            <p class="tf-nav-label">General</p>

            @if(auth()->user()->role === 'admin')
            <a href="{{ Route::has('settings.index') ? route('settings.index') : '#' }}"
            class="tf-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Settings</span>
            </a>
            @endif

            <a href="{{ route('help.index') }}" 
                class="tf-nav-item {{ request()->routeIs('help.index') ? 'active' : '' }}">
                    <span class="tf-nav-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </span>
                    <span class="tf-nav-text">Help</span>
             </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="tf-nav-item tf-nav-item--btn">
                    <span class="tf-nav-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </span>
                    <span class="tf-nav-text">Logout</span>
                </button>
            </form>
        </div>
    </nav>
<p style="color:red;font-size:12px;padding:1rem;">
    Role: {{ auth()->user()->role }} | 
    Is admin: {{ auth()->user()->role === 'admin' ? 'YES' : 'NO' }}
</p>
    
</aside>

{{-- Mobile overlay --}}
<div class="tf-overlay" id="tfOverlay" onclick="tfToggle()"></div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600&display=swap');

:root {
    --sb-w:        240px;
    --sb-bg:       #0f1729;
    --sb-surface:  #162035;
    --sb-active:   #1e3a6e;
    --sb-hover:    rgba(255,255,255,0.05);
    --sb-border:   rgba(255,255,255,0.07);
    --sb-text:     #8fa4cc;
    --sb-text-hi:  #d6e4ff;
    --sb-label:    #3d5275;
    --sb-badge:    #3b6be8;
    --sb-accent:   #4f83ff;
    --sb-radius:   9px;
}

.tf-sidebar {
    position: fixed; top: 0; left: 0; bottom: 0;
    width: var(--sb-w);
    background: var(--sb-bg);
    border-right: 1px solid var(--sb-border);
    display: flex; flex-direction: column;
    z-index: 200;
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    font-family: 'Epilogue', sans-serif;
}
.tf-logo {
    display: flex; align-items: center; gap: .7rem;
    padding: 1.4rem 1.25rem 1.2rem;
    border-bottom: 1px solid var(--sb-border);
    flex-shrink: 0;
}
.tf-logo-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--sb-badge);
    display: flex; align-items: center; justify-content: center;
    color: #fff; flex-shrink: 0;
}
.tf-logo-text { font-size: 17px; font-weight: 700; color: var(--sb-text-hi); letter-spacing: -.01em; }
.tf-nav { flex: 1; overflow-y: auto; padding: .75rem 0; scrollbar-width: none; }
.tf-nav::-webkit-scrollbar { display: none; }
.tf-nav-section { padding: .5rem 0 .25rem; }
.tf-nav-label {
    font-size: 9.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .14em; color: var(--sb-label);
    padding: .5rem 1.3rem .4rem; margin: 0;
}
.tf-nav-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .6rem 1rem .6rem 1.1rem;
    margin: 2px .6rem;
    border-radius: var(--sb-radius);
    text-decoration: none; color: var(--sb-text);
    font-size: 13.5px; font-weight: 500;
    transition: background .16s, color .16s;
    cursor: pointer;
}
.tf-nav-item:hover { background: var(--sb-hover); color: var(--sb-text-hi); }
.tf-nav-item.active {
    background: var(--sb-active); color: var(--sb-text-hi);
    box-shadow: inset 3px 0 0 var(--sb-accent);
}
.tf-nav-item.active .tf-nav-icon { color: var(--sb-accent); }
.tf-nav-item--btn {
    width: calc(100% - 1.2rem); border: none;
    background: transparent; font-family: 'Epilogue', sans-serif; text-align: left;
}
.tf-nav-icon {
    width: 20px; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; opacity: .85; transition: opacity .16s;
}
.tf-nav-item:hover .tf-nav-icon { opacity: 1; }
.tf-nav-text { flex: 1; }
.tf-badge {
    background: var(--sb-badge); color: #fff;
    font-size: 10px; font-weight: 700;
    padding: 2px 7px; border-radius: 99px;
    min-width: 20px; text-align: center; line-height: 16px;
    animation: badgePop .3s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes badgePop {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.tf-user {
    display: flex; align-items: center; gap: .75rem;
    padding: 1rem 1.1rem;
    border-top: 1px solid var(--sb-border); flex-shrink: 0;
}
.tf-user-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--sb-badge); color: #fff;
    font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; letter-spacing: .03em;
}
.tf-user-name { font-size: 13px; font-weight: 600; color: var(--sb-text-hi); line-height: 1.2; margin: 0; }
.tf-user-role { font-size: 11px; color: var(--sb-label); margin: 0; }
.tf-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); backdrop-filter: blur(2px); z-index: 199;
}
.tf-toggle {
    display: none; position: fixed; top: 1rem; left: 1rem;
    z-index: 300; width: 38px; height: 38px;
    background: var(--sb-bg); border: 1px solid var(--sb-border);
    border-radius: 8px; color: var(--sb-text-hi);
    align-items: center; justify-content: center; cursor: pointer;
}
.tf-page-wrap {
    margin-left: var(--sb-w); min-height: 100vh;
    transition: margin-left .28s cubic-bezier(.4,0,.2,1);
}
@media (max-width: 768px) {
    .tf-sidebar { transform: translateX(calc(-1 * var(--sb-w))); }
    .tf-sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,.4); }
    .tf-overlay.open { display: block; }
    .tf-toggle { display: flex; }
    .tf-page-wrap { margin-left: 0; }
}
.tf-nav-item { animation: navFade .3s ease both; }
.tf-nav-item:nth-child(1) { animation-delay: .04s; }
.tf-nav-item:nth-child(2) { animation-delay: .08s; }
.tf-nav-item:nth-child(3) { animation-delay: .12s; }
.tf-nav-item:nth-child(4) { animation-delay: .16s; }
.tf-nav-item:nth-child(5) { animation-delay: .20s; }
@keyframes navFade {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>

<script>
function tfToggle() {
    document.getElementById('tfSidebar').classList.toggle('open');
    document.getElementById('tfOverlay').classList.toggle('open');
}
</script>