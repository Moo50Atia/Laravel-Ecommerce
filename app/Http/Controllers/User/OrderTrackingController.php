<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    public function show($id)
    {
        $order = Order::with(['statusHistory.changer', 'items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.order-tracking', compact('order'));
    }
}
