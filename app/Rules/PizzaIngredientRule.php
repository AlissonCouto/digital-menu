<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Models\Ingredient;

class PizzaIngredientRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $qtdNulls = 0;

        foreach ($value as $ingredient) {

            if (!empty($ingredient)) {
                $qtdNulls += 1;
            }

            if (is_null(Ingredient::find($ingredient))) {
                $fail('Um dos ingredientes informados não existe.');
            }
        } // foreach()

        if ($qtdNulls == 0) {
            $fail('Informe pelo menos um ingrediente.');
        }
    }
}
