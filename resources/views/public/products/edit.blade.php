@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-12">
    <form action="{{ route("vendor.products.update", $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
      @csrf
      @method('PUT')

      <h2 class="text-2xl font-bold mb-4">تعديل المنتج</h2>

      <!-- باقي حقول المنتج -->
      <div class="mb-4">
        <label class="block">الاسم</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="border p-2 w-full rounded-lg">
      </div>

      <div class="mb-4">
        <label class="block">الوصف</label>
        <textarea name="description" class="border p-2 w-full rounded-lg">{{ old('description', $product->description) }}</textarea>
      </div>

      <div class="mb-4">
        <label class="block">الوصف القصير</label>
        <textarea name="short_description" class="border p-2 w-full rounded-lg">{{ old('short_description', $product->short_description) }}</textarea>
      </div>

      <div class="mb-4">
        <label class="block">السعر</label>
        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="border p-2 w-full rounded-lg">
      </div>

      <div class="mb-4">
        <label class="block">صورة المنتج الحالية</label>
        <img src="{{ asset('storage/' . $product->image->url) }}" alt="صورة المنتج" class="w-32 h-32 object-cover rounded mb-2">
        <input type="file" name="image" class="border p-2 w-full rounded-lg">
      </div>

      <!-- اختيار الـ variants -->
      <div class="mb-4">
        <label class="block font-bold mb-1">اختر الـ Variants للتعديل</label>
        <select name="variant_ids[]" multiple class="border p-2 w-full rounded-lg" id="variant">
          @foreach($product->variants as $variant)
            <option value="{{ $variant->id }}">
              {{ $variant->option_name }} - {{ $variant->option_value }}
            </option>
          @endforeach
        </select>
        <small class="text-gray-500">يمكنك اختيار أكثر من Variant بالضغط على Ctrl أو Shift.</small>
      </div>

      <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">تحديث المنتج</button>
    </form>
  </div>
@endsection

