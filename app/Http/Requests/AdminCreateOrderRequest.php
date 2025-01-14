<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\OrderItemRule;

class AdminCreateOrderRequest extends FormRequest
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
            'order' => json_decode($this->input('order'), true)
        ]);
    } // prepareForValidation()

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd($this->all());
        return [
            'order.client.id' => 'required|exists:clients,id',
            'order.address.id' => 'required|exists:addresses,id',
            'order.formPayment' => ['required', Rule::in('credit-card', 'debit-card', 'cash', 'pix')],
            'order.deliveryMethod' => ['required', Rule::in('delivery', 'withdrawal', 'comeget')],
            'order.discounts' => 'required|decimal:0,2',
            'order.shipping' => 'required|decimal:0,2',
            'order.subtotal' => 'required|decimal:0,2',
            'order.total' => 'required|decimal:0,2',
            'order.items' => 'required|array',
            'order.items' => [new OrderItemRule()]
        ];
    }
}
