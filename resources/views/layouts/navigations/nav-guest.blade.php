
<nav class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8" data-aos="fade-down">
    <div class="max-w-7xl mx-auto flex justify-between items-center h-16">

        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="{{ route('index') }}">
                <x-application-logo class="h-9 w-auto fill-current text-blue-600" />
            </a>
        </div>

        <!-- Centered Links -->
        <div class="hidden md:flex flex-1 justify-center space-x-10 items-center">
            <a href="/" class="text-primary font-bold text-xl">MyShop</a>
            <a href="{{ route("blogs.index") }}" class="text-gray-700 hover:text-primary transition">Blog</a>
            <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition">Login</a>
            <a href="{{ route('register') }}" class="text-gray-700 hover:text-primary transition">Register</a>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="px-4 py-2 bg-gray-200 rounded">☰ القائمة</button>
                </x-slot>

                <x-slot name="content">
                    <a href="/" class="block px-4 py-2 hover:bg-gray-100">MyShop</a>
                    <a href="/Blog" class="block px-4 py-2 hover:bg-gray-100">Blog</a>
                    <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100">Register</a>
                </x-slot>
            </x-dropdown>
        </div>

    </div>
</nav>

