<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientUpdate extends FormRequest
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
            "email" => 'nullable|email|unique:clients,email,' . $this->route('client')->id,
            "password" => 'nullable|string',
            "gender" => ['required', Rule::in('f', 'm', 'o')],
        ];

        if ($this->input('gender') == '0') {
            $rules['gender-other'] = 'required';
        }

        return $rules;
    }
}
