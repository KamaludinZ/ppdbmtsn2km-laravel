<?php
/**
 * Compile Assets for Production (Without Vite)
 * Run this locally, then upload the generated CSS/JS to production
 */

echo "Compiling Assets for Production (No Vite)\n";
echo "=========================================\n\n";

// Create directories
@mkdir('public/css', 0755, true);
@mkdir('public/js', 0755, true);

// 1. Compile CSS
echo "Creating production CSS...\n";

$cssContent = '/* PPDB MTsN 2 Kota Malang - Production CSS */

/* Import Tailwind from CDN */
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");

/* Import Fonts */
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");

/* Base Styles */
:root {
    --primary: #10b981;
    --secondary: #3b82f6;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #06b6d4;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Figtree", system-ui, -apple-system, sans-serif;
    background-color: #f9fafb;
}

/* Utility Classes */
.container {
    width: 100%;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .container { max-width: 640px; }
}

@media (min-width: 768px) {
    .container { max-width: 768px; }
}

@media (min-width: 1024px) {
    .container { max-width: 1024px; }
}

@media (min-width: 1280px) {
    .container { max-width: 1280px; }
}

/* Button Styles */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    text-align: center;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
}

.btn-primary:hover {
    background-color: #059669;
}

.btn-secondary {
    background-color: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background-color: #2563eb;
}

.btn-danger {
    background-color: var(--danger);
    color: white;
}

.btn-danger:hover {
    background-color: #dc2626;
}

/* Card Component */
.card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.card-header {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

/* Form Styles */
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: white;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-error {
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Alert Styles */
.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert-warning {
    background-color: #fed7aa;
    color: #92400e;
    border: 1px solid #fdba74;
}

.alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background-color: #f3f4f6;
}

.table th {
    padding: 0.75rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
}

.table td {
    padding: 0.75rem;
    border-bottom: 1px solid #f3f4f6;
}

.table tbody tr:hover {
    background-color: #f9fafb;
}

/* Loading Spinner */
.spinner {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 3px solid #e5e7eb;
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Navigation */
.navbar {
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1rem 0;
}

.navbar-brand {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
}

.navbar-menu {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.navbar-link {
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.navbar-link:hover {
    color: var(--primary);
}

/* Livewire Loading States */
.livewire-loading {
    opacity: 0.5;
    pointer-events: none;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Print Styles */
@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: white;
    }

    .card {
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
}
';

file_put_contents('public/css/app.css', $cssContent);
echo "✓ Created public/css/app.css\n";

// 2. Compile JavaScript
echo "Creating production JavaScript...\n";

$jsContent = '/* PPDB MTsN 2 Kota Malang - Production JavaScript */

(function() {
    "use strict";

    // Wait for DOM
    document.addEventListener("DOMContentLoaded", function() {
        console.log("PPDB Application Loaded - Production Mode");

        // Initialize Alpine.js if present
        if (typeof Alpine !== "undefined") {
            Alpine.start();
        }

        // Initialize Livewire if present
        if (typeof Livewire !== "undefined") {
            console.log("Livewire detected and running");

            // Add loading states
            Livewire.hook("message.sent", () => {
                document.body.classList.add("livewire-loading");
            });

            Livewire.hook("message.received", () => {
                document.body.classList.remove("livewire-loading");
            });

            Livewire.hook("message.failed", () => {
                document.body.classList.remove("livewire-loading");
                console.error("Livewire request failed");
            });
        }

        // CSRF Token Setup
        const token = document.querySelector(\'meta[name="csrf-token"]\');
        if (token) {
            window.csrfToken = token.content;

            // Add to all AJAX requests
            if (window.axios) {
                window.axios.defaults.headers.common["X-CSRF-TOKEN"] = window.csrfToken;
            }

            // Add to fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                if (args[1] && args[1].method && args[1].method.toUpperCase() !== "GET") {
                    args[1].headers = args[1].headers || {};
                    args[1].headers["X-CSRF-TOKEN"] = window.csrfToken;
                }
                return originalFetch.apply(this, args);
            };
        }

        // Form Validation
        document.querySelectorAll("form[data-validate]").forEach(form => {
            form.addEventListener("submit", function(e) {
                let isValid = true;

                // Check required fields
                form.querySelectorAll("[required]").forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add("border-red-500");

                        // Show error message
                        let error = field.nextElementSibling;
                        if (!error || !error.classList.contains("form-error")) {
                            error = document.createElement("div");
                            error.classList.add("form-error");
                            field.parentNode.insertBefore(error, field.nextSibling);
                        }
                        error.textContent = "Field ini wajib diisi";
                    } else {
                        field.classList.remove("border-red-500");
                        let error = field.nextElementSibling;
                        if (error && error.classList.contains("form-error")) {
                            error.remove();
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    console.log("Form validation failed");
                }
            });
        });

        // Auto-dismiss alerts
        document.querySelectorAll(".alert[data-dismiss]").forEach(alert => {
            const timeout = parseInt(alert.dataset.dismiss) || 5000;
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, timeout);
        });

        // Dropdown menus
        document.querySelectorAll("[data-dropdown]").forEach(trigger => {
            trigger.addEventListener("click", function(e) {
                e.stopPropagation();
                const dropdownId = this.dataset.dropdown;
                const dropdown = document.getElementById(dropdownId);

                if (dropdown) {
                    dropdown.classList.toggle("hidden");

                    // Close on outside click
                    document.addEventListener("click", function closeDropdown(e) {
                        if (!dropdown.contains(e.target)) {
                            dropdown.classList.add("hidden");
                            document.removeEventListener("click", closeDropdown);
                        }
                    });
                }
            });
        });

        // File upload preview
        document.querySelectorAll("input[type=file][data-preview]").forEach(input => {
            input.addEventListener("change", function() {
                const previewId = this.dataset.preview;
                const preview = document.getElementById(previewId);

                if (preview && this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (preview.tagName === "IMG") {
                            preview.src = e.target.result;
                        } else {
                            preview.textContent = input.files[0].name;
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        // Smooth scroll for anchors
        document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
            anchor.addEventListener("click", function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute("href"));
                if (target) {
                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        });

        // Print functionality
        window.printPage = function() {
            window.print();
        };

        // Copy to clipboard
        window.copyToClipboard = function(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    alert("Berhasil disalin!");
                }).catch(err => {
                    console.error("Failed to copy:", err);
                });
            } else {
                // Fallback
                const textarea = document.createElement("textarea");
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                alert("Berhasil disalin!");
            }
        };

        // Toggle password visibility
        document.querySelectorAll("[data-toggle-password]").forEach(toggle => {
            toggle.addEventListener("click", function() {
                const targetId = this.dataset.togglePassword;
                const input = document.getElementById(targetId);

                if (input) {
                    input.type = input.type === "password" ? "text" : "password";
                    this.textContent = input.type === "password" ? "Show" : "Hide";
                }
            });
        });

        // Number format for Indonesian currency
        window.formatCurrency = function(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(num);
        };

        // Date format for Indonesian
        window.formatDate = function(date) {
            return new Intl.DateTimeFormat("id-ID", {
                year: "numeric",
                month: "long",
                day: "numeric"
            }).format(new Date(date));
        };

        // Global error handler
        window.addEventListener("error", function(e) {
            console.error("Global error:", e.error);
        });

        // Handle back button for modals
        window.addEventListener("popstate", function() {
            // Close all modals
            document.querySelectorAll(".modal.show").forEach(modal => {
                modal.classList.remove("show");
            });
        });

    });

    // Utility functions
    window.ppdb = {
        // Show loading spinner
        showLoading: function() {
            const loader = document.getElementById("global-loader");
            if (loader) loader.classList.remove("hidden");
        },

        // Hide loading spinner
        hideLoading: function() {
            const loader = document.getElementById("global-loader");
            if (loader) loader.classList.add("hidden");
        },

        // Show notification
        notify: function(message, type = "info") {
            console.log(`[${type.toUpperCase()}] ${message}`);
            // You can implement toast notifications here
        },

        // Confirm dialog
        confirm: function(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
    };

})();
';

file_put_contents('public/js/app.js', $jsContent);
echo "✓ Created public/js/app.js\n";

// 3. Copy Livewire assets if they exist
if (file_exists('vendor/livewire/livewire/dist/livewire.js')) {
    @copy('vendor/livewire/livewire/dist/livewire.js', 'public/js/livewire.js');
    echo "✓ Copied Livewire JS\n";
}

// 4. Create index for assets
$indexContent = '<?php
// Assets Index - Redirect to home
header("Location: /");
exit;
';

file_put_contents('public/css/index.php', $indexContent);
file_put_contents('public/js/index.php', $indexContent);

echo "\n";
echo "=========================================\n";
echo "✓ Assets compiled successfully!\n";
echo "=========================================\n\n";

echo "Next steps:\n";
echo "1. Upload public/css/app.css to production\n";
echo "2. Upload public/js/app.js to production\n";
echo "3. Make sure blade templates use these files instead of @vite\n";
echo "\n";

echo "In your blade templates, replace:\n";
echo "  @vite(['resources/css/app.css', 'resources/js/app.js'])\n";
echo "With:\n";
echo '  <link rel="stylesheet" href="{{ asset(\'css/app.css\') }}">' . "\n";
echo '  <script src="{{ asset(\'js/app.js\') }}" defer></script>' . "\n";
?>