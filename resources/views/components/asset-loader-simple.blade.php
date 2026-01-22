{{-- Simple Asset Loader - For Error Pages & Special Cases --}}
{{-- Always uses static assets, no Vite --}}

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet">

<!-- CSS -->
@if(app()->environment('production') && file_exists(public_path('css/app-production-optimized.css')))
    <link rel="stylesheet" href="{{ asset('css/app-production-optimized.css') }}">
@elseif(file_exists(public_path('css/app-production-optimized.css')))
    <link rel="stylesheet" href="{{ asset('css/app-production-optimized.css') }}">
@elseif(file_exists(public_path('css/app-production.css')))
    <link rel="stylesheet" href="{{ asset('css/app-production.css') }}">
@elseif(file_exists(public_path('css/app.css')))
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endif

{{-- Alpine.js - Single instance only --}}
@if(!isset($GLOBALS['alpine_loaded']))
    @php $GLOBALS['alpine_loaded'] = true; @endphp
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
@endif

{{-- Axios - Only if needed (using Cloudflare CDN) --}}
@if(!isset($GLOBALS['axios_loaded']))
    @php $GLOBALS['axios_loaded'] = true; @endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js" integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endif

<!-- Livewire Styles -->
@livewireStyles

<!-- Critical CSS -->
<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Figtree', system-ui, sans-serif; }

    /* Fix YouTube responsive */
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

<!-- JavaScript -->
@if(file_exists(public_path('js/app-production-optimized.js')))
    <script src="{{ asset('js/app-production-optimized.js') }}" defer></script>
@elseif(file_exists(public_path('js/app.js')))
    <script src="{{ asset('js/app.js') }}" defer></script>
@endif

@if(file_exists(public_path('js/youtube-fix.js')))
    <script src="{{ asset('js/youtube-fix.js') }}"></script>
@endif

{{-- Image Fallback Handler --}}
@if(file_exists(public_path('js/image-fallback.js')))
    <script src="{{ asset('js/image-fallback.js') }}" defer></script>
@endif