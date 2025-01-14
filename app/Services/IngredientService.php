<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Ingredient;

use App\Http\Requests\IngredientStore;

class IngredientService
{

    public function store(IngredientStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $ingredient = new Ingredient();
                $ingredient->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->ingredients()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $ingredient->slug = $slug;

                if (str_replace(['.', ','], ['', '.'], $data->additional_price)) {
                    $ingredient->additional_price = number_format($data->additional_price, 2, '.', '');
                }

                $ingredient->company_id = $company->id;
                $ingredient->save();

                return [
                    'success' => true,
                    'message' => 'Ingrediente cadastrado com sucesso.',
                    'data' => $ingredient
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar ingrediente.',
                'data' => []
            ];
        }
    } // store()

    public function update(IngredientStore $data, Ingredient $ingredient)
    {

        try {

            $return = DB::transaction(function () use ($data, $ingredient) {
                $user = Auth::user();
                $company = $user->company()->first();

                $ingredient->name = $data->name;

                $slug = Str::slug($data->name);

                if ($ingredient->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->ingredients()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $ingredient->slug = $slug;
                }


                if (str_replace(['.', ','], ['', '.'], $data->additional_price)) {
                    $ingredient->additional_price = number_format($data->additional_price, 2, '.', '');
                }

                $ingredient->save();


                return [
                    'success' => true,
                    'message' => 'Ingrediente editado com sucesso.',
                    'data' => $ingredient
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar ingrediente.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(Ingredient $ingredient)
    {

        try {

            $return = DB::transaction(function () use ($ingredient) {

                $ingredient->delete();

                return [
                    'success' => true,
                    'message' => 'Ingrediente deletado com sucesso.',
                    'data' => []
                ];
            });

            return $return;
        } catch (\Exception $e) {

            $error = [
                'File: ' => $e->getFile(),
                'Line: ' => $e->getLine(),
                'Message: ' => $e->getMessage()
            ];
            dd($error);

            return [
                'success' => false,
                'message' => 'Erro ao tentar apagar ingrediente.'
            ];
        }
    } // destroy()

}
