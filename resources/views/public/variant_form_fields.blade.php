<div class="mb-6 border-b pb-6">
  <input type="hidden" name="variants[{{ $variant->id }}][id]" value="{{ $variant->id }}">

  {{-- اسم الاختيار --}}
  <div class="mb-4">
    <label class="block mb-1">اسم الاختيار</label>
    <select name="variants[{{ $variant->id }}][option_name]" class="w-full border rounded px-3 py-2 option_name_select" required>
      <option value="">اختر اسم الاختيار</option>
    </select>
  </div>

  {{-- قيمة الاختيار --}}
  <div class="mb-4">
    <label class="block mb-1">قيمة الاختيار</label>
    <select name="variants[{{ $variant->id }}][option_value]" class="w-full border rounded px-3 py-2 option_value_select" required>
      <option value="">اختر قيمة</option>
    </select>
  </div>

  {{-- تعديل السعر --}}
  <div class="mb-4">
    <label class="block mb-1">تعديل السعر</label>
    <input type="number" step="0.01" name="variants[{{ $variant->id }}][price_modifier]" value="{{ $variant->price_modifier }}" class="w-full border rounded px-3 py-2">
  </div>

  {{-- المخزون --}}
  <div class="mb-4">
    <label class="block mb-1">المخزون</label>
    <input type="number" name="variants[{{ $variant->id }}][stock]" value="{{ $variant->stock }}" class="w-full border rounded px-3 py-2" required>
  </div>
</div>
