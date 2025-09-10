

<x-app-layout>
<x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
    .product-card { transition: transform 0.3s; }
    .product-card:hover { transform: scale(1.05); }
    .product-card img { height: 200px; object-fit: cover; width: 100%; }
    .btn-primary { transition: all 0.3s; }
    .btn-primary:hover { transform: scale(1.1); }
    .swiper-slide { text-align: center; padding: 20px; }
    .swiper-slide img { height: 300px; object-fit: cover; width: 100%; }
    .swiper-content { margin-top: 10px; }
    .subcategory-select { display: none; }
  </style>
</x-slot>
  <!-- السكشن الأول: شريط البحث والفلاتر -->
  {{-- <div class="container mx-auto px-4 py-12">
    <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-down" data-aos-duration="2000">
      <h2 class="text-2xl font-bold text-center mb-6">ابحث عن منتجاتك</h2>
      <div class="flex flex-col md:flex-row gap-4">
        <input type="text"   class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"  placeholder="ابحث باسم المنتج...">
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md mt-6" data-aos="fade-up" data-aos-duration="2000">
      <h3 class="text-lg font-semibold mb-4">نطاق السعر</h3>
      <input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" min="0" max="1000" value="500">
      <p class="mt-2">السعر: <span id="priceValue">500 $</span></p>
    </div>
  </div> --}}
<section class="container mx-auto px-4 py-12">
  <form action="{{route("product.search")}}" method="GET">
    @csrf
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold text-center mb-6">ابحث عن منتجاتك</h2>
      <input type="text" name="search"
             class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
             placeholder="ابحث باسم المنتج...">
      <h3 class="text-lg font-semibold mt-6 mb-2">نطاق السعر</h3>
      <input type="range" id="priceRange" min="0" max="1000" value="500"
             class="w-full h-2 bg-gray-200" oninput="
               document.getElementById('priceValue').textContent = this.value + ' $';
               document.getElementById('priceInput').value = this.value;">
      <p class="mt-2">السعر: <span id="priceValue">500 $</span></p>
      <input type="hidden" name="max_price" id="priceInput" value="500">
      <button type="submit" class="mt-4 w-full bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
        ابحث
      </button>
    </div>
  </form>
</section>
    @if(session('done'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" data-aos="fade-down">
        {{ session('done') }}
    </div>
@endif
@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" data-aos="fade-down">
    {{ session('success') }}
</div>
@endif
@if(session('NoProduct'))
<div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" data-aos="fade-down">
    {{ session('NoProduct') }}
</div>
@endif
  <!-- السكشن الثاني: شبكة المنتجات -->
  <div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center mb-8">المنتجات</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      @foreach ($products as $product )
        
    
      <div class="product-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-duration="2000">
      <img src="{{ asset('storage/' . ($product->image?->url ?? 'no-image.jpg')) }}" class="w-full" alt="No Image">

        <div class="p-4">
          <h5 class="text-xl font-semibold mb-2">{{$product->name}}</h5>
          <p class="text-gray-600 mb-2">{{$product->price}} $</p>
          <p class="text-gray-500 mb-4">{{ $product->short_description }}</p>
       <div class="flex items-center gap-2">
    <a href="/products/{{$product->id}}" 
       class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" 
       data-aos="zoom-in" data-aos-trigger="hover">
        عرض التفاصيل
    </a>

     <form action="{{route("user.chose.variant")}}" method="GET">
                  <input type="hidden" name="product_id" value="{{$product->id}}">
                  <button type="submit" 
                          class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                          data-aos="zoom-in" data-aos-trigger="hover">
                      أضف للسلة
                  </button>
              </form> 
</div>

        </div>
  
      </div>
        @endforeach

    </div>
  </div>

  <!-- السكشن الثالث: سلايدر المنتجات المميزة -->
  <div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center mb-8">المنتجات المميزة</h1>
    <div class="swiper mySwiper" data-aos="fade-right" data-aos-duration="2000">
      <div class="swiper-wrapper">
          @foreach ($special_products as $special_product )
            

        <div class="swiper-slide">
          <img src="{{asset("storage/" . $special_product->imgae?->url ?? "no image")}}" alt="منتج مميز 1">
          <div class="swiper-content">
            <h3 class="text-xl font-semibold mt-2">{{$special_product->title}}</h3>
            <p class="text-gray-600 mb-4">{{$special_product->price}} $</p>
       <div class="flex items-center gap-2">
              <a href="{{route("products.show" , $product->id)}}" 
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" 
                data-aos="zoom-in" data-aos-trigger="hover">
                  عرض التفاصيل
              </a>

              <form action="{{route("user.chose.variant")}}" method="GET">
                  <input type="hidden" name="product_id" value="{{$product->id}}">
                  <button type="submit" 
                          class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                          data-aos="zoom-in" data-aos-trigger="hover">
                      أضف للسلة
                  </button>
              </form> 
          </div> 
          </div>
        </div>
          @endforeach

        
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>


  </div>
{{$products->links()}}

<x-slot name="script">

  <script>
    // تهيئة AOS
    AOS.init();

    // تهيئة Swiper
    var swiper = new Swiper('.mySwiper', {
      loop: true,
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      autoplay: { delay: 3000 },
      breakpoints: {
        640: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 40 }
      }
    });

    // تحديث قيمة السعر عند تحريك الـ range
    document.querySelector('input[type="range"]').addEventListener('input', function() {
      document.getElementById('priceValue').textContent = this.value + ' $';
    });

    // التحكم في القوائم المنسدلة الفرعية
    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    categorySelect.addEventListener('change', function() {
      const selectedCategory = this.value;
      subcategorySelect.style.display = selectedCategory ? 'block' : 'none';
      const options = subcategorySelect.querySelectorAll('option');
      options.forEach(option => {
        if (option.value === '' || option.dataset.category === selectedCategory) {
          option.style.display = 'block';
        } else {
          option.style.display = 'none';
        }
      });
    });
  </script>
</x-slot>
</x-app-layout>

















