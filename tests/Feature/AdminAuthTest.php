<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_away_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_customer_session_does_not_grant_access_to_admin_area(): void
    {
        $customer = \App\Models\User::factory()->create();

        $response = $this->actingAs($customer, 'web')->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_with_correct_credentials(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_fails_with_wrong_password(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    public function test_only_super_admin_can_manage_admin_users(): void
    {
        $manager = Admin::factory()->create(['role' => 'Gestor']);

        $response = $this->actingAs($manager, 'admin')->get(route('admin.users.index'));

        $response->assertForbidden();
    }
}
