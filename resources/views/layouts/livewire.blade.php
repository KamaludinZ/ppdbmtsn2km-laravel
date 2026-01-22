<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'PMBM MTsN 2 Kota Malang') }}</title>

    @if($favicon = \App\Models\Setting::get('app_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Smart Asset Loader -->
    <x-asset-loader />
</head>
<body class="antialiased bg-gray-50">
    {{ $slot }}

    @livewireScripts
</body>
</html>