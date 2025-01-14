<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MenuOrderItemRule;
use Illuminate\Support\Facades\Auth;

class MenuCreateOrderRequest extends FormRequest
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
            'cart' => json_decode($this->input('cart'), true)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd($this->all());
        $rules = [
            'phone' => 'required|min:16|max:16',
            'name' => 'required|min:3|max:100|regex:/[a-zA-Zà-úÀ-Ú\s]/',
            'payments' => ['required', Rule::in(1, 2, 3)],
            'descriptions' => 'nullable|regex:/[a-zA-Zà-úÀ-Ú\s]/|max:200',
            //'cart.formPayment' => ['required', Rule::in('credit', 'debit', 'cash', 'pix')],
            //'cart.deliveryMethod' => ['required', Rule::in('delivery', 'withdrawal', 'comeget')],
            'cart.discounts' => 'required|decimal:0,2',
            'cart.shipping' => 'required|decimal:0,2',
            'cart.subtotal' => 'required|decimal:0,2',
            'cart.total' => 'required|decimal:0,2',
            'cart.items' => 'required|array',
            'cart.items' => [new MenuOrderItemRule()]
        ];
        // O endereço pode ser withdrawal, comeget ou id do endereço
        if (Auth::guard('client')->check()) {
            // Logado

            $rules['password'] = "nullable|min:8";

            if (is_numeric($this->input('address'))) {
                $rules['address'] = 'required|exists:addresses,id';
            } else {
                $rules['address'] = ['required', Rule::in(['withdrawal', 'comeget'])];
            }
        } else {
            // Deslogado
            $rules['password'] = "required|min:8";
            $rules['address'] = ['required', Rule::in(['withdrawal', 'comeget', 'delivery'])];

            if ($this->input('address') == 'delivery') {
                $rules['street'] = 'nullable|string|min:5';
                $rules['number'] = 'nullable|integer';
                $rules['neighborhood'] = 'nullable|string|min:5';
                $rules['reference'] = 'nullable|string|min:5';
            }
        }

        return $rules;
    }
}
