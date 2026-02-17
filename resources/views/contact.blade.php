@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
        <!-- Contact Info -->
        <div class="md:w-1/3 bg-indigo-600 p-12 text-white">
            <h2 class="text-3xl font-bold mb-8">اتصل بنا</h2>
            <p class="text-indigo-100 mb-12">نحن هنا للإجابة على جميع استفساراتك ومساعدتك في أي وقت.</p>

            <ul class="space-y-6">
                <li class="flex items-center gap-4">
                    <span class="bg-indigo-500 p-2 rounded-lg">📍</span>
                    <span>القاهرة، مصر</span>
                </li>
                <li class="flex items-center gap-4">
                    <span class="bg-indigo-500 p-2 rounded-lg">📧</span>
                    <span>support@ecommerce.com</span>
                </li>
            </ul>
        </div>

        <!-- Form -->
        <div class="md:w-2/3 p-12">
            <form action="#" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم</label>
                        <input type="text" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="اسمك الكريم">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="example@mail.com">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الموضوع</label>
                    <input type="text" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الرسالة</label>
                    <textarea class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" rows="5"></textarea>
                </div>
                <button type="button" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">إرسال الرسالة</button>
            </form>
        </div>
    </div>
</div>
@endsection