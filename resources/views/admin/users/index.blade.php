<x-app-layout>
    @section('title', 'User Management')

    <x-slot name="header">
        <div class="tk-topnav" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            
            {{-- Left Side: Title & Action Button --}}
            <div class="flex items-center gap-6 pl-2 md:pl-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight m-0">
                        {{ __('User Directory') }}
                    </h2>
                </div>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-user-modal')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add User
                </button>
            </div>

            {{-- Right Side: Notifications & Profile (Matching Dashboard) --}}
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
                                <div class="tk-notif-content">
                                    <div class="tk-notif-text text-gray-500 text-sm py-2 text-center">No new notifications.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                            @if(auth()->user()->role === 'super_admin')
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

    {{-- Inject Dashboard CSS & JS to make the Top Nav dropdowns work --}}
    @push('styles')
        @vite('resources/css/dashboard.css')
    @endpush
    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded shadow-sm">
                    <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded shadow-sm">
                    <ul class="text-red-700 text-sm font-medium list-disc pl-4">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-4 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <x-input-label for="search" value="Search" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm" placeholder="Name or email...">
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <x-input-label for="role" value="Role" />
                        <select name="role" id="role" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm">
                            <option value="">All Roles</option>
                            @if(isset($roles))
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->slug }}" {{ request('role') == $roleOption->slug ? 'selected' : '' }}>{{ $roleOption->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <x-input-label for="campaign" value="Campaign" />
                        <select name="campaign" id="campaign" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp->id }}" {{ request('campaign') == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <x-input-label for="status" value="Status" />
                        <select name="status" id="status" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Setup</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Deactivated</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition shadow-sm">Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition shadow-sm">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignment</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" x-data="{ userToEdit: null }">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'manager' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->campaign ? $user->campaign->name : 'Unassigned' }}</div>
                                        <div class="text-xs text-gray-500">Leader: {{ $user->teamLeader ? $user->teamLeader->name : 'None' }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$user->is_active)
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full bg-red-100 text-red-800">Deactivated</span>
                                        @elseif($user->email_verified_at)
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending Setup</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <button x-on:click="$dispatch('open-modal', 'edit-user-modal'); $dispatch('set-edit-user', {{ json_encode($user) }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Edit Profile
                                                </button>

                                                @if($user->is_active && !$user->email_verified_at)
                                                    <form method="POST" action="{{ route('admin.users.resend-invite', $user) }}">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">Resend Invite</button>
                                                    </form>
                                                @endif

                                                @if(auth()->id() !== $user->id)
                                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm {{ $user->is_active ? 'text-red-600' : 'text-green-600' }} hover:bg-gray-100">
                                                            {{ $user->is_active ? 'Deactivate Account' : 'Reactivate Account' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <p class="text-base font-medium text-gray-900">No users found</p>
                                        <p class="text-sm">Try adjusting your filters or search query.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <x-modal name="create-user-modal" focusable>
        <form method="post" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-4">Add New User</h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-input-label for="name" value="Full Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="email" value="Email Address" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="role" value="Role" />
                    <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                        @if(isset($roles))
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption->slug }}">{{ $roleOption->name }}</option>
                            @endforeach
                        @else
                            <option value="team_member">Team Member</option>
                            <option value="super_admin">Super Admin</option>
                        @endif
                    </select>
                </div>
                <div>
                    <x-input-label for="campaign_id" value="Campaign (Optional)" />
                    <select name="campaign_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($campaigns as $camp)
                            <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="team_leader_id" value="Team Leader (Optional)" />
                    <select name="team_leader_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($teamLeaders as $leader)
                            <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>Send Invitation</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-user-modal" focusable>
        <div x-data="{ user: {} }" @set-edit-user.window="user = $event.detail">
            <form method="POST" x-bind:action="`/admin/users/${user.id}`" class="p-6">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-medium text-gray-900 mb-4">Edit User Profile</h2>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <x-input-label for="edit_name" value="Full Name" />
                        <x-text-input id="edit_name" name="name" type="text" x-model="user.name" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="edit_email" value="Email Address" />
                        <x-text-input id="edit_email" name="email" type="email" x-model="user.email" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="edit_role" value="Role" />
                        <select name="role" x-model="user.role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                            @if(isset($roles))
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->slug }}">{{ $roleOption->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_campaign" value="Campaign (Optional)" />
                        <select name="campaign_id" x-model="user.campaign_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">-- None --</option>
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_leader" value="Team Leader (Optional)" />
                        <select name="team_leader_id" x-model="user.team_leader_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">-- None --</option>
                            @foreach($teamLeaders as $leader)
                                <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button>Save Changes</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>