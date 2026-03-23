{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Taskflow') }}</title>
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; background: #f4f5f7; color: #1a1e2e; font-family: 'Epilogue', sans-serif; }
        .tf-main-header { background: #ffffff; border-bottom: 1px solid #e2e5eb; position: sticky; top: 0; z-index: 100; }
        .tf-main-header-inner { max-width: 100%; margin: 0 auto; padding: 0; }
        .tf-page-wrap { margin-left: 240px; min-height: 100vh; transition: margin-left .28s cubic-bezier(.4,0,.2,1); }
        .tf-toggle { display: none; position: fixed; top: 1rem; left: 1rem; z-index: 300; width: 38px; height: 38px; background: #0f1729; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; color: #d6e4ff; align-items: center; justify-content: center; cursor: pointer; }
        @media (max-width: 768px) { .tf-page-wrap { margin-left: 0; } .tf-toggle { display: flex; } }
    </style>

    {{-- ↓ Page-specific styles (e.g. dashboard.css injected via @push in each view) --}}
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

    {{-- ↓ Page-specific scripts (e.g. dashboard.js injected via @push in each view) --}}
    @stack('scripts')

</body>
</html>