@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">مراقبة المخزون العالمي</h1>
            <p class="text-gray-600">تتبع حركات المخزون لجميع المنتجات عبر جميع المتاجر</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المنتج</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الكمية</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">النوع</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التاجر</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">ملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($movements as $movement)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $movement->product->name }}</div>
                        @if($movement->variant)
                        <div class="text-xs text-gray-500">{{ $movement->variant->name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 
                               ($movement->type === 'out' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ strtoupper($movement->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $movement->product->vendor->store_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $movement->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ Str::limit($movement->notes, 30) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        لا توجد حركات مخزون مسجلة
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $movements->links() }}
    </div>
</div>
@endsection