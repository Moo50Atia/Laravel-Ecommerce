{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Store Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your store or business information.') }}
        </p>
    </header>

    {{-- <form method="post" action="{{ route('vendor.profile.update') }}" class="mt-6 space-y-6"> 
    <form method="post" action="" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="store_name" :value="__('Store Name')" />
            <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full" value="{{ old('store_name', $vendor->store_name ?? '') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
        </div>

        <div>
            <x-input-label for="store_address" :value="__('Store Address')" />
            <x-text-input id="store_address" name="store_address" type="text" class="mt-1 block w-full" value="{{ old('store_address', $vendor->store_address ?? '') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('store_address')" />
        </div>

        {{-- أي بيانات إضافية تخص المتجر 

        <x-primary-button>{{ __('Save') }}</x-primary-button>
    </form>
</section> --}}
{{-- <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000">
  <h2 class="text-2xl font-bold mb-4">معلومات المتجر</h2>

    {{-- store name --}}

   <div>
            <x-input-label for="store_name" :value="__('Store Name')" />
            {{-- <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full" value="{{ old('store_name', $vendor->store_name ?? '') }}" />
            <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full" value="" />
            <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
        </div>
  {{-- Logo 
  @if($vendor->logo)
    <div class="mb-4 flex items-center space-x-4">
      <img src="{{ asset('storage/' . $vendor->logo) }}" alt="لوجو المتجر" class="w-20 h-20 rounded-full border object-cover">
      <span class="font-semibold text-lg">{{ $vendor->store_name }}</span>
    </div>
  @endif

  {{-- Commission Rate 
  <div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">نسبة العمولة:</label>
    <p class="text-gray-800 bg-gray-100 px-4 py-2 rounded">{{ $vendor->commission_rate }}%</p>
    <p class="text-gray-800 bg-gray-100 px-4 py-2 rounded">{{ $vendor->%</p>
  </div>

  {{-- Description 
  <div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">وصف المتجر:</label>
    <p class="text-gray-800 bg-gray-100 px-4 py-2 rounded">{{ $vendor->store_description ?? 'لا يوجد وصف متاح' }}</p>
  </div>

  {{-- Store Phone



</div> --}}
{{-- <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto mt-10" data-aos="fade-up" data-aos-duration="1000">
  <h2 class="text-2xl font-bold mb-6 text-gray-800">معلومات المتجر</h2>

  {{-- Logo + Store Name 
  <div class="mb-6 flex items-center space-x-4">
    <img src="https://via.placeholder.com/80" alt="لوجو المتجر" class="w-20 h-20 rounded-full object-cover border">
    <span class="font-semibold text-xl text-gray-700">متجر الأحلام</span>
  </div>

  {{-- Commission Rate
  <div class="mb-4">
    <label class="block text-gray-600 font-medium mb-1">نسبة العمولة:</label>
    <p class="bg-gray-100 px-4 py-2 rounded text-gray-800">10.00%</p>
  </div>

  {{-- Description 
  <div class="mb-4">
    <label class="block text-gray-600 font-medium mb-1">وصف المتجر:</label>
    <p class="bg-gray-100 px-4 py-2 rounded text-gray-800">
      نحن نقدم أفضل المنتجات بأفضل الأسعار، مع شحن سريع وخدمة عملاء ممتازة.
    </p>
  </div>

  {{-- Store Phone 
  <div class="mb-4">
    <label class="block text-gray-600 font-medium mb-1">رقم الهاتف:</label>
    <p class="bg-gray-100 px-4 py-2 rounded text-gray-800">+201234567890</p>
  </div>

  {{-- Store Email 
  <div class="mb-4">
    <label class="block text-gray-600 font-medium mb-1">البريد الإلكتروني:</label>
    <p class="bg-gray-100 px-4 py-2 rounded text-gray-800">store@example.com</p>
  </div>

  {{-- Optional Store Banner 
  <div class="mt-6">
    <label class="block text-gray-600 font-medium mb-1">غلاف المتجر:</label>
    <img src="https://via.placeholder.com/800x200" class="w-full rounded-lg shadow" alt="غلاف المتجر">
  </div>
</div> --}}


{{-- <form method="POST" action="#" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto mt-10" data-aos="fade-up" data-aos-duration="1000">
    {{-- CSRF Token 
    @csrf
    @method('POST')  ممكن تعدلها لـ PUT لو انت بتحدث فعلياً 

    <h2 class="text-2xl font-bold mb-6 text-gray-800">تعديل بيانات المتجر</h2>



    {{-- Store Name 
    <div class="mb-4">
        <label class="block text-gray-600 font-medium mb-1">اسم المتجر:</label>
        <input type="text" name="store_name" value="متجر الأحلام" class="w-full border px-4 py-2 rounded text-gray-800 focus:outline-none focus:ring focus:ring-blue-300">
    </div>

    {{-- Commission Rate 
    <div class="mb-4">
        <label class="block text-gray-600 font-medium mb-1">نسبة العمولة (%):</label>
        <input type="number" name="commission_rate" value="10.00" step="0.01" class="w-full border px-4 py-2 rounded text-gray-800 focus:outline-none focus:ring focus:ring-blue-300">
    </div>

    {{-- Description 
    <div class="mb-4">
        <label class="block text-gray-600 font-medium mb-1">وصف المتجر:</label>
        <textarea name="description" rows="3" class="w-full border px-4 py-2 rounded text-gray-800 focus:outline-none focus:ring focus:ring-blue-300">
نحن نقدم أفضل المنتجات بأفضل الأسعار، مع شحن سريع وخدمة عملاء ممتازة.
        </textarea>
    </div>




    {{-- Submit 
    <div class="mt-6 text-end">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">حفظ التعديلات</button>
    </div>
</form> --}}
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Vendor Profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Edit your store information and commission settings.') }}
        </p>
    </header>

    <form method="POST" action="{{route("profile.updateVendor")}}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')


        <div class="grid grid-cols-2 gap-4">
{{-- اسم المتجر --}}
<div>
    <x-input-label for="store_name" :value="__('Store Name')" />
    <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full" value="{{ $user->vendor->store_name ?? '' }}" />
    <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
</div>

{{-- نسبة العمولة --}}
<div>
    <x-input-label for="commission_rate" :value="__('Commission Rate (%)')" />
    <x-text-input id="commission_rate" name="commission_rate" type="number" step="0.01" class="mt-1 block w-full" value="{{ $user->vendor->commission_rate ?? '' }}" />
    <x-input-error class="mt-2" :messages="$errors->get('commission_rate')" />
</div>



        {{-- وصف المتجر --}}
        <div>
            <x-input-label for="description" :value="__('Store Description')" />
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border rounded px-3 py-2 text-gray-800" value="{{ $user->vendor->description ?? " " }}">

            </textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>



        {{-- زر الحفظ --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'vendor-profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
