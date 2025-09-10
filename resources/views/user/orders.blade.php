<x-app-layout>
  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">طلباتي</h1>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3">رقم الطلب</th>
            <th class="px-6 py-3">التاريخ</th>
            <th class="px-6 py-3">الحالة</th>
            <th class="px-6 py-3">الإجمالي</th>
            <th class="px-6 py-3">تفاصيل</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $order )
            
          <tr class="border-b hover:bg-gray-50">
            <td class="px-6 py-4">{{$order->order_number}}</td>
            <td class="px-6 py-4">{{$order->created_at}}</td>
            <td class="px-6 py-4 text-green-600"> {{$order->status}}</td>
            <td class="px-6 py-4">{{$order->grand_total}}</td>
            <td class="px-6 py-4">
              <a href="{{route("user.order-details" , $order->id)}}" class="text-blue-600 hover:underline">عرض</a>
            </td>
          </tr>
          @endforeach
          <!-- كرر حسب الطلبات -->
        </tbody>
      </table>
    </div>
  </div>
</x-app-layout>
