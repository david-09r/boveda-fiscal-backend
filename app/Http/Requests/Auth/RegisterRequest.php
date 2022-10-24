<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'type_identification' => 'required|string',
            'identification' => 'required|integer',
            'code_number' => 'required|integer',
            'phone_number' => 'required|integer',
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
        ];
    }
}
