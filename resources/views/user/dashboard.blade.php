<x-app-layout>
  <x-slot name="style">
    <style>
      .dashboard-card { @apply bg-white p-6 rounded-lg shadow hover:shadow-lg transition; }
    </style>
  </x-slot>

  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">لوحة التحكم</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- الطلبات -->
      <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-xl font-semibold mb-2">طلباتي</h2>
        <p class="text-gray-600">عرض وتتبع حالة الطلبات السابقة والحالية.</p>
        <a href="/user/orders" class="text-blue-600 mt-2 inline-block">عرض الطلبات</a>
      </div>

      <!-- المنتجات المفضلة -->
      <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
        <h2 class="text-xl font-semibold mb-2">المفضلة</h2>
        <p class="text-gray-600">المنتجات التي قمت بحفظها لاحقاً.</p>
        <a href="/user/wishlist" class="text-blue-600 mt-2 inline-block">عرض المفضلة</a>
      </div>

      <!-- إعدادات الحساب -->
      <div class="dashboard-card" data-aos="fade-up" data-aos-delay="300">
        <h2 class="text-xl font-semibold mb-2">إعدادات الحساب</h2>
        <p class="text-gray-600">تحديث بياناتك الشخصية وكلمة المرور.</p>
        <a href="/user/settings" class="text-blue-600 mt-2 inline-block">تعديل الإعدادات</a>
      </div>
    </div>
  </div>

</x-app-layout>
