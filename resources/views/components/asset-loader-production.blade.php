{{-- Production Asset Loader Component --}}
{{-- Optimized untuk deployment tanpa Vite --}}
@php
    $isLocal = app()->environment('local');
    $useVite = $isLocal && env('USE_VITE_IN_DEV', true);

    // Read manifest for production
    $cssPath = 'build/assets/app-DPXrAvEv.css'; // fallback
    $jsPath = 'build/assets/app-C8iUzNnm.js';   // fallback

    if (!$useVite) {
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            if (isset($manifest['resources/css/app.css']['file'])) {
                $cssPath = 'build/' . $manifest['resources/css/app.css']['file'];
            }
            if (isset($manifest['resources/js/app.js']['file'])) {
                $jsPath = 'build/' . $manifest['resources/js/app.js']['file'];
            }
        }
    }
@endphp

{{-- Preconnect untuk performa --}}
<link rel="preconnect" href="https://fonts.bunny.net">

{{-- Font Loading --}}
<link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet">

{{-- CSS & JavaScript Loading --}}
@if($useVite)
    {{-- Development dengan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Production - Use built assets --}}
    <link rel="stylesheet" href="{{ asset($cssPath) }}">
    <script type="module" src="{{ asset($jsPath) }}"></script>
@endif

{{-- Alpine.js untuk production (sudah included dalam build) --}}

{{-- Livewire Styles --}}
@livewireStyles

{{-- Critical CSS --}}
<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Figtree', system-ui, sans-serif; }
</style>

{{-- Countdown function untuk Alpine.js --}}
<script>
    // Global countdown function
    window.countdown = function(targetDate) {
        return {
            days: '00', hours: '00', minutes: '00', seconds: '00',
            init() {
                this.updateCountdown();
                setInterval(() => { this.updateCountdown(); }, 1000);
            },
            updateCountdown() {
                const target = new Date(targetDate).getTime();
                const now = new Date().getTime();
                const distance = target - now;

                if (distance < 0) {
                    this.days = this.hours = this.minutes = this.seconds = '00';
                    return;
                }

                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        };
    };
</script>