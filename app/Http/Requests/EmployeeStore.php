<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStore extends FormRequest
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
        $rules = [
            'name' => 'required|min:3|max:100|regex:/[a-zA-Zà-úÀ-Ú\s]/',
            'phone' => 'nullable|min:15|max:16',
            'cpf' => 'required|regex:/\d{3}\.\d{3}\.\d{3}\-\d{2}/',
            'birth' => 'nullable|date|date_format:Y-m-d',
            'password' => 'required|string'
        ];

        if (isset($this->route('employee')->user_id)) {
            $rules['email'] = 'nullable|email|unique:users,email,' . $this->route('employee')->user_id;
        } else {
            $rules['email'] = 'nullable|email|unique:users,email';
        }

        if (!is_null($this->input('street'))) {
            $rules['street'] = 'required|string|min:5';
            $rules['number'] = 'required|integer';
            $rules['neighborhood'] = 'nullable|string|min:5';
            $rules['reference'] = 'nullable|string|min:5';
        }

        return $rules;
    }
}
