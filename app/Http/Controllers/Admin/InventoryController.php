<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $movements = InventoryMovement::with(['product', 'variant'])
            ->latest()
            ->paginate(15);

        return view('admin.inventory.index', compact('movements'));
    }

    public function show(Product $product)
    {
        $movements = $product->inventoryMovements()->with('variant')->latest()->get();
        return view('admin.inventory.show', compact('product', 'movements'));
    }
}
