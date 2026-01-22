{{-- Production Optimized Asset Loader --}}
{{-- Prevents duplicate loading and optimizes for production --}}
@php
    $appEnv = app()->environment();
    $isProduction = $appEnv === 'production';
    $isLocal = $appEnv === 'local';

    // Check if we should use production assets
    $useProductionAssets = $isProduction || config('assets.force_production', false);
@endphp

{{-- Fonts - Single preconnect --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet">

{{-- CSS Loading - Production Optimized --}}
@if($useProductionAssets && file_exists(public_path('css/app-production-final.css')))
    <link rel="stylesheet" href="{{ asset('css/app-production-final.css') }}?v={{ filemtime(public_path('css/app-production-final.css')) }}">
@elseif(file_exists(public_path('css/app-production.css')))
    <link rel="stylesheet" href="{{ asset('css/app-production.css') }}?v={{ filemtime(public_path('css/app-production.css')) }}">
@else
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
@endif

{{-- Livewire Styles - MUST come before Alpine --}}
@livewireStyles

{{-- Alpine.js - Single instance only --}}
@if(!isset($GLOBALS['alpine_loaded']))
    @php $GLOBALS['alpine_loaded'] = true; @endphp
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
@endif

{{-- Axios - Only if needed --}}
@if(!isset($GLOBALS['axios_loaded']))
    @php $GLOBALS['axios_loaded'] = true; @endphp
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>
@endif

{{-- Critical inline styles --}}
<style>
    /* Prevent FOUC */
    [x-cloak] { display: none !important; }

    /* Fix Livewire loading states */
    [wire\:loading] { opacity: 0.5; }
    [wire\:loading].block { display: block !important; }
    [wire\:loading].inline-block { display: inline-block !important; }
    [wire\:loading].flex { display: flex !important; }
    [wire\:loading].table { display: table !important; }
    [wire\:loading].grid { display: grid !important; }
    [wire\:loading].hidden { display: none !important; }

    /* Base font */
    body {
        font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Fix YouTube embed responsive */
    .youtube-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .youtube-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>

{{-- JavaScript Loading - Optimized order --}}
@if(file_exists(public_path('js/countdown-function.js')))
    <script src="{{ asset('js/countdown-function.js') }}"></script>
@endif

@if($useProductionAssets && file_exists(public_path('js/app-production-final.js')))
    <script src="{{ asset('js/app-production-final.js') }}?v={{ filemtime(public_path('js/app-production-final.js')) }}" defer></script>
@elseif(file_exists(public_path('js/app.js')))
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
@endif

{{-- Prevent duplicate Alpine initialization --}}
<script>
    // Ensure Alpine is only initialized once
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Alpine && !window.Alpine._initialized) {
            window.Alpine._initialized = true;
        }
    });

    // Prevent console errors in production
    @if($isProduction)
    if (typeof console !== 'undefined') {
        console.log = function() {};
        console.warn = function() {};
        console.info = function() {};
    }
    @endif
</script>