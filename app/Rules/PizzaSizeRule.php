<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Models\PizzaSize;

class PizzaSizeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $qtdNulls = 0;

        foreach ($value as $k => $price) {

            if (!is_null($price)) {
                $price = str_replace(['.', ','], ['', '.'], $price);
            }

            if (!empty($price)) {
                $qtdNulls += 1;
            }

            if (is_null(PizzaSize::find($k))) {
                $fail('Um dos tamanhos de pizza informados não existe.');
            }

            if (!empty($price)) {
                if (preg_match('/^\d+(\.\d{1,2})?$/', $price) !== 1) {
                    $fail('Erro no formato do preço da pizza.');
                }
            }
        } // foreach()

        if ($qtdNulls == 0) {
            $fail('Informe pelo menos um preço para a pizza.');
        }
    }
}
