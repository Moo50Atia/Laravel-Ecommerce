
@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-10">
    <h2 class="text-xl font-bold mb-4">إضافة اختيارات جديدة للمنتج</h2>

    <form id="variantsForm" action="{{ route('vendor.variant.store') }}" method="POST">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <input type="hidden" id="category" value="{{ $category }}">

      <div id="variantFields">
        {{-- اختيار نوع الاختيار --}}
        <div class="mb-4">
          <label class="block mb-1">اسم الاختيار</label>
          <select name="option_name" id="option_name" class="w-full border rounded px-3 py-2">
            <option value="">اختر اسم الاختيار</option>
          </select>
        </div>

        {{-- قيمة الاختيار --}}
        <div class="mb-4">
          <label class="block mb-1">قيمة الاختيار</label>
          <select name="option_value" id="option_value" class="w-full border rounded px-3 py-2">
            <option value="">اختر قيمة</option>
          </select>
        </div>

        {{-- تعديل السعر --}}
        <div class="mb-4">
          <label class="block mb-1">تعديل السعر</label>
          <input type="number" step="0.01" name="price_modifier" id="price_modifier" class="w-full border rounded px-3 py-2">
        </div>

        {{-- المخزون --}}
        <div class="mb-4">
          <label class="block mb-1">المخزون</label>
          <input type="number" name="stock" id="stock" class="w-full border rounded px-3 py-2">
        </div>
      </div>

      <button type="button" id="addVariantBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">إضافة خاصية جديدة</button>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">حفظ كل الخصائص</button>
    </form>
  </div>
  @if ($errors->any())
    <div style="background: #ffecec; border: 1px solid #ff5c5c; color: #d8000c; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        <strong>حدثت أخطاء أثناء الحفظ:</strong>
        <ul style="margin: 5px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@section('script')
<script>
  const allOptions = {
    Electronics: {
      "Color": ["Black", "White", "Silver"],
      "Warranty": ["6 months", "1 year", "2 years"],
      "Voltage": ["110V", "220V", "Universal"]
    },
    Clothing: {
      "Size": ["S", "M", "L"],
      "Color": ["Red", "Blue", "Black"],
      "Material": ["Cotton", "Wool", "Polyester"]
    },
    Books: {
      "Language": ["Arabic", "English", "French"],
      "Cover Type": ["Hardcover", "Paperback", "Digital"],
      "Edition": ["1st", "2nd", "3rd"]
    },
    Home: {
      "Material": ["Wood", "Metal", "Plastic"],
      "Color": ["Brown", "White", "Gray"],
      "Room Type": ["Kitchen", "Bedroom", "Living Room"]
    }
  };

  const category = document.getElementById("category").value;
  const optionNameSelect = document.getElementById("option_name");
  const optionValueSelect = document.getElementById("option_value");
  const priceModifierInput = document.querySelector('input[name="price_modifier"]');
  const stockInput = document.querySelector('input[name="stock"]');
  const form = document.getElementById("variantsForm");

  let variants = [];

  function populateOptionNames() {
    optionNameSelect.innerHTML = '<option value="">اختر اسم الاختيار</option>';
    optionValueSelect.innerHTML = '<option value="">اختر قيمة</option>';

    if (allOptions[category]) {
      Object.keys(allOptions[category]).forEach(optionName => {
        const option = document.createElement("option");
        option.value = optionName;
        option.textContent = optionName;
        optionNameSelect.appendChild(option);
      });
    }
  }

  function populateOptionValues(optionName) {
    optionValueSelect.innerHTML = '<option value="">اختر قيمة</option>';
    const values = allOptions[category]?.[optionName] || [];
    values.forEach(value => {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = value;
      optionValueSelect.appendChild(option);
    });
  }

  function clearFormFields() {
    optionNameSelect.value = "";
    optionValueSelect.innerHTML = '<option value="">اختر قيمة</option>';
    priceModifierInput.value = "";
    stockInput.value = "";
  }

  document.getElementById("option_name").addEventListener("change", (e) => {
    populateOptionValues(e.target.value);
  });

  document.getElementById("addVariantBtn").addEventListener("click", (e) => {
    e.preventDefault();

    if (!optionNameSelect.value || !optionValueSelect.value || !stockInput.value) {
      alert("يرجى ملء جميع الحقول المطلوبة قبل الإضافة");
      return;
    }

    variants.push({
      product_id: document.querySelector('input[name="product_id"]').value,
      option_name: optionNameSelect.value,
      option_value: optionValueSelect.value,
      price_modifier: priceModifierInput.value || 0,
      stock: stockInput.value
    });

    console.log("الخصائص الحالية:", variants);
    clearFormFields();
  });

  form.addEventListener("submit", (e) => {
    // لو لسه فيه بيانات في الحقول لكن ما اتعملهاش إضافة
    if (optionNameSelect.value || optionValueSelect.value || stockInput.value) {
      if (!optionNameSelect.value || !optionValueSelect.value || !stockInput.value) {
        e.preventDefault();
        alert("أكمل بيانات الخاصية الحالية أو اضغط على إضافة قبل الحفظ");
        return;
      }
      variants.push({
        product_id: document.querySelector('input[name="product_id"]').value,
        option_name: optionNameSelect.value,
        option_value: optionValueSelect.value,
        price_modifier: priceModifierInput.value || 0,
        stock: stockInput.value
      });
    }

    // لو مفيش أي خاصية
    if (variants.length === 0) {
      e.preventDefault();
      alert("يجب إضافة خاصية واحدة على الأقل قبل الحفظ");
      return;
    }

    // إضافة البيانات لـ hidden input
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "variants_json";
    hiddenInput.value = JSON.stringify(variants);
    form.appendChild(hiddenInput);
  });

  window.addEventListener("DOMContentLoaded", () => {
    populateOptionNames();
  });
</script>
@endsection


@endsection
