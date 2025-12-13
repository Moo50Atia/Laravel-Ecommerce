<?php

namespace App\Http\Controllers\all_pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductReviewRequest;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use App\Services\ImageUploadService;
use App\Services\ReviewManagementService;
use Illuminate\Http\RedirectResponse;



class ProductController extends Controller
{
    protected $imageUploadService;
    protected $reviewManagementService;

    public function __construct(ImageUploadService $imageUploadService, ReviewManagementService $reviewManagementService)
    {
        $this->imageUploadService = $imageUploadService;
        $this->reviewManagementService = $reviewManagementService;
    }
    public function index(): \Illuminate\Contracts\View\View
    {
        $products = Product::latest()->paginate(10);
        $special_products = Product::withAvg("productReviews" , "rating")->orderByDesc("productReviews_avg_rating")->get() ;
        return view('public.products.index', compact('products' , "special_products"));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('public.products.create');
    }

    public function store(ProductRequest $request): RedirectResponse
    { 
        // dd(Auth::user()->vendor->id);
            $data = $request->validated();
            unset($data['image']);
            unset($data['additional_images']);
            unset($data['quantity']);

            // Ensure vendor_id is always set correctly
            if (Auth::check() && Auth::user()->role === 'admin') {
                // Admin must explicitly choose a vendor
                if (!$request->filled('vendor_id')) {
                    return back()
                        ->withErrors(['vendor_id' => 'يجب اختيار البائع لهذا المنتج'])
                        ->withInput();
                }
                $data['vendor_id'] = (int) $request->input('vendor_id');
            } else {
                // Vendor user: assign their own vendor id
                $vendorId = optional(Auth::user()->vendor)->id;
                if (!$vendorId) {
                    return back()
                        ->withErrors(['vendor' => 'حسابك غير مرتبط ببائع. من فضلك أكمل بيانات البائع أولاً.'])
                        ->withInput();
                }
                $data['vendor_id'] = $vendorId;
            }

        $product = Product::create($data);
        
        // Handle main image upload
        if ($request->hasFile('image')) {
            $this->imageUploadService->uploadSingleImage($request->file('image'), $product, 'card');
        }
        
        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            $this->imageUploadService->uploadMultipleImages($request->file('additional_images'), $product, 'detail');
        }

        return redirect()->route("vendor.variant.create" , parameters: ['product' => $product,'category' => $request->input("category")])->with('success', 'Created successfully');
    }

    public function show(Product $product): \Illuminate\Contracts\View\View
    { 
        $similarProducts = Product::where('category', $product->category)
        ->where('id', '!=', $product->id) // عشان ميجبش نفس المنتج
        ->take(4) // عدد المنتجات اللي هتعرضها
        ->get();
        $totalStock = $product->variants()->sum('stock');
        $product = Product::with('vendor.user', 'variants', 'productReviews.user')->findOrFail($product->id);


        return view('public.products.show', compact('product' , "totalStock" , "similarProducts"));
    }

    public function edit(Product $product): \Illuminate\Contracts\View\View
    {
        return view('public.products.edit', compact('product'));
    }

public function addToWishlist(Request $request, Product $product): \Illuminate\Http\RedirectResponse
{
    // Check if product already exists in user's wishlist
    $existingWishlist = Wishlist::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();
    
    // If product is not in wishlist, add it
    if (!$existingWishlist) {
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);
        return back()->with('success', 'تمت إضافة المنتج إلى المفضلة');
    }
    
    // If product is already in wishlist, return with message
    return back()->with('info', 'المنتج موجود بالفعل في المفضلة');
}

public function update(ProductRequest $request, Product $product): \Illuminate\Http\RedirectResponse
{
    // 1. التحقق من البيانات
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'short_description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ]);

    // 2. تحديث الصورة إذا تم رفع واحدة جديدة
    if ($request->hasFile('image')) {
        $this->imageUploadService->updateOrCreateImage($request->file('image'), $product, 'card');
    }
    if ($request->input('variant_ids') && $request->input('variant_ids') != null){
    // 3. الحصول على الـ variants المختارة
    $variantIds = $request->input('variant_ids', []);
    $variants = collect(); // Collection فاضية افتراضيًا

    if (!empty($variantIds)) {
        $variants = ProductVariant::whereIn('id', $variantIds)->get();
    }
    $product->update($data);
    
    // 5. تمرير البيانات للـ route مع استخدام IDs أو Models حسب الحاجة
    $category = $product->category;

    // 4. تحديث بيانات المنتج

    return redirect()
        ->route('vendor.variant.edit', [
            'product' => $product->id, // أو $product لو عامل Model Binding
            'category' => $category, // أو $category
            'variants' => $variantIds 
        ])
        ->with('success', 'Updated successfully');}
        else {
            $product->update($data);
    
            // 5. تمرير البيانات للـ route مع استخدام IDs أو Models حسب الحاجة
            $category = $product->category;
        
            // 4. تحديث بيانات المنتج
        
            return redirect()->route("products.show" , $product->id);
        }
}


    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        $product->delete();
        return redirect()->route("vendor.products")->with('success', 'Deleted successfully');
    } 


 
public function search(Request $request)
{ 

            $query = Product::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            $products = $query->paginate(10);

    return view("public.products.search_products", compact("products"));
}

public function storeReview(ProductReviewRequest $request, Product $product): \Illuminate\Http\RedirectResponse
{
    $result = $this->reviewManagementService->createProductReview($request, $product);
    
    if ($result['success']) {
        return back()->with('success', $result['message']);
    } else {
        return back()->with('error', $result['message']);
    }
}
}
