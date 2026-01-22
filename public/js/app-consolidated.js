/**
 * Consolidated Application JavaScript
 * Prevents duplicate initializations
 */

// Global initialization flag
window.appInitialized = window.appInitialized || false;

if (!window.appInitialized) {
    window.appInitialized = true;

    console.log('PPDB Application Loaded');

    // Initialize only once
    document.addEventListener('DOMContentLoaded', function() {
        // Single Alpine check
        if (window.Alpine && !window.Alpine._isInitialized) {
            window.Alpine._isInitialized = true;
            console.log('Alpine.js ready');
        }

        // Single Livewire check
        if (window.Livewire && !window.Livewire._isInitialized) {
            window.Livewire._isInitialized = true;
            console.log('Livewire ready');
        }

        // Single Axios setup
        if (window.axios && !window.axios._configured) {
            window.axios._configured = true;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
            console.log('Axios configured');
        }
    });
}