<x-app-layout>
    <x-slot name="style">
        <style>
            .stats-card { transition: all .3s ease; }
            .stats-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المستخدمين</h1>
                    <p class="text-gray-600">عرض وإدارة المستخدمين مع فلاتر وبحث</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2">
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition">+ إضافة مستخدم</a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg p-6 text-white">
                <p class="text-blue-100">إجمالي المستخدمين</p>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div class="stats-card bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg p-6 text-white">
                <p class="text-green-100">نشطون</p>
                <p class="text-3xl font-bold">{{ $activeUsers }}</p>
            </div>
            <div class="stats-card bg-gradient-to-r from-rose-500 to-pink-500 rounded-lg p-6 text-white">
                <p class="text-rose-100">موقوفون</p>
                <p class="text-3xl font-bold">{{ $inactiveUsers }}</p>
            </div>
            <div class="stats-card bg-gradient-to-r from-cyan-500 to-sky-500 rounded-lg p-6 text-white">
                <p class="text-cyan-100">موثقو البريد</p>
                <p class="text-3xl font-bold">{{ $verifiedUsers }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="ابحث بالاسم أو البريد أو الهاتف" value="{{ request('search') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex flex-wrap gap-2">
                    <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">كل الأدوار</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select name="verified" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">التحقق</option>
                        <option value="yes" {{ request('verified') === 'yes' ? 'selected' : '' }}>موثق</option>
                        <option value="no" {{ request('verified') === 'no' ? 'selected' : '' }}>غير موثق</option>
                    </select>
                    <select name="has_vendor" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">لديه بائع؟</option>
                        <option value="yes" {{ request('has_vendor') === 'yes' ? 'selected' : '' }}>نعم</option>
                        <option value="no" {{ request('has_vendor') === 'no' ? 'selected' : '' }}>لا</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">تطبيق</button>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">مسح</a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">البريد</th>
                        <th class="px-4 py-3">تم التحقق</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">الدور</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3">بائع؟</th>
                        <th class="px-4 py-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3">{{ $user->phone ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $user->role ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($user->status === 'active')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">نشط</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $user->vendor ? 'نعم' : 'لا' }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 mr-3">تعديل</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('حذف المستخدم؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">لا يوجد مستخدمون</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    عرض {{ $users->firstItem() ?? 0 }} إلى {{ $users->lastItem() ?? 0 }} من {{ $users->total() }} نتيجة
                </div>
                <div class="flex gap-2 flex-wrap">
                    @php
                        $current = $users->currentPage();
                        $last = $users->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    @if($users->onFirstPage())
                        <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-md cursor-not-allowed">السابق</span>
                    @else
                        <a class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" href="{{ $users->previousPageUrl() }}">السابق</a>
                    @endif

                    @if($start > 1)
                        <a class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" href="{{ $users->url(1) }}">1</a>
                        @if($start > 2)
                            <span class="px-2 py-2 text-sm text-gray-500">...</span>
                        @endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        @if($page == $current)
                            <span class="px-3 py-2 text-sm font-medium bg-blue-600 border border-blue-600 text-white rounded-md">{{ $page }}</span>
                        @else
                            <a class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" href="{{ $users->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="px-2 py-2 text-sm text-gray-500">...</span>
                        @endif
                        <a class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" href="{{ $users->url($last) }}">{{ $last }}</a>
                    @endif

                    @if($users->hasMorePages())
                        <a class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" href="{{ $users->nextPageUrl() }}">التالي</a>
                    @else
                        <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-md cursor-not-allowed">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
