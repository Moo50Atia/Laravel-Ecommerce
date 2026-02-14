<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = Order::where("vendor_id", Auth::user()->vendor->id)->with("user");

        if ($request->filled("status") && $request->input("status") != "all") {
            $query->where("status", $request->input("status"));
        }

        $orders = $query->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('vendor.orders.create');
    }

    public function store(OrderRequest $request): \Illuminate\Http\RedirectResponse
    {
        Order::create($request->validated());
        return redirect()->route('vendor.orders.index')->with('success', 'Created successfully');
    }

    public function show(Order $order): \Illuminate\Contracts\View\View
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): \Illuminate\Contracts\View\View
    {
        return view('vendor.orders.edit', compact('order'));
    }

    public function update(OrderRequest $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $order->update($request->validated());
        return redirect()->route('vendor.orders.show', $order->id)->with('success', 'Updated successfully');
    }

    public function destroy(Order $order): \Illuminate\Http\RedirectResponse
    {
        $order->delete();
        return redirect()->route('vendor.orders.index')->with('success', 'Deleted successfully');
    }
    public function dashboard()
    {
        $all_products = Product::where("vendor_id", Auth::user()->vendor->id)->count();
        $all_orders = Order::where("vendor_id", Auth::user()->vendor->id)->count();
        $current_orders = Order::where("vendor_id", Auth::user()->vendor->id)
            ->where("status", "delivered")->count();
        $canceld_oders = Order::where("vendor_id", Auth::user()->vendor->id)
            ->where("status", "canceled")->count();
        return view("vendor.dashboard", compact("all_products", "all_orders", "current_orders", "canceld_oders"));
    }
}
