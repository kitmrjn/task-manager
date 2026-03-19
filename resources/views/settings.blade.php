<x-app-layout>
<x-slot name="header">
    <div class="db-header-inner">
        <div class="db-header-left">
            <div class="db-avatar">⚙️</div>
            <div>
                <p class="db-greeting">Manage your account</p>
                <h2 class="db-title">Settings</h2>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Epilogue:wght@300;400;500;600&display=swap');
:root{--c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;--c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;--c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;--c-red:#c0354a;--c-red-lt:#fdeef1;--c-green:#1a8a5a;--c-green-lt:#e8f6f0;--c-rule:#e8eaf0;--radius:10px;--shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);}
body{background:var(--c-bg);color:var(--c-text);font-family:'Epilogue',sans-serif;}
.db-header-inner{display:flex;justify-content:space-between;align-items:center;}
.db-header-left{display:flex;align-items:center;gap:.9rem;}
.db-avatar{width:44px;height:44px;border-radius:10px;background:var(--c-navy);color:#fff;font-size:20px;display:flex;align-items:center;justify-content:center;}
.db-greeting{font-size:11px;color:var(--c-soft);letter-spacing:.05em;text-transform:uppercase;font-weight:500;}
.db-title{font-size:17px;font-weight:600;color:var(--c-text);}

.st-page{padding:2rem 0 3rem;}
.st-wrap{max-width:860px;margin:0 auto;padding:0 1.5rem;display:flex;gap:1.5rem;align-items:flex-start;}
@media(max-width:700px){.st-wrap{flex-direction:column;}}

/* Sidebar tabs */
.st-tabs{width:200px;flex-shrink:0;background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s ease both;}
.st-tab{display:flex;align-items:center;gap:.65rem;padding:.75rem 1rem;font-size:13px;font-weight:500;color:var(--c-muted);cursor:pointer;border-left:3px solid transparent;transition:background .15s,color .15s,border-color .15s;}
.st-tab:hover{background:var(--c-surface);color:var(--c-text);}
.st-tab.active{background:var(--c-blue-lt);color:var(--c-blue);border-left-color:var(--c-blue);}
.st-tab-icon{font-size:15px;width:20px;text-align:center;}

/* Panels */
.st-panels{flex:1;display:flex;flex-direction:column;gap:1rem;}
.st-panel{display:none;animation:fadeUp .3s ease both;}
.st-panel.active{display:flex;flex-direction:column;gap:1rem;}
.st-card{background:var(--c-white);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;}
.st-card-header{padding:1.1rem 1.4rem;border-bottom:1px solid var(--c-rule);}
.st-card-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--c-navy);}
.st-card-sub{font-size:11.5px;color:var(--c-soft);margin-top:2px;}
.st-card-body{padding:1.3rem 1.4rem;display:flex;flex-direction:column;gap:1.1rem;}

/* Avatar section */
.st-avatar-row{display:flex;align-items:center;gap:1.2rem;}
.st-big-av{width:64px;height:64px;border-radius:50%;background:var(--c-blue);color:#fff;font-family:'Playfair Display',serif;font-size:22px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.st-av-actions{display:flex;flex-direction:column;gap:.4rem;}
.st-btn-sm{padding:.4rem .9rem;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Epilogue',sans-serif;border:1.5px solid var(--c-border-2);background:var(--c-white);color:var(--c-navy);transition:background .15s,border-color .15s;}
.st-btn-sm:hover{background:var(--c-navy);color:#fff;border-color:var(--c-navy);}

/* Form fields */
.st-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
@media(max-width:600px){.st-row{grid-template-columns:1fr;}}
.st-field{display:flex;flex-direction:column;gap:.35rem;}
.st-field.full{grid-column:1/-1;}
.st-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.09em;color:var(--c-muted);}
.st-input{padding:.6rem .9rem;border:1.5px solid var(--c-border);border-radius:7px;font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);background:var(--c-white);outline:none;transition:border-color .15s,box-shadow .15s;}
.st-input:focus{border-color:var(--c-blue);box-shadow:0 0 0 3px rgba(45,82,196,0.1);}
select.st-input{cursor:pointer;}

/* Toggle */
.st-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--c-rule);}
.st-toggle-row:last-child{border-bottom:none;}
.st-toggle-info .st-toggle-label{font-size:13px;font-weight:500;color:var(--c-text);}
.st-toggle-info .st-toggle-sub{font-size:11.5px;color:var(--c-soft);margin-top:2px;}
.toggle{position:relative;width:40px;height:22px;flex-shrink:0;}
.toggle input{opacity:0;width:0;height:0;}
.toggle-track{position:absolute;inset:0;background:#d0d4dd;border-radius:99px;cursor:pointer;transition:background .2s;}
.toggle input:checked + .toggle-track{background:var(--c-blue);}
.toggle-thumb{position:absolute;top:3px;left:3px;width:16px;height:16px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);}
.toggle input:checked ~ .toggle-thumb{transform:translateX(18px);}

/* Save btn */
.st-save{display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.4rem;background:var(--c-navy);color:#fff;border:none;border-radius:7px;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .15s;}
.st-save:hover{opacity:.85;}
.st-danger{border-color:var(--c-red-lt);}
.st-danger .st-card-title{color:var(--c-red);}
.st-btn-danger{padding:.5rem 1.1rem;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Epilogue',sans-serif;border:1.5px solid var(--c-red);background:var(--c-red-lt);color:var(--c-red);transition:background .15s;}
.st-btn-danger:hover{background:var(--c-red);color:#fff;}

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
</style>

<div class="st-page">
<div class="st-wrap">

    {{-- Tab nav --}}
    <div class="st-tabs">
        <div class="st-tab active" onclick="stTab('profile')"><span class="st-tab-icon">👤</span> Profile</div>
        <div class="st-tab" onclick="stTab('account')"><span class="st-tab-icon">🔐</span> Account</div>
        <div class="st-tab" onclick="stTab('notifications')"><span class="st-tab-icon">🔔</span> Notifications</div>
        <div class="st-tab" onclick="stTab('appearance')"><span class="st-tab-icon">🎨</span> Appearance</div>
    </div>

    <div class="st-panels">

        {{-- Profile --}}
        <div class="st-panel active" id="panel-profile">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Profile Information</div>
                    <div class="st-card-sub">Update your name, role, and photo</div>
                </div>
                <div class="st-card-body">
                    <div class="st-avatar-row">
                        <div class="st-big-av">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                        <div class="st-av-actions">
                            <button class="st-btn-sm">Upload Photo</button>
                            <button class="st-btn-sm" style="color:var(--c-soft)">Remove</button>
                        </div>
                    </div>
                    <form method="POST" action="{{ Route::has('profile.update') ? route('profile.update') : '#' }}">
                        @csrf @method('PATCH')
                        <div class="st-row">
                            <div class="st-field">
                                <label class="st-label">First Name</label>
                                <input class="st-input" type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] }}">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Last Name</label>
                                <input class="st-input" type="text" name="last_name" value="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                            </div>
                            <div class="st-field full">
                                <label class="st-label">Email Address</label>
                                <input class="st-input" type="email" name="email" value="{{ Auth::user()->email }}">
                            </div>
                            <div class="st-field full">
                                <label class="st-label">Role / Title</label>
                                <input class="st-input" type="text" name="role" value="{{ Auth::user()->role ?? 'Team Member' }}" placeholder="e.g. Project Manager">
                            </div>
                        </div>
                        <div style="margin-top:1rem">
                            <button type="submit" class="st-save">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Account --}}
        <div class="st-panel" id="panel-account">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Change Password</div>
                    <div class="st-card-sub">Use a strong password you don't use elsewhere</div>
                </div>
                <div class="st-card-body">
                    <form method="POST" action="{{ Route::has('password.update') ? route('password.update') : '#' }}">
                        @csrf @method('PUT')
                        <div class="st-row">
                            <div class="st-field full">
                                <label class="st-label">Current Password</label>
                                <input class="st-input" type="password" name="current_password">
                            </div>
                            <div class="st-field">
                                <label class="st-label">New Password</label>
                                <input class="st-input" type="password" name="password">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Confirm Password</label>
                                <input class="st-input" type="password" name="password_confirmation">
                            </div>
                        </div>
                        <div style="margin-top:1rem"><button type="submit" class="st-save">Update Password</button></div>
                    </form>
                </div>
            </div>
            <div class="st-card st-danger">
                <div class="st-card-header">
                    <div class="st-card-title">Danger Zone</div>
                    <div class="st-card-sub">These actions are irreversible</div>
                </div>
                <div class="st-card-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:13px;font-weight:500;">Delete Account</div>
                            <div style="font-size:11.5px;color:var(--c-soft);margin-top:2px;">Permanently delete your account and all data</div>
                        </div>
                        <button class="st-btn-danger">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="st-panel" id="panel-notifications">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Notification Preferences</div>
                    <div class="st-card-sub">Choose what you want to be notified about</div>
                </div>
                <div class="st-card-body">
                    @foreach([
                        ['Task assigned to you','Get notified when someone assigns you a task',true],
                        ['Task due soon','Reminder 24 hours before a task deadline',true],
                        ['Task completed','When a task you created is marked done',false],
                        ['Team mentions','When someone @mentions you in a comment',true],
                        ['Weekly summary','A weekly digest of your team\'s activity',false],
                    ] as [$label,$sub,$checked])
                    <div class="st-toggle-row">
                        <div class="st-toggle-info">
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
                    <button class="st-save" style="margin-top:.5rem">Save Preferences</button>
                </div>
            </div>
        </div>

        {{-- Appearance --}}
        <div class="st-panel" id="panel-appearance">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Appearance</div>
                    <div class="st-card-sub">Customize how Taskflow looks for you</div>
                </div>
                <div class="st-card-body">
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
                    <button class="st-save">Save Preferences</button>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script>
function stTab(id) {
    document.querySelectorAll('.st-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.st-panel').forEach(p => p.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.getElementById('panel-' + id).classList.add('active');
}
</script>
</x-app-layout>