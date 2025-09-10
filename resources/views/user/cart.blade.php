<x-app-layout>
  <x-slot name="style">
    <style>
      .cart-item img { @apply w-24 h-24 object-cover rounded; }
    </style>
  </x-slot>
@if(session("error"))
<div class="alert alert-danger">
  {{session("error")}}
</div>
@endif
  <div class="container mx-auto px-4 py-8" data-aos="fade-up">
    <h1 class="text-3xl font-bold mb-6">سلة المشتريات</h1>

    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
      <!-- Main form for updating cart -->
      <form action="{{route("user.update_cart", $order->id) }}" method="post" id="cartForm">
        @csrf
        @method("PUT")
        
        <!-- عنصر في السلة -->
        @foreach ( $order->items as $item )
          
        
        <div class="flex items-center justify-between cart-item" data-aos="fade-up" data-aos-delay="100">
          <div class="flex items-center gap-4">
            <img src="{{asset("storage" . $item->product->image->url)}}" alt="منتج">
            <div>
              <h2 class="font-semibold text-lg">{{$item->product->name}}</h2>
              <p class="text-gray-600 price" data-price="{{$item->price}}" id="price-{{$item->id}}">the price : {{$item->price}}</p>
              <p class="text-sm text-gray-500"> quantity 
                <input type="number" name="items[{{$item->id}}][quantity]" 
       value="{{$item->quantity}}" min="1" 
       class="quantity-input border p-1 w-16 text-center rounded" />
<input type="hidden" name="items[{{$item->id}}][price]" value="{{$item->price}}" />
              </p>
            </div>
          </div>
          
          <!-- Delete button - separate from main form -->
          <button type="button" 
                  onclick="deleteItem({{$item->id}})" 
                  class="text-red-500 hover:underline">
            إزالة
          </button>
        </div>
          @endforeach
       
 
        <!-- الإجمالي -->
        <div class="text-right border-t pt-4">
          <p class="text-lg font-bold">the total is :<span id="total" name="total" class="text-blue-600"></span></p>
          <button type="submit" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">متابعة الدفع</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Hidden form for deleting items -->
  <form id="deleteForm" method="post" style="display: none;">
    @csrf
    @method("DELETE")
  </form>

  <x-slot name="script">
    <script>AOS.init();</script>
    
    <script>
      // Function to delete cart items
      function deleteItem(itemId) {
        if (confirm('Are you sure you want to remove this item?')) {
          const deleteForm = document.getElementById('deleteForm');
          deleteForm.action = "{{route('user.delete_item', ':id')}}".replace(':id', itemId);
          deleteForm.submit();
        }
      }

      // Script to calculate total price based on item prices and quantities in the cart
      document.addEventListener('DOMContentLoaded', function() {
        function calculateTotal() {
          let total = 0;
          
          // Select all cart items
          document.querySelectorAll('.cart-item').forEach(function(item) {
            // Get price from data-price attribute
            let priceElem = item.querySelector('[data-price]');
            let price = 0;
            if (priceElem) {
              price = parseFloat(priceElem.getAttribute('data-price')) || 0;
            }
            
            // Get quantity from quantity input
            let qtyElem = item.querySelector('.quantity-input');
            let qty = 1;
            if (qtyElem) {
              qty = parseInt(qtyElem.value) || 1;
            }
            
            total += price * qty;
          });

          // Update total display
          let totalSpan = document.querySelector('#total');
          if (totalSpan) {
            totalSpan.textContent = total.toFixed(2) + ' $';
          }
          
          console.log('Total calculated:', total); // Debug log
        }

        // Initial calculation
        calculateTotal();

        // Listen for changes in quantity inputs
        document.querySelectorAll('.quantity-input').forEach(function(input) {
          input.addEventListener('input', calculateTotal);
          input.addEventListener('change', calculateTotal);
        });

        // Debug: Log form submission
        document.getElementById('cartForm').addEventListener('submit', function(e) {
          console.log('Form submitting...');
          console.log('Form action:', this.action);
          console.log('Form method:', this.method);
        });
      });
    </script>

  </x-slot>
</x-app-layout>
