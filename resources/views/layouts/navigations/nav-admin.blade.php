
<nav class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
        <a href="/admin/dashboard" class="text-blue-600 font-bold text-xl">Admin Panel</a>

        <div class="hidden md:flex space-x-6 items-center">
            <a href="{{route("admin.dashboard")}}" class="text-gray-700 hover:text-blue-600 ms-4">Dashboard</a>
            <a href="{{route("admin.products.index")}}" class="text-gray-700 hover:text-blue-600">Products</a>
            <a href="{{route("admin.orders.index")}}" class="text-gray-700 hover:text-blue-600">Orders</a>
            <a href="{{route("admin.vendors.index")}}" class="text-gray-700 hover:text-blue-600">Vendors</a>
            <a href="{{route("admin.users.index")}}" class="text-gray-700 hover:text-blue-600">Users</a>
            <a href="{{route("admin.vendors.index")}}" class="text-gray-700 hover:text-blue-600">vendors</a>
            <a href="{{route("admin.categories.index")}}" class="text-gray-700 hover:text-blue-600">Categories</a>
            <a href="{{ route('admin.blogs.index') }}" class="text-gray-700 hover:text-blue-600">Blog</a>
        </div>

        <button @click="open = ! open" class="md:hidden text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" class="md:hidden flex flex-col space-y-2 mt-2">
        <a href="/admin/dashboard" class="text-gray-700 hover:text-blue-600 ms-4">Dashboard</a>
        <a href="/admin/products" class="text-gray-700 hover:text-blue-600">Products</a>
        <a href="/admin/orders" class="text-gray-700 hover:text-blue-600">Orders</a>
        <a href="/admin/vendors" class="text-gray-700 hover:text-blue-600">Vendors</a>
        <a href="/admin/users" class="text-gray-700 hover:text-blue-600">Users</a>
        <a href="/admin/coupons" class="text-gray-700 hover:text-blue-600">Coupons</a>
        <a href="/admin/categories" class="text-gray-700 hover:text-blue-600">Categories</a>
        <a href="{{ route('admin.blogs.index') }}" class="text-gray-700 hover:text-blue-600">Blog</a>
    </div>
</nav>
