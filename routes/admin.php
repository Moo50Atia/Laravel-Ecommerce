<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
    Route::resource('/users', UserController::class);
    
    // Product management
    Route::resource('/products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Order management
    Route::resource('/orders', \App\Http\Controllers\Admin\OrderController::class);
    
    // Category management
    Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Vendor management
    Route::resource('/vendors', \App\Http\Controllers\Admin\VendorController::class);
    
    // Coupon management
    Route::resource('/coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    // Blog management
    Route::resource('/blogs', \App\Http\Controllers\Admin\BlogController::class);
});
