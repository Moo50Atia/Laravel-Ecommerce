@extends('layouts.app')

@section('style')
<style>
    body { font-family: 'Cairo', sans-serif; }
    .product-card { transition: transform 0.3s; }
    .product-card:hover { transform: scale(1.05); }
    .product-card img { height: 200px; object-fit: cover; width: 100%; }
    .btn-primary { transition: all 0.3s; }
    .btn-primary:hover { transform: scale(1.1); }
    .swiper-slide { text-align: center; padding: 20px; }
    .swiper-slide img { height: 300px; object-fit: cover; width: 100%; }
    .swiper-content { margin-top: 10px; }
    .subcategory-select { display: none; }
  </style>
@endsection

@section('content')
@section('style')

  <style>
    body { font-family: 'Cairo', sans-serif; }
    .product-card { transition: transform 0.3s; }
    .product-card:hover { transform: scale(1.05); }
    .product-card img { height: 200px; object-fit: cover; width: 100%; }
    .btn-primary { transition: all 0.3s; }
    .btn-primary:hover { transform: scale(1.1); }
    .swiper-slide { text-align: center; padding: 20px; }
    .swiper-slide img { height: 300px; object-fit: cover; width: 100%; }
    .swiper-content { margin-top: 10px; }
    .subcategory-select { display: none; }
  </style>
@endsection

@section('script')
<script>
    // تهيئة AOS
    AOS.init();

    // تهيئة Swiper
    var swiper = new Swiper('.mySwiper', {
      loop: true,
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      autoplay: { delay: 3000 },
      breakpoints: {
        640: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 40 }
      }
    });

    // تحديث قيمة السعر عند تحريك الـ range
    document.querySelector('input[type="range"]').addEventListener('input', function() {
      document.getElementById('priceValue').textContent = this.value + ' $';
    });

    // التحكم في القوائم المنسدلة الفرعية
    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    categorySelect.addEventListener('change', function() {
      const selectedCategory = this.value;
      subcategorySelect.style.display = selectedCategory ? 'block' : 'none';
      const options = subcategorySelect.querySelectorAll('option');
      options.forEach(option => {
        if (option.value === '' || option.dataset.category === selectedCategory) {
          option.style.display = 'block';
        } else {
          option.style.display = 'none';
        }
      });
    });
  </script>
@endsection