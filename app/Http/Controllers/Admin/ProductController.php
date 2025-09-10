<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Vendor;
USE Illuminate\Support\Facades\Auth;
use App\Models\Product;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $user = Auth::user();
        $query = Product::with(['vendor', 'category', 'variants']);
        $query = $query->ForAdmin(  $user);



        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $category = $request->get('category');
            $query->whereHas('category', function($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Vendor filter
        if ($request->filled('vendor')) {
            $vendor = $request->get('vendor');
            $query->whereHas('vendor', function($q) use ($vendor) {
                $q->where('name', 'like', "%{$vendor}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        // Get statistics for all products (not just current page)
        
        $allProducts = Product::ForAdmin($user)->get();
        $totalProducts = $allProducts->count();
        $activeProducts = $allProducts->where('is_active', true)->count();
        $inactiveProducts = $allProducts->where('is_active', false)->count();
        $totalVariants = $allProducts->sum(function($product) {
            return $product->variants->count();
        });

        // Get unique values for filter dropdowns
        // $categories = Product::with('category')
        //     ->whereHas('category')
        //     ->get()
        //     ->pluck('category.name')
        //     ->unique()
        //     ->filter()
        //     ->values();

        $vendors = Product::with('vendor')
            ->whereHas('vendor')
            ->get()
            ->pluck('vendor.name')
            ->unique()
            ->filter()
            ->values();
 
        return view('admin.manage-products', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'totalVariants',
           
            'vendors'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_active' => 'boolean',
            'short_description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'vendor_id' => $request->get('vendor_id'),
            'is_active' => $request->has('is_active'),
            'short_description' => $request->get('short_description'),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product-images', 'public');
                
                \App\Models\Image::create([
                    'url' => $imagePath,
                    'type' => 'product',
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    { 
        $vendors1 = Vendor::all();
        
        return view('public.products.edit', compact('product' ,"vendors1"));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_active' => 'boolean',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'category_id' => $request->get('category_id'),
            'vendor_id' => $request->get('vendor_id'),
            'is_active' => $request->has('is_active'),
            'sku' => $request->get('sku'),
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product-images', 'public');
                
                \App\Models\Image::create([
                    'url' => $imagePath,
                    'type' => 'product',
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id
                ]);
            }
        }

        return redirect()->route('admin.products.show', $product)->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // Delete associated images
        if ($product->images) {
            foreach ($product->images as $image) {
                // Delete file from storage
                if (Storage::disk('public')->exists($image->url)) {
                    Storage::disk('public')->delete($image->url);
                }
                $image->delete();
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
