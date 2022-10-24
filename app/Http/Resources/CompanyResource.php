<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'nit' => $this->nit,
            'social_reason' => $this->social_reason,
            'site_direction' => $this->site_direction,
            'phone_number' => '+' .$this->code_number .' ' .$this->phone_number,
            'email' => $this->email,
            'website' => $this->website
        ];
    }
}
