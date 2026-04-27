<aside class="tf-sidebar" id="tfSidebar">

{{-- LOGO SECTION --}}
<div class="tf-logo">
    <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: .7rem; text-decoration: none; color: inherit;">
        @if(!empty($siteSettings['app_logo']))
            <div class="tf-custom-logo">
                <img src="{{ asset('storage/' . $siteSettings['app_logo']) }}" alt="Logo">
            </div>
        @else
            <div class="tf-logo-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity=".9"/>
                    <path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".6"/>
                    <path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".8"/>
                </svg>
            </div>
        @endif
        <span class="tf-logo-text">{{ $siteSettings['app_name'] ?? 'ProductivityDaily' }}</span>
    </a>
</div>

{{-- DYNAMIC THEME COLOR --}}
<style>
:root {
    var(--sb-badge): {{ $siteSettings['brand_color'] ?? '#3b6be8' }};
    var(--sb-accent): {{ $siteSettings['brand_color'] ?? '#4f83ff' }};
}
</style>

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

            {{-- EMAIL --}}
            <a href="{{ Route::has('email.index') ? route('email.index') : '#' }}"
               class="tf-nav-item {{ request()->routeIs('email.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </span>
                <span class="tf-nav-text">Email</span>
                <span class="tf-badge" id="email-unread-badge" style="background-color: #ef4444; color: #ffffff; display: none;">0</span>
            </a>

            {{-- ============================================================
                 CALENDAR DROPDOWN
            ============================================================ --}}
            @if(auth()->user()->can_access('can_view_calendar'))
            <div class="tf-nav-dropdown {{ request()->routeIs('calendar.*') ? 'open' : '' }}" id="calDropdown">

                {{-- Main Calendar row (clickable toggle) --}}
                <div class="tf-nav-item tf-nav-dropdown-toggle {{ request()->routeIs('calendar.*') ? 'active' : '' }}"
                     onclick="toggleCalDropdown()">
                    <span class="tf-nav-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8"  y1="2" x2="8"  y2="6"/>
                            <line x1="3"  y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    <span class="tf-nav-text">Calendar</span>
                    <svg class="tf-dropdown-chevron" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>

                {{-- Sub-items --}}
                <div class="tf-nav-dropdown-menu">

                    <a href="{{ route('calendar.index') }}?cal=general"
                    class="tf-nav-sub-item {{ request()->routeIs('calendar.*') && request()->get('cal') === 'general' ? 'active' : '' }}">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.7;flex-shrink:0;">
                            <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                        General
                    </a>

                    <a href="{{ route('calendar.index') }}?cal=personal"
                    class="tf-nav-sub-item {{ request()->routeIs('calendar.*') && request()->get('cal') === 'personal' ? 'active' : '' }}">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.7;flex-shrink:0;">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Personal
                    </a>

                    <a href="{{ route('calendar.index') }}?cal=team"
                    class="tf-nav-sub-item {{ request()->routeIs('calendar.*') && request()->get('cal') === 'team' ? 'active' : '' }}">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.7;flex-shrink:0;">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Team
                    </a>

                </div>
            </div>
            @endif
            {{-- ============================================================
                 END CALENDAR DROPDOWN
            ============================================================ --}}

            @if(auth()->user()->can_access('can_view_analytics'))
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
            @endif

            @if(auth()->user()->isAtLeastAdmin() || auth()->user()->isAtLeastManager() || \App\Models\User::where('team_leader_id', auth()->id())->exists())
            <a href="{{ route('eod.index') }}"
               class="tf-nav-item {{ request()->routeIs('eod.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </span>
                <span class="tf-nav-text">EOD Reports</span>
            </a>
            @endif

            @if(auth()->user()->can_access('can_view_team'))
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
            @endif
        </div>

        <div class="tf-nav-section">
            <p class="tf-nav-label">General</p>

            @if(auth()->user()->isAtLeastAdmin())
            <a href="{{ route('admin.users.index') }}"
               class="tf-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                </span>
                <span class="tf-nav-text">Users</span>
            </a>

            <a href="{{ route('admin.system-data.index') }}"
               class="tf-nav-item {{ request()->routeIs('admin.system-data.*') ? 'active' : '' }}">
                <span class="tf-nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                    </svg>
                </span>
                <span class="tf-nav-text">System Data</span>
            </a>
            @endif

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

        </div>
    </nav>

    <p style="color:var(--sb-label);font-size:11px;padding:1rem;font-weight:600;">
        V1.0
    </p>
</aside>

{{-- Mobile overlay --}}
<div class="tf-overlay" id="tfOverlay" onclick="tfToggle()"></div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600&display=swap');

.tf-logo a { transition: opacity 0.2s ease; }
.tf-logo a:hover { opacity: 0.8; }
.tf-logo a:active { transform: scale(0.98); }
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
    width: 48px; height: 48px; border-radius: 10px;
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
.tf-custom-logo { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; border-radius: 8px; }
.tf-custom-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
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
.tf-nav-icon { width: 20px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; opacity: .85; transition: opacity .16s; }
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
.tf-user { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.1rem; border-top: 1px solid var(--sb-border); flex-shrink: 0; }
.tf-user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--sb-badge); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: .03em; }
.tf-user-name { font-size: 13px; font-weight: 600; color: var(--sb-text-hi); line-height: 1.2; margin: 0; }
.tf-user-role { font-size: 11px; color: var(--sb-label); margin: 0; }
.tf-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); backdrop-filter: blur(2px); z-index: 199; }
.tf-toggle { display: none; position: fixed; top: 1rem; left: 1rem; z-index: 300; width: 38px; height: 38px; background: var(--sb-bg); border: 1px solid var(--sb-border); border-radius: 8px; color: var(--sb-text-hi); align-items: center; justify-content: center; cursor: pointer; }
.tf-page-wrap { margin-left: var(--sb-w); min-height: 100vh; transition: margin-left .28s cubic-bezier(.4,0,.2,1); }
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

/* ============================================================
   CALENDAR DROPDOWN
============================================================ */
.tf-nav-dropdown-menu {
    overflow: hidden;
    max-height: 0;
    transition: max-height .28s cubic-bezier(.4,0,.2,1), opacity .22s ease;
    opacity: 0;
}
.tf-nav-dropdown.open .tf-nav-dropdown-menu {
    max-height: 200px;
    opacity: 1;
}
.tf-dropdown-chevron {
    color: var(--sb-label);
    flex-shrink: 0;
    transition: transform .25s cubic-bezier(.4,0,.2,1);
}
.tf-nav-dropdown.open .tf-dropdown-chevron {
    transform: rotate(180deg);
}
.tf-nav-dropdown-toggle {
    cursor: pointer;
    user-select: none;
}
.tf-nav-sub-item {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .5rem 1rem .5rem 2.85rem;
    margin: 1px .6rem;
    border-radius: var(--sb-radius);
    text-decoration: none;
    color: var(--sb-text);
    font-size: 13px;
    font-weight: 500;
    transition: background .16s, color .16s;
}
.tf-nav-sub-item:hover {
    background: var(--sb-hover);
    color: var(--sb-text-hi);
}
.tf-nav-sub-item.active {
    background: var(--sb-active);
    color: var(--sb-text-hi);
}
.tf-sub-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    transition: transform .2s;
}
.tf-nav-sub-item.active .tf-sub-dot { transform: scale(1.35); }
.tf-nav-sub-item:hover  .tf-sub-dot { transform: scale(1.2);  }
</style>

<script>
function tfToggle() {
    document.getElementById('tfSidebar').classList.toggle('open');
    document.getElementById('tfOverlay').classList.toggle('open');
}

/* ── Calendar dropdown toggle ── */
function toggleCalDropdown() {
    document.getElementById('calDropdown').classList.toggle('open');
}

/* ── Email unread badge ── */
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("email.unread") }}', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.count && data.count > 0) {
            const badge = document.getElementById('email-unread-badge');
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
        }
    })
    .catch(error => console.warn('Could not load unread emails:', error));
});
</script>