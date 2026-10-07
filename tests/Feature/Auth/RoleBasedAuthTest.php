<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RoleBasedAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_redirects_to_manager_dashboard_after_login(): void
    {
        $manager = User::factory()->create([
            'email' => 'manager@wms.test',
            'role' => 'manager',
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', 'manager@wms.test')
            ->set('form.password', 'password');

        $component->call('login');

        $component->assertRedirect(route('manager.dashboard', absolute: false));
        $this->assertAuthenticatedAs($manager);
    }

    public function test_employee_redirects_to_dashboard_after_login(): void
    {
        $employee = User::factory()->create([
            'email' => 'medewerker@wms.test',
            'role' => 'employee',
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', 'medewerker@wms.test')
            ->set('form.password', 'password');

        $component->call('login');

        $component->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($employee);
    }

    public function test_employee_cannot_access_manager_dashboard(): void
    {
        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $response = $this->actingAs($employee)->get(route('manager.dashboard'));

        $response->assertStatus(403);
    }

    public function test_manager_can_access_manager_dashboard(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
        ]);

        $response = $this->actingAs($manager)->get(route('manager.dashboard'));

        $response->assertStatus(200);
    }
}
