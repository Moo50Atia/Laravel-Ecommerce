@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">إعدادات المتجر</h1>

        <form action="{{ route('vendor.settings.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المتجر</label>
                        <input type="text" name="store_name" value="{{ $vendor->store_name }}" required class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">بريد المتجر الإلكتروني</label>
                        <input type="email" name="store_email" value="{{ $vendor->store_email }}" required class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">وصف المتجر</label>
                    <textarea name="description" rows="4" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">{{ $vendor->description }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">هاتف المتجر</label>
                        <input type="text" name="store_phone" value="{{ $vendor->store_phone }}" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500" placeholder="+20 123 456 789">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان الفعلي</label>
                        <input type="text" name="address" value="{{ $vendor->address }}" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection