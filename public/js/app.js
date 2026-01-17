/* PPDB MTsN 2 Kota Malang - Production JavaScript */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('PPDB Application Loaded - Production Mode');

    // CSRF Token Setup
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.content;

        // Add to fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (args[1] && args[1].method && args[1].method.toUpperCase() !== 'GET') {
                args[1].headers = args[1].headers || {};
                args[1].headers['X-CSRF-TOKEN'] = window.csrfToken;
                args[1].headers['X-Requested-With'] = 'XMLHttpRequest';
            }
            return originalFetch.apply(this, args);
        };
    }

    // Initialize Alpine.js if present
    if (typeof Alpine !== 'undefined') {
        // Already initialized by Alpine CDN
        console.log('Alpine.js detected');
    }

    // Initialize Livewire hooks if present
    if (typeof Livewire !== 'undefined') {
        console.log('Livewire detected');

        Livewire.hook('message.sent', () => {
            // Show loading state
        });

        Livewire.hook('message.received', () => {
            // Hide loading state
        });

        Livewire.hook('message.failed', () => {
            console.error('Livewire request failed');
        });
    }

    // Auto-dismiss alerts
    document.querySelectorAll('.alert[data-dismiss]').forEach(alert => {
        const timeout = parseInt(alert.dataset.dismiss) || 5000;
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, timeout);
    });

    // Form validation
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;

            // Check required fields
            form.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi');
            }
        });
    });

    // File upload preview
    document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        preview.textContent = this.files[0].name;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Global functions
    window.confirmDelete = function(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
    };

    window.printPage = function() {
        window.print();
    };
});

// Handle Alpine.js loading
if (typeof Alpine === 'undefined') {
    // Load Alpine from CDN if not already loaded
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
    script.defer = true;
    document.head.appendChild(script);
}