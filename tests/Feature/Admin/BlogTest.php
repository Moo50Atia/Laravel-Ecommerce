<?php

namespace Tests\Feature\Admin;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_blog_with_featured_image()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('blog.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blogs.store'), [
                'title' => 'Admin Blog Post',
                'description' => 'Detailed description of the blog post',
                'category' => 'Technology',
                'is_published' => true,
                'featured_image' => $file
            ]);

        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', ['title' => 'Admin Blog Post']);

        $blog = Blog::where('title', 'Admin Blog Post')->first();
        $this->assertNotNull($blog->image);
        Storage::disk('public')->assertExists($blog->image->url);
    }

    public function test_admin_can_update_blog()
    {
        $blog = Blog::factory()->create(['author_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.blogs.update', $blog->id), [
                'title' => 'Updated Blog Title',
                'description' => 'Updated content',
                'category' => 'Lifestyle'
            ]);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Blog Title'
        ]);
    }

    public function test_admin_can_delete_blog()
    {
        $blog = Blog::factory()->create(['author_id' => $this->admin->id]);

        $this->actingAs($this->admin)->delete(route('admin.blogs.destroy', $blog->id));

        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }
}
