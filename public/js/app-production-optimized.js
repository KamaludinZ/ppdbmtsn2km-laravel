/**
 * Production JavaScript Bundle - Optimized
 * Generated: 2026-01-18 13:01:17
 */

/**
 * Countdown Function for Alpine.js
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

// Make sure the function is available globally
window.countdown = window.countdown || (function(endTime) {
    let endDate = typeof endTime === 'string' ? new Date(endTime).getTime() : endTime;
    
    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        
        init() {
            this.updateCountdown();
            this.interval = setInterval(() => {
                this.updateCountdown();
            }, 1000);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        },
        
        updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;
            
            if (distance > 0) {
                this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
            } else {
                this.days = 0;
                this.hours = 0;
                this.minutes = 0;
                this.seconds = 0;
                
                if (this.interval) {
                    clearInterval(this.interval);
                }
            }
        }
    };
});

console.log('Countdown and init functions loaded successfully');

/**
 * YouTube Embed Fix
 * Handles YouTube embeds without thumbnail errors
 */

(function() {
    'use strict';

    // Fix YouTube embed errors
    function initYouTubeEmbeds() {
        // Find all YouTube iframes
        const youtubeIframes = document.querySelectorAll('iframe[src*="youtube.com"], iframe[src*="youtu.be"]');

        youtubeIframes.forEach(function(iframe) {
            // Ensure iframe has proper attributes
            if (!iframe.hasAttribute('frameborder')) {
                iframe.setAttribute('frameborder', '0');
            }

            if (!iframe.hasAttribute('allowfullscreen')) {
                iframe.setAttribute('allowfullscreen', 'true');
            }

            // Wrap in container if not already wrapped
            if (!iframe.parentElement.classList.contains('youtube-container')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'youtube-container';
                iframe.parentNode.insertBefore(wrapper, iframe);
                wrapper.appendChild(iframe);
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initYouTubeEmbeds);
    } else {
        initYouTubeEmbeds();
    }

    // Also initialize on Livewire updates
    if (window.Livewire) {
        window.Livewire.on('contentUpdated', function() {
            initYouTubeEmbeds();
        });
    }
})();


// Prevent duplicate Alpine initialization
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        if (window.Alpine && !window.Alpine._initialized) {
            window.Alpine._initialized = true;
        }
    });

    // Fix Livewire loading states
    if (window.Livewire) {
        window.Livewire.on('load', function() {
            // Ensure Alpine is ready
            if (window.Alpine) {
                window.Alpine.start();
            }
        });
    }
})();
