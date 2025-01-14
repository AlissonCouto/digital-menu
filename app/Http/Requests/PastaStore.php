<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PastaStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->input('price')) {
            $this->merge([
                'price' => str_replace(['.', ','], ['', '.'], $this->input('price'))
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100|regex:/[a-zA-Zà-úÀ-Ú\s]/',
            'price' => 'nullable|decimal:2'
        ];
    }
}
