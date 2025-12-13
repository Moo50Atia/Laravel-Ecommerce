@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-image {
                transition: all 0.3s ease;
            }
            .product-image:hover {
                transform: scale(1.05);
            }
            .variant-card {
                transition: all 0.3s ease;
            }
            .variant-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
        </style>
@endsection

@section('content')
@section('style')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <style>
            .product-image {
                transition: all 0.3s ease;
            }
            .product-image:hover {
                transform: scale(1.05);
            }
            .variant-card {
                transition: all 0.3s ease;
            }
            .variant-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
        </style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            // Initialize Swiper for product images
            const swiper = new Swiper('.product-swiper', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        </script>
@endsection