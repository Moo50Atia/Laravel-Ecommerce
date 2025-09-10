<x-app-layout>
{{-- all_products
all_orders
current_orders
canceld_oders --}}
<div class="p-6 space-y-6" data-aos="fade-up">
  <h2 class="text-2xl font-bold">لوحة التحكم</h2>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

    <!-- عدد المنتجات -->
    {{-- <a href="{{ route('vendor.products.index') }}" class="block bg-white p-4 shadow rounded-lg text-center hover:bg-gray-100 transition"> --}}
    <a href="{{route("vendor.orders.index")}}" class="block bg-white p-4 shadow rounded-lg text-center hover:bg-gray-100 transition">
      <p class="text-sm text-gray-500">المنتجات الكلية</p>
      <p class="text-xl font-bold">{{$all_products}}</p>
    </a>

    <!-- الطلبات الحالية -->
    {{-- <form method="GET" action="{{ route('vendor.orders.index') }}"> --}}
    <form method="GET" action="">
      <input type="hidden" name="status" value="current">
      <button type="submit" class="w-full bg-white p-4 shadow rounded-lg text-center hover:bg-gray-100 transition">
        <p class="text-sm text-gray-500">الطلبات الحالية</p>
        <p class="text-xl font-bold">{{$current_orders}}</p>
      </button>
    </form>

    <!-- إجمالي الطلبات -->
    <form method="GET" action="{{ route('vendor.orders.index') }}">
    {{-- <form method="GET" action=""> --}}
        <input type="hidden" name="status" value="all">
        <button type="submit" class="w-full bg-white p-4 shadow rounded-lg text-center hover:bg-gray-100 transition">
            <p class="text-sm text-gray-500">إجمالي الطلبات</p>
            <p class="text-xl font-bold">{{$all_orders}}</p>
        </button>
    </form>

    <!-- الطلبات الملغية -->
    <form method="GET" action="{{ route('vendor.orders.index') }}">
    {{-- <form method="GET" action=""> --}}
      <input type="hidden" name="status" value="cancelled">
      <button type="submit" class="w-full bg-white p-4 shadow rounded-lg text-center hover:bg-gray-100 transition">
        <p class="text-sm text-gray-500">الطلبات الملغية</p>
        <p class="text-xl font-bold">{{$canceld_oders}}</p>
      </button>
    </form>

  </div>

  <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4">
    <p class="font-bold">تنبيه:</p>
    <p>بعض منتجاتك على وشك النفاذ! راجع المخزون.</p>
  </div>

  <div>
    {{-- <a href="{{ route('vendor.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"> --}}
    <a href="{{route("vendor.products.create")}}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
      إضافة منتج جديد
    </a>
  </div>
</div>

</x-app-layout>
