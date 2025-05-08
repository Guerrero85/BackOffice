<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'nullable|date', 
            'gender' => 'nullable|string|in:Masculino,Femenino,Otro',
            'address' => 'nullable|string|max:500', 
            'phone_number' => 'nullable|string|max:20', 
            'insurance' => 'nullable|int|', 
            'dni' => 'nullable|string|max:20', 
            'product' => 'nullable|int', 
            'membership' => 'nullable|string|max:50', 
        ];
    }
}
