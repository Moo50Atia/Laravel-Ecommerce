<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
USE Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;



class HandleOrdersControler extends Controller
{
    public function Wishlist(){
        
            $wishlists = Wishlist::with("product")->where("user_id" , Auth::user()->id )->get();
        return view("user.wishlist" , compact("wishlists"));
    
    }
    public function DeleteWishlist(Request $request){
        $wishlist = Wishlist::where("user_id",Auth::user()->id)->where("product_id" , $request->input("product_id"));
        $wishlist->delete();
        return redirect()->route("user.wishlist");
    }


    public function GetCart (){
        $order = Order::where("user_id", Auth::id())->where("status" , "pending")->latest()->first(); 
        
        if(!$order || $order->items->count() == 0){
            return redirect()->route("products.index")->with("NoProduct","No product in the cart");
        }
        
        $total = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        return view("user.cart" , compact("order" , "total"));
    }




    public function GetCheckout ($id){

        $order = Order::where("id", $id)->where("user_id", Auth::id())->first();
        if (!$order) {
            return redirect()->route("user.cart")->with("error", "Order not found");
        }
        $NumOfProduct = $order->items->count();
        return view("user.checkout" , compact("order" , "NumOfProduct"));
    } 
public function PostCheckout (Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);

    $order->update([
        "name" => $request->name,
        "address" => $request->address,
        "phone" => $request->phone,
        "email" => $request->email,
        "shipping_address" => $request->shipping_address, // Array → JSON
        "billing_address"  => $request->same_as_shipping 
                                ? $request->shipping_address 
                                : $request->billing_address,

        "payment_method"   => $request->payment_method,
        "notes"            => $request->notes,
        "status"           => "processing", // بعد التأكيد مثلاً
    ]);

    return redirect()->route("products.index")->with("success", "تم تأكيد الطلب!");
}

    public function GetOrders (){
            $orders = Order::where("user_id" , Auth::user()->id)->get();
        return view("user.orders" , compact("orders"));
    } 

    public function GetVariants (Request $request){
            $product = Product::where("id", $request->input("product_id") )->first();
            return view("user.choes_product_variant" , compact("product"));
        }

    public function PostCart (Request $request){
                                // هل فيه order pending؟
                    $order = Order::where('user_id', Auth::id())
                        ->where('status', 'pending')
                        ->first();
                    $product = Product::find($request->product_id);
                    if (!$order) {
                        $order = Order::create([
                            'user_id' => Auth::user()->id,
                            'status' => 'pending',
                            'expires_at' => now()->addDay(),
                            'total' => 0,
                            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                        ]);
                    }
                        $variant = ProductVariant::findOrFail($request->variant_id);
              
                    // أضف المنتج
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $request->product_id,
                        'variant_id' => $request->variant_id,
                        "vendor_id" => $product->vendor->id,
                        'quantity' => 1,
                        'price' => $variant->price_modifier + $product->price ,
                    ]);

            return redirect()->route("products.index")->with("done" , "Your Product added to cart");
        }

    public function GetOrderDetails ($id){
            $order = Order::where("id",$id)->where("user_id",Auth::user()->id)->first();
            $order_items = OrderItem::where("order_id" , $id)->get();
        return view("user.order-details" , compact("order","order_items"));
    } 

    public function GetProducts (){

        return view("public.products");
    } 
    public function DestroyItem(OrderItem $item){
        $item->delete();
        return redirect()->route("user.cart");
    }

    public function UpdateCart(Request $request, $orderId)
    {
        $order = Order::with("items")->findOrFail($orderId);
        $total = 0;    
        foreach ($request->items as $itemId => $data) {
            $orderItem = $order->items->where("id", $itemId)->first();
    
            if ($orderItem) {
                $orderItem->update([
                    "quantity" => $data["quantity"],
                    "price" => $data["price"],
                ]);
    
                $total += $data["price"] * $data["quantity"];
            }
        }
    
        $order->update([
            "grand_total" => $total,
            "expires_at" => null,
        ]);
    
        return redirect()->route("user.checkout", $order->id)->with("success", "Cart updated successfully!");
    }
    
}
