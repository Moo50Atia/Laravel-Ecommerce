@extends('layouts.app')

@section('style')
<style>
    .blog-card {
        @apply bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1;
    }

    .stats-card {
        @apply bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1;
    }

    .status-badge {
        @apply px-2 py-1 text-xs font-semibold rounded-full;
    }

    .pagination {
        @apply mt-8 flex justify-center;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة المدونة</h1>
            <p class="text-gray-600">إدارة المقالات، الأخبار، والمحتوى التعليمي</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            إضافة مقال جديد
        </a>
    </div>

    <!-- Statistics Grid -->
    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card">
            <p class="text-sm font-medium text-gray-500">إجمالي المقالات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_blogs'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-green-500">
            <p class="text-sm font-medium text-gray-500">مقالات منشورة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['published_blogs'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-yellow-500">
            <p class="text-sm font-medium text-gray-500">مسودات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['draft_blogs'] ?? 0 }}</p>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.blogs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="عنوان المقال، المحتوى..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الكاتب</label>
                <select name="author" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">كل الكتاب</option>
                    @foreach($authors ?? [] as $author)
                    <option value="{{ $author }}" {{ request('author') == $author ? 'selected' : '' }}>{{ $author }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المقال</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الكاتب</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المشاهدات</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">تاريخ النشر</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($blogs as $blog)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . ($blog->image->url ?? 'default-blog.jpg')) }}" class="w-12 h-12 rounded object-cover border">
                            <div>
                                <div class="font-bold text-gray-900">{{ Str::limit($blog->title, 40) }}</div>
                                <div class="text-xs text-gray-400">{{ $blog->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $blog->author->name ?? 'مجهول' }}</td>
                    <td class="px-6 py-4">
                        <span class="status-badge {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $blog->status === 'published' ? 'منشور' : 'مسودة' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($blog->views_count ?? 0) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $blog->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.blogs.show', $blog) }}" class="text-blue-600 hover:text-blue-900" title="عرض">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">لا توجد مقالات مطابقة للبحث</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $blogs->links() }}
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', () => select.closest('form').submit());
        });
    });
</script>
@endsection