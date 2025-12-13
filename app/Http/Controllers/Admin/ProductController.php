<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Services\ImageUploadService;
use App\Services\SearchFilterService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\VendorRepositoryInterface;


class ProductController extends Controller
{
    protected $imageUploadService;
    protected $searchFilterService;
    protected $productRepository;
    protected $vendorRepository;

    public function __construct(
        ImageUploadService $imageUploadService, 
        SearchFilterService $searchFilterService,
        ProductRepositoryInterface $productRepository,
        VendorRepositoryInterface $vendorRepository
    ) {
        $this->imageUploadService = $imageUploadService;
        $this->searchFilterService = $searchFilterService;
        $this->productRepository = $productRepository;
        $this->vendorRepository = $vendorRepository;
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Use repository for product filtering and pagination
        $products = $this->productRepository->getForAdmin($user, [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'vendor_id' => $request->get('vendor_id'),
            'is_active' => $request->get('is_active'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'sort_by' => $request->get('sort_by'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->productRepository->getAdminStatistics($user);

        // Get vendors for filter dropdown using repository
        $vendors = $this->vendorRepository->getWithUser()
            ->pluck('user.name')
            ->unique()
            ->filter()
            ->values();
 
        return view('admin.manage-products', compact(
            'products',
            'statistics',
            'vendors'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(CreateProductRequest $request)
    {
        // Use repository to create product
        $product = $this->productRepository->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'vendor_id' => $request->get('vendor_id'),
            'is_active' => $request->has('is_active'),
            'short_description' => $request->get('short_description'),
        ]);

        // Handle image uploads using service
        if ($request->hasFile('images')) {
            $this->imageUploadService->uploadMultipleImages($request->file('images'), $product, 'product');
        }

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    { 
        // Get vendors using repository
        $vendors1 = $this->vendorRepository->all();
        
        return view('public.products.edit', compact('product' ,"vendors1"));
    }

    public function update(CreateProductRequest $request, Product $product)
    {
        // Use repository to update product
        $this->productRepository->update($product->id, [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'category_id' => $request->get('category_id'),
            'vendor_id' => $request->get('vendor_id'),
            'is_active' => $request->has('is_active'),
            'sku' => $request->get('sku'),
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
        ]);

        // Handle image uploads using service
        if ($request->hasFile('images')) {
            $this->imageUploadService->uploadMultipleImages($request->file('images'), $product, 'product');
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

        // Use repository to delete product
        $this->productRepository->delete($product->id);
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
