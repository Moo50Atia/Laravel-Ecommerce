@extends('layouts.app')

@section('content')
<div class="bg-indigo-600 py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6">من نحن</h1>
        <p class="text-indigo-100 text-xl max-w-2xl mx-auto">نحن نوفر أفضل تجربة تسويق إلكتروني في المنطقة، نربط بين أفضل البائعين والمشترين في منصة واحدة آمنة وسهلة.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">رؤيتنا</h2>
            <p class="text-gray-600 leading-relaxed mb-6 italic">أن نكون المنصة الأولى التي يثق بها كل بيت للبحث عن الجودة والقيمة الحقيقية.</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">مهمتنا</h2>
            <p class="text-gray-600 leading-relaxed text-indigo-700 font-medium">تسهيل عملية البيع والشراء عبر توفير أدوات متطورة وضمان حقوق جميع الأطراف.</p>
        </div>
        <div class="bg-gray-100 rounded-3xl h-64 flex items-center justify-center border-2 border-dashed border-gray-300">
            <span class="text-gray-400">About Us Illustration</span>
        </div>
    </div>
</div>
@endsection