<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Vendor $vendor;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);

        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $this->vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $this->category = Category::factory()->create();
    }

    public function test_admin_can_create_product_with_image()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'New Product',
                'description' => 'Description goes here',
                'price' => 199.99,
                'category_id' => $this->category->id,
                'vendor_id' => $this->vendor->id,
                'is_active' => true,
                'image' => $file
            ]);

        $response->assertRedirect(); // Likely redirects to variant creation
        $this->assertDatabaseHas('products', ['name' => 'New Product', 'vendor_id' => $this->vendor->id]);

        $product = Product::where('name', 'New Product')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image->url);
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product->id), [
                'name' => 'Updated Name',
                'price' => 250.00,
                'description' => 'New description'
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'price' => 250.00
        ]);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);

        $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product->id));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
