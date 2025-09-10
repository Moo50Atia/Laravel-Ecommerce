{{-- <x-app-layout>
    <x-slot name="style">
  <style>
    body { font-family: 'Cairo', sans-serif; }
    .product-img { height: 250px; object-fit: cover; width: 100%; }
    .swiper-slide { text-align: center; }
    .related-products img { height: 100px; object-fit: cover; width: 100%; }
    .subcategory-select { display: none; }
  </style>
    </x-slot>

  <!-- تفاصيل المنتج -->
  <div class="container mx-auto px-4 py-12">
    <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">
      <h1 class="text-2xl font-bold mb-4">منتج 1</h1>
      <div class="swiper mySwiper mb-4">
        <div class="swiper-wrapper">
          <div class="swiper-slide"><img src="https://via.placeholder.com/300" class="product-img" alt="منتج 1"></div>
          <div class="swiper-slide"><img src="https://via.placeholder.com/300" class="product-img" alt="منتج 1"></div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
<p class="text-gray-600 mb-2">
  السعر: 
  <span class="font-semibold">100.00 $</span> 
  <span class="text-red-500 line-through">  120.00 </span>
</p>
          <p class="text-gray-600 mb-2">التاجر: متجر أحمد</p>
          <p class="text-green-500 mb-2">حالة التوفر: متوفر</p>
          <p class="text-gray-700 mb-4">الفئة: إلكترونيات</p>
        </div>
        <div>
          <p class="text-gray-700 mb-4">الوصف: هذا المنتج يتميز بجودة عالية وأداء ممتاز، مثالي للاستخدام اليومي.</p>
          <p class="text-gray-600 mb-4">الوزن: 0.5 كجم | الأبعاد: 20x10x5 سم</p>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">المتغيرات:</label>
        <select class="w-full p-3 border rounded-lg">
          <option value="red">أحمر</option>
          <option value="blue">أزرق</option>
        </select>
      </div>
      <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 w-full" data-aos="zoom-in" data-aos-trigger="hover">إضافة إلى السلة</button>
    </div>
    <!-- تقييمات المستخدمين -->
    <div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">تقييمات المستخدمين</h2>
      <div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <p class="font-semibold">محمد: ★★★★★</p>
        <p>منتج رائع، يستحق الشراء!</p>
      </div>
    </div>
    <!-- منتجات مشابهة -->
    <div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">منتجات مشابهة</h2>
      <div class="swiper relatedSwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="bg-white rounded-lg shadow-md">
              <img src="https://via.placeholder.com/100" class="related-products" alt="منتج مشابه 1">
              <div class="p-4">
                <h5 class="text-lg font-semibold">منتج مشابه 1</h5>
                <p class="text-gray-600">150.00 $</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <x-slot name="script">
  <script>
    AOS.init();
    var swiper = new Swiper('.mySwiper', {
      loop: true,
      pagination: { el: '.swiper-pagination', clickable: true },
      autoplay: { delay: 3000 }
    });
    var relatedSwiper = new Swiper('.relatedSwiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });

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

    </x-app-layout>  --}}

{{-- داخل resources/views/product.blade.php مثلاً --}}
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
        <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">
            <h1 class="text-2xl font-bold mb-4">منتج 1</h1>
            <div class="swiper mySwiper mb-4">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="https://via.placeholder.com/300" class="product-img" alt="منتج 1"></div>
                    <div class="swiper-slide"><img src="https://via.placeholder.com/300" class="product-img" alt="منتج 1"></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 mb-2">
                        السعر:
                        <span class="font-semibold inline-block">$100.00</span>
                        <span class="text-red-500 line-through inline-block">$120.00</span>
                    </p>
                    <p class="text-gray-600 mb-2">التاجر: متجر أحمد</p>
                    <p class="text-green-500 mb-2">حالة التوفر: متوفر</p>
                    <p class="text-gray-700 mb-4">الفئة: إلكترونيات</p>
                </div>
                <div>
                    <p class="text-gray-700 mb-4">الوصف: منتج ممتاز للاستخدام اليومي.</p>
                    <p class="text-gray-600 mb-4">الوزن: 0.5 كجم | الأبعاد: 20x10x5 سم</p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">المتغيرات:</label>
                <select class="w-full p-3 border rounded-lg">
                    <option value="red">أحمر</option>
                    <option value="blue">أزرق</option>
                </select>
            </div>
            <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 w-full" data-aos="zoom-in" data-aos-trigger="hover">إضافة إلى السلة</button>
        </div>
           <div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">تقييمات المستخدمين</h2>
      <div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <p class="font-semibold">محمد: ★★★★★</p>
        <p>منتج رائع، يستحق الشراء!</p>
      </div>
    </div>
    <!-- منتجات مشابهة -->
<div class="mt-8" data-aos="fade-up" data-aos-duration="2000">
  <h2 class="text-2xl font-bold mb-4">منتجات مشابهة</h2>

  <!-- الشبكة اللي فيها 3 منتجات في كل صف -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <!-- كرت منتج -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <img src="https://via.placeholder.com/300x200" class="w-full h-40 object-cover" alt="منتج مشابه 1">
      <div class="p-4">
        <h5 class="text-lg font-semibold">منتج مشابه 1</h5>
        <p class="text-gray-600">150.00 $</p>
      </div>
    </div>

    <!-- كرر نفس الشكل لباقي المنتجات -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
    </div>

    <!-- وهكذا -->
  </div>
</div>


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
