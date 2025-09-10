
<nav class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
        <a href="/vendor/dashboard" class="text-blue-600 font-bold text-xl ">Vendor Panel</a>

        <div class="hidden md:flex space-x-6 items-center">
            <a href="{{ route("vendor.dashboard")}}" class="text-gray-700 hover:text-blue-600 ms-4">Dashboard</a>
            <a href="{{ route("vendor.products")}}" class="text-gray-700 hover:text-blue-600">Products</a>
            <a href="{{ route("vendor.orders.index")}}" class="text-gray-700 hover:text-blue-600">Orders</a>
            <a href="{{ route("profile.edit")}}" class="text-gray-700 hover:text-blue-600">Profile</a>
        </div>

        <button @click="open = ! open" class="md:hidden text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- <div x-show="open" class="md:hidden flex flex-col space-y-2 mt-2">
        <a href="/vendor/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</a>
        <a href="/vendor/products" class="text-gray-700 hover:text-blue-600">Products</a>
        <a href="/vendor/orders" class="text-gray-700 hover:text-blue-600">Orders</a>
        <a href="/vendor/profile" class="text-gray-700 hover:text-blue-600">Profile</a>
        <a href="/vendor/subscription" class="text-gray-700 hover:text-blue-600">Subscription</a>
    </div> --}}
    <div class="md:hidden">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="px-4 py-2 bg-gray-200 rounded">القائمة ☰</button>
        </x-slot>

        <x-slot name="content">
             <a href="{{ route('vendor.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
        <a href="{{ route('vendor.products') }}" class="text-gray-700 hover:text-blue-600">Products</a>
        <a href="{{ route('vendor.orders.index') }}" class="text-gray-700 hover:text-blue-600">Orders</a>
        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600">Profile</a>
             <form method="POST" action="{{ route('logout') }}" >
            @csrf
            <button class="text-red-500 mt-2  ms-4">Logout</button>
        </form>
        </x-slot>
    </x-dropdown>
    
</div>
</nav>