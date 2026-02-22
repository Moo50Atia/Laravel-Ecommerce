@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    .product-card {
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        @apply bg-white rounded-lg shadow-md p-6 border-l-4;
    }

    .status-badge {
        @apply px-2 py-1 text-xs font-semibold rounded-full;
    }

    .pagination {
        @apply mt-8 flex justify-center;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة المنتجات</h1>
            <p class="text-gray-600">عرض وإدارة جميع المنتجات المتاحة في المتجر</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة منتج جديد
            </a>
            <button onclick="exportProducts()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                تصدير
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card border-l-indigo-500">
            <p class="text-sm font-medium text-gray-500">إجمالي المنتجات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_products'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-green-500">
            <p class="text-sm font-medium text-gray-500">منتجات نشطة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['active_products'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-red-500">
            <p class="text-sm font-medium text-gray-500">نفذ من المخزن</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['out_of_stock'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-yellow-500">
            <p class="text-sm font-medium text-gray-500">متوسط السعر</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($statistics['avg_price'] ?? 0, 2) }}</p>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المنتج، الوصف..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">التصنيف</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البائع</label>
                <select name="vendor_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">كل البائعين</option>
                    @foreach($vendors ?? [] as $vendor)
                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->store_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="is_active" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المنتج</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التصنيف</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">البائع</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">السعر</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المخزون</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . ($product->image->url ?? 'default.jpg')) }}" class="w-10 h-10 rounded object-cover border">
                            <div>
                                <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $product->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-indigo-600">{{ $product->vendor->store_name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="{{ $product->total_stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ $product->total_stock }} وحدة
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900" title="عرض">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">لا توجد منتجات مطابقة للبحث</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', () => select.closest('form').submit());
        });
    });

    function exportProducts() {
        alert('جاري تجهيز البيانات للتصدير...');
    }
</script>
@endsection