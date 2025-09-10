<x-app-layout> 

<div class="max-w-5xl mx-auto p-6 bg-white rounded shadow mt-10">
  <h2 class="text-xl font-bold mb-4">تعديل الاختيارات</h2>

  <form action="{{ route('vendor.variant.update' , $product->id) }}" method="POST" id="variantsForm">
    @csrf
    @method('PUT')
    <input type="hidden" id="category" value="{{ $category }}">
    <input type="hidden" id="product_id" name="product_id" value="{{ $category }}">

    @if($variants instanceof \Illuminate\Support\Collection && $variants->count() > 1)
        {{-- أكتر من Variant --}}
        @foreach($variants as $variant)
          @include('public.variant_form_fields', ['variant' => $variant])
        @endforeach

    @else
        {{-- Variant واحد --}}
        @php
          $variant = $variants instanceof \Illuminate\Support\Collection 
                      ? $variants->first() 
                      : $variants;
        @endphp
        @include('public.variant_form_fields', ['variant' => $variant])
    @endif

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">تحديث</button>
  </form>
</div>

<x-slot name="script">
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

  document.querySelectorAll(".option_name_select").forEach((select, index) => {
    const variantNames = @json($variants instanceof \Illuminate\Support\Collection 
                              ? $variants->pluck('option_name') 
                              : collect([$variants->option_name]));

    // تعبئة اسم الاختيار
    select.innerHTML = '<option value="">اختر اسم الاختيار</option>';
    if (allOptions[category]) {
      Object.keys(allOptions[category]).forEach(optionName => {
        const option = document.createElement("option");
        option.value = optionName;
        option.textContent = optionName;
        if (optionName === variantNames[index]) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    }
  });

  document.querySelectorAll(".option_name_select").forEach((select, index) => {
    const variantValues = @json($variants instanceof \Illuminate\Support\Collection 
                               ? $variants->pluck('option_value') 
                               : collect([$variants->option_value]));

    const valueSelect = document.querySelectorAll(".option_value_select")[index];
    const fillValues = (name) => {
      valueSelect.innerHTML = '<option value="">اختر قيمة</option>';
      const values = allOptions[category]?.[name] || [];
      values.forEach(value => {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = value;
        if (value === variantValues[index]) {
          option.selected = true;
        }
        valueSelect.appendChild(option);
      });
    };

    fillValues(select.value);

    select.addEventListener("change", (e) => fillValues(e.target.value));
  });
</script>
</x-slot>

</x-app-layout>
