<x-app-layout>
    <x-slot name="style">
        <style>
            .form-group {
                margin-bottom: 1.5rem;
            }
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: #374151;
            }
            .form-input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }
            .form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            .form-textarea {
                min-height: 120px;
                resize: vertical;
            }
            .error-message {
                color: #dc2626;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة منتج جديد</h1>
                    <p class="text-gray-600">إنشاء منتج جديد في المتجر</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Display -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4" data-aos="fade-up">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في النموذج</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">المعلومات الأساسية</h2>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">اسم المنتج *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   class="form-input @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">وصف المنتج</label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-input form-textarea @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label for="category_id" class="form-label">الفئة</label>
                            <select id="category_id" 
                                    name="category_id" 
                                    class="form-input @error('category_id') border-red-500 @enderror">
                                <option value="">اختر الفئة</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <div class="form-group">
                            <label for="vendor_id" class="form-label">البائع</label>
                            <select id="vendor_id" 
                                    name="vendor_id" 
                                    class="form-input @error('vendor_id') border-red-500 @enderror">
                                <option value="">اختر البائع</option>
                                @foreach(\App\Models\Vendor::all() as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="mr-2 text-sm text-gray-700">نشط</span>
                            </label>
                        </div>
                    </div>

                    <!-- Images and Additional Info -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">الصور والمعلومات الإضافية</h2>
                        
                        <div class="form-group">
                            <label for="images" class="form-label">صور المنتج</label>
                            <input type="file" 
                                   id="images" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*"
                                   class="form-input @error('images') border-red-500 @enderror">
                            @error('images')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">يمكنك اختيار أكثر من صورة</p>
                        </div>

                        {{-- <div class="form-group">
                            <label for="sku" class="form-label">رمز المنتج (SKU)</label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}" 
                                   class="form-input @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <div class="form-group">
                            <label for="short_description" class="form-label">short_description</label>
                            <input type="text" 
                                   id="short_description" 
                                   name="short_description" 
                                   value="{{ old('short_description') }}" 
                                   class="form-input @error('short_description') border-red-500 @enderror">
                            @error('short_description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">description</label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-input form-textarea @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        إنشاء المنتج
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
