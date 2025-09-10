<x-app-layout>
  <x-slot name="style">
    <style>
      .form-input { @apply w-full p-3 border rounded-md; }
      .form-select { @apply w-full p-3 border rounded-md bg-white; }
      .form-textarea { @apply w-full p-3 border rounded-md resize-none; }
    </style>
  </x-slot>

  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">إتمام عملية الشراء</h1>

    <form action="{{route("user.checkout.store", $order->id)}}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-white p-6 rounded-lg shadow-md">
      @csrf
      @method("POST")
      
      <!-- بيانات الشحن -->
      <div>
        <h2 class="text-xl font-semibold mb-4">معلومات الشحن</h2>
        <input type="text" name="name" placeholder="{{$order->user->name}}" class="form-input mb-4" required>
        <input type="text" name="address" placeholder="{{$order->user->address}}" class="form-input mb-4" required>
        <input type="text" name="phone" placeholder="{{$order->user->phone}}" class="form-input mb-4" required>
        <input type="email" name="email" placeholder="{{$order->user->email}}" class="form-input mb-4" required>
        
        <!-- Shipping Address Fields -->
        <h3 class="text-lg font-medium mb-3 mt-6">عنوان الشحن</h3>
        <input type="text" name="shipping_address[street]" placeholder="الشارع" class="form-input mb-3" required>
        <input type="text" name="shipping_address[city]" placeholder="المدينة" class="form-input mb-3" required>
        <input type="text" name="shipping_address[state]" placeholder="الولاية/المحافظة" class="form-input mb-3" required>
        <input type="text" name="shipping_address[postal_code]" placeholder="الرمز البريدي" class="form-input mb-3" required>
        <input type="text" name="shipping_address[country]" placeholder="البلد" class="form-input mb-3" required>
      </div>

      <!-- بيانات الفواتير -->
      <div>
        <h2 class="text-xl font-semibold mb-4">معلومات الدفع</h2>
        
        <!-- Payment Method -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الدفع</label>
          <select name="payment_method" class="form-select" required>
            <option value="">اختر طريقة الدفع</option>
            <option value="credit_card">بطاقة ائتمان</option>
            <option value="cod">الدفع عند الاستلام</option>
            <option value="bank_transfer">تحويل بنكي</option>
          </select>
        </div>

        <!-- Billing Address Fields -->
        <h3 class="text-lg font-medium mb-3 mt-6">عنوان الفواتير</h3>
        <div class="mb-3">
          <label class="flex items-center">
            <input type="checkbox" name="same_as_shipping" id="same_as_shipping" class="mr-2">
            <span class="text-sm">نفس عنوان الشحن</span>
          </label>
        </div>
        
        <div id="billing-address-fields">
          <input type="text" name="billing_address[street]" placeholder="الشارع" class="form-input mb-3">
          <input type="text" name="billing_address[city]" placeholder="المدينة" class="form-input mb-3">
          <input type="text" name="billing_address[state]" placeholder="الولاية/المحافظة" class="form-input mb-3">
          <input type="text" name="billing_address[postal_code]" placeholder="الرمز البريدي" class="form-input mb-3">
          <input type="text" name="billing_address[country]" placeholder="البلد" class="form-input mb-3">
        </div>

        <!-- Notes -->
        <div class="mt-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات إضافية</label>
          <textarea name="notes" placeholder="أي ملاحظات أو تعليمات خاصة..." class="form-textarea" rows="3"></textarea>
        </div>
      </div>

      <!-- ملخص الطلب -->
      <div>
        <h2 class="text-xl font-semibold mb-4">ملخص الطلب</h2>
        <div class="bg-gray-50 p-4 rounded mb-4">
          <p class="text-gray-700">إجمالي المنتجات: <span class="float-left font-bold">{{$NumOfProduct}}</span></p>
          <p class="text-gray-700">الإجمالي: <span class="float-left font-bold text-blue-600">{{$order->grand_total}}</span></p>
        </div>
        <button type="submit" class="w-full bg-green-500 text-white py-3 rounded hover:bg-green-600">تأكيد الطلب</button>
      </div>
    </form>
  </div>

  <x-slot name="script">
    <script>AOS.init();</script>
    
    <script>
      // Handle same as shipping checkbox
      document.getElementById('same_as_shipping').addEventListener('change', function() {
        const billingFields = document.getElementById('billing-address-fields');
        const billingInputs = billingFields.querySelectorAll('input');
        
        if (this.checked) {
          // Copy shipping address to billing address
          const shippingInputs = document.querySelectorAll('input[name^="shipping_address"]');
          billingInputs.forEach((input, index) => {
            if (shippingInputs[index]) {
              input.value = shippingInputs[index].value;
              input.disabled = true;
            }
          });
        } else {
          // Enable billing address fields
          billingInputs.forEach(input => {
            input.disabled = false;
            input.value = '';
          });
        }
      });
    </script>
  </x-slot>
</x-app-layout>
