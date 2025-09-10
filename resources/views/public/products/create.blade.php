
<x-app-layout>
<x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
</x-slot>
  <div class="container mx-auto px-4 py-12">
<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">      @csrf
        <h2 class="text-2xl font-bold mb-4">إضافة منتج جديد</h2>
      <div class="mb-4">
        <label class="block text-gray-700">الاسم</label>
        <input type="text" name="name" class="border p-2 w-full rounded-lg">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">الوصف</label>
        <textarea name="description" class="border p-2 w-full rounded-lg"></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">الوصف القصير</label>
        <textarea name="short_description" class="border p-2 w-full rounded-lg"></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">السعر</label>
        <input type="number" name="price" step="0.01" class="border p-2 w-full rounded-lg">
      </div>
      {{-- <div class="mb-4">
        <label class="block text-gray-700">الكمية</label>
        <input type="number" name="quantity" class="border p-2 w-full rounded-lg">
      </div>   --}}
      <div class="mb-4">
        <label class="block text-gray-700">الوزن (كجم) (اختياري)</label>
        <input type="number" name="weight" step="0.01" class="border p-2 w-full rounded-lg">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">الأبعاد (مثال: 20x10x5) (اختياري)</label>
        <input type="text" name="dimensions" class="border p-2 w-full rounded-lg">
      </div>
      <div class="mb-4">
    <label for="category" class="block text-gray-700">التصنيف</label>
    <select name="category" id="category" class="border p-2 w-full rounded-lg">
        <option value="">-- اختر التصنيف --</option>
        @foreach(['Electronics', 'Clothing', 'Books', 'Home'] as $category)
            <option value="{{ $category }}" {{ old('category', $product->category ?? '') === $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
    </select>
</div>
@auth
    @if(auth()->user()->role == "admin")
        <div class="mb-4">
            <label for="vendor_id" class="block text-gray-700">البائع (Vendor)</label>
            <select name="vendor_id" id="vendor_id" class="border p-2 w-full rounded-lg">
                <option value="">-- اختر البائع --</option>
                @foreach(App\Models\Vendor::all() as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->user->name ?? 'Vendor' }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
@endauth

              <div>
                <label class="block mb-1 font-medium">Card Image</label>
                <input type="file" name="image" class="w-full border rounded px-3 py-2">
                @error("image") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>  

            <div class="mb-4">
    <label class="block text-gray-700">عدد الصور الإضافية</label>
    <input type="number" id="imageCount" class="border p-2 rounded w-full" min="1" max="10">
    <button type="button" onclick="generateImageFields()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">توليد الحقول</button>
</div>

<div id="imageFieldsContainer" class="space-y-3 mt-4"></div>

      <div class="mb-4">
        <label class="block text-gray-700">مفعل؟</label>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" checked>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">مميز؟</label>
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1">
      </div>
           
      <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">حفظ المنتج</button>
    </form>
  </div>
  @if ($errors->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<x-slot name="script">
  <script>
function generateImageFields() {
    const count = document.getElementById('imageCount').value;
    const container = document.getElementById('imageFieldsContainer');
    container.innerHTML = '';

    for (let i = 1; i <= count; i++) {
        const div = document.createElement('div');
        div.classList.add('mb-2');

        const label = document.createElement('label');
        label.textContent = `صورة إضافية ${i}`;
        label.classList.add('block', 'text-gray-700');

        const input = document.createElement('input');
        input.type = 'file';
        input.name = `additional_images[]`; // دي تبعتهم Array
        input.classList.add('border', 'p-2', 'rounded', 'w-full');

        div.appendChild(label);
        div.appendChild(input);
        container.appendChild(div);
    }
}
</script>

</x-slot>
</x-app-layout>
