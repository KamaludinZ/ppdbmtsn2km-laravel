/**
 * Production JavaScript Bundle
 * Generated: 2026-01-18 08:54:09
 * No ES6 modules - ready for direct browser use
 */

// Axios Configuration
if (typeof axios !== 'undefined') {
    window.axios = axios;
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    
    // CSRF Token
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
}

// Countdown Function for Alpine.js
window.countdown = function(targetDate) {
    return {
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
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

// App Initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('PPDB Application Loaded - Production Mode');
    
    // Initialize any custom components here
    
});
