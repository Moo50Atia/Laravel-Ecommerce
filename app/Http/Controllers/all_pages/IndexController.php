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

class IndexController extends Controller
{
    protected $productRepository;
    protected $userRepository;
    protected $vendorRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        UserRepositoryInterface $userRepository,
        VendorRepositoryInterface $vendorRepository
    ) {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->vendorRepository = $vendorRepository;
    }

    // Code for favorite product in first section 
    public function index()
    {
        // Get top rated products using repository
        $topRatedProducts = $this->productRepository->getTopRated(5);
        
        // Get coupons (keeping direct model access for now as no repository exists)
        $describeCoupon = Coupon::orderByDesc("created_at")->get();

        // Get customer reviews (keeping direct model access for now as no repository exists)
        $customerReviews = ProductReview::with(["user"])->orderByDesc("created_at")->get();
        
        // Get statistics using repositories
        $userStats = $this->userRepository->getStatistics();
        $vendorStats = $this->vendorRepository->getStatistics();
        $user = Auth::user();
        if (!$user) {
            $user = new \App\Models\User();
            $user->role = 'user'; // Default role for public access
        }
        $productStats = $this->productRepository->getAdminStatistics($user);
        
        // Get real stories using repository
        $real_stories = $this->vendorRepository->getTopRated(10);

        // Extract individual statistics for blade template
        $numofvendors = $vendorStats['total_vendors'];
        $numofusers = $userStats['total_users'];
        $numofproducts = $productStats['total_products'];

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
