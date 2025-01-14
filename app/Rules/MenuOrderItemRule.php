<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Category;
use App\Models\Product;
use App\Models\PizzaSize;
use App\Models\PastaOption;
use App\Models\BorderOption;
use App\Models\Ingredient;

class MenuOrderItemRule implements ValidationRule
{

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $item) {

            if (empty($item['category']) || Category::where('slug', $item['category']['name'])->count() == 0) {
                $fail('Categoria inválida nos itens do pedido.');
            } else {

                if ($item['category'] == 'pizzas') {

                    // Pizzas
                    if (count($item['products']) == 0) {
                        $fail('Informe pelo menos um sabor para a pizza.');
                    } else if (count($item['products']) > 2) {
                        $fail('Informe no máximo dois sabores para a pizza.');
                    } else {

                        if (empty($item['sizeId']) || is_null(PizzaSize::find($item['sizeId']))) {
                            $fail('O tamanho de pizza informado é inválido.');
                        }

                        if (count($item['border']) > 0) {
                            if (empty($item['border']['id']) || is_null(BorderOption::find($item['border']['id']))) {
                                $fail('A borda informada para a pizza é inválida.');
                            }

                            if (!empty($item['border']['price'])) {
                                if (preg_match('/^\d+(\.\d{1,2})?$/', $item['border']['price']) !== 1) {
                                    $fail('O formato do preço da borda está incorreto.');
                                }
                            }
                        }

                        if (count($item['pasta']) == 0) {
                            $fail('O tipo de massa é obrigatório.');
                        } else {

                            if (empty($item['pasta']['id']) || is_null(PastaOption::find($item['pasta']['id']))) {
                                $fail('A massa informada para a pizza é inválida.');
                            }

                            if (!empty($item['pasta']['price'])) {
                                if (preg_match('/^\d+(\.\d{1,2})?$/', $item['pasta']['price']) !== 1) {
                                    $fail('O formato do preço da massa está incorreto.');
                                }
                            }
                        }

                        // Percorrendo os produtos que compõem o item
                        foreach ($item['products'] as $product) {
                            if (empty($product['productId']) || is_null(Product::find($product['productId']))) {
                                $fail('O tamanho de pizza informado é inválido.');
                            }

                            if (empty($product['price'])) {
                                $fail('O preço da pizza é obrigatório.');
                            }

                            if (preg_match('/^\d+(\.\d{1,2})?$/', $product['price']) !== 1) {
                                $fail('O formato do preço da pizza está incorreto.');
                            }

                            if ($product['additionals']) {

                                foreach ($product['additionals'] as $additional) {
                                    if (empty($additional['id']) || is_null(Ingredient::find($additional['id']))) {
                                        $fail('O adicional da pizza informado é inválido.');
                                    }
                                }
                            }

                            if ($product['removedIngredients']) {

                                foreach ($product['removedIngredients'] as $removedIngredient) {
                                    if (empty($removedIngredient['id']) || is_null(Ingredient::find($removedIngredient['id']))) {
                                        $fail('O ingrediente removido da pizza informado é inválido.');
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Lanches

                    if (empty($item['id'])) {
                        $fail('O id do item é obrigatório.');
                    }

                    if (is_null(Product::find($item['id']))) {
                        $fail('O produto informado no item não existe.');
                    }
                } // Se lanche ou pizza

                if (empty($item['price'])) {
                    $fail('Informe o preço de todos os itens do pedido.');
                }

                if (preg_match('/^\d+(\.\d{1,2})?$/', $item['price']) !== 1) {
                    $fail('O formato do preço do item está incorreto.');
                }

                if (empty($item['quantity'])) {
                    $fail('Informe a quantidade de cada item.');
                }

                if (gettype($item['quantity']) != 'integer') {
                    $fail('A quantidade dos itens deve ser um número.');
                }
            } // Se informou a categoria do item
        } // foreach($values as $item)
    }
}
