<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .swiper-slide {
                height: auto;
            }
            .stats-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card-2 {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            .stats-card-3 {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            .stats-card-4 {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }
            .status-active { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-inactive { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .pagination { @apply mt-8; }
            .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
            .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8 transition-all duration-300" data-aos="fade-down">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 transition-all duration-300">إدارة المنتجات</h1>
                    <p class="text-gray-600 transition-all duration-300">إدارة وإضافة وتعديل المنتجات في المتجر</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2 transition-all duration-300">
                    <a href="{{ route('vendor.products.create') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 transform hover:scale-105">
                        <svg class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="transition-all duration-300">إضافة منتج جديد</span>
                    </a>
                    <button onclick="exportProducts()" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center gap-2 transform hover:scale-105">
                        <svg class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="transition-all duration-300">تصدير البيانات</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 transition-all duration-300">
            <div class="stats-card rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 transition-all duration-300">إجمالي المنتجات</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $totalProducts }}</p>
                    </div>
                    <div class="text-blue-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-2 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 transition-all duration-300">المنتجات النشطة</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $activeProducts }}</p>
                    </div>
                    <div class="text-pink-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-3 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 transition-all duration-300">المنتجات غير النشطة</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $inactiveProducts }}</p>
                    </div>
                    <div class="text-cyan-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-4 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 transition-all duration-300">إجمالي المتغيرات</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $totalVariants }}</p>
                    </div>
                    <div class="text-green-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 transition-all duration-300" data-aos="fade-up" data-aos-delay="500">
            @if(request('search') || request('category') || request('vendor') || request('status'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg transition-all duration-300 hover:bg-blue-100">
                    <p class="text-sm text-blue-800 transition-all duration-300">
                        <strong class="transition-all duration-300">نتائج البحث:</strong> 
                        تم العثور على {{ $products->total() }} نتيجة
                        @if(request('search'))
                            للبحث "{{ request('search') }}"
                        @endif
                        @if(request('category'))
                            في فئة "{{ request('category') }}"
                        @endif
                        @if(request('vendor'))
                            للبائع "{{ request('vendor') }}"
                        @endif
                        @if(request('status'))
                            بحالة "{{ request('status') === 'active' ? 'نشط' : 'غير نشط' }}"
                        @endif
                    </p>
                </div>
            @endif
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row gap-4 transition-all duration-300">
                <div class="flex-1">
                    <input type="text" 
                           name="search"
                           placeholder="البحث في المنتجات..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           value="{{ request('search') }}">
                </div>
                {{-- <div class="flex gap-2 transition-all duration-300">
                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }} class="transition-all duration-300">
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    <select name="vendor" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع البائعين</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor }}" {{ request('vendor') === $vendor ? 'selected' : '' }} class="transition-all duration-300">
                                {{ $vendor }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع الحالات</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }} class="transition-all duration-300">نشط</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }} class="transition-all duration-300">غير نشط</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                        <span class="transition-all duration-300">تطبيق الفلاتر</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 transform hover:scale-105">
                        <span class="transition-all duration-300">مسح الفلاتر</span>
                    </a>
                </div> --}}
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 transition-all duration-300" id="productsGrid">
            @forelse($products as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300">
                    
                    <!-- Product Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ Str::limit($product->name, 30) }}</h3>
                                    <p class="text-sm text-gray-500">{{ $product->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-{{ $product->is_active ? 'active' : 'inactive' }}">
                                    @if($product->is_active)
                                        <span class="text-green-800">نشط</span>
                                    @else
                                        <span class="text-red-800">غير نشط</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Product Image -->
                        @if($product->image && $product->image->count() > 0)
                            <div class="mb-4 transition-all duration-300">
                                <img src="{{ asset('storage/' . $product->image->url) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover rounded-lg transition-all duration-300 hover:scale-105">
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الفئة:</span>
                                <span class="font-medium">{{ $product->category->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">البائع:</span>
                                <span class="font-medium">{{ $product->vendor->user->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المتغيرات:</span>
                                <span class="font-medium">{{ $product->variants->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">السعر:</span>
                                <span class="font-medium text-green-600">
                                    @if($product->variants->count() > 0)
                                        {{ number_format($product->variants->min('price_modifier'), 2) }} - {{ number_format($product->variants->max('price_modifier'), 2) }} ريال
                                    @else
                                        غير محدد
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="mt-4">
                            <p class="text-gray-700 text-sm">{{ Str::limit($product->description, 100) }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700 transition-all duration-200 text-sm transform hover:scale-105">
                                عرض التفاصيل
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700 transition-all duration-200 text-sm transform hover:scale-105">
                                تعديل
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-all duration-200 text-sm transform hover:scale-105">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 transition-all duration-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400 transition-all duration-300 hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 transition-all duration-300">
                        @if(request('search') || request('category') || request('vendor') || request('status'))
                            لا توجد نتائج للبحث
                        @else
                            لا توجد منتجات
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 transition-all duration-300">
                        @if(request('search') || request('category') || request('vendor') || request('status'))
                            جرب تغيير معايير البحث أو 
                            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-500 transition-all duration-300">مسح الفلاتر</a>
                        @else
                            ابدأ بإضافة منتج جديد.
                        @endif
                    </p>
                    @if(!request('search') && !request('category') && !request('vendor') && !request('status'))
                        <div class="mt-6 transition-all duration-300">
                            <a href="{{ route('vendor.products.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                                إضافة منتج جديد
                            </a>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 transition-all duration-300">
                        عرض {{ $products->firstItem() ?? 0 }} إلى {{ $products->lastItem() ?? 0 }} من {{ $products->total() }} نتيجة
                    </div>
                    <div class="flex items-center space-x-2 transition-all duration-300">
                        @if($products->onFirstPage())
                            <span class="pagination-link opacity-50 cursor-not-allowed">السابق</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="pagination-link">السابق</a>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="pagination-link pagination-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="pagination-link">التالي</a>
                        @else
                            <span class="pagination-link opacity-50 cursor-not-allowed">التالي</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-slot name="script">
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            // Auto-refresh every 30 seconds to show latest data
            setInterval(function() {
                // You can implement AJAX refresh here if needed
            }, 30000);

            // Add some interactivity for better UX
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form when filters change
                const filterSelects = document.querySelectorAll('select[name="category"], select[name="vendor"], select[name="status"]');
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

            function exportProducts() {
                // Implement export functionality
                alert('سيتم تصدير البيانات قريباً');
            }
        </script>
    </x-slot>
</x-app-layout>
