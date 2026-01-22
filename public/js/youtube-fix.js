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