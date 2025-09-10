<x-app-layout>
  <x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
  </x-slot>
  <div class="container mx-auto px-4 py-12">
    <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="2000">
      <h2 class="text-2xl font-bold mb-4">تفاصيل الطلب #123</h2>
      <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">بيانات العميل</h3>
        <p>الاسم: أحمد محمد</p>
        <p>الإيميل: ahmed@example.com</p>
        <p>رقم الهاتف: 01234567890</p>
      </div>
      <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">تفاصيل الطلب</h3>
        <table class="w-full bg-gray-50 rounded-lg">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">المنتج</th>
              <th class="p-2">الكمية</th>
              <th class="p-2">السعر</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
              <tr class="border-t">
              <td class="p-2">منتج 1</td>
              <td class="p-2">2</td>
              <td class="p-2">100.00 $</td>
            </tr>
          </tbody>
        </table>
        <p class="mt-2">طريقة الدفع: بطاقة ائتمان</p>
        <p>العنوان: شارع المثال، القاهرة</p>
      </div>
      <div class="mt-4">
        <label class="block text-gray-700">حالة الطلب</label>
        <select class="border p-2 w-full rounded-lg">
          <option>قيد التنفيذ</option>
          <option>تم التوصيل</option>
          <option>ملغي</option>
        </select>
        <button class="bg-green-500 text-white px-6 py-2 rounded-lg mt-4 hover:bg-green-600">حفظ التغييرات</button>
      </div>
    </div>
  </div>
  </x-app-layout>
