<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'details' => 'required|array',
            'details.*.name' => 'required|string',
            'details.*.quantity' => 'required|integer',
            'details.*.unit_value' => 'required|numeric',
            'total_iva_collected' => 'required|numeric',
            'total_amount_payable' => 'required|numeric',
            'date_issuance' => 'required|date_format:Y-m-d\TH:i:s',
            'date_payment' => 'required|date_format:Y-m-d\TH:i:s',
            'type' => [
                'required',
                Rule::in([
                    'Nota débito', 'Nota crédito', 'Normal'
                ])
            ],
            'state' => [
                'required',
                Rule::in([
                    'Activa', 'Cancelado'
                ])
            ]
        ];
    }
}
