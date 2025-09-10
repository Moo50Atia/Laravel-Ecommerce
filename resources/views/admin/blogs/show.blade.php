<x-app-layout>
    <x-slot name="style">
        <style>
            .blog-container { @apply bg-white rounded-lg shadow-md; }
            .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg; }
            .blog-content { @apply p-6; }
            .blog-meta { @apply flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 p-4 bg-gray-50 rounded-lg; }
            .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">تفاصيل المدونة</h1>
                    <p class="text-gray-600 mt-2">عرض تفاصيل المدونة</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-warning">
                        تعديل
                    </a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn-secondary">
                        العودة إلى المدونات
                    </a>
                </div>
            </div>
        </div>

        <!-- Blog Details -->
        <div class="blog-container">
            <div class="blog-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold">{{ $blog->title }}</h2>
                    <span class="px-3 py-1 text-sm rounded-full 
                        @if($blog->is_published) bg-green-200 text-green-800
                        @else bg-yellow-200 text-yellow-800
                        @endif">
                        {{ $blog->is_published ? 'منشورة' : 'مسودة' }}
                    </span>
                </div>
            </div>
            
            <div class="blog-content">
                @if($blog->coverImage)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $blog->coverImage->url) }}" 
                             alt="{{ $blog->title }}" 
                             class="w-full max-h-96 object-cover rounded-lg">
                    </div>
                @endif
                
                @if($blog->short_description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">الوصف المختصر</h3>
                        <p class="text-gray-700">{{ $blog->short_description }}</p>
                    </div>
                @endif
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">المحتوى</h3>
                    <div class="text-gray-700 prose max-w-none">
                        {!! nl2br(e($blog->content)) !!}
                    </div>
                </div>
                
                <div class="blog-meta">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span><strong>المؤلف:</strong> {{ $blog->author->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span><strong>تاريخ الإنشاء:</strong> {{ $blog->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($blog->published_at)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong>تاريخ النشر:</strong> {{ $blog->published_at->format('Y-m-d H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span><strong>التعليقات:</strong> {{ $blog->reviews->count() }}</span>
                    </div>
                </div>
                
                @if($blog->reviews->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">التعليقات</h3>
                        <div class="space-y-4">
                            @foreach($blog->reviews as $review)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium">{{ $review->user->name ?? 'مستخدم غير معروف' }}</span>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-8">
                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المدونة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">حذف المدونة</button>
                    </form>
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-warning">
                        تعديل
                    </a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn-secondary">
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
