@extends('layouts.app')

@section('style')
@section('content')
        <style>
            body { font-family: 'Cairo', sans-serif; }
            .product-img { height: 250px; object-fit: cover; width: 100%; }
            .swiper-slide { text-align: center; }
            .related-products img { height: 100px; object-fit: cover; width: 100%; }
            .subcategory-select { display: none; }
        </style>
@endsection

@section('content')
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
            new Swiper('.mySwiper', {
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 3000 }
            });
            new Swiper('.relatedSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            });

            // Star Rating Interaction
            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('input[name="rating"]');
                const starLabels = document.querySelectorAll('label[for^="rating"]');
                
                starLabels.forEach((label, index) => {
                    label.addEventListener('click', function() {
                        // Remove all yellow classes
                        starLabels.forEach(l => l.classList.remove('text-yellow-400'));
                        
                        // Add yellow class to clicked star and previous stars
                        for (let i = 0; i <= index; i++) {
                            starLabels[i].classList.add('text-yellow-400');
                        }
                    });
                    
                    label.addEventListener('mouseenter', function() {
                        // Remove all yellow classes
                        starLabels.forEach(l => l.classList.remove('text-yellow-400'));
                        
                        // Add yellow class to hovered star and previous stars
                        for (let i = 0; i <= index; i++) {
                            starLabels[i].classList.add('text-yellow-400');
                        }
                    });
                });
                
                // Reset stars when mouse leaves the rating container
                const ratingContainer = document.querySelector('.flex.space-x-1');
                if (ratingContainer) {
                    ratingContainer.addEventListener('mouseleave', function() {
                        const checkedStar = document.querySelector('input[name="rating"]:checked');
                        if (!checkedStar) {
                            starLabels.forEach(l => l.classList.remove('text-yellow-400'));
                        } else {
                            const checkedIndex = Array.from(stars).indexOf(checkedStar);
                            starLabels.forEach(l => l.classList.remove('text-yellow-400'));
                            for (let i = 0; i <= checkedIndex; i++) {
                                starLabels[i].classList.add('text-yellow-400');
                            }
                        }
                    });
                }
            });
        </script>
@endsection