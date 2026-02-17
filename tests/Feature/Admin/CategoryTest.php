<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_list_categories()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Electronics');
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Fashion',
                'description' => 'Latest trends'
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Fashion']);
    }

    public function test_category_requires_unique_name()
    {
        Category::factory()->create(['name' => 'Books']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Books'
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category->id), [
                'name' => 'New Name'
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
