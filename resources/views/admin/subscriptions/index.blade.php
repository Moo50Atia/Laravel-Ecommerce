@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">سجل الاشتراكات</h1>
            <p class="text-gray-600">عرض وإدارة اشتراكات المستخدمين والتجار</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المستخدم</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الخطة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">تاريخ البدء</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">تاريخ الانتهاء</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ substr($sub->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $sub->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $sub->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900">{{ $sub->plan->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($sub->status === 'canceled' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $sub->status === 'active' ? 'نشط' : ($sub->status === 'canceled' ? 'ملغي' : 'منتهي') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->start_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->end_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.subscriptions.show', $sub) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">تفاصيل</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        لا توجد اشتراكات حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 text-right" dir="ltr">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection