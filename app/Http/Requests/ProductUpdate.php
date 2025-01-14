<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use App\Rules\PizzaSizeRule;
use App\Rules\PizzaAdditionalRule;
use App\Rules\PizzaIngredientRule;

class ProductUpdate extends FormRequest
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
        $this->merge([
            'price' => str_replace(['.', ','], ['', '.'], $this->input('price'))
        ]);
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
            'description' => 'nullable|regex:/[a-zA-Zà-úÀ-Ú\s]/|max:200',
            'category_id' => 'required|exists:categories,id',
            'menu' => ['required', Rule::in(0, 1)],
            'status' => ['required', Rule::in(0, 1)],
            'ingredients' => ['required', 'array', new PizzaIngredientRule()]
        ];

        // Verificações específicas para pizzas
        if ($this->category_id == 1) {
            if ($this->additionals) {
                $rules['additionals'] = ['nullable', 'array', new PizzaAdditionalRule()];
            }

            $rules['pizza_size'] = ['required', 'array', new PizzaSizeRule()];
        } else {
            $rules['price'] = 'required|decimal:2';
        }

        return $rules;
    }
}
