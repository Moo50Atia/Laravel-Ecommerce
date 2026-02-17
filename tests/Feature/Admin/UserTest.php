<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_list_users()
    {
        $user = User::factory()->create(['name' => 'Regular User']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Regular User');
    }

    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'role' => 'user',
                'status' => 'active'
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_admin_can_update_user_without_password()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'password' => Hash::make('old_password')
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user->id), [
                'name' => 'New Name',
                'email' => $user->email,
                'role' => 'user',
                'status' => 'active',
                'password' => '' // Empty password
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
        $user->refresh();
        $this->assertTrue(Hash::check('old_password', $user->password));
    }

    public function test_check_email_json_endpoint()
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.checkEmail', ['email' => 'taken@example.com']));

        $response->assertJson(['exists' => true]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.checkEmail', ['email' => 'free@example.com']));
        $response->assertJson(['exists' => false]);
    }
}
