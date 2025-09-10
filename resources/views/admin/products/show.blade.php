<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-image {
                transition: all 0.3s ease;
            }
            .product-image:hover {
                transform: scale(1.05);
            }
            .variant-card {
                transition: all 0.3s ease;
            }
            .variant-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">تفاصيل المنتج</h1>
                    <p class="text-gray-600">عرض معلومات المنتج والتفاصيل الكاملة</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل المنتج
                    </a>
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-right">
                <h2 class="text-xl font-semibold mb-4">صور المنتج</h2>
                @if($product->images && $product->images->count() > 0)
                    <div class="swiper product-swiper">
                        <div class="swiper-wrapper">
                            @foreach($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->url) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-64 object-cover rounded-lg product-image">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">لا توجد صور للمنتج</p>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-left">
                <h2 class="text-xl font-semibold mb-4">معلومات المنتج</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">اسم المنتج</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">الوصف</label>
                        <p class="mt-1 text-gray-600">{{ $product->description ?? 'لا يوجد وصف' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">الفئة</label>
                            <p class="mt-1 text-gray-900">{{ $product->category->name ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">البائع</label>
                            <p class="mt-1 text-gray-900">{{ $product->vendor->user->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">الحالة</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">تاريخ الإنشاء</label>
                            <p class="mt-1 text-gray-900">{{ $product->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">عدد المتغيرات</label>
                            <p class="mt-1 text-gray-900">{{ $product->variants->count() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نطاق السعر</label>
                            <p class="mt-1 text-gray-900">
                                @if($product->variants->count() > 0)
                                    {{ number_format($product->variants->min('price_modifier'), 2) }} - {{ number_format($product->variants->max('price_modifier'), 2) }} جنيه
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Variants -->
        @if($product->variants && $product->variants->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8" data-aos="fade-up">
                <h2 class="text-xl font-semibold mb-4">متغيرات المنتج</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($product->variants as $variant)
                        <div class="variant-card bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $variant->option_name ?? 'متغير ' . $loop->iteration }}</h3>
                                <span class="text-lg font-bold text-green-600">{{ number_format($variant->price_modifier, 2) }} جنيه</span>
                            </div>
                            
                            <div class="space-y-1 text-sm text-gray-600">
                                @if($variant->stock !== null)
                                    <div>المخزون: {{ $variant->stock_quantity }}</div>
                                @endif
                            </div>

                            @if($variant->images && $variant->images->count() > 0)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $variant->images->first()->url) }}" 
                                         alt="{{ $variant->name }}" 
                                         class="w-full h-20 object-cover rounded">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                تعديل المنتج
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')"
                        class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition duration-300 transform hover:scale-105">
                    حذف المنتج
                </button>
            </form>
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition duration-300 transform hover:scale-105">
                العودة للقائمة
            </a>
        </div>
    </div>

    <x-slot name="script">
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            // Initialize Swiper for product images
            const swiper = new Swiper('.product-swiper', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        </script>
    </x-slot>
</x-app-layout>
