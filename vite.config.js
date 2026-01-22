import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'app/Livewire/**',
                'resources/views/**',
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    css: {
        preprocessorOptions: {
            css: {
                charset: false,
            },
        },
    },
    // Filter console warnings
    logLevel: 'info',
    customLogger: (() => {
        const ignoredWarnings = [
            '-webkit-text-size-adjust',
            'text-size-adjust',
            '-moz-osx-font-smoothing',
            '-moz-',
            '-webkit-',
            'wire:',
            'program:',
            'Unknown property',
            'Declaration dropped',
        ];

        return {
            info: (msg) => {
                // Only show info if not in ignored list
                if (!ignoredWarnings.some(warning => msg.includes(warning))) {
                    console.log(msg);
                }
            },
            warn: (msg) => {
                // Filter CSS warnings
                if (!ignoredWarnings.some(warning => msg.includes(warning))) {
                    console.warn(msg);
                }
            },
            error: console.error,
            warnOnce: (msg) => {
                // Suppress repeated warnings
                if (!ignoredWarnings.some(warning => msg.includes(warning))) {
                    console.warn(msg);
                }
            }
        };
    })(),
});