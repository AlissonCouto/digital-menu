<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\PizzaSize;

use App\Http\Requests\PizzaSizeStore;

class PizzaSizeService
{

    public function store(PizzaSizeStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $size = new PizzaSize();
                $size->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->pizza_sizes()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $size->slug = $slug;

                $size->company_id = $company->id;
                $size->save();

                return [
                    'success' => true,
                    'message' => 'Tamanho cadastrado com sucesso.',
                    'data' => $size
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar tamanho.',
                'data' => []
            ];
        }
    } // store()

    public function update(PizzaSizeStore $data, PizzaSize $size)
    {

        try {

            $return = DB::transaction(function () use ($data, $size) {
                $user = Auth::user();
                $company = $user->company()->first();

                $size->name = $data->name;

                $slug = Str::slug($data->name);

                if ($size->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->pizza_sizes()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $size->slug = $slug;
                }

                $size->save();


                return [
                    'success' => true,
                    'message' => 'Tamanho editado com sucesso.',
                    'data' => $size
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar tamanho.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(PizzaSize $size)
    {

        try {

            $return = DB::transaction(function () use ($size) {

                $size->delete();

                return [
                    'success' => true,
                    'message' => 'Tamanho deletado com sucesso.',
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
                'message' => 'Erro ao tentar apagar tamanho.'
            ];
        }
    } // destroy()

}
