@extends('layouts.app')

@section('style')
<style>
    .tracking-line {
        position: absolute;
        top: 24px;
        bottom: 0;
        right: 23px;
        width: 2px;
        background: #e5e7eb;
    }

    .step-active .step-dot {
        @apply ring-4 ring-indigo-100 bg-indigo-600;
    }

    .step-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #d1d5db;
        z-index: 10;
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">تتبع طلبك</h1>
                <p class="text-gray-600 mt-2">رقم الطلب: #{{ $order->order_number }}</p>
            </div>
            <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-bold">
                {{ strtoupper($order->status) }}
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8 relative">
            <div class="tracking-line"></div>

            <div class="space-y-12">
                @foreach($order->statusHistory as $index => $history)
                <div class="relative flex items-start gap-6 step-active">
                    <div class="step-dot mt-2"></div>
                    <div class="flex-1 pb-4 border-b border-gray-50">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $history->to_status === 'pending' ? 'تم استلام الطلب' : 
                                   ($history->to_status === 'processing' ? 'قيد التجهيز' : 
                                   ($history->to_status === 'shipped' ? 'جاري التوصيل' : 
                                   ($history->to_status === 'completed' ? 'تم التوصيل بنجاح' : 'تحديث الحالة'))) }}
                            </h3>
                            <span class="text-sm text-gray-400 font-medium">{{ $history->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <p class="text-gray-600">{{ $history->notes ?? 'يتم العمل على طلبك حالياً.' }}</p>
                        <p class="text-xs text-gray-400 mt-2">بواسطة: {{ $history->changer->name ?? 'النظام' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('user.orders') }}" class="text-indigo-600 font-bold hover:underline">العودة لقائمة طلباتي</a>
        </div>
    </div>
</div>
@endsection