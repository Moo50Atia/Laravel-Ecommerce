<x-app-layout>
    <x-slot name="style">
        <style>
            .order-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card {
                transition: all 0.3s ease;
            }
            .stats-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-timeline {
                position: relative;
            }
            .status-timeline::before {
                content: '';
                position: absolute;
                left: 20px;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #e5e7eb;
            }
            .timeline-item {
                position: relative;
                padding-left: 50px;
                margin-bottom: 20px;
            }
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 12px;
                top: 0;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #3b82f6;
                border: 3px solid #fff;
                box-shadow: 0 0 0 3px #e5e7eb;
            }
            .timeline-item.active::before {
                background: #10b981;
                box-shadow: 0 0 0 3px #d1fae5;
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="order-header rounded-lg p-8 text-white mb-8" data-aos="fade-down">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $order->order_number }}</h1>
                        <p class="text-blue-100">تم إنشاؤه في {{ $order->created_at->format('Y-m-d H:i') }}</p>
                        @if($order->user)
                            <p class="text-blue-100 text-sm">العميل: {{ $order->user->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2">
                    <a href="{{ route('admin.orders.edit', $order) }}" 
                       class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل الطلب
                    </a>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-white rounded-lg p-6 shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">حالة الطلب</p>
                        <p class="text-lg font-bold text-blue-600">
                            @switch($order->status)
                                @case('pending')
                                    <span class="text-yellow-600">معلق</span>
                                    @break
                                @case('processing')
                                    <span class="text-blue-600">قيد المعالجة</span>
                                    @break
                                @case('shipped')
                                    <span class="text-purple-600">تم الشحن</span>
                                    @break
                                @case('delivered')
                                    <span class="text-green-600">تم التوصيل</span>
                                    @break
                                @case('canceled')
                                    <span class="text-red-600">ملغي</span>
                                    @break
                                @case('refunded')
                                    <span class="text-gray-600">مسترد</span>
                                    @break
                            @endswitch
                        </p>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-lg p-6 shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">حالة الدفع</p>
                        <p class="text-lg font-bold text-green-600">
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
                        </p>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-lg p-6 shadow-md" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">عدد المنتجات</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $order->items->count() }}</p>
                    </div>
                    <div class="text-purple-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-lg p-6 shadow-md" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الطلب</p>
                        <p class="text-3xl font-bold text-orange-600">{{ number_format($order->grand_total, 2) }} جنيه</p>
                    </div>
                    <div class="text-orange-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-1" data-aos="fade-up" data-aos-delay="500">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">معلومات الطلب</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">رقم الطلب</label>
                            <p class="text-gray-900 font-medium">{{ $order->order_number }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">تاريخ الإنشاء</label>
                            <p class="text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">آخر تحديث</label>
                            <p class="text-gray-900">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">طريقة الدفع</label>
                            <p class="text-gray-900">
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
                            </p>
                        </div>
                        
                        @if($order->notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500">ملاحظات</label>
                                <p class="text-gray-900">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                @if($order->user)
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6" data-aos="fade-up" data-aos-delay="600">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">معلومات العميل</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">الاسم</label>
                                <p class="text-gray-900 font-medium">{{ $order->user->name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                                <p class="text-gray-900">{{ $order->user->email }}</p>
                            </div>
                            
                            @if($order->user->phone)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">رقم الهاتف</label>
                                    <p class="text-gray-900">{{ $order->user->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6" data-aos="fade-up" data-aos-delay="700">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">مسار الطلب</h3>
                    
                    <div class="status-timeline">
                        <div class="timeline-item {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="text-sm text-gray-500">تم إنشاء الطلب</div>
                            <div class="text-xs text-gray-400">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="text-sm text-gray-500">قيد المعالجة</div>
                            <div class="text-xs text-gray-400">
                                @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                    {{ $order->updated_at->format('Y-m-d H:i') }}
                                @else
                                    قيد الانتظار
                                @endif
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                            <div class="text-sm text-gray-500">تم الشحن</div>
                            <div class="text-xs text-gray-400">
                                @if(in_array($order->status, ['shipped', 'delivered']))
                                    {{ $order->updated_at->format('Y-m-d H:i') }}
                                @else
                                    قيد الانتظار
                                @endif
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                            <div class="text-sm text-gray-500">تم التوصيل</div>
                            <div class="text-xs text-gray-400">
                                @if($order->status === 'delivered')
                                    {{ $order->updated_at->format('Y-m-d H:i') }}
                                @else
                                    قيد الانتظار
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="lg:col-span-2" data-aos="fade-up" data-aos-delay="800">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">المنتجات المطلوبة</h3>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            {{ $order->items->count() }} منتج
                        </span>
                    </div>

                    @if($order->items->count() > 0)
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="product-card bg-gray-50 rounded-lg p-4 border">
                                    <div class="flex items-center gap-4">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image->url) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-16 h-16 rounded object-cover">
                                        @else
                                            <div class="w-16 h-16 bg-gray-300 rounded flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->product->name ?? 'منتج غير محدد' }}</h4>
                                            <p class="text-sm text-gray-500">الكمية: {{ $item->quantity }}</p>
                                            @if($item->variant)
                                                <p class="text-sm text-gray-500">المتغير: {{ $item->variant->name }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-700 font-medium">{{ number_format($item->price, 2) }} جنيه</p>
                                            <p class="text-sm text-gray-500">المجموع: {{ number_format($item->price * $item->quantity, 2) }} جنيه</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-8 border-t pt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">ملخص الطلب</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">إجمالي المنتجات:</span>
                                    <span class="font-medium">{{ number_format($order->total_amount ?? 0, 2) }} جنيه</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الخصم:</span>
                                        <span class="font-medium text-green-600">-{{ number_format($order->discount_amount, 2 ?? 0) }} جنيه</span>
                                    </div>
                                @endif
                                @if($order->shipping_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الشحن:</span>
                                        <span class="font-medium">{{ number_format($order->shipping_amount, 2) }} جنيه</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span class="text-gray-900">الإجمالي النهائي:</span>
                                    <span class="text-blue-600">{{ number_format($order->grand_total, 2) }} جنيه</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                            <p class="text-gray-600">لم يتم العثور على منتجات في هذا الطلب</p>
                        </div>
                    @endif
                </div>

                <!-- Address Information -->
                @if($order->shipping_address || $order->billing_address)
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6" data-aos="fade-up" data-aos-delay="900">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">معلومات العنوان</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($order->shipping_address)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">عنوان الشحن</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if(is_array($order->shipping_address))
                                            @foreach($order->shipping_address as $key => $value)
                                                @if($value)
                                                    <p><span class="font-medium">{{ ucfirst($key) }}:</span> {{ $value }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p>{{ $order->shipping_address }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            @if($order->billing_address)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">عنوان الفواتير</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if(is_array($order->billing_address))
                                            @foreach($order->billing_address as $key => $value)
                                                @if($value)
                                                    <p><span class="font-medium">{{ ucfirst($key) }}:</span> {{ $value }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p>{{ $order->billing_address }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
