@extends('layouts.app')

@section('style')
<style>
      .form-input { @apply w-full p-3 border rounded-md; }
      .form-select { @apply w-full p-3 border rounded-md bg-white; }
      .form-textarea { @apply w-full p-3 border rounded-md resize-none; }
    </style>
@endsection

@section('content')
@section('style')
    <style>
      .form-input { @apply w-full p-3 border rounded-md; }
      .form-select { @apply w-full p-3 border rounded-md bg-white; }
      .form-textarea { @apply w-full p-3 border rounded-md resize-none; }
    </style>
@endsection

@section('script')
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
@endsection