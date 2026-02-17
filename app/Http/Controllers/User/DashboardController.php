<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $activeSubscription = $user->subscription;

        return view('user.dashboard', compact('user', 'recentOrders', 'wishlistCount', 'activeSubscription'));
    }
}
