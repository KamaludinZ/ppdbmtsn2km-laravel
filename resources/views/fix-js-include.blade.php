{{--
    JAVASCRIPT FIX INCLUDE
    Add this to your templates with: @include('fix-js-include')
    Place it right before closing </body> tag
--}}

{{-- Define countdown function --}}
<script>
// Ensure countdown function is defined
if (typeof window.countdown === 'undefined') {
    window.countdown = function(targetDate) {
        return {
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            interval: null,

            init() {
                this.updateCountdown();
                this.interval = setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },

            updateCountdown() {
                const target = new Date(targetDate).getTime();
                const now = new Date().getTime();
                const distance = target - now;

                if (distance < 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                    return;
                }

                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        };
    };
}

// Log for debugging
console.log('Fix JS Include loaded - countdown defined');
</script>

{{-- Alpine.js from CDN --}}
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js"></script>

{{-- Fallback for Alpine if CDN fails --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof Alpine === 'undefined') {
            console.warn('Alpine.js not loaded from primary CDN, loading fallback...');
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js';
            script.defer = true;
            document.head.appendChild(script);
        } else {
            console.log('Alpine.js loaded successfully');
        }
    }, 2000);
});
</script>

{{-- Livewire Scripts if not already included --}}
@if(!isset($__livewire))
    @livewireScripts
@endif

{{-- Verification --}}
<script>
// Final verification
window.addEventListener('load', function() {
    console.log('=== JavaScript Libraries Status ===');
    console.log('Alpine.js:', typeof Alpine !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
    console.log('Livewire:', typeof Livewire !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
    console.log('Countdown:', typeof window.countdown === 'function' ? '✅ Defined' : '❌ Not defined');
    console.log('===================================');
});
</script>