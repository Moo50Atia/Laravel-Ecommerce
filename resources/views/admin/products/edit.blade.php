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
            .current-image {
                max-width: 200px;
                border-radius: 0.5rem;
                border: 2px solid #e5e7eb;
            }
        </style>
@endsection

@section('content')
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
            .current-image {
                max-width: 200px;
                border-radius: 0.5rem;
                border: 2px solid #e5e7eb;
            }
        </style>
@endsection

@section('script')
<script>
            function deleteImage(imageId) {
                if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                    // You can implement AJAX delete here
                    // For now, we'll just show an alert
                    alert('سيتم حذف الصورة قريباً');
                }
            }
        </script>
@endsection