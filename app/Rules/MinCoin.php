<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinCoin implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $formated = str_replace(['.', ','], ['', '.'], $value);

        if ($formated < 1) {
            $fail("O campo {$attribute} não pode ser menor que 1.");
        }
    }
}
