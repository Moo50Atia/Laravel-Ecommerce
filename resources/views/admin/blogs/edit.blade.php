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
            .current-image { @apply max-w-xs rounded-lg border border-gray-300; }
        </style>
@endsection

@section('content')
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
            .current-image { @apply max-w-xs rounded-lg border border-gray-300; }
        </style>
@endsection