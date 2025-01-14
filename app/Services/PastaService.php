<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\PastaOption;

use App\Http\Requests\PastaStore;

class PastaService
{

    public function store(PastaStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $pasta = new PastaOption();
                $pasta->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->pastas()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $pasta->slug = $slug;

                if (str_replace(['.', ','], ['', '.'], $data->price)) {
                    $pasta->price = number_format($data->price, 2, '.', '');
                }

                $pasta->company_id = $company->id;
                $pasta->save();

                return [
                    'success' => true,
                    'message' => 'Massa cadastrada com sucesso.',
                    'data' => $pasta
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar massa.',
                'data' => []
            ];
        }
    } // store()

    public function update(PastaStore $data, PastaOption $pasta)
    {

        try {

            $return = DB::transaction(function () use ($data, $pasta) {
                $user = Auth::user();
                $company = $user->company()->first();

                $pasta->name = $data->name;

                $slug = Str::slug($data->name);

                if ($pasta->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->pastas()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $pasta->slug = $slug;
                }


                if (str_replace(['.', ','], ['', '.'], $data->price)) {
                    $pasta->price = number_format($data->price, 2, '.', '');
                }

                $pasta->save();


                return [
                    'success' => true,
                    'message' => 'Massa editada com sucesso.',
                    'data' => $pasta
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar massa.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(PastaOption $pasta)
    {

        try {

            $return = DB::transaction(function () use ($pasta) {

                $pasta->delete();

                return [
                    'success' => true,
                    'message' => 'Massa deletada com sucesso.',
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
                'message' => 'Erro ao tentar apagar massa.'
            ];
        }
    } // destroy()

}
