<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'vendor')->orWhere('role', 'user')->get();

        foreach ($users as $user) {
            Subscription::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
