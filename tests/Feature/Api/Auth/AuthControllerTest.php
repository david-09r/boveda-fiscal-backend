<?php

namespace Tests\Feature\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Utils\Enum\EnumForRole;
use App\Utils\Enum\EnumForStatus;
use App\Utils\Enum\EnumForUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_register()
    {
        $this->withExceptionHandling();
        Role::factory()->create([
           'name' => EnumForRole::ROLE1
        ]);

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $response = $this->postJson('api/auth/register', [
            'name' => 'Felipe Gomez',
            'type_identification' => EnumForUser::DOCUMENT1,
            'identification' => 123123123,
            'code_number' => 601,
            'phone_number' => 3213213213,
            'email' => 'felipe@email.com',
            'password' => 'hola.1236',
            'password_confirmation' => 'hola.1236',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'message' => 'Registered User!',
                'status' => 200,
                'msg' => 'OK'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Felipe Gomez',
            'type_identification' => 'Cedula de ciudadania',
            'identification' => 123123123,
            'code_number' => 601,
            'phone_number' => 3213213213,
            'email' => 'felipe@email.com',
            'role_id' => $role->id
        ]);
    }

    public function test_auth_login()
    {
        $this->withExceptionHandling();
        Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $user = User::factory()->create([
            'email' => 'david@email.com1',
            'password' => Hash::make('hola.1236'),
            'role_id' => $role->id
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'hola.1236'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'access_token'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'name' => $user['name'] .'Logging!',
                'access_token' => $response['data']['access_token'],
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

    }
}
