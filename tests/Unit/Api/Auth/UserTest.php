<?php

namespace Tests\Unit\Api\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_belong_to_role()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Role::class, $user->role);
    }

    public function test_user_has_many_companies()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class, $user->companies);
    }
}
