<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — {{ config('app.name', 'TaskFlow') }}</title>

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
        }

        /* ── PAGE GRID ── */
        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 420px 1fr;
        }

        /* ══════════════════════════
           LEFT PANEL
        ══════════════════════════ */
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

        /* lock icon circle */
        .lock-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: rgba(59,114,240,0.18);
            border: 1px solid rgba(107,155,245,0.30);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 28px;
        }

        .lock-circle svg { width: 32px; height: 32px; color: var(--b3); }

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
            margin-bottom: 36px;
        }

        /* steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .step-num {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: rgba(59,114,240,0.28);
            border: 1px solid rgba(107,155,245,0.40);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-family: var(--font);
            font-size: 12px;
            font-weight: 700;
            color: var(--b3);
            margin-top: 1px;
        }

        .step-text {
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            line-height: 1.55;
        }

        .step-text strong {
            color: rgba(255,255,255,0.90);
            font-weight: 600;
        }

        .panel-footer {
            position: relative; z-index: 2;
            font-size: 13px;
            color: rgba(255,255,255,0.22);
        }

        /* ══════════════════════════
           RIGHT PANEL
        ══════════════════════════ */
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

        /* back button */
        .back-btn {
            position: absolute;
            top: 28px; left: 32px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
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

        /* form wrap */
        .form-wrap {
            width: 100%;
            max-width: 380px;
            animation: rise 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* icon at top of form */
        .form-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: var(--b0);
            border: 1.5px solid var(--b1);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
            color: var(--b5);
        }

        .form-icon svg { width: 26px; height: 26px; }

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
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 15.5px;
            color: var(--g4);
            line-height: 1.65;
            margin-bottom: 28px;
        }

        /* session status — success */
        .session-status {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 14px;
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .session-status svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }

        /* field */
        .field { margin-bottom: 20px; }

        label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--g7);
            margin-bottom: 6px;
            letter-spacing: -0.1px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
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

        .err {
            font-size: 12px;
            color: var(--red);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .err svg { width: 12px; height: 12px; flex-shrink: 0; }

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

        .submit-btn svg { width: 18px; height: 18px; }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(35,85,200,0.42);
        }

        .submit-btn:active { transform: translateY(0); }

        /* back to login */
        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14.5px;
            color: var(--g4);
        }

        .back-to-login a {
            color: var(--b5);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-to-login a:hover { color: var(--b4); text-decoration: underline; }
        .back-to-login a svg { width: 14px; height: 14px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 820px) {
            .page { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 80px 24px 48px; }
        }

        @media (max-height: 720px) {
            .panel-right { padding: 68px 48px 32px; }
            .form-subtitle { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- ══ LEFT PANEL ══ -->
    <div class="panel-left">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <div class="panel-content">

            <div class="lock-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <div class="panel-headline">Forgot your<br>password?</div>
            <p class="panel-sub">No worries — it happens. We'll send a secure reset link straight to your inbox.</p>

            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text"><strong>Enter your email</strong> address in the form and hit send.</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text"><strong>Check your inbox</strong> for a password reset link from us.</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text"><strong>Click the link</strong> and choose a new secure password.</div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            © {{ date('Y') }} {{ config('app.name', 'TaskFlow') }}. All rights reserved.
        </div>
    </div>

    <!-- ══ RIGHT PANEL ══ -->
    <div class="panel-right">

        <a href="{{ route('login') }}" class="back-btn">
            <span class="back-btn-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </span>
            Back to Log In
        </a>

        <div class="form-wrap">

            <div class="form-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <div class="form-eyebrow">Account Recovery</div>
            <div class="form-title">Reset password</div>
            <div class="form-subtitle">Enter your email and we'll send you a link to get back into your account.</div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="session-status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           class="{{ $errors->has('email') ? 'is-error' : '' }}"
                           required autofocus />
                    @error('email')
                        <div class="err">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Send Reset Link
                </button>
            </form>

            <div class="back-to-login">
                Remembered your password?
                <a href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Log in instead
                </a>
            </div>
        </div>

    </div>
</div>
</body>
</html>