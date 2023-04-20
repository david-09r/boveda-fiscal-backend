<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use App\Utils\Enum\EnumForInvoice;
use App\Utils\Enum\EnumForRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_list_pagination_by_representative()
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
            'user_id' => $user->id
        ]);

        Invoice::factory()->count(20)->create([
            'company_id' => $company->id
        ]);

        $response = $this->getJson("api/company/{$company->id}/invoices");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'details',
                            'total_iva_collected',
                            'total_amount_payable',
                            'date_issuance',
                            'date_payment',
                            'type',
                            'state'
                        ]
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ]);
    }

    public function test_invoice_list_by_admin()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        Invoice::factory()->create()->count(3);

        $response = $this->getJson('api/company/all/invoices');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'details',
                            'total_iva_collected',
                            'total_amount_payable',
                            'date_issuance',
                            'date_payment',
                            'type',
                            'state'
                        ]
                    ]
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ]);
    }

    public function test_invoice_list_top_by_admin()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        Invoice::factory()->count(10)->create();

        $response = $this->getJson('api/company/all/invoices?sort=total_amount_payable');

        $invoices = $response->json('data')['data'];

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'details',
                            'total_iva_collected',
                            'total_amount_payable',
                            'date_issuance',
                            'date_payment',
                            'type',
                            'state'
                        ]
                    ]
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ]);

        $sortedInvoices = collect($invoices)->sortByDesc('total_amount_payable')->values()->all();
        $this->assertEquals($sortedInvoices, $invoices);
    }

    public function test_invoice_company_incorrect_company_endpoint()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('api/company/0prueba0/invoices');

        $response->assertStatus(404);
    }

    public function test_invoice_store_by_representative()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        $company = Company::factory()->create([
            'user_id' => $user->id
        ]);

        Passport::actingAs($user);

        $response = $this->postJson("api/company/$company->id/invoice", [
            'details' => [
                [
                    'name' => 'Nombre ejemplo',
                    'quantity' => 201,
                    'unit_value' => 1000.2
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                    'quantity' => 10,
                    'unit_value' => 312.2
                ]
            ],
            'total_iva_collected' => 1234.0,
            'total_amount_payable' => 12345.0,
            'date_issuance' => '2022-10-20T20:02:02',
            'date_payment' => '2022-10-20T20:02:02',
            'type' => 'Nota débito',
            'state' => 'Cancelado'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'details',
                    'total_iva_collected',
                    'total_amount_payable',
                    'date_issuance',
                    'date_payment',
                    'type',
                    'state'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'data' => [
                    'details' => [
                        [
                            'name' => 'Nombre ejemplo',
                            'quantity' => 201,
                            'unit_value' => 1000.2,
                            'total' => 201040.2
                        ],
                        [
                            'name' => 'Nombre ejemplo 2',
                            'quantity' => 10,
                            'unit_value' => 312.2,
                            'total' => 3122
                        ]
                    ],
                    'total_iva_collected' => 1234.0,
                    'total_amount_payable' => 12345.0,
                    'date_issuance' => '2022-10-20T20:02:02',
                    'date_payment' => '2022-10-20T20:02:02',
                    'type' => 'Nota débito',
                    'state' => 'Cancelado',
                ]
            ]);

        $this->assertDatabaseHas('invoices', [
            'details' => json_encode([
                [
                    'name' => 'Nombre ejemplo',
                    'quantity' => 201,
                    'unit_value' => 1000.2,
                    'total' => 201040.2
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                    'quantity' => 10,
                    'unit_value' => 312.2,
                    'total' => 3122
                ]
            ]),
            'total_iva_collected' => 1234.0,
            'total_amount_payable' => 12345.0,
            'date_issuance' => '2022-10-20T20:02:02',
            'date_payment' => '2022-10-20T20:02:02',
            'type' => 'Nota débito',
            'state' => 'Cancelado',
            'company_id' => $company->id
        ]);
    }

    public function test_invoice_store_by_admin()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create();

        $response = $this->postJson("api/company/$company->id/invoice", [
            'details' => [
                [
                    'name' => 'Nombre ejemplo',
                    'quantity' => 201,
                    'unit_value' => 1000.2
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                    'quantity' => 10,
                    'unit_value' => 312.2
                ]
            ],
            'total_iva_collected' => 1234.0,
            'total_amount_payable' => 12345.0,
            'date_issuance' => '2022-10-20T20:02:02',
            'date_payment' => '2022-10-20T20:02:02',
            'type' => 'Nota débito',
            'state' => 'Cancelado'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'details',
                    'total_iva_collected',
                    'total_amount_payable',
                    'date_issuance',
                    'date_payment',
                    'type',
                    'state'
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'data' => [
                    'details' => [
                        [
                            'name' => 'Nombre ejemplo',
                            'quantity' => 201,
                            'unit_value' => 1000.2,
                            'total' => 201040.2
                        ],
                        [
                            'name' => 'Nombre ejemplo 2',
                            'quantity' => 10,
                            'unit_value' => 312.2,
                            'total' => 3122
                        ]
                    ],
                    'total_iva_collected' => 1234.0,
                    'total_amount_payable' => 12345.0,
                    'date_issuance' => '2022-10-20T20:02:02',
                    'date_payment' => '2022-10-20T20:02:02',
                    'type' => 'Nota débito',
                    'state' => 'Cancelado',
                ]
            ]);

        $this->assertDatabaseHas('invoices', [
            'details' => json_encode([
                [
                    'name' => 'Nombre ejemplo',
                    'quantity' => 201,
                    'unit_value' => 1000.2,
                    'total' => 201040.2
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                    'quantity' => 10,
                    'unit_value' => 312.2,
                    'total' => 3122
                ]
            ]),
            'total_iva_collected' => 1234.0,
            'total_amount_payable' => 12345.0,
            'date_issuance' => '2022-10-20T20:02:02',
            'date_payment' => '2022-10-20T20:02:02',
            'type' => 'Nota débito',
            'state' => 'Cancelado',
            'company_id' => $company->id
        ]);
    }

    public function test_invoice_delete_by_admin()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create();

        $invoice = Invoice::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->deleteJson("api/company/$company->id/invoice/$invoice->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'status',
                    'msg'
                ]
            ])->assertJsonFragment([
                'message' => EnumForInvoice::DELETED_INVOICE
            ]);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id
        ]);
    }

    public function test_invoice_delete_by_representative()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE2
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        Passport::actingAs($user);

        $company = Company::factory()->create();

        $invoice = Invoice::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->deleteJson("api/company/$company->id/invoice/$invoice->id");

        $response->assertStatus(401);
    }
}
