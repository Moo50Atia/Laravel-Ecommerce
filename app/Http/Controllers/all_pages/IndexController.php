<?php

namespace App\Http\Controllers\all_pages;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ProductReview;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class IndexController extends Controller
{
    // Code for favoruite product in first section 
    public function index(){
    $topRatedProducts = Product::withAvg('productReviews', 'rating')
    ->with('image') // assuming morph relation called 'images'
    ->orderByDesc('reviews_avg_rating')
    ->take(5)
    ->get();
// code for coupons section
$describeCoupon = Coupon::orderByDesc("created_at")->get();

    // customer reviews
$customerReviews = ProductReview::with("user")->orderByDesc("created_at")->get();
// numpers 
$numofusers = User::count();
$numofvendors = Vendor::count();
$numofproducts = Product::count();
// real stories
$real_stories = Vendor::orderBy("rating")->with("image")->get();

    return view("public.index" , compact([
        "topRatedProducts",
        "describeCoupon",
        "numofusers",
        "numofvendors",
        "numofproducts",
        "real_stories",
        "customerReviews",
    ]));
    }
}
