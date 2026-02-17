@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة الكوبونات</h1>
            <p class="text-gray-600">تحفيز المبيعات عبر الخصومات والعروض</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
            إضافة كوبون جديد
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الرمز (Code)</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الخصم</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحد الأدنى</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">تاريخ الانتهاء</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded border border-indigo-100">
                            {{ $coupon->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->type === 'percentage')
                        {{ $coupon->discount }}%
                        @else
                        ${{ number_format($coupon->discount, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        ${{ number_format($coupon->minimum_amount ?? 0, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'دائم' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $coupon->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $coupon->status === 'active' ? 'نشط' : 'ملغي' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">لا توجد كوبونات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
</div>
@endsection