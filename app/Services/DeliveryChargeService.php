<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\DeliveryCharge;

use App\Http\Requests\DeliveryChargesStore;

class DeliveryChargeService
{

    public function store(DeliveryChargesStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $fee = new DeliveryCharge();
                $fee->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->delivery_charges()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $fee->slug = $slug;

                if (str_replace(['.', ','], ['', '.'], $data->value)) {
                    $fee->value = number_format($data->value, 2, '.', '');
                }

                $fee->company_id = $company->id;
                $fee->save();

                return [
                    'success' => true,
                    'message' => 'Taxa cadastrada com sucesso.',
                    'data' => $fee
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar taxa.',
                'data' => []
            ];
        }
    } // store()

    public function update(DeliveryChargesStore $data, DeliveryCharge $fee)
    {

        try {

            $return = DB::transaction(function () use ($data, $fee) {
                $user = Auth::user();
                $company = $user->company()->first();

                $fee->name = $data->name;

                $slug = Str::slug($data->name);

                if ($fee->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->delivery_charges()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $fee->slug = $slug;
                }


                if (str_replace(['.', ','], ['', '.'], $data->value)) {
                    $fee->value = number_format($data->value, 2, '.', '');
                }

                $fee->save();

                return [
                    'success' => true,
                    'message' => 'Taxa editada com sucesso.',
                    'data' => $fee
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar taxa.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(DeliveryCharge $fee)
    {

        try {

            $return = DB::transaction(function () use ($fee) {

                $fee->delete();

                return [
                    'success' => true,
                    'message' => 'Taxa deletada com sucesso.',
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
                'message' => 'Erro ao tentar apagar taxa.'
            ];
        }
    } // destroy()

}
