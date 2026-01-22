/**
 * Countdown Component Fix
 * This file provides the missing countdown function that's causing Alpine.js errors
 */

// Define the countdown function globally to fix Alpine.js errors
if (typeof window.countdown !== 'function') {
    window.countdown = function(endTime) {
        // Parse the end time
        let endDate = typeof endTime === 'string' ? new Date(endTime).getTime() : endTime;
        
        return {
            // Reactive properties
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            
            // Initialize the countdown
            init() {
                this.updateCountdown();
                // Update every second
                this.interval = setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },
            
            // Clean up interval when component is destroyed
            destroy() {
                if (this.interval) {
                    clearInterval(this.interval);
                }
            },
            
            // Update countdown values
            updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;
                
                if (distance > 0) {
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                } else {
                    // Countdown finished
                    this.days = 0;
                    this.hours = 0;
                    this.minutes = 0;
                    this.seconds = 0;
                    
                    // Optionally clear the interval when finished
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                }
            }
        };
    };
}

// Also define init function if needed
if (typeof window.init !== 'function') {
    window.init = function() {
        // Generic init function for Alpine components
        // This can be customized based on specific needs
    };
}

console.log('Countdown and init functions loaded successfully');