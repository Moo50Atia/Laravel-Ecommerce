@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .swiper-slide {
                height: auto;
            }
            .stats-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card-2 {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            .stats-card-3 {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            .stats-card-4 {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }
            .status-active { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-inactive { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .pagination { @apply mt-8; }
            .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
            .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('content')
@section('style')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .swiper-slide {
                height: auto;
            }
            .stats-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card-2 {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            .stats-card-3 {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            .stats-card-4 {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }
            .status-active { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-inactive { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .pagination { @apply mt-8; }
            .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
            .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            // Auto-refresh every 30 seconds to show latest data
            setInterval(function() {
                // You can implement AJAX refresh here if needed
            }, 30000);

            // Add some interactivity for better UX
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form when filters change
                const filterSelects = document.querySelectorAll('select[name="category"], select[name="vendor"], select[name="status"]');
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

            function exportProducts() {
                // Implement export functionality
                alert('سيتم تصدير البيانات قريباً');
            }
        </script>
@endsection