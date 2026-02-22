@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    .order-card {
        transition: all 0.3s ease;
    }

    .order-card:hover {
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
            <h1 class="text-3xl font-bold text-gray-900">إدارة الطلبات</h1>
            <p class="text-gray-600">متابعة وإدارة طلبات العملاء وحالات الشحن</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.exportOrders()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                تصدير البيانات
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card border-l-blue-500">
            <p class="text-sm font-medium text-gray-500">إجمالي الطلبات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_orders'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-yellow-500">
            <p class="text-sm font-medium text-gray-500">قيد المعالجة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['processing_orders'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-green-500">
            <p class="text-sm font-medium text-gray-500">مكتملة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['completed_orders'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-purple-500">
            <p class="text-sm font-medium text-gray-500">إجمالي الإيرادات</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($statistics['total_revenue'] ?? 0, 2) }}</p>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم الطلب، اسم العميل..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($statuses ?? [] as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">حالة الدفع</label>
                <select name="payment_status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($paymentStatuses ?? [] as $key => $label)
                    <option value="{{ $key }}" {{ request('payment_status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">رقم الطلب</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">العميل</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">القيمة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">حالة الطلب</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">حالة الدفع</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">#{{ $order->order_number }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $order->user->name ?? 'عميل غير معروف' }}</div>
                        <div class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="status-badge {{ 
                            $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) 
                        }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge {{ 
                            $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' 
                        }}">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900" title="عرض">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">لا توجد طلبات مطابقة للبحث</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $orders->links() }}
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
@vite(['resources/js/admin/orders.js'])
@endsection