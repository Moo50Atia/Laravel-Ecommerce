<x-app-layout>
  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">order numper {{$order->order_number}}</h1>

    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h2 class="text-xl font-semibold mb-4">معلومات الطلب</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
        <p><span class="font-semibold">date</span> {{$order->created_at}}</p>
        <p><span class="font-semibold">statues</span> <span class="text-green-600">{{$order->status}}</span></p>
        <p><span class="font-semibold">payment method</span> {{$order->payment_method}}</p>
        {{-- <p><span class="font-semibold">address</span> {{$order->billing_address}}</p> --}}
        <div class="mb-4">
    <h3 class="font-bold">Billing Address</h3>
    @if(is_array($order->billing_address))
        <p><span class="font-semibold">Street:</span> {{ $order->billing_address['street'] ?? '' }}</p>
        <p><span class="font-semibold">City:</span> {{ $order->billing_address['city'] ?? '' }}</p>
        <p><span class="font-semibold">State:</span> {{ $order->billing_address['state'] ?? '' }}</p>
        <p><span class="font-semibold">Postal Code:</span> {{ $order->billing_address['postal_code'] ?? '' }}</p>
        <p><span class="font-semibold">Country:</span> {{ $order->billing_address['country'] ?? '' }}</p>
    @else
        <p>{{ $order->billing_address }}</p>
    @endif
</div>
      </div>
    </div>
    

    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-xl font-semibold mb-4">المنتجات</h2>
      <div class="space-y-4">
        @foreach ( $order_items as $item )
        <!-- منتج -->
        <div class="flex items-center justify-between border-b pb-4">
          <div class="flex items-center gap-4">
            <img src="{{asset("storage" . $item->product->image->url)}}" alt="منتج" class="w-20 h-20 object-cover rounded">
            <div>
              <h3 class="font-semibold">{{$item->product->name}}</h3>
              <p class="text-gray-500 text-sm">quantity : {{$item->quantity}}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-gray-700">price : {{$item->price}}</p>
          </div>
          
        </div>
        @endforeach
        <!-- كرر حسب عدد المنتجات -->
      </div>

      <!-- الإجمالي -->
      <div class="text-right mt-6 border-t pt-4">
        <p class="text-lg font-bold">order total <span class="text-blue-600">{{$order->grand_total}}</span></p>
      </div>
    </div>
  </div>
@if(session('success'))
  <div class="mb-6">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  </div>
@endif
  
</x-app-layout>
