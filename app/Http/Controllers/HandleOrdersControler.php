<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;

class HandleOrdersControler extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected UserRepositoryInterface $userRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function Wishlist()
    {
        $wishlists = $this->userRepository->getWishlist(Auth::id());
        return view("user.wishlist", compact("wishlists"));
    }

    public function DeleteWishlist(Request $request)
    {
        $this->userRepository->removeFromWishlist(Auth::id(), (int)$request->input("product_id"));
        return redirect()->route("user.wishlist");
    }

    public function GetCart()
    {
        $order = $this->orderRepository->getPendingOrder(Auth::id());

        if (!$order || $order->items->count() == 0) {
            return redirect()->route("products.index")->with("NoProduct", "No product in the cart");
        }

        $total = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view("user.cart", compact("order", "total"));
    }

    public function GetCheckout($id)
    {
        $order = $this->orderRepository->find((int)$id);

        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route("user.cart")->with("error", "Order not found");
        }

        $NumOfProduct = $order->items->count();
        return view("user.checkout", compact("order", "NumOfProduct"));
    }

    public function PostCheckout(\App\Http\Requests\CheckoutRequest $request, $orderId)
    {
        $order = $this->orderRepository->find((int)$orderId);

        if (!$order) {
            return redirect()->route("user.cart")->with("error", "Order not found");
        }

        $this->authorize('update', $order);

        $this->orderRepository->update((int)$orderId, [
            "name" => $request->name,
            "address" => $request->address,
            "phone" => $request->phone,
            "email" => $request->email,
            "shipping_address" => $request->shipping_address,
            "billing_address"  => $request->same_as_shipping
                ? $request->shipping_address
                : $request->billing_address,
            "payment_method"   => $request->payment_method,
            "notes"            => $request->notes,
            "status"           => "processing",
        ]);

        return redirect()->route("products.index")->with("success", "تم تأكيد الطلب!");
    }

    public function GetOrders()
    {
        $orders = $this->orderRepository->getByUser(Auth::id());
        return view("user.orders", compact("orders"));
    }

    public function GetVariants(Request $request)
    {
        $product = $this->productRepository->find((int)$request->input("product_id"));
        return view("user.choes_product_variant", compact("product"));
    }

    public function PostCart(Request $request)
    {
        $order = $this->orderRepository->getPendingOrder(Auth::id());
        $product = $this->productRepository->find((int)$request->product_id);

        if (!$order) {
            $order = $this->orderRepository->createPendingOrder(Auth::id());
        }

        $variant = ProductVariant::findOrFail($request->variant_id);

        $this->orderRepository->addItem($order->id, [
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'vendor_id'  => $product->vendor_id,
            'quantity'   => 1,
            'price'      => $variant->price_modifier + $product->price,
        ]);

        return redirect()->route("products.index")->with("done", "Your Product added to cart");
    }

    public function GetOrderDetails($id)
    {
        $order = $this->orderRepository->find((int)$id);

        if (!$order) {
            return redirect()->route("user.orders")->with("error", "Order not found");
        }

        $this->authorize('view', $order);

        $order_items = $order->items;
        return view("user.order-details", compact("order", "order_items"));
    }

    public function GetProducts()
    {
        return view("public.products");
    }

    public function DestroyItem($id)
    {
        $this->orderRepository->removeItem((int)$id);
        return redirect()->route("user.cart");
    }

    public function UpdateCart(Request $request, $orderId)
    {
        $order = $this->orderRepository->find((int)$orderId);

        if (!$order) {
            return redirect()->route("user.cart")->with("error", "Order not found");
        }

        $this->authorize('update', $order);

        $total = 0;
        foreach ($request->items as $itemId => $data) {
            $this->orderRepository->updateItem((int)$itemId, [
                "quantity" => $data["quantity"],
                "price" => $data["price"],
            ]);
            $total += $data["price"] * $data["quantity"];
        }

        $this->orderRepository->update((int)$orderId, [
            "grand_total" => $total,
        ]);

        return redirect()->route("user.checkout", $order->id)->with("success", "Cart updated successfully!");
    }
}
