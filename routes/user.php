<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HandleOrdersControler;
use App\Http\Controllers\User\DashboardController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Wishlist;
use Illuminate\Http\Request;
USE Illuminate\Support\Facades\Auth;
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function (){

        return view("user.dashboard");
    } )->name('dashboard');



      Route::get('/wishlist', [HandleOrdersControler::class,"Wishlist"] )->name('wishlist');


    Route::delete("/wishlist" , [HandleOrdersControler::class,"DeleteWishlist"])->name("wishlist.delete");

    
      Route::get('/cart', [HandleOrdersControler::class,"GetCart"] )->name('cart');

       Route::get('/checkout', [HandleOrdersControler::class,"GetCheckout"])->name('checkout'); 



           Route::get('/orders', [HandleOrdersControler::class,"GetOrders"])->name('orders');

        Route::get("/choes-variant" , [HandleOrdersControler::class,"GetVariants"])->name("chose.variant");

        Route::post("/cart" ,[HandleOrdersControler::class,"PostCart"])->name("cart.store");



           Route::get('/order-details/{id}', [HandleOrdersControler::class,"GetOrderDetails"])->name('order-details');

    Route::get('/products',[HandleOrdersControler::class,"GetProducts"])->name('products');

    Route::delete("/cart/{id}" ,[HandleOrdersControler::class,"DestroyItem"])->name("delete_item");

    Route::put("/cart/{id}" ,[HandleOrdersControler::class,"UpdateCart"])->name("update_cart");

    Route::get("/checkout/{id}" , [HandleOrdersControler::class,"GetCheckout"])->name("checkout");

    Route::post("/checkout/{id}" , [HandleOrdersControler::class,"PostCheckout"])->name("checkout.store");

  });


