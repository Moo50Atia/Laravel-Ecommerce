<x-app-layout>
    <x-slot name="style">
        <style>
                         .blog-card { @apply bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1; }
                         .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-t-lg p-4 transition-all duration-300; }
                         .blog-content { @apply p-6 transition-all duration-200; }
                         .blog-meta { @apply flex items-center space-x-4 text-sm text-gray-600 mb-3 transition-all duration-200; }
                         .blog-actions { @apply flex items-center space-x-2 mt-4 transition-all duration-200; }
                         .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-success { @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .search-box { @apply w-full md:w-96 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .filter-select { @apply px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .stats-card { @apply bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1; }
                         .pagination { @apply mt-8; }
             .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
             .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
                 <!-- Header -->
         <div class="mb-8 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                                         <h1 class="text-3xl font-bold text-gray-900 transition-all duration-300">إدارة المدونات</h1>
                                         <p class="text-gray-600 mt-2 transition-all duration-300">إدارة وإضافة وتعديل المدونات في المتجر</p>
                </div>
                                 <a href="{{ route('admin.blogs.create') }}" class="btn-primary flex items-center space-x-2 transition-all duration-300">
                                         <svg class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                                         <span class="transition-all duration-300">إضافة مدونة جديدة</span>
                </a>
            </div>
        </div>

                 <!-- Statistics Cards -->
         <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 transition-all duration-300">
            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                                         <div class="ml-4">
                         <p class="text-sm font-medium text-gray-600">إجمالي المدونات</p>
                         <p class="text-2xl font-bold text-gray-900">{{ $totalBlogs }}</p>
                     </div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                                         <div class="ml-4">
                         <p class="text-sm font-medium text-gray-600">نشطة</p>
                         <p class="text-2xl font-bold text-gray-900">{{ $publishedBlogs }}</p>
                     </div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                                         <div class="ml-4">
                         <p class="text-sm font-medium text-gray-600">قيد المراجعة</p>
                         <p class="text-2xl font-bold text-gray-900">{{ $draftBlogs }}</p>
                     </div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                                         <div class="ml-4">
                         <p class="text-sm font-medium text-gray-600">إجمالي التعليقات</p>
                         <p class="text-2xl font-bold text-gray-900">{{ $totalReviews }}</p>
                     </div>
                </div>
            </div>
        </div>

                 <!-- Search and Filters -->
         <div class="bg-white rounded-lg shadow-md p-6 mb-8 transition-all duration-300">
            @if(request('search') || request('status') || request('author'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg transition-all duration-300 hover:bg-blue-100">
                                         <p class="text-sm text-blue-800 transition-all duration-300">
                                                 <strong class="transition-all duration-300">نتائج البحث:</strong> 
                        تم العثور على {{ $blogs->total() }} نتيجة
                        @if(request('search'))
                            للبحث "{{ request('search') }}"
                        @endif
                        @if(request('status'))
                            بحالة "{{ request('status') === 'published' ? 'منشورة' : 'مسودة' }}"
                        @endif
                        @if(request('author'))
                            للمؤلف "{{ request('author') }}"
                        @endif
                    </p>
                </div>
            @endif
                         <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col md:flex-row gap-4 transition-all duration-300">
                <div class="flex-1">
                                         <input type="text" 
                            name="search"
                            placeholder="البحث في المدونات..." 
                            class="search-box transition-all duration-300"
                            value="{{ request('search') }}">
                </div>
                                 <div class="flex gap-4 transition-all duration-300">
                                         <select class="filter-select transition-all duration-300" name="status">
                                                 <option value="" class="transition-all duration-300">جميع الحالات</option>
                         <option value="published" {{ request('status') === 'published' ? 'selected' : '' }} class="transition-all duration-300">منشورة</option>
                         <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }} class="transition-all duration-300">مسودة</option>
                    </select>
                                         <select class="filter-select transition-all duration-300" name="author">
                                                 <option value="" class="transition-all duration-300">جميع المؤلفين</option>
                         @foreach($authors as $authorName)
                             <option value="{{ $authorName }}" {{ request('author') === $authorName ? 'selected' : '' }} class="transition-all duration-300">
                                 {{ $authorName }}
                             </option>
                         @endforeach
                    </select>
                                         <button type="submit" class="btn-primary transition-all duration-300">
                                                 <span class="transition-all duration-300">تطبيق الفلاتر</span>
                    </button>
                                         <a href="{{ route('admin.blogs.index') }}" class="btn-secondary transition-all duration-300">
                                                 <span class="transition-all duration-300">مسح الفلاتر</span>
                    </a>
                </div>
            </form>
        </div>

                 <!-- Blogs Grid -->
         <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 transition-all duration-300" id="blogsGrid">
            @forelse($blogs as $blog)
                <div class="blog-card">
                                         <div class="blog-header transition-all duration-300">
                        <div class="flex items-center justify-between">
                                                         <h3 class="text-lg font-semibold transition-all duration-300">{{ Str::limit($blog->title, 50) }}</h3>
                                                         <span class="px-2 py-1 text-xs rounded-full transition-all duration-300
                                 @if($blog->status === 'published') bg-green-200 text-green-800
                                 @else bg-yellow-200 text-yellow-800
                                 @endif">
                                 {{ $blog->status_text }}
                             </span>
                        </div>
                    </div>
                    
                                         <div class="blog-content transition-all duration-300">
                                                 @if($blog->coverImage)
                             <div class="mb-4 transition-all duration-300">
                                                                 <img src="{{ asset('storage/' . $blog->coverImage->url) }}" 
                                      alt="{{ $blog->title }}" 
                                      class="w-full h-48 object-cover rounded-lg transition-all duration-300 hover:scale-105">
                            </div>
                        @endif
                        
                                                 <p class="text-gray-700 mb-3 transition-all duration-300">{{ Str::limit($blog->short_description ?? $blog->content, 120) }}</p>
                        
                                                 <div class="blog-meta transition-all duration-300">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ $blog->author->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $blog->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ $blog->reviews->count() }} تعليق</span>
                            </div>
                        </div>
                        
                        <div class="blog-actions">
                            <a href="{{ route('admin.blogs.show', $blog) }}" class="btn-primary text-sm">
                                عرض
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-warning text-sm">
                                تعديل
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المدونة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-sm">حذف</button>
                            </form>
                        </div>
                    </div>
                </div>
                         @empty
                 <div class="col-span-full text-center py-12 transition-all duration-300">
                     <svg class="mx-auto h-12 w-12 text-gray-400 transition-all duration-300 hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                     </svg>
                     <h3 class="mt-2 text-sm font-medium text-gray-900 transition-all duration-300">
                         @if(request('search') || request('status') || request('author'))
                             لا توجد نتائج للبحث
                         @else
                             لا توجد مدونات
                         @endif
                     </h3>
                     <p class="mt-1 text-sm text-gray-500 transition-all duration-300">
                         @if(request('search') || request('status') || request('author'))
                             جرب تغيير معايير البحث أو 
                             <a href="{{ route('admin.blogs.index') }}" class="text-blue-600 hover:text-blue-500 transition-all duration-300">مسح الفلاتر</a>
                         @else
                             ابدأ بإنشاء مدونة جديدة.
                         @endif
                     </p>
                     @if(!request('search') && !request('status') && !request('author'))
                         <div class="mt-6 transition-all duration-300">
                             <a href="{{ route('admin.blogs.create') }}" class="btn-primary">
                                 إضافة مدونة جديدة
                             </a>
                         </div>
                     @endif
                 </div>
            @endforelse
        </div>

                 <!-- Pagination -->
         @if($blogs->hasPages())
             <div class="pagination transition-all duration-300">
                <div class="flex items-center justify-between">
                                         <div class="text-sm text-gray-700 transition-all duration-300">
                        عرض {{ $blogs->firstItem() ?? 0 }} إلى {{ $blogs->lastItem() ?? 0 }} من {{ $blogs->total() }} نتيجة
                    </div>
                                         <div class="flex items-center space-x-2 transition-all duration-300">
                        @if($blogs->onFirstPage())
                            <span class="pagination-link opacity-50 cursor-not-allowed">السابق</span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}" class="pagination-link">السابق</a>
                        @endif

                        @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                            @if($page == $blogs->currentPage())
                                <span class="pagination-link pagination-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}" class="pagination-link">التالي</a>
                        @else
                            <span class="pagination-link opacity-50 cursor-not-allowed">التالي</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-slot name="script">
        <script>
            // Auto-refresh every 30 seconds to show latest data
            setInterval(function() {
                // You can implement AJAX refresh here if needed
            }, 30000);

            // Add some interactivity for better UX
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form when filters change
                const filterSelects = document.querySelectorAll('select[name="status"], select[name="author"]');
                filterSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        this.closest('form').submit();
                    });
                });

                // Auto-submit search with delay
                const searchInput = document.querySelector('input[name="search"]');
                let searchTimeout;
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.closest('form').submit();
                        }, 500); // 500ms delay
                    });
                }

                // Add loading state to form submission
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري التطبيق...';
                            submitBtn.disabled = true;
                        }
                        
                        // Add loading overlay
                        const loadingOverlay = document.createElement('div');
                        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        loadingOverlay.innerHTML = '<div class="bg-white p-4 rounded-lg"><div class="flex items-center"><svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري تحميل النتائج...</div></div>';
                        document.body.appendChild(loadingOverlay);
                    });
                }
            });
        </script>
    </x-slot>

</x-app-layout>