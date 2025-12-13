@extends('layouts.app')

@section('style')
<style>
                         .blog-card { @apply bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1; }
                         .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-t-lg p-4 transition-all duration-300; }
                         .blog-content { @apply p-6 transition-all duration-200; }
                         .blog-meta { @apply flex items-center space-x-4 text-sm text-gray-600 mb-3 transition-all duration-200; }
                         .blog-actions { @apply flex items-center space-x-2 mt-4 transition-all duration-200; }
                         .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-success { @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .search-box { @apply w-full md:w-96 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .filter-select { @apply px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .stats-card { @apply bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1; }
                         .pagination { @apply mt-8; }
             .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
             .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('content')
@section('style')
        <style>
                         .blog-card { @apply bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1; }
                         .blog-header { @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-t-lg p-4 transition-all duration-300; }
                         .blog-content { @apply p-6 transition-all duration-200; }
                         .blog-meta { @apply flex items-center space-x-4 text-sm text-gray-600 mb-3 transition-all duration-200; }
                         .blog-actions { @apply flex items-center space-x-2 mt-4 transition-all duration-200; }
                         .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-success { @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
             .btn-warning { @apply bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105; }
                         .search-box { @apply w-full md:w-96 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .filter-select { @apply px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200; }
                         .stats-card { @apply bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1; }
                         .pagination { @apply mt-8; }
             .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
             .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('script')
<script>
            // Auto-refresh every 30 seconds to show latest data
            setInterval(function() {
                // You can implement AJAX refresh here if needed
            }, 30000);

            // Add some interactivity for better UX
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form when filters change
                const filterSelects = document.querySelectorAll('select[name="status"], select[name="author"]');
                filterSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        this.closest('form').submit();
                    });
                });

                // Auto-submit search with delay
                const searchInput = document.querySelector('input[name="search"]');
                let searchTimeout;
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.closest('form').submit();
                        }, 500); // 500ms delay
                    });
                }

                // Add loading state to form submission
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري التطبيق...';
                            submitBtn.disabled = true;
                        }
                        
                        // Add loading overlay
                        const loadingOverlay = document.createElement('div');
                        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        loadingOverlay.innerHTML = '<div class="bg-white p-4 rounded-lg"><div class="flex items-center"><svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري تحميل النتائج...</div></div>';
                        document.body.appendChild(loadingOverlay);
                    });
                }
            });
        </script>
@endsection