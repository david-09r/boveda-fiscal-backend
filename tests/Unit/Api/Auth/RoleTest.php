<?php

namespace Tests\Unit\Api\Auth;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_has_many_users()
    {
        $role = Role::factory()->create();

        $this->assertInstanceOf(Collection::class, $role->users);
    }
}
