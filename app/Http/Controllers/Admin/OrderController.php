<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\SearchFilterService;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Enums\OrderStatus;

class OrderController extends Controller
{
    protected $searchFilterService;
    protected $orderRepository;

    public function __construct(
        SearchFilterService $searchFilterService,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->searchFilterService = $searchFilterService;
        $this->orderRepository = $orderRepository;
    }
    public function index(Request $request)
    {
        $user = Auth::user();

        // Use repository for order filtering and pagination
        $orders = $this->orderRepository->getForAdmin($user, [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'payment_method' => $request->get('payment_method'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'min_amount' => $request->get('min_amount'),
            'max_amount' => $request->get('max_amount'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->orderRepository->getAdminStatistics($user);

        // Get filter options using repository
        $statuses = $this->orderRepository->getStatusesForAdmin($user);
        $paymentMethods = $this->orderRepository->getPaymentMethodsForAdmin($user);
        $paymentStatuses = $this->orderRepository->getPaymentStatusesForAdmin($user);
        // dd($orders, $statistics, $statuses, $paymentMethods, $paymentStatuses);
        return view('admin.manage-orders', compact(
            'orders',
            'statistics',
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
        $cases = OrderStatus::cases();
        return view('admin.orders.edit', compact('order', 'cases'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
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

        // Use repository to update order
        $this->orderRepository->update($order->id, $data);

        return redirect()->route('admin.orders.show', $order)->with('success', 'تم تحديث الطلب بنجاح');
    }

    public function destroy(Order $order)
    {
        // Check if order can be deleted
        if (in_array($order->status, ['delivered', 'shipped'])) {
            return redirect()->route('admin.orders.index')->with('error', 'لا يمكن حذف الطلبات المكتملة أو المشحونة');
        }

        // Use repository to delete order
        $this->orderRepository->delete($order->id);
        return redirect()->route('admin.orders.index')->with('success', 'تم حذف الطلب بنجاح');
    }
}
