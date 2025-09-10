
<nav class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8" data-aos="fade-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        
        <div class="flex items-center space-x-8">
            <a href="/" class="text-primary font-bold text-xl">MyShop</a>

            <div class="hidden md:flex space-x-6">
                <a href="{{route("products.index")}}" class="hover:text-primary">Products</a>
                <a href="{{route("user.cart")}}" class="hover:text-primary">Cart</a>
                <a href="{{route("user.wishlist")}}" class="hover:text-primary">Wishlist</a>
                <a href="{{route("user.orders")}}" class="hover:text-primary">My Orders</a>
                <a href="{{route("profile.edit")}}" class="hover:text-primary block" >Profile</a>
                <a href="{{route("blogs.index")}}" class="hover:text-primary block" >blogs</a>
            </div>
        </div>

      

        <div class="md:hidden">
            <button @click="open = !open" class="text-primary">
                ☰
            </button>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div class="md:hidden">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="px-4 py-2 bg-gray-200 rounded">القائمة ☰</button>
        </x-slot>

        <x-slot name="content">
            <a href="{{ route('products.index') }}" class="block px-4 py-2 hover:bg-gray-100">Products</a>
            <a href="{{ route('user.cart') }}" class="block px-4 py-2 hover:bg-gray-100">Cart</a>
            <a href="{{ route('user.wishlist') }}" class="block px-4 py-2 hover:bg-gray-100">Wishlist</a>
            <a href="{{ route('user.orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
             <form method="POST" action="{{ route('logout') }}" >
            @csrf
            <button class="text-red-500 mt-2  ms-4">Logout</button>
        </form>
        </x-slot>
    </x-dropdown>
    
</div>

</nav>
