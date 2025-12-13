<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Commerce') }}</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="{{ vite_asset('resources/css/app.css') }}">
    <script type="module" src="{{ vite_asset('resources/js/app.js') }}"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div data-aos="zoom-in">
            <a href="/">
                <x-application-logo class="w-24 h-24 fill-current text-blue-600" />
            </a>
        </div>

        <div data-aos="fade-up" class="w-full sm:max-w-md mt-6 p-6 bg-white shadow rounded-lg">
            {{ $slot }}
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
