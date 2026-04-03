<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSettings['app_name'] ?? 'ProductivityDaily' }}</title>

    @if(!empty($siteSettings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $siteSettings['app_favicon']) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Brand color pulled from DB — falls back to default blue */
            --brand:  {{ $siteSettings['brand_color'] ?? '#2355c8' }};
            --brand2: {{ $siteSettings['brand_color'] ?? '#3b72f0' }};

            --b9: #07102a;
            --b7: #0f2254;
            --b6: #1a3a8a;
            --b5: var(--brand);
            --b4: var(--brand2);
            --b3: #6b9bf5;
            --b2: #a8c4fc;
            --b1: #d8e8ff;
            --b0: #eef4ff;
            --g9: #111827;
            --g7: #1f2937;
            --g6: #374151;
            --g5: #4b5563;
            --g4: #6b7280;
            --g3: #9ca3af;
            --g2: #d1d5db;
            --g1: #f3f4f6;
            --g0: #f9fafb;
            --white: #ffffff;
            --font: 'Bricolage Grotesque', sans-serif;
            --body: 'DM Sans', sans-serif;
        }

        html, body { height: 100%; overflow: hidden; }
        body { font-family: var(--body); background: var(--white); color: var(--g9); -webkit-font-smoothing: antialiased; }

        /* ══ PAGE ══ */
        .page {
            height: 100vh; width: 100vw;
            display: flex; flex-direction: column;
            overflow: hidden;
            background: linear-gradient(135deg, #e8f0ff 0%, #d0e2ff 50%, #bdd4ff 100%);
        }

        /* ══ NAV ══ */
        nav {
            position: absolute; top: 0; left: 0; right: 0;
            padding: 1.6rem 3rem;
            display: flex; align-items: center;
            z-index: 10;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }

        .nav-logo {
            height: 32px; width: auto; object-fit: contain;
            filter: drop-shadow(0 1px 3px rgba(0,0,0,0.12));
        }

        /* Default icon when no logo uploaded */
        .nav-logo-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--brand);
            display: flex; align-items: center; justify-content: center;
            color: #fff; flex-shrink: 0;
        }

        .nav-name {
            font-family: var(--font);
            font-size: 19px; font-weight: 800;
            color: var(--g9); letter-spacing: -0.3px;
        }

        /* ══ MAIN SPLIT ══ */
        .main {
            flex: 1; display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        /* ══ LEFT ══ */
        .left {
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 56px 48px; position: relative;
            animation: fadeLeft 0.7s 0.1s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .left::before {
            content: ''; position: absolute;
            top: -120px; left: -120px;
            width: 480px; height: 480px; border-radius: 50%;
            background: radial-gradient(circle, rgba(59,114,240,0.10) 0%, transparent 65%);
            pointer-events: none;
        }

        .left-content { width: 100%; max-width: 480px; }
        
        /* ══ HERO BRAND (White Label) ══ */
        .hero-brand {
            display: flex; 
            align-items: center; 
            gap: 16px;
        }

        .hero-logo {
            height: 200px; /* Makes the logo nice and prominent */
            width: auto; 
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.08));
        }

        .hero-name {
            font-family: var(--font);
            font-size: 50px; 
            font-weight: 800;
            color: var(--g9); 
            letter-spacing: -1.2px;
            line-height: 1;
        }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 7px;
            font-family: var(--body); font-size: 12px; font-weight: 500;
            color: var(--brand); letter-spacing: 0.08em;
            text-transform: uppercase; margin-bottom: 16px;
        }

        .eyebrow-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--brand2);
            animation: pulse 2.4s ease-in-out infinite;
        }

        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

        .headline {
            font-family: var(--font);
            font-size: clamp(32px, 3.5vw, 38px);
            font-weight: 800; line-height: 1.04;
            letter-spacing: -2px; color: var(--g9);
            margin-bottom: 20px;
        }

        .headline span { color: var(--brand); }

        .subtext {
            font-size: 16px; line-height: 1.7;
            color: var(--g6); max-width: 420px;
            margin-bottom: 36px; font-weight: 500;
        }

        /* ══ BUTTONS ══ */
        .auth-buttons { display: flex; flex-direction: column; gap: 13px; max-width: 340px; }

        .btn {
            display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 17px 28px; border-radius: 13px;
            font-family: var(--font); font-size: 16px; font-weight: 700;
            text-decoration: none; cursor: pointer; border: none;
            transition: all 0.2s ease; letter-spacing: -0.2px;
        }

        .btn svg { width: 19px; height: 19px; flex-shrink: 0; }

        .btn-register {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand2) 100%);
            color: white;
            box-shadow: 0 5px 18px rgba(35,85,200,0.35), inset 0 1px 0 rgba(255,255,255,0.14);
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(35,85,200,0.44); }

        .btn-login {
            background: var(--white); color: var(--g7);
            border: 2px solid var(--g2);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .btn-login:hover { border-color: var(--b3); color: var(--brand); background: var(--b0); transform: translateY(-2px); }

        .btn-dashboard {
            background: linear-gradient(135deg, var(--g9), var(--b7));
            color: white; box-shadow: 0 5px 18px rgba(10,15,40,0.25);
        }
        .btn-dashboard:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(10,15,40,0.35); }

        .dot-row { display: flex; gap: 8px; margin-top: 40px; }
        .dot-row span { width: 8px; height: 8px; border-radius: 50%; }

        /* ══ RIGHT ══ */
        .right {
            display: flex; align-items: center; justify-content: center;
            padding: 36px 48px 36px 24px;
            position: relative; overflow: hidden;
            animation: fadeRight 0.7s 0.18s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeRight {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .gear { position: absolute; border-radius: 50%; border: 2px dashed rgba(59,114,240,0.08); pointer-events: none; }
        .gear-1 { width: 220px; height: 220px; top: -40px; right: -40px; }
        .gear-2 { width: 140px; height: 140px; bottom: 30px; left: 20px; animation: spin 30s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .right-bg-circle {
            position: absolute; width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(59,114,240,0.06) 0%, transparent 65%);
            top: 50%; left: 50%; transform: translate(-50%,-50%);
            pointer-events: none;
        }

        /* ══ MOCKUP ══ */
        .mockup { position: relative; z-index: 2; width: 100%; max-width: 400px; }

        .mockup-window {
            background: white; border-radius: 16px;
            border: 1px solid var(--g2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06), 0 24px 56px rgba(35,85,200,0.10);
            overflow: hidden;
        }

        .win-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; background: var(--g9); gap: 10px;
        }
        .win-dots { display: flex; gap: 5px; }
        .win-dots i { width: 9px; height: 9px; border-radius: 50%; display: block; }
        .win-dots i:nth-child(1) { background: #ff5f57; }
        .win-dots i:nth-child(2) { background: #ffbd2e; }
        .win-dots i:nth-child(3) { background: #28c840; }
        .win-title { font-family: var(--font); font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.85); letter-spacing: 0.04em; display: flex; align-items: center; gap: 7px; }
        .win-title svg { width: 14px; height: 14px; color: var(--b3); }
        .win-add { display: flex; align-items: center; gap: 5px; background: var(--brand); color: white; font-family: var(--font); font-size: 11px; font-weight: 600; padding: 5px 11px; border-radius: 7px; }
        .win-add svg { width: 11px; height: 11px; }

        .task-list { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; }
        .task-row { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 10px; background: var(--g0); border: 1px solid var(--g2); }
        .task-check { width: 18px; height: 18px; border-radius: 5px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .task-check.done { background: var(--brand); }
        .task-check.done svg { width: 10px; height: 10px; color: white; }
        .task-check.todo { border: 2px solid var(--g2); background: white; }
        .task-info { flex: 1; min-width: 0; }
        .task-name { font-size: 12.5px; font-weight: 600; color: var(--g9); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .task-name.strikethrough { text-decoration: line-through; color: var(--g3); }
        .task-deadline { font-size: 10.5px; color: var(--g3); font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .task-deadline svg { width: 10px; height: 10px; }
        .task-deadline.overdue { color: #e11d48; }
        .task-badge { flex-shrink: 0; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.03em; }
        .badge-blue   { background: rgba(59,114,240,0.1); color: var(--brand); }
        .badge-gray   { background: var(--g1); color: var(--g5); }
        .badge-green  { background: rgba(34,197,94,0.1); color: #15803d; }
        .badge-amber  { background: rgba(245,158,11,0.1); color: #b45309; }

        .mockup-calendar { margin: 0 16px 16px; background: var(--g0); border: 1px solid var(--g2); border-radius: 12px; padding: 12px 14px; }
        .cal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .cal-month { font-family: var(--font); font-size: 12px; font-weight: 700; color: var(--g9); }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; }
        .cal-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 500; border-radius: 5px; color: var(--g4); }
        .cal-day.header { color: var(--g3); font-weight: 600; font-size: 9px; letter-spacing: 0.04em; }
        .cal-day.today  { background: var(--brand); color: white; font-weight: 700; }
        .cal-day.marked { background: var(--b1); color: var(--brand); font-weight: 600; }
        .cal-day.done-mark { position: relative; }
        .cal-day.done-mark::after { content: ''; position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); width: 3px; height: 3px; border-radius: 50%; background: var(--brand2); }

        /* floating stat */
        .stat-float {
            position: absolute; bottom: 40px; left: -28px;
            background: white; border: 1px solid var(--g2);
            border-radius: 14px; padding: 14px 18px;
            box-shadow: 0 8px 24px rgba(35,85,200,0.12);
            animation: floatCard 3.5s ease-in-out infinite; z-index: 3;
        }
        @keyframes floatCard { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .stat-float-label { font-size: 10.5px; color: var(--g3); font-weight: 500; margin-bottom: 3px; }
        .stat-float-value { font-family: var(--font); font-size: 22px; font-weight: 800; color: var(--brand); letter-spacing: -0.8px; line-height: 1; }
        .stat-float-sub { font-size: 10px; color: var(--g3); margin-top: 2px; }

        /* ══ FOOTER ══ */
        .footer {
            height: 44px; display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: var(--g5); flex-shrink: 0;
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 860px) {
            .main { grid-template-columns: 1fr; }
            .right { display: none; }
            .left { padding: 100px 28px 40px; align-items: center; text-align: center; }
            .subtext { margin-left: auto; margin-right: auto; }
            .auth-buttons { max-width: 100%; }
            .dot-row { justify-content: center; }
            html, body { overflow: auto; }
            .page { height: auto; min-height: 100vh; overflow: auto; }
        }
    </style>
</head>

<body>
<div class="page">



    <div class="main">

{{-- ── LEFT: hero copy + buttons ── --}}
        <div class="left">
            <div class="left-content">

                {{-- Hero Brand / White Label Title --}}
                <div class="hero-brand">
                    @if(!empty($siteSettings['app_logo']))
                        <img class="hero-logo" src="{{ asset('storage/' . $siteSettings['app_logo']) }}" alt="{{ $siteSettings['app_name'] ?? 'Logo' }}">
                    @else
                        {{-- Scaled up fallback icon if no logo is uploaded --}}
                        <div class="nav-logo-icon" style="width: 48px; height: 48px; border-radius: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity=".9"/>
                                <path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".6"/>
                                <path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" opacity=".8"/>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Company Title --}}
                    <span class="hero-name">{{ $siteSettings['app_name'] ?? 'ProductivityDaily' }}</span>
                </div>

                {{-- Eyebrow --}}
                <div class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    {{ $siteSettings['app_eyebrow'] ?? 'Productivity · Simplified' }}
                </div>
            

        {{-- Headline --}}
        @php
            $headline = $siteSettings['app_headline'] ?? 'Streamline your Workflows.';
            // Split on the last word to colorize it, or just show as-is
            $words    = explode(' ', $headline);
            $last     = array_pop($words);
            $rest     = implode(' ', $words);
        @endphp
        <h1 class="headline">
            {{ $rest }}<br><span>{{ $last }}</span>
        </h1>

        {{-- Subtext / Description --}}
        <p class="subtext">
            {{ $siteSettings['app_description'] ?? 'Organize your work, collaborate with your team, and hit every deadline — all from one clean, focused workspace.' }}
        </p>

                @auth
                    <div class="auth-buttons">
                        <a href="{{ url('/dashboard') }}" class="btn btn-dashboard">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                            Open Dashboard
                        </a>
                    </div>
                @else
                    <div class="auth-buttons">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                Log In to Your Account
                            </a>
                        @endif
                    </div>
                @endauth

                <div class="dot-row">
                    <span style="background:var(--brand);width:22px;border-radius:4px;height:8px;"></span>
                    <span style="background:var(--b2)"></span>
                    <span style="background:var(--g2)"></span>
                    <span style="background:var(--g2)"></span>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: mockup illustration ── --}}
        <div class="right">
            <div class="right-bg-circle"></div>
            <div class="gear gear-1"></div>
            <div class="gear gear-2"></div>

            <div class="mockup">
                <div class="stat-float">
                    <div class="stat-float-label">Tasks done today</div>
                    <div class="stat-float-value">8 / 12</div>
                    <div class="stat-float-sub">↑ 3 more than yesterday</div>
                </div>

                <div class="mockup-window">
                    <div class="win-bar">
                        <div class="win-dots"><i></i><i></i><i></i></div>
                        <div class="win-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            {{ strtoupper($siteSettings['app_name'] ?? 'TASKS') }}
                        </div>
                        <div class="win-add">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            ADD TASK
                        </div>
                    </div>

                    <div class="task-list">
                        <div class="task-row">
                            <div class="task-check done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="task-info">
                                <div class="task-name strikethrough">Design system setup</div>
                                <div class="task-deadline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Deadline: Mar 15</div>
                            </div>
                            <span class="task-badge badge-green">Done</span>
                        </div>
                        <div class="task-row">
                            <div class="task-check done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="task-info">
                                <div class="task-name strikethrough">API endpoint review</div>
                                <div class="task-deadline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Deadline: Mar 18</div>
                            </div>
                            <span class="task-badge badge-green">Done</span>
                        </div>
                        <div class="task-row">
                            <div class="task-check todo"></div>
                            <div class="task-info">
                                <div class="task-name">Update landing page copy</div>
                                <div class="task-deadline overdue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Deadline: Mar 19 — Overdue!</div>
                            </div>
                            <span class="task-badge badge-amber">Urgent</span>
                        </div>
                        <div class="task-row">
                            <div class="task-check todo"></div>
                            <div class="task-info">
                                <div class="task-name">QA testing sprint #4</div>
                                <div class="task-deadline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Deadline: Mar 25</div>
                            </div>
                            <span class="task-badge badge-blue">In Progress</span>
                        </div>
                        <div class="task-row">
                            <div class="task-check todo"></div>
                            <div class="task-info">
                                <div class="task-name">Stakeholder presentation</div>
                                <div class="task-deadline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Deadline: Mar 28</div>
                            </div>
                            <span class="task-badge badge-gray">Pending</span>
                        </div>
                    </div>

                    <div class="mockup-calendar">
                        <div class="cal-header">
                            <span class="cal-month">March 2025</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--g3)"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                        <div class="cal-grid">
                            <div class="cal-day header">Mo</div><div class="cal-day header">Tu</div><div class="cal-day header">We</div><div class="cal-day header">Th</div><div class="cal-day header">Fr</div><div class="cal-day header">Sa</div><div class="cal-day header">Su</div>
                            <div class="cal-day"></div><div class="cal-day"></div><div class="cal-day"></div><div class="cal-day"></div><div class="cal-day">1</div><div class="cal-day">2</div><div class="cal-day">3</div>
                            <div class="cal-day">4</div><div class="cal-day">5</div><div class="cal-day">6</div><div class="cal-day">7</div><div class="cal-day done-mark">8</div><div class="cal-day">9</div><div class="cal-day">10</div>
                            <div class="cal-day">11</div><div class="cal-day">12</div><div class="cal-day done-mark">13</div><div class="cal-day">14</div><div class="cal-day marked">15</div><div class="cal-day">16</div><div class="cal-day">17</div>
                            <div class="cal-day done-mark">18</div><div class="cal-day today">19</div><div class="cal-day">20</div><div class="cal-day">21</div><div class="cal-day">22</div><div class="cal-day">23</div><div class="cal-day">24</div>
                            <div class="cal-day marked">25</div><div class="cal-day">26</div><div class="cal-day">27</div><div class="cal-day marked">28</div><div class="cal-day">29</div><div class="cal-day">30</div><div class="cal-day">31</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /.main --}}

    <div class="footer">
        © {{ date('Y') }} {{ $siteSettings['app_name'] ?? config('app.name') }} &nbsp;·&nbsp; All rights reserved.
    </div>

</div>
</body>
</html>