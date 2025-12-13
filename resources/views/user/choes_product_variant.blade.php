@extends('layouts.app')

@section('content')
<form action="{{route("user.cart.store")}}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <label for="variant">variant</label>
    <select name="variant_id" id="variant" class="border p-2" required>
        <option value="">chose</option>
        @foreach($product->variants as $variant)
            <option value="{{ $variant->id }}">
                {{ $variant->option_name }} - {{ $variant->option_value }}
                ({{ number_format($variant->price_modifier + $product->price, 2) }} ج.م)
            </option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">إضافة للسلة</button>
</form>
@endsection