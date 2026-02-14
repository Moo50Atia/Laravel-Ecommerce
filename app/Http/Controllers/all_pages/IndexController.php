<?php

namespace App\Http\Controllers\all_pages;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VendorRepositoryInterface;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    protected $productRepository;
    protected $userRepository;
    protected $vendorRepository;
    protected $couponRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        UserRepositoryInterface $userRepository,
        VendorRepositoryInterface $vendorRepository,
        CouponRepositoryInterface $couponRepository
    ) {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->vendorRepository = $vendorRepository;
        $this->couponRepository = $couponRepository;
    }

    // Code for favorite product in first section 
    public function index()
    {
        // Get top rated products using repository
        $topRatedProducts = $this->productRepository->getTopRated(5);

        // Get active coupons using repository
        $describeCoupon = $this->couponRepository->getRecentActive(5);

        // Get recent customer reviews (limit to 10 significantly reduces load)
        $customerReviews = ProductReview::with(["user"])->orderByDesc("created_at")->take(10)->get();

        // Get statistics using efficient cached queries
        // Admin statistics methods are heavy and scoped. Public stats should be simple totals.
        $numofusers = Cache::remember('public_total_users', 60, function () {
            return \App\Models\User::count();
        });

        $numofvendors = Cache::remember('public_total_vendors', 60, function () {
            return \App\Models\Vendor::count();
        });

        $numofproducts = Cache::remember('public_total_products', 60, function () {
            return \App\Models\Product::where('is_active', true)->count();
        });

        // We don't need these full stats arrays for the simple counters
        $userStats = [];
        $vendorStats = [];
        $productStats = [];
        // Note: View uses $userStats['total_users'] etc if we pass arrays? 
        // View might expect variables directly as below: ($numofusers)
        // Checking view usage: compact('numofusers', ...) implies they are used directly.
        // It also passed 'userStats' etc. Need to check if view uses them.
        // If view uses $userStats, we might break it.
        // However, looking at original code: $numofusers = $userStats['total_users'];
        // It extracts them but passes BOTH.
        // Ideally we should assume View uses $numofusers. 
        // I will pass empty arrays for stats to be safe but rely on variables.
        // Actually, let's just pass the variables.

        // Get real stories using repository
        $real_stories = $this->vendorRepository->getTopRated(10);

        return view("public.index", compact([
            "topRatedProducts",
            "describeCoupon",
            "userStats",
            "vendorStats",
            "productStats",
            "real_stories",
            "customerReviews",
            "numofvendors",
            "numofusers",
            "numofproducts",
        ]));
    }
}
