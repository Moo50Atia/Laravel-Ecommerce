@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة المخزون</h1>
            <p class="text-gray-600">تحديث وتتبع كميات المنتجات المتوفرة في متجرك</p>
        </div>
        <button onclick="document.getElementById('movement-modal').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
            تحديث المخزون
        </button>
    </div>

    <!-- Inventory Log -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المنتج</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الكمية</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
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
                        <span class="text-xs text-indigo-600">{{ $movement->variant->name }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $movement->type === 'in' ? 'توريد' : 'صرف / تعديل' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $movement->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ $movement->notes }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        لا توجد حركات مخزون حديثة
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Simplified Modal for Stock Update -->
    <div id="movement-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-6">تحديث المخزون</h3>
            <form action="{{ route('vendor.inventory.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اختر المنتج</label>
                        <select name="product_id" required class="w-full rounded-lg border-gray-300">
                            @foreach(Auth::user()->vendor->products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الكمية (+ للزيادة، - للنقص)</label>
                        <input type="number" name="quantity" required class="w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع الحركة</label>
                        <select name="type" class="w-full rounded-lg border-gray-300">
                            <option value="in">توريد (In)</option>
                            <option value="out">صرف (Out)</option>
                            <option value="adjustment">تعديل (Adjustment)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                        <textarea name="notes" class="w-full rounded-lg border-gray-300" rows="3"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex gap-4">
                    <button type="button" onclick="document.getElementById('movement-modal').classList.add('hidden')" class="flex-1 py-2 border rounded-lg hover:bg-gray-50">إلغاء</button>
                    <button type="submit" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection