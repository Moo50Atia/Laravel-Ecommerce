@extends('layouts.app')

@section('style')
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
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إضافة منتج جديد</h1>
                    <p class="text-gray-600 mt-2">أضف منتج جديد إلى المتجر</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                    العودة إلى المنتجات
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">اسم المنتج *</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Price -->
                    <div class="form-group">
                        <label for="price" class="form-label">السعر *</label>
                        <input type="number" id="price" name="price" class="form-input" value="{{ old('price') }}" step="0.01" min="0" required>
                        @error('price')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Description -->
                    <div class="form-group md:col-span-2">
                        <label for="description" class="form-label">وصف المنتج</label>
                        <textarea id="description" name="description" class="form-textarea">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Image -->
                    <div class="form-group">
                        <label for="image" class="form-label">صورة المنتج</label>
                        <input type="file" id="image" name="image" class="form-input" accept="image/*">
                        @error('image')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Status -->
                    <div class="form-group">
                        <label for="status" class="form-label">حالة المنتج</label>
                        <select id="status" name="status" class="form-input">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                        @error('status')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-primary">
                        إضافة المنتج
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection