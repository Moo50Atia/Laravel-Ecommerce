<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_profile_edit_page_loads()
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    public function test_user_can_update_personal_info()
    {
        $response = $this->actingAs($this->user)
            ->patch(route('profile.update'), [ // Note: route names might differ based on Breeze/Fortify
                'name' => 'New Name',
                'email' => 'newemail@example.com'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com'
        ]);
    }

    public function test_email_change_marks_unverified()
    {
        $this->user->email_verified_at = now();
        $this->user->save();

        $response = $this->actingAs($this->user)
            ->patch(route('profile.update'), [
                'name' => 'New Name',
                'email' => 'different@example.com'
            ]);

        $this->assertNull($this->user->fresh()->email_verified_at);
    }

    public function test_user_can_update_address()
    {
        $response = $this->actingAs($this->user)
            ->post(route('profile.address.update'), [
                'address_line1' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001'
            ]);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $this->user->id,
            'city' => 'New York'
        ]);
    }

    public function test_user_can_update_vendor_info()
    {
        $response = $this->actingAs($this->user)
            ->post(route('profile.vendor.update'), [
                'store_name' => 'My New Store',
                'description' => 'A great store',
                'commission_rate' => 15.0
            ]);

        $this->assertDatabaseHas('vendors', [
            'user_id' => $this->user->id,
            'store_name' => 'My New Store'
        ]);
    }

    public function test_user_can_delete_account_with_correct_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password')
        ]);

        $response = $this->actingAs($user)
            ->delete(route('profile.destroy'), [
                'password' => 'correct-password'
            ]);

        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_account_with_wrong_password()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('profile.destroy'), [
                'password' => 'wrong-password'
            ]);

        $response->assertSessionHasErrorsIn('userDeletion', 'password');
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }
}
