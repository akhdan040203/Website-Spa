<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'admin@unguspa.com', 'password' => 'secret-password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
