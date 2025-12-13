@extends('layouts.app')

@section('style')
<style>
    .stat-card { @apply bg-white rounded-lg shadow-md p-6 border-l-4; }
    .stat-card.orders { @apply border-l-blue-500; }
    .stat-card.products { @apply border-l-green-500; }
    .stat-card.users { @apply border-l-purple-500; }
    .stat-card.revenue { @apply border-l-yellow-500; }
    .stat-number { @apply text-3xl font-bold text-gray-800; }
    .stat-label { @apply text-sm font-medium text-gray-600; }
    .stat-change { @apply text-sm font-medium; }
    .stat-change.positive { @apply text-green-600; }
    .stat-change.negative { @apply text-red-600; }
    .chart-container { @apply bg-white rounded-lg shadow-md p-6; }
    .quick-action { @apply bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow cursor-pointer; }
    .recent-item { @apply flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">لوحة التحكم</h1>
            <p class="text-gray-600 mt-2">مرحباً بك في لوحة إدارة المتجر الإلكتروني</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="stat-card orders">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">إجمالي الطلبات</p>
                        <p class="stat-number">{{ $totalOrders ?? 0 }}</p>
                        <p class="stat-change positive">+{{ $newOrders ?? 0 }} جديد</p>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="stat-card products">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">إجمالي المنتجات</p>
                        <p class="stat-number">{{ $totalProducts ?? 0 }}</p>
                        <p class="stat-change positive">+{{ $newProducts ?? 0 }} جديد</p>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="stat-card users">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">إجمالي المستخدمين</p>
                        <p class="stat-number">{{ $totalUsers ?? 0 }}</p>
                        <p class="stat-change positive">+{{ $newUsers ?? 0 }} جديد</p>
                    </div>
                    <div class="text-purple-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card revenue">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">إجمالي الإيرادات</p>
                        <p class="stat-number">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                        <p class="stat-change positive">+{{ $revenueGrowth ?? 0 }}% هذا الشهر</p>
                    </div>
                    <div class="text-yellow-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Orders Chart -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">إحصائيات الطلبات</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">الإيرادات الشهرية</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Orders by Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">الطلبات حسب الحالة</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">قيد الانتظار</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $ordersByStatus['pending'] ?? 0 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">قيد المعالجة</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $ordersByStatus['processing'] ?? 0 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">مكتمل</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $ordersByStatus['completed'] ?? 0 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">ملغي</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $ordersByStatus['cancelled'] ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">أفضل المنتجات مبيعاً</h3>
                <div class="space-y-3">
                    @forelse($topProducts ?? [] as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('storage' . ($product->image->url ?? '')) }}" 
                                 alt="{{ $product->name ?? 'Product' }}" 
                                 class="w-8 h-8 rounded object-cover">
                            <span class="text-sm font-medium text-gray-800">{{ Str::limit($product->name ?? 'Unknown Product', 20) }}</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $product->order_items_count ?? 0 }} طلب</span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">لا توجد بيانات متاحة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">إجراءات سريعة</h3>
                
                <div class="quick-action" onclick="window.location.href='{{ route('admin.products.create') }}'">
                    <div class="flex items-center space-x-3">
                        <div class="text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">إضافة منتج جديد</p>
                            <p class="text-sm text-gray-600">إضافة منتج جديد للمتجر</p>
                        </div>
                    </div>
                </div>

                <div class="quick-action" onclick="window.location.href='{{ route('admin.orders.index') }}'">
                    <div class="flex items-center space-x-3">
                        <div class="text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">إدارة الطلبات</p>
                            <p class="text-sm text-gray-600">عرض وإدارة جميع الطلبات</p>
                        </div>
                    </div>
                </div>

                <div class="quick-action" onclick="window.location.href='{{ route('admin.users.index') }}'">
                    <div class="flex items-center space-x-3">
                        <div class="text-purple-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">إدارة المستخدمين</p>
                            <p class="text-sm text-gray-600">عرض وإدارة المستخدمين</p>
                        </div>
                    </div>
                </div>

                
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">أحدث الطلبات</h3>
                <div class="space-y-3">
                    @forelse($recentOrders ?? [] as $order)
                    <div class="recent-item">
                        <div>
                            <p class="font-medium text-gray-800">#{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-600">{{ $order->user->name ?? 'Unknown User' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-800">${{ number_format($order->grand_total ?? 0, 2) }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($order->status ?? 'Unknown') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">لا توجد طلبات حديثة</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        عرض جميع الطلبات →
                    </a>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">أحدث المنتجات</h3>
                <div class="space-y-3">
                    @forelse($recentProducts ?? [] as $product)
                    <div class="recent-item">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('storage' . ($product->image->url ?? '')) }}" 
                                 alt="{{ $product->name ?? 'Product' }}" 
                                 class="w-10 h-10 rounded object-cover">
                            <div>
                                <p class="font-medium text-gray-800">{{ Str::limit($product->name ?? 'Unknown Product', 20) }}</p>
                                <p class="text-sm text-gray-600">${{ number_format($product->price ?? 0, 2) }}</p>
                            </div>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $product->vendor ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->vendor ? 'متوفر' : 'غير متوفر' }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">لا توجد منتجات حديثة</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        عرض جميع المنتجات →
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">حالة النظام</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-800">المتجر يعمل</p>
                    <p class="text-sm text-gray-600">جميع الأنظمة تعمل بشكل طبيعي</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-800">آخر تحديث</p>
                    <p class="text-sm text-gray-600">{{ now()->format('Y-m-d H:i') }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-800">إحصائيات</p>
                    <p class="text-sm text-gray-600">تم تحديث البيانات</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);

    // Add click animations to quick actions
    document.querySelectorAll('.quick-action').forEach(action => {
        action.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });

    // Chart.js Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart');
        if (ordersCtx) {
            new Chart(ordersCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyOrders->pluck('month')) !!},
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: {!! json_encode($monthlyOrders->pluck('orders')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
                    datasets: [{
                        label: 'الإيرادات ($)',
                        data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection