<x-app-layout>
  <x-slot name="style">
    <style>
      .wishlist-item img { @apply w-full h-40 object-cover rounded; }
    </style>
  </x-slot>

  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">قائمة المفضلة</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <!-- مثال لمنتج محفوظ -->
      @foreach ($wishlists as $wishlist)
      
      <div class="bg-white p-4 rounded-lg shadow-md wishlist-item" data-aos="fade-up" data-aos-delay="100">
        <img src="{{asset("sotrage" . $wishlist->product->image->url)}}" alt="منتج">
        <div class="mt-4">
          <h2 class="text-lg font-semibold">{{$wishlist->product->name}}</h2>
          <p class="text-gray-600">{{$wishlist->product->price}}</p>
          <div class="flex justify-between mt-3">
            <a href="{{route("products.show",$wishlist->product->id)}}" class="text-blue-600 text-sm">عرض المنتج</a>
            <form action="{{route("user.wishlist.delete")}}" method="post"> 
              <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
              @csrf
              @method("DELETE")
              <button class="text-red-500 text-sm hover:underline" type="submit">Delete</button>
            </form>
          </div>
        </div>
      </div>
      @endforeach
      <!-- تكرار منتجات المفضلة هنا -->
    </div>
  </div>

  <x-slot name="script">
    <script>AOS.init();</script>
  </x-slot>
</x-app-layout>
