<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSuspensionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Spatie\Permission\Models\Role::create(['name' => 'user']);
    }

    public function test_active_user_can_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'suspended_until' => null,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_suspended_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'status' => 'suspended',
            'suspended_until' => now()->addDay(),
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect(route('filament.admin.auth.login'))
            ->assertSessionHasErrors(['email']);
    }

    public function test_inactive_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'status' => 'inactive',
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect(route('filament.admin.auth.login'))
            ->assertSessionHasErrors(['email']);
    }

    public function test_user_with_expired_suspension_can_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'status' => 'suspended',
            'suspended_until' => now()->subDay(),
        ]);

        // Note: The middleware logic checks isSuspended() which returns false if date is in past.
        // However, status is still 'suspended'. The middleware checks:
        // if ($user->status !== 'active' || $user->isSuspended())
        // So if status is 'suspended', they are blocked regardless of date?
        // Let's check my logic.
        // Logic: if ($user->status !== 'active' || $user->isSuspended())
        // So if status is 'suspended', they are blocked.
        // This means the 'Activate' action or a scheduled job is needed to reset status to 'active'.
        // Or I should change logic to allow login if suspension expired, even if status says suspended?
        // Usually, if suspended_until is passed, they should be allowed.
        // But if status is explicitly 'suspended', maybe we want to keep them suspended?
        // The requirement was "suspend for specific time".
        // If I suspend for 1 hour, after 1 hour I expect to login.
        // So I should probably update the middleware to handle this case:
        // If suspended_until is past, we might want to auto-activate or ignore status 'suspended'.
        
        // Let's adjust the test expectation based on current implementation:
        // Current implementation: blocked because status is 'suspended'.
        
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
            
        $this->assertEquals('active', $user->fresh()->status);
    }

    public function test_new_user_gets_default_role(): void
    {
        $user = User::factory()->create();
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_user_resource_page_loads(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(UserResource::getUrl())
            ->assertStatus(200);
    }
}
