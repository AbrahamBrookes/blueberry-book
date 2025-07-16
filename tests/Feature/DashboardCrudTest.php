<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Dashboard')
        );
    }

    public function test_guest_cannot_view_dashboard()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_root_path_redirects_authenticated_user_to_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_root_path_redirects_guest_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }
}
