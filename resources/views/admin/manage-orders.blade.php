<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .order-card {
                transition: all 0.3s ease;
            }
            .order-card:hover {
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
            .status-pending { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-processing { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
            .status-shipped { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-delivered { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-canceled { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .status-refunded { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 transition-all duration-300">إدارة الطلبات</h1>
                    <p class="text-gray-600 transition-all duration-300">إدارة وتتبع جميع طلبات العملاء</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2 transition-all duration-300">
                    <button onclick="exportOrders()" 
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
                        <p class="text-blue-100 transition-all duration-300">إجمالي الطلبات</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $allOrdersCount }}</p>
                    </div>
                    <div class="text-blue-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-2 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 transition-all duration-300">إجمالي المبيعات</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $totalSails }} ريال</p>
                    </div>
                    <div class="text-pink-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-3 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 transition-all duration-300">طلبات معلقة</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $pendingOrders }}</p>
                    </div>
                    <div class="text-cyan-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-4 rounded-lg p-6 text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 transition-all duration-300">طلبات مكتملة</p>
                        <p class="text-3xl font-bold transition-all duration-300">{{ $CompletedOrders }}</p>
                    </div>
                    <div class="text-green-100 transition-all duration-300">
                        <svg class="w-8 h-8 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 transition-all duration-300" data-aos="fade-up" data-aos-delay="500">
            @if(request('search') || request('status') || request('payment_method') || request('payment_status'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg transition-all duration-300 hover:bg-blue-100">
                    <p class="text-sm text-blue-800 transition-all duration-300">
                        <strong class="transition-all duration-300">نتائج البحث:</strong> 
                        تم العثور على {{ $orders->total() }} نتيجة
                        @if(request('search'))
                            للبحث "{{ request('search') }}"
                        @endif
                        @if(request('status'))
                            بحالة "{{ request('status') }}"
                        @endif
                        @if(request('payment_method'))
                            بطريقة دفع "{{ request('payment_method') }}"
                        @endif
                        @if(request('payment_status'))
                            بحالة دفع "{{ request('payment_status') }}"
                        @endif
                    </p>
                </div>
            @endif
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4 transition-all duration-300">
                <div class="flex-1">
                    <input type="text" 
                           name="search"
                           placeholder="البحث في الطلبات..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           value="{{ request('search') }}">
                </div>
                <div class="flex gap-2 transition-all duration-300">
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }} class="transition-all duration-300">
                                @switch($status)
                                    @case('pending')
                                        معلق
                                        @break
                                    @case('processing')
                                        قيد المعالجة
                                        @break
                                    @case('shipped')
                                        تم الشحن
                                        @break
                                    @case('delivered')
                                        تم التوصيل
                                        @break
                                    @case('canceled')
                                        ملغي
                                        @break
                                    @case('refunded')
                                        مسترد
                                        @break
                                    @default
                                        {{ $status }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    <select name="payment_method" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع طرق الدفع</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ request('payment_method') === $method ? 'selected' : '' }} class="transition-all duration-300">
                                @switch($method)
                                    @case('credit_card')
                                        بطاقة ائتمان
                                        @break
                                    @case('cod')
                                        الدفع عند الاستلام
                                        @break
                                    @case('bank_transfer')
                                        تحويل بنكي
                                        @break
                                    @default
                                        {{ $method }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    <select name="payment_status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="" class="transition-all duration-300">جميع حالات الدفع</option>
                        @foreach($paymentStatuses as $paymentStatus)
                            <option value="{{ $paymentStatus }}" {{ request('payment_status') === $paymentStatus ? 'selected' : '' }} class="transition-all duration-300">
                                @switch($paymentStatus)
                                    @case('paid')
                                        مدفوع
                                        @break
                                    @case('unpaid')
                                        غير مدفوع
                                        @break
                                    @case('failed')
                                        فشل في الدفع
                                        @break
                                    @default
                                        {{ $paymentStatus }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                        <span class="transition-all duration-300">تطبيق الفلاتر</span>
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 transform hover:scale-105">
                        <span class="transition-all duration-300">مسح الفلاتر</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 transition-all duration-300" id="ordersGrid">
            @forelse($orders as $order)
                <div class="order-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300">
                    
                    <!-- Order Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-{{ $order->status }}">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="text-yellow-800">معلق</span>
                                            @break
                                        @case('processing')
                                            <span class="text-blue-800">قيد المعالجة</span>
                                            @break
                                        @case('shipped')
                                            <span class="text-purple-800">تم الشحن</span>
                                            @break
                                        @case('delivered')
                                            <span class="text-green-800">تم التوصيل</span>
                                            @break
                                        @case('canceled')
                                            <span class="text-red-800">ملغي</span>
                                            @break
                                        @case('refunded')
                                            <span class="text-gray-800">مسترد</span>
                                            @break
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->user->name ?? 'عميل غير محدد' }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->email ?? 'لا يوجد بريد إلكتروني' }}</p>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">عدد المنتجات:</span>
                                <span class="font-medium">{{ $order->items->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">طريقة الدفع:</span>
                                <span class="font-medium">
                                    @switch($order->payment_method)
                                        @case('credit_card')
                                            بطاقة ائتمان
                                            @break
                                        @case('cod')
                                            الدفع عند الاستلام
                                            @break
                                        @case('bank_transfer')
                                            تحويل بنكي
                                            @break
                                        @default
                                            غير محدد
                                    @endswitch
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">حالة الدفع:</span>
                                <span class="font-medium">
                                    @switch($order->payment_status)
                                        @case('paid')
                                            <span class="text-green-600">مدفوع</span>
                                            @break
                                        @case('unpaid')
                                            <span class="text-red-600">غير مدفوع</span>
                                            @break
                                        @case('failed')
                                            <span class="text-red-600">فشل</span>
                                            @break
                                        @default
                                            <span class="text-gray-600">غير محدد</span>
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Total -->
                    <div class="px-6 py-4 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">الإجمالي:</span>
                            <span class="text-xl font-bold text-blue-600">{{ number_format($order->grand_total, 2) }} ريال</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700 transition text-sm">
                                عرض التفاصيل
                            </a>
                            <a href="{{ route('admin.orders.edit', $order) }}" 
                               class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700 transition text-sm">
                                تعديل
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 transition-all duration-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400 transition-all duration-300 hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 transition-all duration-300">
                        @if(request('search') || request('status') || request('payment_method') || request('payment_status'))
                            لا توجد نتائج للبحث
                        @else
                            لا توجد طلبات
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 transition-all duration-300">
                        @if(request('search') || request('status') || request('payment_method') || request('payment_status'))
                            جرب تغيير معايير البحث أو 
                            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-500 transition-all duration-300">مسح الفلاتر</a>
                        @else
                            لم يتم إنشاء أي طلبات بعد
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 transition-all duration-300">
                        عرض {{ $orders->firstItem() ?? 0 }} إلى {{ $orders->lastItem() ?? 0 }} من {{ $orders->total() }} نتيجة
                    </div>
                    <div class="flex items-center space-x-2 transition-all duration-300">
                        @if($orders->onFirstPage())
                            <span class="pagination-link opacity-50 cursor-not-allowed">السابق</span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}" class="pagination-link">السابق</a>
                        @endif

                        @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            @if($page == $orders->currentPage())
                                <span class="pagination-link pagination-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}" class="pagination-link">التالي</a>
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
                const filterSelects = document.querySelectorAll('select[name="status"], select[name="payment_method"], select[name="payment_status"]');
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

            function exportOrders() {
                // Implement export functionality
                alert('سيتم تصدير البيانات قريباً');
            }
        </script>
    </x-slot>
</x-app-layout>
