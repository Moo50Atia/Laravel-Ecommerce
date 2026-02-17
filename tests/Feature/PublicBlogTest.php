<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase;

    protected Blog $blog;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->blog = Blog::factory()->create([
            'author_id' => $admin->id,
            'is_published' => true,
            'published_at' => now()
        ]);
    }

    public function test_public_can_view_blog_list()
    {
        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertSee($this->blog->title);
    }

    public function test_public_can_view_single_blog()
    {
        $response = $this->get(route('blogs.show', $this->blog->id));

        $response->assertStatus(200);
        $response->assertSee($this->blog->title);
    }

    public function test_authenticated_user_can_rate_blog()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('blogs.rate', $this->blog->id), [
                'rating' => 4,
                'comment' => 'Interesting read'
            ]);

        $this->assertDatabaseHas('blog_reviews', [
            'blog_id' => $this->blog->id,
            'user_id' => $user->id,
            'rating' => 4
        ]);
    }
}
