<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\all_pages\ProductController;
use App\Http\Controllers\all_pages\ProductVariantController;
use App\Models\Product;
use App\Models\Order;
USE Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrderController;

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('/dashboard',[OrderController::class , "dashboard"]  )->name('dashboard');


    //     Route::get('/orders12', function(){
    //     return view("vendor.orders.orders");
    // } )->name('vendor-orders');



        Route::get('/order-details', function(){
        return view("vendor.orders.order-details");
    } )->name('vendor-order-details');


Route::resource('/products', ProductController::class)
    ->except(['index', 'show'])
    // ->withoutMiddlewareFor("create","")
    ->middlewareFor(["edit" , "destroy"] , "check_product");

Route::get("products" , function (){

$products = Product::orderByDesc("created_at")
    ->with("variants")
    ->where("vendor_id", Auth::user()->vendor->id)
    ->get();
    return view("vendor.products.products" , compact("products"));
})->name("products");


Route::get('/create-variant',[ProductVariantController::class,"create"] )->name('variant.create'); 
Route::post("/create-variant", [ProductVariantController::class,"store"] )->name('variant.store');
Route::get('/update-variant', [ProductVariantController::class,"edit"])->name('variant.edit');
Route::put('/update-variant/{id}', [ ProductVariantController::class,"update"])->name('variant.update');
Route::resource('/orders', App\Http\Controllers\OrderController::class);
   
});
