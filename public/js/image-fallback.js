/**
 * Image Fallback Handler
 * Menangani gambar yang gagal load dengan placeholder
 */

// Placeholder SVG untuk gambar yang tidak ada
const placeholderSVG = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2YzZjRmNiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5HYW1iYXIgVGlkYWsgVGVyc2VkaWE8L3RleHQ+PC9zdmc+';

// YouTube thumbnail placeholder
const youtubePlaceholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgwIiBoZWlnaHQ9IjM2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDgwIiBoZWlnaHQ9IjM2MCIgZmlsbD0iIzI4MjgyOCIvPjxjaXJjbGUgY3g9IjI0MCIgY3k9IjE4MCIgcj0iNDAiIGZpbGw9IiNmZjAwMDAiLz48cG9seWdvbiBwb2ludHM9IjIzMCwxNjUgMjYwLDE4MCAyMzAsMTk1IiBmaWxsPSJ3aGl0ZSIvPjx0ZXh0IHg9IjUwJSIgeT0iODAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiM5OTk5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiPlZpZGVvIFRpZGFrIFRlcnNlZGlhPC90ZXh0Pjwvc3ZnPg==';

/**
 * Handle image error dan replace dengan placeholder
 */
function handleImageError(img) {
    // Cek apakah ini YouTube thumbnail
    if (img.src.includes('youtube.com') || img.src.includes('ytimg.com')) {
        img.src = youtubePlaceholder;
        img.alt = 'Video Tidak Tersedia';
    } else {
        img.src = placeholderSVG;
        img.alt = 'Gambar Tidak Tersedia';
    }

    // Tambah class untuk styling
    img.classList.add('image-fallback');

    // Prevent infinite loop jika placeholder juga error
    img.onerror = null;
}

/**
 * Initialize image error handling
 */
function initImageFallback() {
    // Handle semua img yang sudah ada di halaman
    document.querySelectorAll('img').forEach(img => {
        if (!img.complete || img.naturalHeight === 0) {
            img.onerror = function() {
                handleImageError(this);
            };
        }
    });

    // Observer untuk img yang di-load dynamically (Livewire)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.tagName === 'IMG') {
                    node.onerror = function() {
                        handleImageError(this);
                    };
                } else if (node.querySelectorAll) {
                    node.querySelectorAll('img').forEach(img => {
                        img.onerror = function() {
                            handleImageError(this);
                        };
                    });
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Auto-initialize saat DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initImageFallback);
} else {
    initImageFallback();
}

// Livewire hook untuk re-initialize setelah update
document.addEventListener('livewire:navigated', initImageFallback);
document.addEventListener('livewire:load', initImageFallback);

console.log('Image Fallback Handler loaded');
