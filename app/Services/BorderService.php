<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\BorderOption;

use App\Http\Requests\BorderStore;

class BorderService
{

    public function store(BorderStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $border = new BorderOption();
                $border->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->borders()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $border->slug = $slug;

                if (str_replace(['.', ','], ['', '.'], $data->price)) {
                    $border->price = number_format($data->price, 2, '.', '');
                }

                $border->company_id = $company->id;
                $border->save();

                return [
                    'success' => true,
                    'message' => 'Borda cadastrada com sucesso.',
                    'data' => $border
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar borda.',
                'data' => []
            ];
        }
    } // store()

    public function update(BorderStore $data, BorderOption $border)
    {

        try {

            $return = DB::transaction(function () use ($data, $border) {
                $user = Auth::user();
                $company = $user->company()->first();

                $border->name = $data->name;

                $slug = Str::slug($data->name);

                if ($border->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->borders()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $border->slug = $slug;
                }


                if (str_replace(['.', ','], ['', '.'], $data->price)) {
                    $border->price = number_format($data->price, 2, '.', '');
                }

                $border->save();


                return [
                    'success' => true,
                    'message' => 'Borda editada com sucesso.',
                    'data' => $border
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar borda.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(BorderOption $border)
    {

        try {

            $return = DB::transaction(function () use ($border) {

                $border->delete();

                return [
                    'success' => true,
                    'message' => 'Borda deletada com sucesso.',
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
                'message' => 'Erro ao tentar apagar borda.'
            ];
        }
    } // destroy()

}
