<?php
USE Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\all_pages\BlogController;
use App\Http\Controllers\all_pages\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\all_pages\ProductController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

Route::get('/', [IndexController::class , "index"])->name('index');

Route::controller(ProfileController::class)->middleware("auth")->prefix('profile')->name("profile.")->group(function(){ 

Route::get('/edit',"edit" )->name('edit');
Route::delete('/delete',"destroy" )->name('destroy');
Route::patch('/update-address',"updateAddress" )->name('updateAddress');
Route::patch('/update-personal',"updatePersonal" )->name('updatePersonal');
Route::patch('/update-vendor',"updateVendor" )->name('updateVendor');

});


Route::get("/products/search" , [ProductController::class , "search"])->name("product.search");

// Routes عامة يقدر أي حد يشوفها
Route::resource('/products', ProductController::class)->only(['index', 'show']);

// Add to wishlist route
Route::post('/products/{product}/wishlist', [ProductController::class, 'addToWishlist'])->name('products.wishlist.add')->middleware('auth');

// Add product review route
Route::post('/products/{product}/review', [ProductController::class, 'storeReview'])->name('products.review.store')->middleware('auth');


Route::view("/policy","public.policy");

Route::middleware(["auth" , "check_blog"])->resource('/blogs', BlogController::class)
->withoutMiddlewareFor(["index" , "show"],["auth" , "check_blog"])
->withoutMiddlewareFor(["store" , "create"],["auth"]);

// Blog rating route
Route::post('/blogs/{blog}/rate', [BlogController::class, 'rate'])->name('blogs.rate')->middleware('auth');

// Blog review delete route
Route::delete('/blogs/reviews/{review}', [BlogController::class, 'destroyReview'])->name('blogs.reviews.destroy')->middleware('auth');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/vendor.php';
require __DIR__.'/user.php';
require __DIR__.'/superadmin.php';









