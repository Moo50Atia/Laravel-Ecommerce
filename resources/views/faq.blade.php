@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">الأسئلة الشائعة</h1>
        <p class="text-xl text-gray-600">كل ما تحتاج لمعرفته حول خدماتنا وسياساتنا</p>
    </div>

    <div class="max-w-3xl mx-auto space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <button class="w-full p-6 text-right flex justify-between items-center group">
                <span class="font-bold text-gray-900 text-lg">كيف يمكنني تتبع طلبي؟</span>
                <span class="text-indigo-600 group-hover:translate-y-1 transition-transform">↓</span>
            </button>
            <div class="px-6 pb-6 text-gray-600">
                يمكنك تتبع طلبك عبر الذهاب إلى "طلباتي" ثم اختيار الطلب والضغط على "تتبع الطلب" لمشاهدة الجدول الزمني لتجهيز وشحن المنتج.
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <button class="w-full p-6 text-right flex justify-between items-center group">
                <span class="font-bold text-gray-900 text-lg">ما هي طرق الدفع المتاحة؟</span>
                <span class="text-indigo-600 group-hover:translate-y-1 transition-transform">↓</span>
            </button>
            <div class="px-6 pb-6 text-gray-600">
                نوفر الدفع عند الاستلام، والبطاقات الائتمانية، والعديد من بوابات الدفع الإلكتروني المحلية لضمان أقصى درجات الراحة.
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <button class="w-full p-6 text-right flex justify-between items-center group">
                <span class="font-bold text-gray-900 text-lg">كيف أصبح تاجراً في المنصة؟</span>
                <span class="text-indigo-600 group-hover:translate-y-1 transition-transform">↓</span>
            </button>
            <div class="px-6 pb-6 text-gray-600">
                يمكنك التسجيل كتاجر عبر صفحة التسجيل واختيار دور "تاجر"، ثم تفعيل حسابك والبدء في رفع منتجاتك بعد مراجعة الإدارة.
            </div>
        </div>
    </div>
</div>
@endsection