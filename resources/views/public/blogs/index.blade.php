
{{--  --

$blogs
$special_blogs 
stars 
<option value="1"> 1</option>
              <option value="2">⭐⭐ 2</option>
              <option value="3">⭐⭐⭐ 3</option>
              <option value="4">⭐⭐⭐⭐ 4</option>
              <option value="5">⭐⭐⭐⭐⭐ 5</option>

                  <div class="swiper-slide">
            <div class="bg-gray-50 p-6 rounded-lg shadow hover:shadow-md transition flex flex-col justify-between h-full">
              <div>
                <h3 class="text-lg font-semibold mb-2">كيف تجذب عملاء أكثر؟</h3>
                <p class="text-gray-600 mb-4">استراتيجيات فعالة لتحقيق نمو في المبيعات...</p>
              </div>
              <form class="mt-auto">
                <label for="rating2" class="block mb-1 text-sm font-medium text-gray-700">قيم المقال</label>
                <select id="rating2" name="rating" class="w-full border-gray-300 rounded p-2 text-sm focus:ring-primary focus:border-primary">
                  <option value="" disabled selected>اختر تقييم</option>
                  <option value="1">⭐ 1</option>
                  <option value="2">⭐⭐ 2</option>
                  <option value="3">⭐⭐⭐ 3</option>
                  <option value="4">⭐⭐⭐⭐ 4</option>
                  <option value="5">⭐⭐⭐⭐⭐ 5</option>
                </select>
              </form>
            </div>
          </div>
--}}
 
@extends('layouts.app')

@section('content')
     <!-- Header -->

       <header class="bg-gray-100 py-12 mb-8" data-aos="fade-down">
         <div class="max-w-7xl mx-auto px-4 flex justify-between items-center flex-wrap gap-4">
           <h1 class="text-3xl font-bold text-primary">📚 المدونة</h1>
      <a href="{{route("blogs.create")}}" class="bg-primary text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition-all text-sm sm:text-base">
        ✍️ اكتب مقال
      </a>
    </div>
  </header>

  <!-- Swiper Featured Blogs -->
  <section class="py-10 bg-white" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-2xl font-bold mb-6 text-primary">مقالات مميزة</h2>

      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <!-- Slide 
        {{-- here will be spesial slides comes from rating from Blog --}}
        --> 
        @foreach ( $special_blogs  as $special_blog )
          
        
          <div class="swiper-slide">
            <div class="bg-gray-50 p-6 rounded-lg shadow hover:shadow-md transition flex flex-col justify-between h-full">
              <div>
                <h3 class="text-lg font-semibold mb-2">{{ $special_blog->title}}</h3>
                <p class="text-gray-600 mb-4">{{ $special_blog->short_description}}</p>
              </div>
                      @switch($special_blog)
              @case( number_format($special_blog->reviews_avg_rate, 1) == 1)
                    <label for="">⭐</label>
                @break
              @case( number_format($special_blog->reviews_avg_rate, 1) == 2)
                    <label for="">⭐⭐</label>
                @break
              @case( number_format($special_blog->reviews_avg_rate, 1) == 3)
                    <label for="">⭐⭐⭐</label>
                @break
              @case( number_format($special_blog->reviews_avg_rate, 1) == 4)
                    <label for="">⭐⭐⭐⭐</label>
                @break
              @case( number_format($special_blog->reviews_avg_rate, 1) == 5)
                    <label for="">⭐⭐⭐⭐⭐</label>
                @break
            
              @default
                {{"لا يوجد تقييم"}}
            @endswitch
            <label for=""> {{$special_blog->id}} </label>
                    <a href="{{route('blogs.show' ,$special_blog->id) }}" class="text-blue-600 text-sm mb-4 hover:underline">عرض تفاصيل المقال</a>
            </div>
          </div>
          @endforeach

          
        <div class="flex justify-center mt-4 space-x-4">
          <div class="swiper-button-prev text-primary"></div>
          <div class="swiper-button-next text-primary"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Blog Grid Section -->
  <section class="py-10 bg-gray-50" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-2xl font-bold mb-6 text-primary">كل المقالات</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Single Blog Box --
                {{-- here comes all posts by date from Blog --}}
 --> @foreach ( $blogs as $blog)
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between h-full">
          <div>
           
            <h4 class="text-xl font-semibold mb-2 text-primary">{{$blog->title}}</h4>
            <p class="text-gray-700 mb-4">{{$blog->short_description}}</p>
          </div>
          {{-- this is the for you will use in logic  --}}
          
            <label for="rating3" class="block mb-1 text-sm font-medium text-gray-700">تقييم المقال</label>
            @switch($blog)
              @case( number_format($blog->reviews_avg_rate, 1) == 1)
                    <label for="">⭐</label>
                @break
              @case( number_format($blog->reviews_avg_rate, 1) == 2)
                    <label for="">⭐⭐</label>
                @break
              @case( number_format($blog->reviews_avg_rate, 1) == 3)
                    <label for="">⭐⭐⭐</label>
                @break
              @case( number_format($blog->reviews_avg_rate, 1) == 4)
                    <label for="">⭐⭐⭐⭐</label>
                @break
              @case( number_format($blog->reviews_avg_rate, 1) == 5)
                    <label for="">⭐⭐⭐⭐⭐</label>
                @break
            
              @default
                {{"لا يوجد تقييم"}}
            @endswitch
                        <label for=""> {{$blog->id}} </label>
            <a href="{{route("blogs.show", $blog->id) }}" class="text-blue-600 text-sm mb-4 hover:underline">عرض تفاصيل المقال</a>

        </div>
        @endforeach


      </div>
    </div>
  </section>
  {{ $blogs->links() }}


  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <!-- Init -->
  <script>
    const swiper = new Swiper(".mySwiper", {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      autoplay: {
        delay: 3000,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  </script>
@endsection


