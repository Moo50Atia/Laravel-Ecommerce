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
  <!-- السكشن الثاني: شبكة المنتجات -->
  <div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center mb-8">المنتجات</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      @foreach ($products as $product )
        
    
      <div class="product-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-duration="2000">
        <img src="{{asset("storag/" . $product->image?->url ?? "no image")}}" class="w-full" alt=" No Image">
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
          @csrf
                  <button type="submit" 
                          class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                          data-aos="zoom-in" data-aos-trigger="hover">
                      أضف للسلة
                  </button>
              </form>
          </div>        </div>
      </div>
        @endforeach

    </div>
  </div>



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