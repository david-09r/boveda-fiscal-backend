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
            'details' => json_encode([
                [
                    'name' => 'Nombre ejemplo',
                ],
                [
                    'name' => 'Nombre ejemplo 2',
                ],
                [
                    'name' => 'Nombre ejemplo 3',
                ]
            ]),
            'total_iva_collected' => 12312.0,
            'total_amount_payable' => 212312.0,
            'date_issuance' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'date_payment' => $this->faker->dateTimeBetween('now', '+4 years'),
            'type' => 'Nota debito',
            'state' => 'Pagado',
            'company_id' => Company::factory()

        ];
    }
}
