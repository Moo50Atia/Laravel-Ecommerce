

<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow" data-aos="fade-up">
        <h2 class="text-xl font-bold mb-6">إنشاء مقال جديد</h2>
        
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
        
       
        
        
        
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-medium">العنوان</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2">
                @error("title") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">الوصف القصير</label>
                <textarea name="short_description" rows="2" class="w-full border rounded px-3 py-2">{{ old('short_description') }}</textarea>
                @error("short_description") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

                 <div>
                <label class="block mb-1 font-medium">المقال</label>
                <textarea name="content" rows="4" class="w-full border rounded px-3 py-2">{{ old('content') }}</textarea>
                @error("content") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">صورة المقال</label>
                <input type="file" name="featured_image" class="w-full border rounded px-3 py-2">
                @error("featured_image") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
{{-- 
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border rounded px-3 py-2">
                    @error("meta_title") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="w-full border rounded px-3 py-2">
                    @error("meta_description") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div> --}}

            {{-- <div class="flex items-center gap-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" checked class="form-checkbox">
                    <span>نشر الآن</span>
                </label>

                {{-- <input type="hidden" name="published_at" value="{{ now()->format('Y-m-d H:i:s') }}">

            </div> --}}

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">حفظ</button>
        </form>
    </div>
</x-app-layout>
