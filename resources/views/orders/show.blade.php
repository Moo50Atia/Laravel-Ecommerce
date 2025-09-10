<x-app-layout>
  <x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
  </x-slot>

      

  <div class="container mx-auto px-4 py-12">
    <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">تفاصيل الطلب #{{$order->order_number}}</h2>
      <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">بيانات العميل</h3>
        <p>name : {{$order->user->name}}</p>
        <p> email: {{$order->user->email}}</p>
        <p>phone : {{$order->user->phone}}</p>
      </div>
      <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">تفاصيل الطلب</h3>
        <table class="w-full bg-gray-50 rounded-lg">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">المنتج</th>
              <th class="p-2">الكمية</th>
              <th class="p-2">السعر</th>
            </tr>
          </thead>
          <tbody>
            {{-- @php 
                dd($order->items);
            @endphp --}}
            @foreach ( $order->items as $item )
            <tr class="border-t">
              <td class="p-2">{{$item->product->name}}</td>
              <td class="p-2">{{$item->quantity}}</td>
              <td class="p-2">{{$item->price}}</td>
            </tr>
            @endforeach
             {{-- 
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr> --}}
          </tbody>
        </table>
        <p class="mt-2">payment method : {{$order->payment_method}}</p>
        <p>address : {{$order->billing_address}}</p>
      </div>
      <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST" class="mt-4 p-4 bg-white rounded-lg shadow-md border">
    @csrf
    @method("PUT")

    <label for="status" class="block text-gray-700 font-semibold mb-2">حالة الطلب</label>
    {{-- 'pending', 'processing', 'shipped', 'delivered', 'canceled', 'refunded' --}}
    <select name="status" id="status" 
        class="border border-gray-300 p-2 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
        <option value="{{ $order->status }}" selected>{{ $order->status }}</option>
        <option value="pending">pending</option>
        <option value="processing">processing</option>
        <option value="delivered">delivered</option>
        <option value="shipped">shipped</option>
        <option value="canceled">canceled</option>
        <option value="refunded">refunded</option>
    </select>

    <button type="submit" 
        class="bg-green-500 text-white px-6 py-2 rounded-lg mt-4 hover:bg-green-600 transition duration-200">
        💾 حفظ التغييرات
    </button>
</form>

    </div>
  </div>
  </x-app-layout>
