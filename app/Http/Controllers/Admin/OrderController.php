<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['user', 'vendor', 'items.product'])->ForAdmin($user);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $paymentMethod = $request->get('payment_method');
            $query->where('payment_method', $paymentMethod);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $paymentStatus = $request->get('payment_status');
            $query->where('payment_status', $paymentStatus);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Get statistics for all orders (not just current page)
        
        $allOrders = Order::ForAdmin($user)->get();
        $allOrdersCount = $allOrders->count();
        $totalSails = number_format($allOrders->sum('grand_total'), 2);
        $pendingOrders = $allOrders->where('status', 'pending')->count();
        $CompletedOrders = $allOrders->where('status', 'delivered')->count();

        // Get unique values for filter dropdowns
        $statuses = Order::ForAdmin($user)->distinct()->pluck('status')->filter()->values();
        $paymentMethods = Order::ForAdmin($user)->distinct()->pluck('payment_method')->filter()->values();
        $paymentStatuses = Order::ForAdmin($user)->distinct()->pluck('payment_status')->filter()->values();

        return view('admin.manage-orders', compact(
            'orders', 
            'allOrders', 
            'allOrdersCount', 
            'totalSails', 
            'pendingOrders', 
            'CompletedOrders',
            'statuses',
            'paymentMethods',
            'paymentStatuses'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'vendor', 'items.product', 'items.variant']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,canceled,refunded',
            'payment_status' => 'required|in:paid,unpaid,failed',
            'total_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:credit_card,cod,bank_transfer',
            'notes' => 'nullable|string|max:1000'
        ]);

        $data = $request->only([
            'status', 
            'payment_status', 
            'total_amount', 
            'discount_amount', 
            'shipping_amount', 
            'payment_method', 
            'notes'
        ]);

        // Calculate grand total
        $totalAmount = $request->total_amount ?? 0;
        $discountAmount = $request->discount_amount ?? 0;
        $shippingAmount = $request->shipping_amount ?? 0;
        $data['grand_total'] = $totalAmount - $discountAmount + $shippingAmount;

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)->with('success', 'تم تحديث الطلب بنجاح');
    }

    public function destroy(Order $order)
    {
        // Check if order can be deleted
        if (in_array($order->status, ['delivered', 'shipped'])) {
            return redirect()->route('admin.orders.index')->with('error', 'لا يمكن حذف الطلبات المكتملة أو المشحونة');
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'تم حذف الطلب بنجاح');
    }
}
