@extends('layouts.app')

@section('style')
<style>
            .blog-container { @apply bg-white rounded-lg shadow-md; }
            .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg; }
            .blog-content { @apply p-6; }
            .blog-meta { @apply flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 p-4 bg-gray-50 rounded-lg; }
            .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
        </style>
@endsection

@section('content')
@section('style')
        <style>
            .blog-container { @apply bg-white rounded-lg shadow-md; }
            .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg; }
            .blog-content { @apply p-6; }
            .blog-meta { @apply flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 p-4 bg-gray-50 rounded-lg; }
            .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
            .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200; }
        </style>
@endsection