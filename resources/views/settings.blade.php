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
:root{
    --c-bg:#f4f5f7;--c-white:#fff;--c-surface:#fafbfc;--c-border:#e2e5eb;--c-border-2:#d0d4dd;
    --c-text:#1a1e2e;--c-muted:#6b7491;--c-soft:#9ba3be;
    --c-navy:#1b2b5e;--c-blue:#2d52c4;--c-blue-lt:#ebeffa;
    --c-red:#c0354a;--c-red-lt:#fdeef1;
    --c-green:#1a8a5a;--c-green-lt:#e8f6f0;
    --c-rule:#e8eaf0;--radius:10px;
    --shadow-sm:0 1px 4px rgba(27,43,94,0.07),0 1px 2px rgba(0,0,0,0.04);
}
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

/* Avatar */
.st-avatar-row{display:flex;align-items:center;gap:1.2rem;}
.st-big-av{width:64px;height:64px;border-radius:50%;background:var(--c-blue);color:#fff;font-family:'Playfair Display',serif;font-size:22px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.st-av-actions{display:flex;flex-direction:column;gap:.4rem;}
.st-btn-sm{padding:.4rem .9rem;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Epilogue',sans-serif;border:1.5px solid var(--c-border-2);background:var(--c-white);color:var(--c-navy);transition:background .15s,border-color .15s;}
.st-btn-sm:hover{background:var(--c-navy);color:#fff;border-color:var(--c-navy);}

/* Form */
.st-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
@media(max-width:600px){.st-row{grid-template-columns:1fr;}}
.st-field{display:flex;flex-direction:column;gap:.35rem;}
.st-field.full{grid-column:1/-1;}
.st-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.09em;color:var(--c-muted);}
.st-input{padding:.6rem .9rem;border:1.5px solid var(--c-border);border-radius:7px;font-family:'Epilogue',sans-serif;font-size:13px;color:var(--c-text);background:var(--c-white);outline:none;transition:border-color .15s,box-shadow .15s;}
.st-input:focus{border-color:var(--c-blue);box-shadow:0 0 0 3px rgba(45,82,196,.1);}
.st-input.error{border-color:var(--c-red);}
select.st-input{cursor:pointer;}
.st-error{font-size:11.5px;color:var(--c-red);margin-top:.2rem;}

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

/* Buttons */
.st-save{display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.4rem;background:var(--c-navy);color:#fff;border:none;border-radius:7px;font-family:'Epilogue',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .15s;}
.st-save:hover{opacity:.85;}
.st-danger{border-color:var(--c-red-lt);}
.st-danger .st-card-title{color:var(--c-red);}
.st-btn-danger{padding:.5rem 1.1rem;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Epilogue',sans-serif;border:1.5px solid var(--c-red);background:var(--c-red-lt);color:var(--c-red);transition:background .15s;}
.st-btn-danger:hover{background:var(--c-red);color:#fff;}

/* Alert */
.st-alert{padding:.75rem 1rem;border-radius:8px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:.5rem;}
.st-alert.success{background:var(--c-green-lt);color:var(--c-green);border:1px solid #a7f3d0;}
.st-alert.error{background:var(--c-red-lt);color:var(--c-red);border:1px solid #fca5a5;}

/* Delete modal */
.del-overlay{position:fixed;inset:0;background:rgba(16,24,40,.5);backdrop-filter:blur(4px);z-index:500;display:none;align-items:center;justify-content:center;padding:1rem;}
.del-overlay.open{display:flex;}
.del-modal{background:var(--c-white);border-radius:14px;width:100%;max-width:400px;padding:1.5rem;box-shadow:0 12px 32px rgba(27,43,94,.14);}
.del-modal h3{font-family:'Playfair Display',serif;font-size:17px;color:var(--c-red);margin-bottom:.5rem;}
.del-modal p{font-size:13px;color:var(--c-muted);margin-bottom:1.1rem;line-height:1.55;}

@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
</style>

<div class="st-page">
<div class="st-wrap">

    {{-- Tab nav --}}
    <div class="st-tabs">
        <div class="st-tab {{ !session('active_tab') || session('active_tab') === 'profile' ? 'active' : '' }}"
             onclick="stTab('profile', this)">
            <span class="st-tab-icon">👤</span> Profile
        </div>
        <div class="st-tab {{ session('active_tab') === 'account' ? 'active' : '' }}"
             onclick="stTab('account', this)">
            <span class="st-tab-icon">🔐</span> Account
        </div>
        <div class="st-tab" onclick="stTab('notifications', this)">
            <span class="st-tab-icon">🔔</span> Notifications
        </div>
        <div class="st-tab" onclick="stTab('appearance', this)">
            <span class="st-tab-icon">🎨</span> Appearance
        </div>
    </div>

    <div class="st-panels">

        {{-- ── PROFILE PANEL ── --}}
        <div class="st-panel {{ !session('active_tab') || session('active_tab') === 'profile' ? 'active' : '' }}"
             id="panel-profile">

            {{-- Success / error alerts --}}
            @if(session('success_profile'))
            <div class="st-alert success">✓ {{ session('success_profile') }}</div>
            @endif
            @if($errors->has('email') || $errors->has('first_name'))
            <div class="st-alert error">⚠ Please fix the errors below.</div>
            @endif

            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Profile Information</div>
                    <div class="st-card-sub">Update your display name and email address</div>
                </div>
                <div class="st-card-body">
                    <div class="st-avatar-row">
                        <div class="st-big-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="st-av-actions">
                            <div style="font-size:12px;color:var(--c-soft);">{{ Auth::user()->name }}</div>
                            <div style="font-size:11px;color:var(--c-soft);">{{ ucfirst(Auth::user()->role ?? 'member') }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('settings.profile') }}">
                        @csrf @method('PATCH')
                        <div class="st-row">
                            <div class="st-field">
                                <label class="st-label">First Name</label>
                                <input class="st-input {{ $errors->has('first_name') ? 'error' : '' }}"
                                       type="text" name="first_name"
                                       value="{{ old('first_name', explode(' ', Auth::user()->name)[0]) }}"
                                       required>
                                @error('first_name')<div class="st-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="st-field">
                                <label class="st-label">Last Name</label>
                                <input class="st-input" type="text" name="last_name"
                                       value="{{ old('last_name', explode(' ', Auth::user()->name)[1] ?? '') }}">
                            </div>
                            <div class="st-field full">
                                <label class="st-label">Email Address</label>
                                <input class="st-input {{ $errors->has('email') ? 'error' : '' }}"
                                       type="email" name="email"
                                       value="{{ old('email', Auth::user()->email) }}"
                                       required>
                                @error('email')<div class="st-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div style="margin-top:1rem">
                            <button type="submit" class="st-save">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── ACCOUNT PANEL ── --}}
        <div class="st-panel {{ session('active_tab') === 'account' ? 'active' : '' }}"
             id="panel-account">

            @if(session('success_password'))
            <div class="st-alert success">✓ {{ session('success_password') }}</div>
            @endif
            @if($errors->has('current_password'))
            <div class="st-alert error">⚠ {{ $errors->first('current_password') }}</div>
            @endif

            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Change Password</div>
                    <div class="st-card-sub">Use a strong password you don't use elsewhere</div>
                </div>
                <div class="st-card-body">
                    <form method="POST" action="{{ route('settings.password') }}">
                        @csrf @method('PUT')
                        <div class="st-row">
                            <div class="st-field full">
                                <label class="st-label">Current Password</label>
                                <input class="st-input {{ $errors->has('current_password') ? 'error' : '' }}"
                                       type="password" name="current_password" required>
                                @error('current_password')<div class="st-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="st-field">
                                <label class="st-label">New Password</label>
                                <input class="st-input {{ $errors->has('password') ? 'error' : '' }}"
                                       type="password" name="password" required>
                                @error('password')<div class="st-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="st-field">
                                <label class="st-label">Confirm Password</label>
                                <input class="st-input" type="password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div style="margin-top:1rem">
                            <button type="submit" class="st-save">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="st-card st-danger">
                <div class="st-card-header">
                    <div class="st-card-title">Danger Zone</div>
                    <div class="st-card-sub">These actions are irreversible — please be certain</div>
                </div>
                <div class="st-card-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:13px;font-weight:500;">Delete Account</div>
                            <div style="font-size:11.5px;color:var(--c-soft);margin-top:2px;">Permanently delete your account and all data</div>
                        </div>
                        <button class="st-btn-danger" onclick="document.getElementById('deleteModal').classList.add('open')">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── NOTIFICATIONS PANEL ── --}}
        <div class="st-panel" id="panel-notifications">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Notification Preferences</div>
                    <div class="st-card-sub">Choose what you want to be notified about</div>
                </div>
                <div class="st-card-body">
                    @foreach([
                        ['task_assigned',  'Task assigned to you',   'Get notified when someone assigns you a task', true],
                        ['task_due',       'Task due soon',           'Reminder 24 hours before a task deadline',    true],
                        ['task_completed', 'Task completed',          'When a task you created is marked done',       false],
                        ['team_mention',   'Team mentions',           'When someone mentions you in a comment',       true],
                        ['weekly_summary', 'Weekly summary',          'A weekly digest of your team\'s activity',    false],
                    ] as [$key, $label, $sub, $checked])
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
                    <div style="margin-top:.5rem;font-size:11.5px;color:var(--c-soft);">
                        ℹ Notification saving coming soon — preferences are UI only for now.
                    </div>
                </div>
            </div>
        </div>

        {{-- ── APPEARANCE PANEL ── --}}
        <div class="st-panel" id="panel-appearance">
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-title">Appearance</div>
                    <div class="st-card-sub">Customize how the app looks for you</div>
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
                    <div style="font-size:11.5px;color:var(--c-soft);">
                        ℹ Appearance preferences coming soon.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

{{-- ── DELETE ACCOUNT MODAL ── --}}
<div class="del-overlay" id="deleteModal">
<div class="del-modal">
    <h3>⚠ Delete Account</h3>
    <p>This will permanently delete your account, tasks, and all associated data. This action <strong>cannot be undone</strong>.</p>
    <form method="POST" action="{{ route('settings.delete') }}">
        @csrf @method('DELETE')
        <div class="st-field" style="margin-bottom:1rem;">
            <label class="st-label">Confirm your password</label>
            <input class="st-input" type="password" name="password"
                   placeholder="Enter your password to confirm" required>
            @error('password')<div class="st-error">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:.65rem;justify-content:flex-end;">
            <button type="button" class="st-btn-sm"
                    onclick="document.getElementById('deleteModal').classList.remove('open')">
                Cancel
            </button>
            <button type="submit" class="st-btn-danger">Yes, Delete My Account</button>
        </div>
    </form>
</div>
</div>

<script>
function stTab(id, el) {
    document.querySelectorAll('.st-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.st-panel').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('panel-' + id).classList.add('active');
}

// Close delete modal on backdrop click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('deleteModal').classList.remove('open');
});
</script>
</x-app-layout>