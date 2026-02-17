@extends('layouts.app')

@section('style')
<style>
    .plan-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }

    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .plan-card.active {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.02);
    }

    .feature-item::before {
        content: '✓';
        margin-left: 8px;
        color: #10b981;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">خطط الاشتراك</h1>
        <p class="text-xl text-gray-600">اختر الخطة التي تناسب احتياجاتك وتطور تجارتك</p>
    </div>

    @if($subscription)
    <div class="max-w-4xl mx-auto mb-16 bg-white rounded-2xl shadow-sm border border-indigo-100 p-8 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-indigo-600">اشتراكك الحالي</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $subscription->plan->name }}</h3>
                <p class="text-gray-500">ينتهي في: {{ $subscription->end_date->format('Y-m-d') }}</p>
            </div>
        </div>
        <div class="flex gap-4">
            <form action="{{ route('user.subscription.cancel') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء الاشتراك؟')">
                @csrf
                <button type="submit" class="px-6 py-2 rounded-lg text-red-600 font-medium hover:bg-red-50 transition-colors">إلغاء الاشتراك</button>
            </form>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @foreach($availablePlans as $plan)
        <div class="plan-card bg-white rounded-2xl shadow-sm p-8 flex flex-col {{ optional($subscription)->plan_id === $plan->id ? 'active' : '' }}">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                <div class="flex items-baseline">
                    <span class="text-4xl font-extrabold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                    <span class="text-gray-500 mr-1">/{{ $plan->duration_days }} يوم</span>
                </div>
            </div>

            <ul class="space-y-4 mb-10 flex-1">
                @if($plan->features)
                @foreach($plan->features as $feature)
                <li class="feature-item text-gray-600">{{ $feature }}</li>
                @endforeach
                @else
                <li class="feature-item text-gray-600">الوصول الكامل لجميع المميزات</li>
                <li class="feature-item text-gray-600">دعم فني متميز</li>
                @endif
            </ul>

            <form action="{{ route('user.subscription.subscribe') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button type="submit"
                    class="w-full py-3 rounded-xl font-bold transition-all {{ optional($subscription)->plan_id === $plan->id ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200' }}"
                    {{ optional($subscription)->plan_id === $plan->id ? 'disabled' : '' }}>
                    {{ optional($subscription)->plan_id === $plan->id ? 'خطتك الحالية' : 'اشترك الآن' }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection