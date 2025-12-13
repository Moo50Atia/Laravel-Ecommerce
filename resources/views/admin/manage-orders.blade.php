@extends('layouts.app')

@section('style')
@section('content')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .order-card {
                transition: all 0.3s ease;
            }
            .order-card:hover {
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
            .status-pending { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-processing { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
            .status-shipped { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-delivered { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-canceled { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .status-refunded { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
            .pagination { @apply mt-8; }
            .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
            .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .order-card {
                transition: all 0.3s ease;
            }
            .order-card:hover {
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
            .status-pending { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-processing { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
            .status-shipped { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
            .status-delivered { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }
            .status-canceled { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
            .status-refunded { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
            .pagination { @apply mt-8; }
            .pagination-link { @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer transform hover:scale-105; }
            .pagination-active { @apply z-10 bg-blue-600 border-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform scale-105; }
        </style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        @vite(['resources/js/admin/orders.js'])
@endsection