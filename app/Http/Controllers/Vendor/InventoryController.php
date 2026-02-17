<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $vendorId = Auth::user()->vendor->id;

        $movements = InventoryMovement::whereHas('product', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            ->with(['product', 'variant'])
            ->latest()
            ->paginate(15);

        return view('vendor.inventory.index', compact('movements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out,adjustment,return,damage',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Ensure vendor owns the product
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403);
        }

        InventoryMovement::create([
            'product_id' => $validated['product_id'],
            'variant_id' => $validated['variant_id'],
            'quantity' => $validated['quantity'],
            'type' => $validated['type'],
            'notes' => $validated['notes'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('vendor.inventory.index')->with('success', 'Stock updated successfully');
    }
}
