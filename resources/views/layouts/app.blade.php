{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(View::hasSection('title'))
            @yield('title') |
        @endif
        {{ $siteSettings['app_name'] ?? config('app.name') }}
    </title>

    @if(!empty($siteSettings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $siteSettings['app_favicon']) }}">
    @endif

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- White label brand color — overrides CSS variables across all pages --}}
    <style>
        :root {
            --c-blue:    {{ $siteSettings['brand_color'] ?? '#2d52c4' }};
            --c-navy:    {{ $siteSettings['brand_color'] ?? '#1b2b5e' }};
            --blue:      {{ $siteSettings['brand_color'] ?? '#2d52c4' }};
            --navy:      {{ $siteSettings['brand_color'] ?? '#1b2b5e' }};
            --sb-active: {{ $siteSettings['brand_color'] ?? '#1e3a6e' }};
            --sb-accent: {{ $siteSettings['brand_color'] ?? '#4f83ff' }};
            --sb-badge:  {{ $siteSettings['brand_color'] ?? '#3b6be8' }};
        }
        .tf-nav-item.active {
            background: var(--sb-active) !important;
            opacity: 0.9;
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>

    {{-- Page-specific styles injected via @push('styles') in each view --}}
    @stack('styles')
</head>
<body>

    @auth
        <button class="tf-toggle" onclick="tfToggle()" aria-label="Toggle menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
        @include('components.sidebar')
    @endauth

    <div class="{{ auth()->check() ? 'tf-page-wrap' : '' }}">

        @isset($header)
            <header class="tf-main-header">
                <div class="tf-main-header-inner">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>{{ $slot }}</main>

    </div>

    {{-- Page-specific scripts injected via @push('scripts') in each view --}}
    @stack('scripts')

</body>
</html>