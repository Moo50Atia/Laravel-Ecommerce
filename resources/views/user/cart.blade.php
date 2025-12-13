@extends('layouts.app')

@section('style')
<style>
      .cart-item img { @apply w-24 h-24 object-cover rounded; }
    </style>
@endsection

@section('content')
@section('style')
    <style>
      .cart-item img { @apply w-24 h-24 object-cover rounded; }
    </style>
@endsection

@section('script')
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
@endsection