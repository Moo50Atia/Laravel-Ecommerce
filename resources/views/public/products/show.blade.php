
<x-app-layout>
    <x-slot name="style">
        <style>
            body { font-family: 'Cairo', sans-serif; }
            .product-img { height: 250px; object-fit: cover; width: 100%; }
            .swiper-slide { text-align: center; }
            .related-products img { height: 100px; object-fit: cover; width: 100%; }
            .subcategory-select { display: none; }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-12">
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" data-aos="fade-down">
            {{ session('success') }}
        </div>
        @endif
        @if(session('info'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg shadow-md" data-aos="fade-down">
            {{ session('info') }}
        </div>
        @endif
        <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">
            <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
            <div class="swiper mySwiper mb-4">
                <div class="swiper-wrapper">
                    @foreach ($product->images as $image )
                    <div class="swiper-slide"><img src="{{ asset('storage/' . $image->url?? "no image") }}" class="product-img" alt="منتج 1"></div>
                    @endforeach
                    {{-- <div class="swiper-slide"><img src="https://via.placeholder.com/300" class="product-img" alt="منتج 1"></div> --}}
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-2">
                        السعر:
                        <span class="font-semibold inline-block"> {{ $product->price }} </span>
                    </p>
                    <p class="text-gray-600 mb-2">vendor name : {{ $product->vendor_name }}</p>
                    @if ($totalStock)
                    <p class="text-green-500 mb-2">avalibale</p>
                    @else
                    <p class="text-red-500 mb-2">not avalibale</p>
                    @endif

                    <p class="text-gray-700 mb-4">category : {{ $product->category }}</p>
                </div>
                <div>
                    <p class="text-gray-700 mb-4">{{ $product->short_description }}</p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">المتغيرات:</label>
                <select class="w-full p-3 border rounded-lg">
                    @foreach ( $product->variants as $variant )
                    <option value="{{ $variant->id }}">{{ $variant->option_name }}</option>
                    @endforeach
                    {{-- <option value="red">أحمر</option>
                    <option value="blue">أزرق</option> --}}
                </select>
            </div>
            <div class="flex flex-col space-y-3">
                <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 w-full" data-aos="zoom-in" data-aos-trigger="hover">إضافة إلى السلة</button>
               
                @auth
                @if (Auth::user()->role == 'user')
                <form action="{{ route('products.wishlist.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 w-full flex items-center justify-center gap-2" data-aos="zoom-in" data-aos-trigger="hover">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        اضف للمفضلة
                    </button>
                </form>
                @endif
                @else
                <a href="{{ route('login') }}" class="bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 w-full text-center">
                    سجل دخول لإضافة المنتج للمفضلة
                </a>
                @endauth
            </div>
        </div>
           <div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">تقييمات المستخدمين</h2>
      @foreach ( $product->productReviews as $review )
      
      <div class="bg-white p-4 rounded-lg shadow-md mb-4">
           @switch($review)
              @case( $review->rating == 1)
                    <p class="font-semibold">{{$review->user->name}} ★</p>
                @break
              @case( $review->rating == 2)
                    <p class="font-semibold">{{$review->user->name}} ★★</p>
                @break
              @case( $review->rating == 3)
                    <p class="font-semibold">{{$review->user->name}} ★★★</p>
                @break
              @case( $review->rating == 4)
                    <p class="font-semibold">{{$review->user->name}} ★★★★</p>
                @break
              @case( $review->rating == 5)
                    <p class="font-semibold">{{$review->user->name}} ★★★★★</p>
                @break
            
              @default
                {{"لا يوجد تقييم"}}
            @endswitch
        
        <p>{{ $review->comment }}</p>
    </div>
      @endforeach
    </div>
    <!-- منتجات مشابهة -->
<div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
  <h2 class="text-2xl font-bold mb-4">منتجات مشابهة</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach ($similarProducts as $similarProduct )
<a href="{{ route('products.show', $similarProduct->id) }}" class="block">
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="{{ asset('storage/' . $similarProduct->image?->url ?? "no image") }}" class="w-full h-40 object-cover" alt="منتج مشابه 1">
        {{-- <img src="https://via.placeholder.com/300" class="w-full h-40 object-cover" alt="منتج مشابه 1"> --}}
        <div class="p-4">
            <h5 class="text-lg font-semibold">{{ $similarProduct->name }}</h5>
            <p class="text-gray-600">{{ $similarProduct->price }}</p>
        </div>
    </div>
</a>
    @endforeach
  <!-- الشبكة اللي فيها 3 منتجات في كل صف -->

    <!-- كرت منتج -->
   

    <!-- كرر نفس الشكل لباقي المنتجات -->
    {{-- <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="https://via.placeholder.com/300x200" class="w-full h-40 object-cover" alt="منتج مشابه 2">
      <div class="p-4">
        <h5 class="text-lg font-semibold">منتج مشابه 2</h5>
        <p class="text-gray-600">160.00 $</p>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="https://via.placeholder.com/300x200" class="w-full h-40 object-cover" alt="منتج مشابه 3">
      <div class="p-4">
        <h5 class="text-lg font-semibold">منتج مشابه 3</h5>
        <p class="text-gray-600">170.00 $</p>
      </div>
    </div> --}}

    <!-- وهكذا -->
  </div>
</div>
@if (Auth::user()->role == "vendor")
    

  <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="mt-4">
    @csrf
    @method('DELETE')
    <button type="submit"
            onclick="return confirm('هل أنت متأكد من حذف المنتج')"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
        🗑️ حذف المنتج
    </button>
</form>
@endif
    <x-slot name="script">
        <script>
            AOS.init();
            new Swiper('.mySwiper', {
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 3000 }
            });
            new Swiper('.relatedSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            });
        </script>
    </x-slot>
</x-app-layout>

