<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;

use App\Http\Requests\CategoryStore;


class CategoryService
{

    public function store(CategoryStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $category = new Category();
                $category->name = $data->name;
                $category->icon = $data->icon;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->categories()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $category->slug = $slug;

                $category->description = $data->description;

                $category->company_id = $company->id;
                $category->save();

                return [
                    'success' => true,
                    'message' => 'Categoria cadastrada com sucesso.',
                    'data' => $category
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar categoria.',
                'data' => []
            ];
        }
    } // store()

    public function update(CategoryStore $data, Category $category)
    {

        try {

            $return = DB::transaction(function () use ($data, $category) {
                $user = Auth::user();
                $company = $user->company()->first();

                $category->name = $data->name;
                $category->icon = $data->icon;

                $slug = Str::slug($data->name);

                if ($category->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->categories()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $category->slug = $slug;
                }


                $category->description = $data->description;

                $category->save();

                return [
                    'success' => true,
                    'message' => 'Categoria editada com sucesso.',
                    'data' => $category
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar categoria.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(Category $category)
    {

        try {

            $return = DB::transaction(function () use ($category) {

                $category->delete();

                return [
                    'success' => true,
                    'message' => 'Categoria deletada com sucesso.',
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
                'message' => 'Erro ao tentar apagar categoria.'
            ];
        }
    } // destroy()

}
