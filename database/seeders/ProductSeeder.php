<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        
  $vendors = Vendor::pluck('id')->toArray();

Product::factory(20)->make()->each(function ($product) use ($vendors) {
    $product->vendor_id = fake()->randomElement($vendors);
    $product->save();
});

    }
}

