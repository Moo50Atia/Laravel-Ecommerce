@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">تعديل الخطة: {{ $plan->name }}</h1>
            <p class="text-gray-600">تحديث تفاصيل وسعر خطة الاشتراك</p>
        </div>

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم الخطة</label>
                    <input type="text" name="name" value="{{ $plan->name }}" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الخطة</label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="user" {{ $plan->type === 'user' ? 'selected' : '' }}>مستخدم عادي</option>
                        <option value="vendor" {{ $plan->type === 'vendor' ? 'selected' : '' }}>تاجر</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">السعر ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ $plan->price }}" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المدة (بالأيام)</label>
                    <input type="number" name="duration_days" value="{{ $plan->duration_days }}" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">المميزات</label>
                <div id="features-container" class="space-y-3">
                    @if($plan->features)
                    @foreach($plan->features as $feature)
                    <div class="flex gap-2">
                        <input type="text" name="features[]" value="{{ $feature }}" class="flex-1 rounded-lg border-gray-300">
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                    @else
                    <div class="flex gap-2">
                        <input type="text" name="features[]" class="flex-1 rounded-lg border-gray-300" placeholder="ميزة">
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addFeature()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ إضافة ميزة أخرى</button>
            </div>

            <div class="mb-8">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-600 font-medium">تفعيل الخطة</span>
                </label>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.plans.index') }}" class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">إلغاء</a>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded-lg hover:bg-indigo-700 transition-colors">تحديث الخطة</button>
            </div>
        </form>
    </div>
</div>

<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" name="features[]" class="flex-1 rounded-lg border-gray-300" placeholder="ميزة إضافية">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(div);
    }
</script>
@endsection