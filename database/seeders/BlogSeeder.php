<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    { 
        $users = User::pluck('id')->toArray();
        for ($i = 0; $i < 10; $i++) {
            Blog::factory()->create([
                'author_id' => fake()->randomElement($users),
            ]);
}
    }
}

