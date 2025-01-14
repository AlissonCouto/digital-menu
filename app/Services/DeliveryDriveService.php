<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\DeliveryDrive;
use App\Models\Address;

use App\Http\Requests\DeliveryDriveStore;

class DeliveryDriveService
{

    public function store(DeliveryDriveStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $companyAddress = Address::where([
                    ['company_id', '=', $company->id],
                    ['client_id', 'IS', NULL],
                    ['deliveryman_id', 'IS', NULL],
                    ['employee_id', 'IS', NULL],
                ]);

                if ($companyAddress->count() > 0) {
                    $city_id = $companyAddress->first()->city_id;
                } else {
                    $city_id = 1;
                }

                $deliveryman = new DeliveryDrive();
                $deliveryman->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->delivery_drivers()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $deliveryman->slug = $slug;

                $deliveryman->cpf = $data->cpf;
                $deliveryman->birth = date('Y-m-d', strtotime($data->birth));
                $deliveryman->phone = $data->phone;
                $deliveryman->email = $data->email;

                $deliveryman->company_id = $company->id;
                $deliveryman->save();

                if (!is_null($data->street)) {
                    $address = new Address();
                    $address->street = $data->street;
                    $address->number = $data->number;
                    $address->neighborhood = $data->neighborhood;
                    $address->reference = $data->reference;
                    $address->main = 1;
                    $address->city_id = $city_id;
                    $address->company_id = $company->id;
                    $address->deliveryman_id = $deliveryman->id;

                    $address->save();
                }

                return [
                    'success' => true,
                    'message' => 'Entregador cadastrado com sucesso.',
                    'data' => $deliveryman
                ];
            });

            return $return;
        } catch (\Exception $e) {
            dd([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar entregador.',
                'data' => []
            ];
        }
    } // store()

    public function update(DeliveryDriveStore $data, DeliveryDrive $deliveryman)
    {

        try {

            $return = DB::transaction(function () use ($data, $deliveryman) {
                $user = Auth::user();
                $company = $user->company()->first();

                $companyAddress = Address::where([
                    ['company_id', '=', $company->id],
                    ['client_id', 'IS', NULL],
                    ['deliveryman_id', 'IS', NULL],
                    ['employee_id', 'IS', NULL],
                ]);

                if ($companyAddress->count() > 0) {
                    $city_id = $companyAddress->first()->city_id;
                } else {
                    $city_id = 1;
                }

                $deliveryman->name = $data->name;

                $slug = Str::slug($data->name);

                if ($deliveryman->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->delivery_drivers()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $deliveryman->slug = $slug;
                }

                $deliveryman->cpf = $data->cpf;
                $deliveryman->birth = date('Y-m-d', strtotime($data->birth));
                $deliveryman->phone = $data->phone;
                $deliveryman->email = $data->email;

                $deliveryman->company_id = $company->id;
                $deliveryman->save();

                // Se já tem endereço salvo
                if ($deliveryman->address()->count() > 0) {
                    $address = $deliveryman->address()->first();

                    // E informou rua, atualiza
                    if (!is_null($data->street)) {
                        $address->street = $data->street;
                        $address->number = $data->number;
                        $address->neighborhood = $data->neighborhood;
                        $address->reference = $data->reference;
                        $address->main = 1;
                        $address->city_id = $city_id;
                        $address->company_id = $company->id;
                        $address->deliveryman_id = $deliveryman->id;

                        $address->save();
                    } else {
                        // Não informou rua, apague o que existe
                        $deliveryman->address()->delete();
                    }
                } else {
                    // Se não tem endereço
                    // E informou rua, cadastra
                    if (!is_null($data->street)) {
                        $address = new Address();
                        $address->street = $data->street;
                        $address->number = $data->number;
                        $address->neighborhood = $data->neighborhood;
                        $address->reference = $data->reference;
                        $address->main = 1;
                        $address->city_id = $city_id;
                        $address->company_id = $company->id;
                        $address->deliveryman_id = $deliveryman->id;

                        $address->save();
                    }
                }

                $deliveryman->save();


                return [
                    'success' => true,
                    'message' => 'Entregador editado com sucesso.',
                    'data' => $deliveryman
                ];
            });

            return $return;
        } catch (\Exception $e) {

            dd([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao editar entregador.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(DeliveryDrive $deliveryman)
    {

        try {

            $return = DB::transaction(function () use ($deliveryman) {

                if ($deliveryman->address()->count() > 0) {
                    $deliveryman->address()->delete();
                }

                $deliveryman->delete();

                return [
                    'success' => true,
                    'message' => 'Entregador deletado com sucesso.',
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
                'message' => 'Erro ao tentar apagar entregador.'
            ];
        }
    } // destroy()

}
