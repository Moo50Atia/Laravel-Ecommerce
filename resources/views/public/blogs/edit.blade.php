{{-- <div class="container">
    <h2>Edit blog</h2>
    <form action="{{ route('blogs.update', $blog->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="title" class="form-label">title</label>
            <input type="text" class="form-control" name="title" value="{{old("title", $blog["title"])}}">
            @error("title")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="slug" class="form-label">slug</label>
            <input type="text" class="form-control" name="slug" value="{{old("slug", $blog["slug"])}}">
            @error("slug")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="rate" class="form-label">rate</label>
            <input type="text" class="form-control" name="rate" value="{{old("rate", $blog["rate"])}}">
            @error("rate")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="content" class="form-label">content</label>
            <input type="text" class="form-control" name="content" value="{{old("content", $blog["content"])}}">
            @error("content")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="author_id" class="form-label">author_id</label>
            <input type="text" class="form-control" name="author_id" value="{{old("author_id", $blog["author_id"])}}">
            @error("author_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="featured_image" class="form-label">featured_image</label>
            <input type="text" class="form-control" name="featured_image" value="{{old("featured_image", $blog["featured_image"])}}">
            @error("featured_image")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="meta_title" class="form-label">meta_title</label>
            <input type="text" class="form-control" name="meta_title" value="{{old("meta_title", $blog["meta_title"])}}">
            @error("meta_title")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="meta_description" class="form-label">meta_description</label>
            <input type="text" class="form-control" name="meta_description" value="{{old("meta_description", $blog["meta_description"])}}">
            @error("meta_description")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="is_published" class="form-label">is_published</label>
            <input type="text" class="form-control" name="is_published" value="{{old("is_published", $blog["is_published"])}}">
            @error("is_published")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="published_at" class="form-label">published_at</label>
            <input type="text" class="form-control" name="published_at" value="{{old("published_at", $blog["published_at"])}}">
            @error("published_at")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div> --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow" data-aos="fade-up">
        <h2 class="text-xl font-bold mb-6">تعديل المقال</h2>
        
        <!-- Errors Display -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            يرجى تصحيح الأخطاء التالية:
                        </h3>
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
        
        @if (Auth::user()->role == "admin")
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">          
        @else 
        <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">        
            @endif
            @csrf
            @method("PATCH")

            <div>
                <label class="block mb-1 font-medium">title</label>
                <input type="text" name="title" value="{{ old('title', $blog->title) }}" class="w-full border rounded px-3 py-2">
                @error("title") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

                <div>
                <label class="block mb-1 font-medium">short-description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $blog->short_description) }}" class="w-full border rounded px-3 py-2">
                @error("short_description") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">content</label>
                <textarea name="content" rows="4" class="w-full border rounded px-3 py-2">{{ old('content', $blog->content) }}</textarea>
                @error("content") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">author_id</label>
                <input type="hidden" name="author_id" value="{{ $blog->author_id }}">
                <span class="text-gray-600">{{ $blog->author->name ?? 'غير محدد' }}</span>
            </div>

            <div>
                <label class="block mb-1 font-medium">صورة المقال</label>
                <input type="file"  
                {{-- @if (Auth::user()->role == "admin") --}}
                name="cover_image" 
                {{-- @else  --}}
                {{-- name="featured_image"  --}}
                {{-- @endif --}}
                class="w-full border rounded px-3 py-2">
                
                @if($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="current image" class="w-20 h-20 mt-2">
                @endif
                
                @error("featured_image") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>


            <div class="flex items-center gap-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }} class="form-checkbox">
                    <span>مفعل</span>
                </label>

                <input type="datetime-local" name="published_at" value="{{ old('published_at', \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i')) }}" class="border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">تحديث</button>
        </form>

        <!-- Admin Ratings Management Section -->
        @if (Auth::user()->role == "admin")
            <div class="mt-8 border-t pt-8">
                <h3 class="text-xl font-bold mb-6 text-gray-800">إدارة التقييمات</h3>
                
                @if($blog->reviews->count() > 0)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-medium text-gray-700">إجمالي التقييمات: {{ $blog->reviews->count() }}</span>
                                <span class="text-lg font-medium text-gray-700">متوسط التقييم: {{ number_format($blog->reviews->avg('rate'), 1) }}/5</span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($blog->reviews as $review)
                                <div class="bg-white p-4 rounded-lg border shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rate)
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900">{{ $review->user->name ?? 'مستخدم غير معروف' }}</p>
                                                <p class="text-sm text-gray-500">{{ $review->created_at->format('Y-m-d H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        <form action="{{ route('blogs.reviews.destroy', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')"
                                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                                🗑️ حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-500">لا توجد تقييمات لهذا المقال</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
