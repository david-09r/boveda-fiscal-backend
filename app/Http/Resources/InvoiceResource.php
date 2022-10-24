<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'details' => $this->details,
            'total_iva_collected' => $this->total_iva_collected,
            'total_amount_payable' => $this->total_amount_payable,
            'date_payment' => $this->date_payment,
            'type' => $this->type,
            'state' => $this->state,
            'company_id' => $this->company_id
        ];
    }
}
