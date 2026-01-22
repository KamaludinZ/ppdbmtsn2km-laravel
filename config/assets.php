<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asset Configuration - Smart Mode
    |--------------------------------------------------------------------------
    | Automatically handles both development and production environments
    */

    // Asset loading mode
    'mode' => env('ASSET_MODE', 'auto'),

    // Enable Vite in development (if available)
    'use_vite_in_dev' => env('USE_VITE_IN_DEV', true),

    // CDN configuration (fallbacks)
    'cdn' => [
        'tailwind' => 'https://cdn.tailwindcss.com',
        'alpine' => 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
        'axios' => 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js',
    ],

    // Local asset paths
    'local' => [
        'css' => [
            'production' => 'css/app-production-final.css',
            'fallback' => 'css/app.css',
        ],
        'js' => [
            'production' => 'js/app-production-final.js',
            'fallback' => 'js/app.js',
        ],
        'tailwind' => 'css/tailwind.min.css',
    ],

    // Vite manifest location
    'vite_manifest' => 'build/manifest.json',

    // Enable fallback to CDN when local assets fail
    'enable_fallback' => true,

    // Cache busting version
    'version' => env('ASSET_VERSION', '20260118085427'),
];