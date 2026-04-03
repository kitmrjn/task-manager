<x-app-layout>
    @section('title', 'System Data')

    <x-slot name="header">
        <div class="tk-topnav" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="flex items-center gap-6 pl-2 md:pl-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0" style="font-family: 'Epilogue', sans-serif;">
                    {{ __('System Lookups') }}
                </h2>
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

    <div class="py-10" style="font-family: 'Epilogue', sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div style="background: rgba(45, 204, 112, 0.1); border-left: 4px solid #2dcc70; padding: 1rem; border-radius: 4px; color: #1e824c; font-size: 14px; font-weight: 500;">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div style="background: rgba(231, 76, 60, 0.1); border-left: 4px solid #e74c3c; padding: 1rem; border-radius: 4px; color: #c0392b; font-size: 14px; font-weight: 500;">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="section-eyebrow mb-6">Database Configuration</div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
                
                {{-- CAMPAIGNS CARD --}}
                <div class="card" style="display: flex; flex-direction: column;">
                    <div class="card-header">
                        <div class="card-title">Campaigns</div>
                        <div style="font-size: 13px; color: var(--c-soft);">Manage assignments</div>
                    </div>
                    
                    <div style="flex: 1; max-height: 400px; overflow-y: auto;">
                        <table class="task-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Users</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $camp)
                                    <tr class="task-row">
                                        <td style="font-weight: 600; color: var(--c-navy);">{{ $camp->name }}</td>
                                        <td><span class="pill" style="border: 1px solid var(--c-border);">{{ $camp->users_count }} assigned</span></td>
                                        <td style="text-align: right;">
                                            <form method="POST" action="{{ route('admin.campaigns.destroy', $camp) }}" onsubmit="return confirm('Delete this campaign?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="color: var(--c-red); border: none; background: transparent; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 500;">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row"><td colspan="3">No campaigns created yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="padding: 1.25rem; border-top: 1px solid var(--c-border-2); background: #f8fafc;">
                        <form method="POST" action="{{ route('admin.campaigns.store') }}" style="display: flex; gap: .75rem;">
                            @csrf
                            <input type="text" name="name" placeholder="e.g. Inbound Sales Q3" required style="flex: 1; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px;">
                            <button type="submit" style="background: var(--c-navy); color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1.25rem; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s;">Add Campaign</button>
                        </form>
                    </div>
                </div>

                {{-- ROLES CARD --}}
                <div class="card" style="display: flex; flex-direction: column;">
                    <div class="card-header">
                        <div class="card-title">Custom Roles</div>
                        <div style="font-size: 13px; color: var(--c-soft);">Manage job titles</div>
                    </div>
                    
                    <div style="flex: 1; max-height: 400px; overflow-y: auto;">
                        <table class="task-table">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Users</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr class="task-row">
                                        <td>
                                            <div style="font-weight: 600; color: var(--c-navy); display: flex; align-items: center; gap: 0.5rem;">
                                                {{ $role->name }}
                                                @if($role->is_system)
                                                    <span style="font-size: 10px; font-weight: 700; background: var(--c-blue); color: #fff; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">Core</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><span class="pill" style="border: 1px solid var(--c-border);">{{ $role->users_count }} assigned</span></td>
                                        <td style="text-align: right;">
                                            @if(!$role->is_system)
                                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Permanently delete this role?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="color: var(--c-red); border: none; background: transparent; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 500;">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="padding: 1.25rem; border-top: 1px solid var(--c-border-2); background: #f8fafc;">
                        <form method="POST" action="{{ route('admin.roles.store') }}" style="display: flex; gap: .75rem;">
                            @csrf
                            <input type="text" name="name" placeholder="e.g. QA Tester" required style="flex: 1; border: 1px solid var(--c-border); border-radius: 6px; padding: 0.5rem 0.8rem; font-family: inherit; font-size: 13px;">
                            <button type="submit" style="background: var(--c-navy); color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1.25rem; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s;">Create Role</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>