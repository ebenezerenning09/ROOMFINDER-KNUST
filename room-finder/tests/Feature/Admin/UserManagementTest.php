<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_remove_own_admin_access(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $admin), [
                'is_admin' => '0',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->is_admin);
    }

    public function test_admin_can_grant_admin_access_to_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'is_admin' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertTrue($user->fresh()->is_admin);
    }
}
