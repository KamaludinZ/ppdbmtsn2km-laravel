{{-- Smart Asset Loader Component --}}
{{-- Automatically switches between Vite (dev) and static assets (production) --}}
@php
    $appEnv = app()->environment();
    $isLocal = $appEnv === 'local';
    $isProduction = $appEnv === 'production';

    // Check if hot file exists (for debug info)
    $hotFileExists = file_exists(public_path('hot'));

    // Smart detection: Use Vite ONLY if:
    // 1. We are in local environment
    // 2. Vite dev server is running (hot file exists)
    // 3. User hasn't explicitly disabled it
    $useVite = $isLocal &&
               $hotFileExists &&
               config('assets.use_vite_in_dev', true);

    // For production or when Vite is not available
    $useProductionAssets = $isProduction || !$useVite;
@endphp

{{-- Preconnect for performance --}}
<link rel="preconnect" href="https://fonts.bunny.net">
@if(!$useVite)
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
@endif

{{-- Fonts (always from CDN for consistency) --}}
<link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

@if($useVite)
    {{-- Development Mode with Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Production Mode with Static Assets --}}

    {{-- CSS Loading - Full Compiled Tailwind (No CDN Dependency) --}}
    @if(file_exists(public_path('css/app-production-full.css')))
        <link rel="stylesheet" href="{{ asset('css/app-production-full.css') }}?v={{ filemtime(public_path('css/app-production-full.css')) }}">
    @elseif($isProduction && file_exists(public_path('css/app-production-optimized-clean.css')))
        <link rel="stylesheet" href="{{ asset('css/app-production-optimized-clean.css') }}?v={{ filemtime(public_path('css/app-production-optimized-clean.css')) }}">
    @elseif($isProduction && file_exists(public_path('css/app-production-optimized.css')))
        <link rel="stylesheet" href="{{ asset('css/app-production-optimized.css') }}?v={{ filemtime(public_path('css/app-production-optimized.css')) }}">
    @elseif($isProduction && file_exists(public_path('css/app-production-final.css')))
        <link rel="stylesheet" href="{{ asset('css/app-production-final.css') }}?v={{ filemtime(public_path('css/app-production-final.css')) }}">
    @elseif(file_exists(public_path('css/app-production.css')))
        <link rel="stylesheet" href="{{ asset('css/app-production.css') }}?v={{ filemtime(public_path('css/app-production.css')) }}">
    @elseif(file_exists(public_path('css/app.css')))
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @else
        {{-- Last resort: Use build assets from Vite --}}
        @if(file_exists(public_path('build/assets')))
            @php
                $manifestPath = public_path('build/.vite/manifest.json');
                if (file_exists($manifestPath)) {
                    $manifest = json_decode(file_get_contents($manifestPath), true);
                    $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                    if ($cssFile) {
                        echo '<link rel="stylesheet" href="' . asset("build/{$cssFile}") . '">';
                    }
                }
            @endphp
        @endif
    @endif

    {{-- Additional CSS fixes if they exist --}}
    @if(file_exists(public_path('css/display-fix.css')))
        <link rel="stylesheet" href="{{ asset('css/display-fix.css') }}">
    @endif

    {{-- Toggle Switch CSS --}}
    @if(file_exists(public_path('css/toggle-switch.css')))
        <link rel="stylesheet" href="{{ asset('css/toggle-switch.css') }}">
    @endif

    {{-- Axios - Cloudflare CDN for better performance --}}
    @if(!isset($GLOBALS['axios_loaded']))
        @php $GLOBALS['axios_loaded'] = true; @endphp
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js" integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endif

    {{-- JavaScript Loading --}}
    @if(file_exists(public_path('js/app-consolidated.js')))
        <script src="{{ asset('js/app-consolidated.js') }}?v={{ filemtime(public_path('js/app-consolidated.js')) }}" defer></script>
    @elseif($isProduction && file_exists(public_path('js/app-production-optimized.js')))
        <script src="{{ asset('js/app-production-optimized.js') }}?v={{ filemtime(public_path('js/app-production-optimized.js')) }}" defer></script>
    @elseif($isProduction && file_exists(public_path('js/app-production-final.js')))
        <script src="{{ asset('js/app-production-final.js') }}?v={{ filemtime(public_path('js/app-production-final.js')) }}" defer></script>
    @elseif(file_exists(public_path('js/app.js')))
        <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @endif

    {{-- Countdown function --}}
    @if(file_exists(public_path('js/countdown-function.js')))
        <script src="{{ asset('js/countdown-function.js') }}"></script>
    @endif

    {{-- Enhanced YouTube Fix --}}
    @if(file_exists(public_path('js/youtube-fix-enhanced.js')))
        <script src="{{ asset('js/youtube-fix-enhanced.js') }}"></script>
    @elseif(file_exists(public_path('js/youtube-fix.js')))
        <script src="{{ asset('js/youtube-fix.js') }}"></script>
    @endif

    {{-- Image Fallback Handler --}}
    @if(file_exists(public_path('js/image-fallback.js')))
        <script src="{{ asset('js/image-fallback.js') }}" defer></script>
    @endif
@endif

{{-- Livewire Styles (always needed) --}}
@livewireStyles

{{-- Critical CSS --}}
<style>
    [x-cloak] { display: none !important; }
    [wire\:loading] { display: inline-block; }
    [wire\:loading].hidden { display: none !important; }
    body { font-family: 'Figtree', system-ui, sans-serif; }

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

{{-- Development Helper --}}
@if($isLocal && !$isProduction)
    @php
        $debugInfo = [
            'environment' => $appEnv,
            'useVite' => $useVite,
            'hotFileExists' => $hotFileExists,
            'productionAssets' => $useProductionAssets
        ];
    @endphp
    <script>
        console.log('Asset Loader Mode:', @json($debugInfo));
    </script>
@endif