<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'details' => [
                [
                    'name' => 'Nombre ejemplo',
                    'amount' => 222,
                    'unit_value' => 234,
                    'total' => 222*234
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                    'amount' => 111,
                    'unit_value' => 334,
                    'total' => 111*334
                ],
                [
                    'name' => 'Nombre ejemplo 3',
                    'amount' => 444,
                    'unit_value' => 134,
                    'total' => 444*134
                ]
            ],
            'total_iva_collected' => 2000,
            'total_amount_payable' => 100006,
            'date_issuance' => $this->faker->dateTime,
            'date_payment' => $this->faker->dateTime,
            'type' => 'Nota debito',
            'state' => 'Activa',
            'company_id' => Company::factory()
        ];
    }
}
