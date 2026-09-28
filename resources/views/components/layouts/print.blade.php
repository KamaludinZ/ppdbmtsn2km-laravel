<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $pageTitle ?? 'Print Preview' }}</title>

    <!-- Tailwind CSS (Vite saat dev, file CSS statis di production) -->
    <x-asset-loader />

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .print\:hidden {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Main Content (No Sidebar, No Header) -->
    <div class="min-h-screen">
        {{ $slot }}
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
