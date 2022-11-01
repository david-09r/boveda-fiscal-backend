<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Role;
use App\Models\User;
use App\Utils\Enum\EnumForCompany;
use App\Utils\Enum\EnumForRole;
use App\Utils\Enum\EnumForStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_list_companies_by_admin()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);

        Company::factory()->create([
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com'
        ]);

        $response = $this->getJson('api/company');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'nit',
                        'social_reason',
                        'site_direction',
                        'phone_number',
                        'email',
                        'website'
                    ]
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'nit' => 123456,
                'social_reason' => 'Empresa 123456',
                'site_direction' => 'Carrera 9A',
                'phone_number' => '+56 23123',
                'email' => 'empresa123456@email.com',
                'website' => 'www.empresa123456.com'
            ])
            ->assertJsonFragment([
                'nit' => 98765,
                'social_reason' => 'Empresa 98765',
                'site_direction' => 'Carrera 1A',
                'phone_number' => '+57 63123',
                'email' => 'empresa63123@email.com',
                'website' => 'www.empresa98765.com'
            ])
            ->assertJsonFragment([
                'meta' => [
                    'status' => EnumForStatus::OK,
                    'msg' => EnumForStatus::MESSAGE_200
                ]
            ]);
    }

    public function test_company_list_companies_by_representative()
    {
        $this->withExceptionHandling();
        Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com',
            'user_id' => $user->id
        ]);

        Company::factory()->create([
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com',
            'user_id' => $user->id
        ]);

        $response = $this->getJson('api/company');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'nit',
                        'social_reason',
                        'site_direction',
                        'phone_number',
                        'email',
                        'website'
                    ]
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'nit' => 123456,
                'social_reason' => 'Empresa 123456',
                'site_direction' => 'Carrera 9A',
                'phone_number' => '+56 23123',
                'email' => 'empresa123456@email.com',
                'website' => 'www.empresa123456.com'
            ])
            ->assertJsonFragment([
                'nit' => 98765,
                'social_reason' => 'Empresa 98765',
                'site_direction' => 'Carrera 1A',
                'phone_number' => '+57 63123',
                'email' => 'empresa63123@email.com',
                'website' => 'www.empresa98765.com'
            ])
            ->assertJsonFragment([
                'meta' => [
                    'status' => EnumForStatus::OK,
                    'msg' => EnumForStatus::MESSAGE_200
                ]
            ]);
    }

    public function test_company_store_company_by_admin()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $user_representative = User::factory()->create();

        $response = $this->postJson('api/company', [
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com',
            'user_id' => $user_representative->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'nit',
                    'social_reason',
                    'site_direction',
                    'phone_number',
                    'email',
                    'website'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'nit' => 98765,
                'social_reason' => 'Empresa 98765',
                'site_direction' => 'Carrera 1A',
                'phone_number' => '+57 63123',
                'email' => 'empresa63123@email.com',
                'website' => 'www.empresa98765.com'
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::CREATED,
                'msg' => EnumForStatus::MESSAGE_201
            ]);

        $this->assertDatabaseHas('companies', [
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com'
        ]);
    }

    public function test_company_store_company_by_representative()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('api/company', [
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com',
            'user_id' => $user->id
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
                'message' => EnumForCompany::NOT_PERMISSIONS
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

        $this->assertDatabaseMissing('companies', [
            'nit' => 98765,
            'social_reason' => 'Empresa 98765',
            'site_direction' => 'Carrera 1A',
            'code_number' => 57,
            'phone_number' => 63123,
            'email' => 'empresa63123@email.com',
            'website' => 'www.empresa98765.com'
        ]);
    }

    public function test_company_find_company_by_admin()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com',
            'user_id' => $user->id
        ]);

        Company::factory()->create([
            'nit' => 312321,
            'social_reason' => 'Empresa 52414321',
            'site_direction' => 'Carrera 10A',
            'code_number' => 52,
            'phone_number' => 312412,
            'email' => 'empresas123456@email.com',
            'website' => 'www.empresas123456.com'
        ]);

        $response = $this->getJson("api/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'nit',
                    'social_reason',
                    'site_direction',
                    'phone_number',
                    'email',
                    'website'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])->assertJsonFragment([
                'nit' => 123456,
                'social_reason' => 'Empresa 123456',
                'site_direction' => 'Carrera 9A',
                'phone_number' => '+56 23123',
                'email' => 'empresa123456@email.com',
                'website' => 'www.empresa123456.com'
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);
    }

    public function test_company_find_company_by_representative()
    {
        $this->withExceptionHandling();
        Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com',
            'user_id' => $user->id
        ]);

        Company::factory()->create([
            'nit' => 312321,
            'social_reason' => 'Empresa 52414321',
            'site_direction' => 'Carrera 10A',
            'code_number' => 52,
            'phone_number' => 312412,
            'email' => 'empresas123456@email.com',
            'website' => 'www.empresas123456.com'
        ]);

        $response = $this->getJson("api/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'nit',
                    'social_reason',
                    'site_direction',
                    'phone_number',
                    'email',
                    'website'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])->assertJsonFragment([
                'nit' => 123456,
                'social_reason' => 'Empresa 123456',
                'site_direction' => 'Carrera 9A',
                'phone_number' => '+56 23123',
                'email' => 'empresa123456@email.com',
                'website' => 'www.empresa123456.com'
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);
    }

    public function test_company_update_company_by_admin()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);

        $response = $this->putJson("api/company/{$company->id}", [
            'nit' => 28765,
            'social_reason' => 'Empresa 28765',
            'site_direction' => 'Carrera 100A',
            'code_number' => 91,
            'phone_number' => 23123,
            'email' => 'empresa@email.com',
            'website' => 'www.empresa.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'nit',
                    'social_reason',
                    'site_direction',
                    'phone_number',
                    'email',
                    'website'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'nit' => 28765,
                'social_reason' => 'Empresa 28765',
                'site_direction' => 'Carrera 100A',
                'phone_number' => '+91 23123',
                'email' => 'empresa@email.com',
                'website' => 'www.empresa.com'
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

        $this->assertDatabaseHas('companies', [
            'nit' => 28765,
            'social_reason' => 'Empresa 28765',
            'site_direction' => 'Carrera 100A',
            'code_number' => 91,
            'phone_number' => 23123,
            'email' => 'empresa@email.com',
            'website' => 'www.empresa.com'
        ])->assertDatabaseMissing('companies', [
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);
    }

    public function test_company_update_company_by_representative()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com',
            'user_id' => $user->id
        ]);

        $response = $this->putJson("api/company/{$company->id}", [
            'nit' => 28765,
            'social_reason' => 'Empresa 28765',
            'site_direction' => 'Carrera 100A',
            'code_number' => 91,
            'phone_number' => 23123,
            'email' => 'empresa@email.com',
            'website' => 'www.empresa.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'nit',
                    'social_reason',
                    'site_direction',
                    'phone_number',
                    'email',
                    'website'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'nit' => 28765,
                'social_reason' => 'Empresa 28765',
                'site_direction' => 'Carrera 100A',
                'phone_number' => '+91 23123',
                'email' => 'empresa@email.com',
                'website' => 'www.empresa.com'
            ])
            ->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

        $this->assertDatabaseHas('companies', [
            'nit' => 28765,
            'social_reason' => 'Empresa 28765',
            'site_direction' => 'Carrera 100A',
            'code_number' => 91,
            'phone_number' => 23123,
            'email' => 'empresa@email.com',
            'website' => 'www.empresa.com'
        ])->assertDatabaseMissing('companies', [
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);
    }

    public function test_company_delete_company_by_admin()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);

        Company::factory()->create();
        Company::factory()->create();
        Company::factory()->create();

        $response = $this->deleteJson("api/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'status',
                    'msg'
                ]
            ])->assertJsonFragment([
                'message' => EnumForCompany::COMPANY_DELETED
            ])->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id
        ]);
    }

    public function test_company_delete_company_by_representative()
    {
        $this->withExceptionHandling();
        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create([
            'nit' => 123456,
            'social_reason' => 'Empresa 123456',
            'site_direction' => 'Carrera 9A',
            'code_number' => 56,
            'phone_number' => 23123,
            'email' => 'empresa123456@email.com',
            'website' => 'www.empresa123456.com'
        ]);

        Company::factory()->create();
        Company::factory()->create();
        Company::factory()->create();

        $response = $this->deleteJson("api/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'status',
                    'msg'
                ]
            ])->assertJsonFragment([
                'message' => EnumForCompany::NOT_PERMISSIONS
            ])->assertJsonFragment([
                'status' => EnumForStatus::OK,
                'msg' => EnumForStatus::MESSAGE_200
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id
        ]);
    }

}
