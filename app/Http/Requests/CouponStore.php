<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Coin;
use App\Rules\MinCoin;

class CouponStore extends FormRequest
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
    public function rules()
    {

        $expirationDateRules = ['date', 'date_format:Y-m-d', 'after_or_equal:today'];
        $expiryTimeRules = ['date_format:H:i'];
        $usage_limitRules = ['numeric', 'min:1'];

        if (request()->validity_type == 'deadline') {
            array_unshift($usage_limitRules, 'nullable');
            array_unshift($expirationDateRules, 'required');
            array_unshift($expiryTimeRules, 'required');
        }

        if (request()->validity_type == 'usage_limit') {
            array_unshift($usage_limitRules, 'required');
            array_unshift($expirationDateRules, 'nullable');
            array_unshift($expiryTimeRules, 'nullable');
        }

        $rules = [
            'name' => 'required|min:3|max:100|regex:/[a-zA-Zà-úÀ-Ú\s]/',
            'description' => 'nullable|min:3|max:255|regex:/[a-zA-Zà-úÀ-Ú\s]/',
            'validity_type' => ['required', Rule::in(['usage_limit', 'deadline'])],
            'discount_type' => ['required', Rule::in(['value', 'percent'])],
            'value' => ['required', new Coin, new MinCoin],
            'usage_limit' => $usage_limitRules,
            'expiration_date' => $expirationDateRules,
            'expiry_time' => $expiryTimeRules,
        ];

        return $rules;
    }
}
