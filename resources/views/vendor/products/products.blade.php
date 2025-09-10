<x-app-layout>
<x-slot name="style">
  <style>
    body { font-family: 'Cairo', sans-serif; }
    .card-img { height: 50px; object-fit: cover; width: 50px; }
    </style>
    </x-slot>
  <div class="container mx-auto px-4 py-12">
    <div class="flex justify-between mb-4" data-aos="fade-up" data-aos-duration="2000">
      <select class="border p-2 rounded-lg">
        <option>الأقدم للأحدث</option>
        <option>الأحدث للأقدم</option>
        <option>الأكثر مبيعًا</option>
      </select>
    </div>
    <div class="overflow-x-auto" data-aos="fade-up" data-aos-duration="2000">
      <table class="w-full bg-white rounded-lg shadow-md">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2">الصورة</th>
            <th class="p-2">الاسم</th>
            <th class="p-2">السعر</th>
            <th class="p-2">الكمية</th>
            <th class="p-2">الحالة</th>
            <th class="p-2">الإجراءات</th>
          </tr>
        </thead>
        <tbody>

          
          @foreach ($products as $product )
          <tr class="border-t">
            <td class="p-2"><img src="{{  asset( 'storage/' .$product->image->url)}}" class="card-img" alt="منتج"></td>
            <td class="p-2"> {{ $product->name }} </td>
            <td class="p-2">{{ $product->price }}</td>
              
            <td class="p-2">{{ $product->total_stock }}</td>
            
            @if ($product->is_active)
            <td class="p-2">مفعل</td>
            @else
            <td class="p-2">غير مفعل</td>
            @endif
            <td class="p-2">
                <div class="flex items-center gap-2">
                    <!-- زر تعديل -->
                    <a href="{{ route('vendor.products.edit', $product->id) }}" 
                      class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition text-center">
                      تعديل
                    </a>

                    <!-- زر حذف -->
                    <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center gap-1">
                            🗑️ <span>حذف المنتج</span>
                        </button>
                    </form>

                    <!-- زر عرض -->
                    <a href="{{ route('products.show', $product->id) }}" 
                      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-center">
                      عرض
                    </a>
                </div>
            </td>
            
          </tr>
          @endforeach
          


          <!-- كرر حسب الحاجة -->
        </tbody>
      </table>
    </div>
    <div class="text-center mt-6" data-aos="zoom-in" data-aos-duration="2000">
      <a href="{{route("vendor.products.create")}}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">إضافة منتج جديد</a>
    </div>
  </div>
</x-app-layout>