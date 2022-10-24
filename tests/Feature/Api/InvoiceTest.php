<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use App\Utils\Enum\EnumForRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_by_representative()
    {
        $this->withExceptionHandling();

        $role = Role::factory()->create([
            'name' => EnumForRole::ROLE1
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        $company = Company::factory()->create();

        Invoice::factory()->create([
            'details' => [
                'name' => 'name 1',
                'amount' => 12,
                'unit_value' => 2323,
                'total' => 12
            ],
            'total_iva_collected' => 2000.2,
            'total_amount_payable' => 100000.6,
            'date_issuance' => '2021-05-20 02:10:20',
            'date_payment' => '2022-10-10 10:50:20',
            'type' => 'Nota debito',
            'state' => 'Activa',
            'company_id' => $company->id
        ]);

        Invoice::factory()->create([
            'details' => [
                'name' => 'name 2',
                'amount' => 31,
                'unit_value' => 123,
                'total' => 31*123
            ],
            'total_iva_collected' => 3000.2,
            'total_amount_payable' => 400000.6,
            'date_issuance' => '2020-02-20 20:20:20',
            'date_payment' => '2020-04-10 10:20:20',
            'type' => 'Nota credito',
            'state' => 'Pendiente',
            'company_id' => $company->id
        ]);

        Invoice::factory()->count(4)->create();

        $response = $this->getJson('api/invoice');

        dd($response->content());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'details',
                        'total_iva_collected',
                        'total_amount_payable',
                        'date_issuance',
                        'date_payment',
                        'type',
                        'state',
                        'company_id'
                    ]
                ],
                'meta' => [
                    'status',
                    'msg'
                ]
            ])
            ->assertJsonFragment([
                'details' => [
                    'name' => 'name 1',
                    'amount' => 12,
                    'unit_value' => 2323,
                    'total' => 12*2323
                ],
                'total_iva_collected' => 2000.2,
                'total_amount_payable' => 100000.6,
                'date_issuance' => '2021-05-20 02:10:20',
                'date_payment' => '2022-10-10 10:50:20',
                'type' => 'Nota debito',
                'state' => 'Activa',
                'company_id' => $company->id
            ])
            ->assertJsonFragment([
                'details' => [
                    'name' => 'name 2',
                    'amount' => 31,
                    'unit_value' => 123,
                    'total' => 31*123
                ],
                'total_iva_collected' => 3000.2,
                'total_amount_payable' => 400000.6,
                'date_issuance' => '2020-02-20 20:20:20',
                'date_payment' => '2020-04-10 10:20:20',
                'type' => 'Nota credito',
                'state' => 'Pendiente',
                'company_id' => $company->id
            ]);
    }
}
