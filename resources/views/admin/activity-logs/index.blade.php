@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">سجل النشاطات</h1>
        <p class="text-gray-600">مراقبة جميع التغييرات والنشاطات التي تتم في النظام</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">المستخدم</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">النوع</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">الحدث</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">الوقت</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">التفاصيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                {{ strtoupper($log->log_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                            {{ $log->event }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4">
                            @if($log->new_values)
                            <button onclick="showDetails('{{ $log->id }}')" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold">عرض الـ JSON</button>
                            @else
                            <span class="text-gray-400 text-xs">لا توجد قيم</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            لا توجد نشاطات مسجلة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection