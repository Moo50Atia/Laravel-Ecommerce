@extends('layouts.app')

@section('style')
<style>
    .form-container { @apply bg-white rounded-lg shadow-md p-6; }
    .form-group { @apply mb-6; }
    .form-label { @apply block text-sm font-medium text-gray-700 mb-2; }
    .form-input { @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent; }
    .form-textarea { @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent h-32; }
    .form-select { @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent; }
    .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition-colors duration-200; }
    .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md transition-colors duration-200; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إضافة مدونة جديدة</h1>
                    <p class="text-gray-600 mt-2">إنشاء مدونة جديدة في المتجر</p>
                </div>
                <a href="{{ route('admin.blogs.index') }}" class="btn-secondary">
                    العودة إلى المدونات
                </a>
            </div>
        </div>

        <!-- Create Blog Form -->
        <div class="form-container">
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="form-group md:col-span-2">
                        <label for="title" class="form-label">عنوان المدونة *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               class="form-input @error('title') border-red-500 @enderror" 
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="form-group md:col-span-2">
                        <label for="short_description" class="form-label">وصف مختصر</label>
                        <textarea id="short_description" 
                                  name="short_description" 
                                  class="form-textarea @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="form-group md:col-span-2">
                        <label for="content" class="form-label">محتوى المدونة *</label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-textarea @error('content') border-red-500 @enderror h-64" 
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Author -->
                    <div class="form-group">
                        <label for="author_id" class="form-label">المؤلف *</label>
                        <select id="author_id" 
                                name="author_id" 
                                class="form-select @error('author_id') border-red-500 @enderror" 
                                required>
                            <option value="">اختر المؤلف</option>
                            @foreach($authors as $user)
                                <option value="{{ $user->id }}" {{ old('author_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publication Status -->
                    <div class="form-group">
                        <label for="is_published" class="form-label">حالة النشر</label>
                        <select id="is_published" 
                                name="is_published" 
                                class="form-select @error('is_published') border-red-500 @enderror">
                            <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>مسودة</option>
                            <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>منشورة</option>
                        </select>
                        @error('is_published')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="form-group md:col-span-2">
                        <label for="cover_image" class="form-label">صورة الغلاف</label>
                        <input type="file" 
                               id="cover_image" 
                               name="cover_image" 
                               accept="image/*" 
                               class="form-input @error('cover_image') border-red-500 @enderror">
                        @error('cover_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">الصيغ المدعومة: JPG, PNG, GIF. الحد الأقصى: 2MB</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.blogs.index') }}" class="btn-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-primary">
                        إنشاء المدونة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection