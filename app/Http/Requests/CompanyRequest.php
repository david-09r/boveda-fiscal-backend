<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'nit' => 'required|integer|unique:users,nit',
                    'social_reason' => 'required|string|unique:users,social_reason',
                    'site_direction' => 'required|string',
                    'code_number' => 'required|integer',
                    'phone_number' => 'required|integer',
                    'email' => 'required|email|unique:users,email',
                    'website' => 'required|string',
                    'user_id' => 'required|integer|exists:users,id'
                ];
            case 'PUT':
                return [
                    'nit' => 'integer|unique:users,nit',
                    'social_reason' => 'string|unique:users,,social_reason',
                    'site_direction' => 'string',
                    'code_number' => 'integer',
                    'phone_number' => 'integer',
                    'email' => 'email|unique:users,email',
                    'website' => 'string'
                ];
        }
        return null;
    }
}
