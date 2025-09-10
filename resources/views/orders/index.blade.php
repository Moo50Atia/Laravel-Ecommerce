<x-app-layout>
<x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
</x-slot>
  <div class="container mx-auto px-4 py-12">
    <div class="overflow-x-auto" data-aos="fade-up" data-aos-duration="2000">
      <table class="w-full bg-white rounded-lg shadow-md">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2">رقم الطلب</th>
            <th class="p-2">اسم العميل</th>
            <th class="p-2">الحالة</th>
            <th class="p-2">المبلغ</th>
            <th class="p-2">التاريخ</th>
            <th class="p-2">الإجراءات</th>
          </tr>
        </thead>
        <tbody>
            @foreach ( $orders as $order )
                
          
          <tr class="border-t">
            <td class="p-2">{{$order->order_number}}</td>
            <td class="p-2">{{$order->user->name}}</td>
            <td class="p-2">{{$order->status}}</td>
            <td class="p-2">{{$order->grand_total}}</td>
            <td class="p-2">{{$order->created_at}}</td>
            <td class="p-2 flex space-x-2">
              <a href="{{route("vendor.orders.show" , $order->id)}}" class="bg-blue-500 text-white px-2 py-1 rounded-lg">عرض</a>
                <form action="{{ route('vendor.orders.destroy', $order->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                            🗑️ Delete   
                        </button>
                    </form>
            </td>
          </tr>
            @endforeach
          <!-- كرر حسب الحاجة -->
        </tbody>
      </table>
    </div>
  </div>
  </x-app-layout>