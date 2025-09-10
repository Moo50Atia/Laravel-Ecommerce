<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorUsers = User::factory(5)->create(['role' => 'vendor']);

        foreach ($vendorUsers as $user) {
            Vendor::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
