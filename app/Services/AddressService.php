<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

use App\Models\Address;
use App\Models\Client;
use App\Models\Company;

class AddressService
{


    public function store(Request $data)
    {

        try {

            // O parâmetro client_id vem do modal do painel de gestão
            if (isset($data->client_id)) {
                $user = Auth::user();
                $company = Company::find($user->id);
                $client = Client::find($data->client_id);
            } else {
                $user = Auth::guard('client')->user();
                $client = Client::find($user->id);

                $company = $client->company()->first();
            }

            $city = $company->addresses()->first()->city()->first();

            $address = new Address();
            $address->description = $data->description;
            $address->street = $data->street;
            $address->number = $data->number;
            $address->neighborhood = $data->neighborhood;

            // Se estiver marcado como principal, desmarca anteriores
            if (isset($client)) {
                $client->addresses()->where('main', 1)->update(['main' => 0]);
            }

            $address->main = $data->main;

            $address->reference = $data->reference;
            $address->company_id = $company->id;
            $address->city_id = $city->id;
            $address->client_id = $client->id;

            $address->save();

            return [
                'success' => true,
                'message' => 'Endereço cadastrado com sucesso',
                'data' => $address->with('city')
            ];
        } catch (\Exception $e) {
            //Log::error($e->getMessage());
            dd($e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar endereço!',
                'data' => []
            ];
        }
    } // store()

    public function update(Request $data, Address $address)
    {

        try {

            $address->description = $data->description;
            $address->street = $data->street;
            $address->number = $data->number;
            $address->neighborhood = $data->neighborhood;

            // Se estiver marcado como principal, desmarca anteriores
            if (isset($client)) {
                $client->addresses()->where('main', 1)->update(['main' => 0]);
            }

            $address->main = $data->main;
            $address->reference = $data->reference;

            $address->save();

            return [
                'success' => true,
                'message' => 'Endereço editado com sucesso',
                'data' => $address->with('city')
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar endereço!',
                'data' => []
            ];
        }
    } // update()

    public function delete(Address $address)
    {

        try {

            if ($address) {

                $address->delete();

                $return = [
                    'success' => true,
                    'message' => 'Endereço deletado com sucesso.',
                    'data' => []
                ];
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Endereço não encontrado.',
                    'data' => []
                ];
            }

            return $return;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar cliente!',
                'data' => []
            ];
        }
    } // delete(Address $address)

}
