<x-app-layout>
<x-slot name="style">

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
</x-slot>
  <div class="container mx-auto px-4 py-12">
    <div class="overflow-x-auto" data-aos="fade-up" data-aos-duration="2000">
      <table class="w-full bg-white rounded-lg shadow-md">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2">رقم الطلب</th>
            <th class="p-2">اسم العميل</th>
            <th class="p-2">الحالة</th>
            <th class="p-2">المبلغ</th>
            <th class="p-2">التاريخ</th>
            <th class="p-2">الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t">
            <td class="p-2">123</td>
            <td class="p-2">أحمد</td>
            <td class="p-2">قيد التنفيذ</td>
            <td class="p-2">100.00 $</td>
            <td class="p-2">17/07/2025</td>
            <td class="p-2 flex space-x-2">
              <a href="#" class="bg-blue-500 text-white px-2 py-1 rounded-lg">عرض</a>
              <a href="#" class="bg-green-500 text-white px-2 py-1 rounded-lg">حفظ</a>
            </td>
          </tr>
          <!-- كرر حسب الحاجة -->
        </tbody>
      </table>
    </div>
  </div>
  </x-app-layout>