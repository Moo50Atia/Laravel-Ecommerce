@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تفاصيل الاشتراك</h1>
                <p class="text-gray-600">عرض وتعديل ملف الاشتراك للمستخدم #{{ $subscription->id }}</p>
            </div>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة للقائمة
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Stats & Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">حالة الاشتراك</h3>
                                <p class="text-sm text-gray-500">اشتراك {{ $subscription->plan->name }}</p>
                            </div>
                        </div>
                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full 
                            {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($subscription->status === 'canceled' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ strtoupper($subscription->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-8 py-6 border-y border-gray-50">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">تاريخ البدء</p>
                            <p class="text-gray-900 font-medium">{{ $subscription->start_date->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">تاريخ الانتهاء</p>
                            <p class="text-gray-900 font-medium">{{ $subscription->end_date->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-3">تفاصيل العميل</p>
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 border border-gray-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $subscription->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $subscription->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Update Form -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-6">تحديث الاشتراك</h3>
                    <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500">
                                <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>نشط (Active)</option>
                                <option value="canceled" {{ $subscription->status === 'canceled' ? 'selected' : '' }}>ملغي (Canceled)</option>
                                <option value="expired" {{ $subscription->status === 'expired' ? 'selected' : '' }}>منتهي (Expired)</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء</label>
                            <input type="date" name="end_date" value="{{ $subscription->end_date->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                            حفظ التغييرات
                        </button>
                    </form>
                </div>

                <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                    <h3 class="font-bold text-red-900 mb-2">منطقة الخطر</h3>
                    <p class="text-sm text-red-700 mb-4">هذا الإجراء سيقوم بحذف سجل الاشتراك تماماً من قاعدة البيانات.</p>
                    <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-white text-red-600 border border-red-200 py-2 rounded-lg font-medium hover:bg-red-100 transition-colors">
                            حذف السجل
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection