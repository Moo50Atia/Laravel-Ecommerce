<?php

namespace App\Http\Controllers\all_pages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductVariantRequest;
use App\Models\Product; 
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    // create 
    public function create (Request $request) {
     $product = Product::findOrFail($request->query('product'));
    $category = $request->query('category');

return view("public.products.creat_product_variant" , compact("product" , "category"));
}

    // store
    public function store (ProductVariantRequest $request) {  
    
    $validated = $request->validated();

    $variants = json_decode($validated['variants_json'], true);

    if (empty($variants)) {
        return back()->withErrors(['variants_json' => 'لا توجد بيانات Variants مرسلة']);
    }

    foreach ($variants as $variant) {
        ProductVariant::create([
            'product_id' => $validated['product_id'],
            'option_name' => $variant['option_name'],
            'option_value' => $variant['option_value'],
            'price_modifier' => $variant['price_modifier'] ?? 0,
            'stock' => $variant['stock'],
        ]);
    }

    return redirect()->route('products.show', $validated['product_id'])->with('success', 'تم إضافة الخصائص بنجاح');
}
    // edit
    public function edit (Request $request) {
            $variantIds = $request->query('variants', []);
            $variants = collect(); // Collection فاضية افتراضيًا
            if (!empty($variantIds)) {
                $variants = ProductVariant::whereIn('id', $variantIds)->get();
            }
            // dd($variants);
            $product = Product::findOrFail($request->query('product'));
        $category = $request->query('category');
        $variant = $product->variants->first();

        if ($variants != "null"){
        return view("public.products.edit_product_variant" , compact("product" , "category","variants"));
        }
        else {
            return redirect(route("products.show"));
        }
        }
    // update 
    public function update (ProductVariantRequest $request, $id) {
            $data = $request->input('variants', []);

            if (empty($data)) {
                return redirect()->back()->withErrors(['variants' => 'لا توجد بيانات للتحديث']);
            }

            foreach ($data as $variantData) {
                if (!empty($variantData['id'])) {
                    ProductVariant::where('id', $variantData['id'])->update([
                        'option_name'   => $variantData['option_name'] ?? null,
                        'option_value'  => $variantData['option_value'] ?? null,
                        'price_modifier'=> $variantData['price_modifier'] ?? 0,
                        'stock'         => $variantData['stock'] ?? 0,
                    ]);
                }
            }

            return redirect(route("products.show" , $id))->with('success', 'تم تحديث الاختيار بنجاح');
        }
}
