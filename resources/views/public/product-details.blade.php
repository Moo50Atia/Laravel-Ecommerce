@extends('layouts.app')

@section('style')
<style>
    body { font-family: 'Cairo', sans-serif; }
    .product-img { height: 250px; object-fit: cover; width: 100%; }
    .swiper-slide { text-align: center; }
    .related-products img { height: 100px; object-fit: cover; width: 100%; }
    .subcategory-select { display: none; }
  </style>
@endsection

@section('content')
@section('style')
  <style>
    body { font-family: 'Cairo', sans-serif; }
    .product-img { height: 250px; object-fit: cover; width: 100%; }
    .swiper-slide { text-align: center; }
    .related-products img { height: 100px; object-fit: cover; width: 100%; }
    .subcategory-select { display: none; }
  </style>
@endsection

@section('script')
<script>
    AOS.init();
    var swiper = new Swiper('.mySwiper', {
      loop: true,
      pagination: { el: '.swiper-pagination', clickable: true },
      autoplay: { delay: 3000 }
    });
    var relatedSwiper = new Swiper('.relatedSwiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });

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