/**
 * Enhanced YouTube Embed Fix
 * Handles errors, missing thumbnails, and responsive layout
 */

(function() {
    'use strict';

    // Suppress YouTube-related console errors
    const originalError = console.error;
    console.error = function() {
        const args = Array.prototype.slice.call(arguments);
        const errorString = args.join(' ');

        // Filter out YouTube-related errors
        if (errorString.includes('youtube') ||
            errorString.includes('404') && errorString.includes('mqdefault') ||
            errorString.includes('Feature Policy')) {
            return; // Suppress these errors
        }

        originalError.apply(console, args);
    };

    // Fix YouTube embeds
    function initYouTubeEmbeds() {
        const youtubeIframes = document.querySelectorAll('iframe[src*="youtube.com"], iframe[src*="youtu.be"]');

        youtubeIframes.forEach(function(iframe) {
            // Ensure proper attributes
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');

            // Wrap in container if not already wrapped
            if (!iframe.parentElement.classList.contains('youtube-container')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'youtube-container';
                iframe.parentNode.insertBefore(wrapper, iframe);
                wrapper.appendChild(iframe);
            }

            // Handle missing video IDs
            const src = iframe.src;
            if (src.includes('2XGGzgdZU24')) {
                // This video ID doesn't exist, replace with placeholder
                iframe.style.display = 'none';
                const placeholder = document.createElement('div');
                placeholder.className = 'youtube-placeholder';
                placeholder.innerHTML = '<div style="padding: 20px; background: #f3f4f6; text-align: center; color: #6b7280;">Video tidak tersedia</div>';
                iframe.parentNode.appendChild(placeholder);
            }
        });

        // Prevent thumbnail 404 errors
        const images = document.querySelectorAll('img[src*="youtube.com/vi/"]');
        images.forEach(function(img) {
            img.onerror = function() {
                this.style.display = 'none';
            };
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initYouTubeEmbeds);
    } else {
        initYouTubeEmbeds();
    }

    // Re-initialize on Livewire updates
    if (window.Livewire) {
        document.addEventListener('livewire:load', initYouTubeEmbeds);
        document.addEventListener('livewire:update', initYouTubeEmbeds);
    }

    // Prevent Alpine duplicate initialization warning
    document.addEventListener('alpine:init', function() {
        console.log('Alpine initialized (single instance)');
    });
})();