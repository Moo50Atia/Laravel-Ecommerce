@extends('layouts.app')

@section('style')
<style>
    .stats-card {
        @apply bg-white rounded-lg shadow-md p-6 border-l-4;
        transition: all .3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
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
            <h1 class="text-3xl font-bold text-gray-900">إدارة المستخدمين</h1>
            <p class="text-gray-600">التحكم في حسابات المستخدمين، الأدوار، والصلاحيات</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            إضافة مستخدم جديد
        </a>
    </div>

    <!-- Statistics Grid -->
    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card border-l-blue-500">
            <p class="text-sm font-medium text-gray-500">إجمالي المستخدمين</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_users'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-purple-500">
            <p class="text-sm font-medium text-gray-500">مشرفين (Admins)</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['admins'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-green-500">
            <p class="text-sm font-medium text-gray-500">باعة (Vendors)</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['vendors'] ?? 0 }}</p>
        </div>
        <div class="stats-card border-l-yellow-500">
            <p class="text-sm font-medium text-gray-500">عملاء (Users)</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['customers'] ?? 0 }}</p>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="الاسم، البريد الإلكتروني، الهاتف..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الدور (Role)</label>
                <select name="role" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($roles ?? [] as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($statuses ?? [] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">تصفية</button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">المستخدم</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الدور</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">التحقق</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">تاريخ التسجيل</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-bold rounded {{ 
                            $user->role === 'superadmin' ? 'bg-indigo-100 text-indigo-700' : 
                            ($user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 
                            ($user->role === 'vendor' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) 
                        }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->email_verified_at)
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        @else
                        <span class="text-xs text-gray-400">غير مؤكد</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900" title="عرض">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">لا توجد مستخدمين مطابقين للبحث</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>
</div>
@endsection