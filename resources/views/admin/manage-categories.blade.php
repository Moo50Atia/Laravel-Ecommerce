@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة التصنيفات</h1>
            <p class="text-gray-600">تنظيم المنتجات في تصنيفات لتسهيل التصفح</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
            إضافة تصنيف جديد
        </a>
    </div>

    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500 font-bold">إجمالي التصنيفات</p>
            <p class="text-2xl font-bold">{{ $statistics['total_categories'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-bold">نشط</p>
            <p class="text-2xl font-bold">{{ $statistics['active_categories'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 font-bold">تصنيفات رئيسية</p>
            <p class="text-2xl font-bold">{{ $statistics['parent_categories'] ?? 0 }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الاسم</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التصنيف الأب</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المنتجات</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $category->name }}</div>
                        <div class="text-xs text-gray-400">{{ Str::limit($category->description, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $category->parent->name ?? 'رئيسي' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-indigo-600">{{ $category->products_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف جميع التصنيفات الفرعية والمنتجات المرتبطة!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا توجد تصنيفات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection