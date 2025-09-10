
    <!DOCTYPE html >
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Commerce') }}</title>

        <!-- Tailwind & Animations -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        @isset($style)
                {!! $style !!}
        @endisset
    </head>
    <body class="bg-gray-50 text-gray-800">
        <div class="min-h-screen">
            @if (Auth::user())
                @include('layouts.navigation')
                @else
                @include('layouts.navigations.nav-guest')

            @endif
        
            
            @isset($header)
                <header data-aos="fade-down" class="bg-blue-600 text-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto p-4" data-aos="fade-up">
            {{-- <main class="mx-auto px-4 py-12" data-aos="fade-up"> --}}
                {{ $slot }}
            </main>
        </div>
        {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>AOS.init();</script> 
        @isset ($script)
                {!! $script !!}
        @endisset
    </body>
    </html>

