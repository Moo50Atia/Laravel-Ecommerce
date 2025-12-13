
@extends('layouts.app')

@section('content')
    <!-- Product Slider 
    here will be shown photos of most important products 
    we will use Orderitem model to insert data 
    -->
    <section class="my-8" data-aos="fade-up">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ( $topRatedProducts as $topRatedProducts1 )
                <div class="swiper-slide"><img src="{{ asset("/storage" . $topRatedProducts1->link) }}" class="w-full h-64 object-cover" alt="no image"></div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories Info -->
    {{-- this part will be static and contain our  Categories Info  --}}
    <section class="max-w-6xl mx-auto my-8 px-4" data-aos="fade-right">
        <h2 class="text-2xl font-bold mb-4">Discover Our Categories</h2>
        <p class="text-gray-600">We offer a wide range of electronics, fashion, home appliances and more to satisfy all your needs.</p>
    </section>

    <!-- Latest Offers  
        we will use discription in coupons 
    -->
<section class="max-w-6xl mx-auto my-8 px-4" data-aos="fade-left">
    <h2 class="text-2xl font-bold mb-4">Latest Offers & Coupons</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($describeCoupon as $describeCoupon1)
            <div class="border p-4 shadow rounded">
                <h3 class="font-bold">
                    {{ $describeCoupon1->discription }} - {{ $describeCoupon1->product?->name ?? "No product" }}
                </h3>

                {{-- @php
                    $vendor = $describeCoupon1->getVendorNameAttribute;
                @endphp --}}

                {{-- @if ($vendor && $vendor->store_name) --}}
                <p>Offered by {{ $describeCoupon1->vendor_name }}</p>
                    {{-- @else --}}
                    {{-- <p>Offered by Unknown Vendor</p> --}}
                {{-- @endif --}}

                <a href="#" class="text-blue-600">Details</a>
            </div>
        @endforeach
    </div>
</section>


    <!-- Customer Reviews Slider 
        it will be the comment from ProductReview 
    -->
    <section class="my-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold text-center mb-4">What Our Customers Say</h2>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                 @foreach ($customerReviews as $review)
                <div class="swiper-slide p-4 bg-gray-100 rounded shadow text-center">
                    "{{$review->comment}}" - {{$review->user->name}}
                </div>
                    @endforeach
                
               
                <div class="swiper-slide p-4 bg-gray-100 rounded shadow text-center">
                    "Highly recommend this store to everyone." - Layla
                </div>
            </div>
        </div>
    </section>

    <!-- Site Statistics  
        the numbres of users from Users
        the numbres of vendors from Vendors
        the numbres of products from Products
    -->
    <section class="max-w-6xl mx-auto my-8 px-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-center" data-aos="fade-up">
        <div class="border p-6 shadow rounded">
            <h3 class="text-3xl font-bold text-blue-600">{{$numofvendors}}+</h3>
            <p class="text-gray-600">Vendors</p>
        </div>
        <div class="border p-6 shadow rounded">
            <h3 class="text-3xl font-bold text-blue-600">{{$numofusers}}+</h3>
            <p class="text-gray-600">Users</p>
        </div>
        <div class="border p-6 shadow rounded">
            <h3 class="text-3xl font-bold text-blue-600">{{$numofproducts}}+</h3>
            <p class="text-gray-600">Products</p>
        </div>
    </section>

    <!-- Vendor Success Stories  
    the logo for the store from Vendors
    and the store_name from Vendors
    the id from Vendors for the link
    -->
    <section class="max-w-6xl mx-auto my-8 px-4" data-aos="fade-right">
        <h2 class="text-2xl font-bold mb-4">Real Success Stories</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
             @foreach ($real_stories as $real_stories1 )
                    
            <div class="border p-4 shadow rounded">
                <img src="{{ asset('storage/' . $real_stories1->url) }}" class="w-full h-40 object-cover mb-2">
                <h3 class="font-bold">{{$real_stories1->store_name}}</h3>
            </div>
               @endforeach
            <div class="border p-4 shadow rounded">
                <img src="/images/vendor2.jpg" class="w-full h-40 object-cover mb-2">
                <h3 class="font-bold">Sara's Fashion</h3>
            </div>
            <div class="border p-4 shadow rounded">
                <img src="/images/vendor3.jpg" class="w-full h-40 object-cover mb-2">
                <h3 class="font-bold">HomeStyle Decor</h3>
            </div>
        </div>
    </section>

    <!-- Vendor Ratings  
    the rating from Vendor
    -->
    <section class="max-w-6xl mx-auto my-8 px-4" data-aos="fade-left">
        <h2 class="text-2xl font-bold mb-4">Top Rated Vendors</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ( $real_stories as $real_stories2 )
                
            @if ($real_stories2->rating > 4 && $real_stories2->rating < 5)
                
               <div class="border p-4 shadow rounded text-center">
                <h3 class="font-bold">{{$real_stories2->store_name}}</h3>
                <p class="text-yellow-500">★★★★★</p>
            </div>
             @elseif ($real_stories2->rating < 4 && $real_stories2->rating > 3)
            <div class="border p-4 shadow rounded text-center">
                <h3 class="font-bold">{{$real_stories2->store_name}}</h3>
                <p class="text-yellow-500">★★★★☆</p>
            </div>
            @endif
             @endforeach
    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // AOS.init();
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000 },
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    </script>
@endsection 




