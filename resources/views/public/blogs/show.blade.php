{{-- <div class="container">
    <h2>blog Details</h2>
     <p><strong>title:</strong> {{ $blog ->title }}</p>
<p><strong>slug:</strong> {{ $blog ->slug }}</p>
<p><strong>rate:</strong> {{ $blog ->rate }}</p>
<p><strong>content:</strong> {{ $blog ->content }}</p>
<p><strong>author_id:</strong> {{ $blog ->author_id }}</p>
<p><strong>featured_image:</strong> {{ $blog ->featured_image }}</p>
<p><strong>meta_title:</strong> {{ $blog ->meta_title }}</p>
<p><strong>meta_description:</strong> {{ $blog ->meta_description }}</p>
<p><strong>is_published:</strong> {{ $blog ->is_published }}</p>
<p><strong>published_at:</strong> {{ $blog ->published_at }}</p>

</div> --}}
{{-- @extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow" data-aos="fade-up">
        <h2 class="text-xl font-bold mb-6">تفاصيل المقال</h2>

        <div class="space-y-3 text-gray-700">
            <p><strong>العنوان:</strong> {{ $blog->title }}</p>
            <p><strong>Slug:</strong> {{ $blog->slug }}</p>
            <p><strong>التقييم:</strong> {{ $blog->rate }}</p>
            <p><strong>المحتوى:</strong> {{ $blog->content }}</p>
            <p><strong>الكاتب:</strong> {{ $blog->author_id }}</p>
            {{-- <p><strong>Meta Title:</strong> {{ $blog->meta_title }}</p> --}}
            {{-- <p><strong>Meta Description:</strong> {{ $blog->meta_description }}</p> --}}
            {{-- <p><strong>حالة النشر:</strong> {{ $blog->is_published ? 'منشور' : 'غير منشور' }}</p> --}}
            {{-- <p><strong>تاريخ النشر:</strong> {{ $blog->published_at }}</p> --}}

            {{-- @if($blog->featured_image)
                <div>
                    <strong>صورة المقال:</strong><br>
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="صورة المقال" class="w-40 mt-2">
                </div>
            @endif
        </div>
    </div>
@endsection  --}}
@extends('layouts.app')

@section('content')

  <!-- Featured Card  افتح الكومنت
        we will use 
        name from relation between Blog and User
        featured_image
        meta_title
        meta_description
        published_at
        from Blog
-->
  <section class="container mx-auto py-12 px-4 md:px-0">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden" data-aos="zoom-in" data-aos-duration="1000">
      <img src="{{ asset('storage/' . $blog->image?->url) }}" class="w-full h-48 object-cover" alt="Featured Image">
      <div class="p-6">
        <h2 class="text-2xl font-bold text-dark mb-4">{{ $blog->title }}</h2>
        <p class="text-gray-500 mb-4">By {{ $blog->author->name }} | Published on {{ $blog->published_at }}</p>
        <p class="text-gray-700">{{$blog->short_description}}</p>
      </div>
    </div>
  </section>

  <!-- Full Content افتح الكومنت 
    we will use here contant from Blog
-->
  <section class="container mx-auto py-12 px-4 md:px-0">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000">
      <h3 class="text-2xl font-semibold text-dark mb-6">Full Content</h3>
      <div class="prose max-w-none text-gray-700">
        <p> {{ $blog->content }} </p>
      </div>
    </div>
  </section>

  <!-- Rating Section -->
  <section class="container mx-auto py-8 px-4 md:px-0">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000">
      <h3 class="text-2xl font-semibold text-dark mb-6">تقييم المقال</h3>
      
      <!-- Current Rating Display -->
      <div class="mb-6">
        <div class="flex items-center gap-4">
          <div class="flex items-center">
            @php
              $averageRating = $blog->reviews->avg('rate') ?? 0;
              $totalReviews = $blog->reviews->count();
            @endphp
            <div class="flex items-center">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= $averageRating)
                  <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                @else
                  <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                @endif
              @endfor
            </div>
            <span class="ml-2 text-lg font-semibold text-gray-700">{{ number_format($averageRating, 1) }}</span>
          </div>
          <span class="text-gray-500">({{ $totalReviews }} تقييم)</span>
        </div>
      </div>

      <!-- Rating Form -->
      @auth
        <div class="border-t pt-6">
          <h4 class="text-lg font-medium text-gray-800 mb-4">أضف تقييمك</h4>
          
          @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                  <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          @endif

          <form action="{{ route('blogs.rate', $blog->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">التقييم</label>
              <div class="flex items-center space-x-2">
                @for($i = 1; $i <= 5; $i++)
                  <input type="radio" id="rate_{{ $i }}" name="rate" value="{{ $i }}" class="sr-only">
                  <label for="rate_{{ $i }}" class="cursor-pointer">
                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  </label>
                @endfor
              </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
              إرسال التقييم
            </button>
          </form>
        </div>
      @else
        <div class="border-t pt-6">
          <p class="text-gray-600">يجب <a href="{{ route('login') }}" class="text-blue-600 hover:underline">تسجيل الدخول</a> لإضافة تقييم</p>
        </div>
      @endauth

      <!-- Recent Reviews -->
      @if($blog->reviews->count() > 0)
        <div class="border-t pt-6 mt-6">
          <h4 class="text-lg font-medium text-gray-800 mb-4">التقييمات الحديثة</h4>
          <div class="space-y-4">
            @foreach($blog->reviews->take(5) as $review)
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center">
                    <div class="flex items-center">
                      @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rate)
                          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                        @else
                          <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                        @endif
                      @endfor
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->user->name ?? 'مستخدم غير معروف' }}</span>
                  </div>
                  <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>
  <div class="flex gap-4 mt-4 justify-center">
    <a href="{{ route('admin.blogs.edit', $blog) }}" 
       class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
        ✏️ تعديل المقال
    </a>
    
    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit"
            onclick="return confirm('هل أنت متأكد من حذف المقال؟')"
              class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
        🗑️ حذف المقال
    </button>
</form>
  </div>

  <script>
    // Interactive star rating
    document.addEventListener('DOMContentLoaded', function() {
      const starLabels = document.querySelectorAll('label[for^="rate_"]');
      const starInputs = document.querySelectorAll('input[name="rate"]');
      
      starLabels.forEach((label, index) => {
        const starIndex = index + 1;
        
        // Hover effect
        label.addEventListener('mouseenter', function() {
          highlightStars(starIndex);
        });
        
        label.addEventListener('mouseleave', function() {
          resetStars();
        });
        
        // Click effect
        label.addEventListener('click', function() {
          selectStar(starIndex);
        });
      });
      
      function highlightStars(count) {
        starLabels.forEach((label, index) => {
          const star = label.querySelector('svg');
          if (index < count) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
          } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
          }
        });
      }
      
      function resetStars() {
        const checkedInput = document.querySelector('input[name="rate"]:checked');
        if (checkedInput) {
          highlightStars(parseInt(checkedInput.value));
        } else {
          starLabels.forEach(label => {
            const star = label.querySelector('svg');
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
          });
        }
      }
      
      function selectStar(count) {
        const input = document.getElementById('rate_' + count);
        input.checked = true;
        highlightStars(count);
      }
    });
  </script>

@endsection
