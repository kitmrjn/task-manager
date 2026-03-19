<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In — {{ config('app.name', 'TaskFlow') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --b9: #07102a;
            --b7: #0f2254;
            --b6: #1a3a8a;
            --b5: #2355c8;
            --b4: #3b72f0;
            --b3: #6b9bf5;
            --b2: #a8c4fc;
            --b1: #d8e8ff;
            --b0: #eef4ff;
            --g9: #111827;
            --g7: #1f2937;
            --g5: #4b5563;
            --g4: #6b7280;
            --g3: #9ca3af;
            --g2: #d1d5db;
            --g1: #f3f4f6;
            --g0: #f9fafb;
            --white: #ffffff;
            --red: #ef4444;
            --font: 'Bricolage Grotesque', sans-serif;
            --body: 'DM Sans', sans-serif;
        }

        html, body {
            height: 100%;
            font-family: var(--body);
            background: var(--white);
            color: var(--g9);
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        /* ══════════════════════════════
           PAGE GRID — matches register exactly
        ══════════════════════════════ */
        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 420px 1fr;
            overflow: hidden;
            position: relative;
        }

        /* ══════════════════════════════
           LEFT — DARK PANEL
        ══════════════════════════════ */
        .panel-left {
            background: linear-gradient(160deg, var(--b6) 0%, var(--b9) 100%);
            display: flex;
            flex-direction: column;
            padding: 40px 40px 36px;
            position: relative;
            overflow: hidden;

        }

        /* grid dots */
        .panel-left::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 38px 38px;
            pointer-events: none;
        }

        .orb {
            position: absolute; border-radius: 50%; pointer-events: none;
        }
        .orb-1 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(59,114,240,0.20) 0%, transparent 65%);
            top: -100px; right: -100px;
        }
        .orb-2 {
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(107,155,245,0.12) 0%, transparent 65%);
            bottom: -60px; left: -40px;
        }

        /* brand */
        .brand {
            position: relative; z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: auto;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.13);
            border: 1px solid rgba(255,255,255,0.22);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-icon svg { width: 19px; height: 19px; color: white; }

        .brand-name {
            font-family: var(--font);
            font-size: 19px;
            font-weight: 700;
            color: white;
            letter-spacing: -0.4px;
        }

        /* content */
        .panel-content {
            position: relative; z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 32px 0 28px;
        }

        .panel-headline {
            font-family: var(--font);
            font-size: clamp(34px, 3.2vw, 52px);
            font-weight: 800;
            color: white;
            letter-spacing: -1.4px;
            line-height: 1.08;
            margin-bottom: 16px;
        }

        .panel-sub {
            font-size: 16px;
            color: rgba(255,255,255,0.52);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .feat-list {
            display: flex;
            flex-direction: column;
            gap: 13px;
        }

        .feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feat-icon {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: rgba(59,114,240,0.28);
            border: 1px solid rgba(107,155,245,0.35);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .feat-icon svg { width: 14px; height: 14px; color: var(--b3); }

        .feat-label {
            font-size: 15px;
            color: rgba(255,255,255,0.68);
            font-weight: 400;
        }

        .panel-footer {
            position: relative; z-index: 2;
            font-size: 13px;
            color: rgba(255,255,255,0.22);
        }

        /* ══════════════════════════════
           RIGHT — FORM PANEL
        ══════════════════════════════ */
        .panel-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px 48px;
            background: var(--white);
            position: relative;
            overflow-y: auto;
            animation: fadeUp 0.6s 0.05s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════════
           BACK BUTTON
        ══════════════════════════════ */
        .back-btn {
            position: absolute;
            top: 28px; left: 32px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0;
            font-family: var(--font);
            font-size: 16px;
            font-weight: 600;
            color: var(--g4);
            text-decoration: none;
            background: none;
            border: none;
            transition: color 0.18s ease;
            letter-spacing: -0.2px;
            z-index: 10;
        }

        .back-btn-icon {
            width: 38px; height: 38px;
            border-radius: 11px;
            background: var(--g1);
            border: 1.5px solid var(--g2);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }

        .back-btn-icon svg { width: 17px; height: 17px; transition: transform 0.18s; }

        .back-btn:hover { color: var(--b5); }
        .back-btn:hover .back-btn-icon { background: var(--b0); border-color: var(--b2); }
        .back-btn:hover .back-btn-icon svg { transform: translateX(-3px); }

        /* ══════════════════════════════
           FORM WRAP
        ══════════════════════════════ */
        .form-wrap {
            width: 100%;
            max-width: 380px;
        }

        .form-eyebrow {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--b4);
            margin-bottom: 8px;
        }

        .form-title {
            font-family: var(--font);
            font-size: 36px;
            font-weight: 800;
            color: var(--g9);
            letter-spacing: -0.9px;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 15.5px;
            color: var(--g4);
            line-height: 1.6;
            margin-bottom: 28px;
        }

        /* session status */
        .session-status {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        /* ── FIELDS ── */
        .field { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--g7);
            margin-bottom: 6px;
            letter-spacing: -0.1px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 13px;
            border-radius: 9px;
            border: 1.5px solid var(--g2);
            background: var(--g0);
            font-family: var(--body);
            font-size: 15px;
            color: var(--g9);
            outline: none;
            transition: all 0.18s ease;
        }

        input::placeholder { color: var(--g3); }

        input:focus {
            border-color: var(--b4);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(59,114,240,0.11);
        }

        input.is-error {
            border-color: var(--red);
            background: #fff8f8;
        }

        /* error */
        .err {
            font-size: 11.5px;
            color: var(--red);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .err svg { width: 11px; height: 11px; flex-shrink: 0; }

        /* remember + forgot row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--b5);
            cursor: pointer;
        }

        .remember-label span {
            font-size: 14px;
            color: var(--g5);
            font-weight: 500;
        }

        .forgot-link {
            font-size: 14px;
            color: var(--b5);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }

        .forgot-link:hover { color: var(--b4); text-decoration: underline; }

        /* submit */
        .submit-btn {
            width: 100%;
            padding: 14px 24px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--b5) 0%, var(--b4) 100%);
            color: white;
            font-family: var(--font);
            font-size: 17px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(35,85,200,0.32), inset 0 1px 0 rgba(255,255,255,0.13);
            letter-spacing: -0.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn svg { width: 17px; height: 17px; }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(35,85,200,0.42);
        }

        .submit-btn:active { transform: translateY(0); }

        /* register link */
        .register-link {
            text-align: center;
            margin-top: 18px;
            font-size: 14.5px;
            color: var(--g4);
        }

        .register-link a {
            color: var(--b5);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }

        .register-link a:hover { color: var(--b4); text-decoration: underline; }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 820px) {
            .page { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 80px 24px 48px; }
            html, body { overflow: auto; }
        }

        @media (max-height: 720px) {
            .panel-right { padding: 68px 48px 32px; }
            .form-subtitle { margin-bottom: 20px; }
            .field { margin-bottom: 12px; }
        }

    </style>
</head>
<body>
<div class="page" id="page">

    <!-- ══ LEFT — DARK PANEL ══ -->
    <div class="panel-left" id="panel-left">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <div class="panel-content">
            <div class="panel-headline">Stay on top<br>of every<br>task.</div>
            <p class="panel-sub">A smarter way to manage your work — assign tasks, track progress, and meet every deadline with ease.</p>

            <div class="feat-list">
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    </div>
                    <span class="feat-label">Visual boards to track every task's status</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="feat-label">Set due dates and never miss a deadline</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="feat-label">Assign tasks to teammates instantly</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <span class="feat-label">Monitor progress with live status updates</span>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            © {{ date('Y') }} {{ config('app.name', 'TaskFlow') }}. All rights reserved.
        </div>
    </div>

    <!-- ══ RIGHT — FORM PANEL ══ -->
    <div class="panel-right">

        <a href="{{ url('/') }}" class="back-btn">
            <span class="back-btn-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </span>
            Back to Home
        </a>

        <div class="form-wrap">
            <div class="form-eyebrow">Welcome Back</div>
            <div class="form-title">Log in to your account</div>
            <div class="form-subtitle">Enter your credentials to pick up right where you left off.</div>

            @if (session('status'))
                <div class="session-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <!-- Email -->
                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           class="{{ $errors->has('email') ? 'is-error' : '' }}"
                           required autofocus autocomplete="username" />
                    @error('email')
                        <div class="err">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password"
                           placeholder="Enter your password"
                           class="{{ $errors->has('password') ? 'is-error' : '' }}"
                           required autocomplete="current-password" />
                    @error('password')
                        <div class="err">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn" id="login-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Log In
                </button>
            </form>

            <div class="register-link">
                Don't have an account?
                <a href="{{ route('register') }}">Create one for free</a>
            </div>
        </div>
    </div>

</div>



</body>
</html>