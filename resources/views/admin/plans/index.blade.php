@extends('layouts.app')

@section('style')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }

    .btn-premium {
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        transition: all 0.3s ease;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">خطط الاشتراك</h1>
            <p class="text-gray-600">إدارة خطط الاشتراكات للمستخدمين والتجار</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn-premium text-white px-6 py-2 rounded-lg font-medium">
            إضافة خطة جديدة
        </a>
    </div>

    <div class="glass-card rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">اسم الخطة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">النوع</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">السعر</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المدة (أيام)</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $plan->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->type === 'vendor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $plan->type === 'vendor' ? 'تاجر' : 'مستخدم' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-900 font-semibold">${{ number_format($plan->price, 2) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->duration_days }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $plan->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        لا توجد خطط حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $plans->links() }}
    </div>
</div>
@endsection