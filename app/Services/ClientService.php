<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\ClientStore;
use App\Http\Requests\ClientUpdate;

use App\Models\Client;

class ClientService
{

    public function searchClient(Request $data)
    {

        try {

            $return = [];

            // Pegando html dos modais
            $htmlClient = '';
            $htmlAddress = '';
            $htmlOrders = '';

            $search = $data->search;

            if (is_numeric($search)) {
                $client = Client::with(['addresses.city'])->where([
                    ['phone', '=', $search]
                ])->first();

                if ($client) {

                    $addresses = $client->addresses()->orderby('id', 'DESC')->get();
                    $orders = $client->orders()->orderby('date', 'DESC')->get();

                    $firstRequest = $orders->last();
                    $lastRequest = $orders->first();
                    $total = $orders->sum('total');

                    // Pegando html dos modais
                    $htmlClient = view('components.modal-admin.tabs.client')->with(compact('client'))->render();
                    $htmlAddress = view('components.modal-admin.tabs.address')->with(compact('addresses', 'client'))->render();
                    $htmlOrders = view('components.modal-admin.tabs.historic')->with(compact('orders', 'client', 'firstRequest', 'lastRequest', 'total'))->render();
                } else {

                    $return = [
                        'success' => false,
                        'message' => 'Nenhum cliente encontrado!',
                        'data' => []
                    ];
                }
            } else {
                $client = Client::with('addresses')->where([
                    ['name', 'like', '%' . $search . '%']
                ])->get();

                if (!$client) {
                    $return = [
                        'success' => false,
                        'message' => 'Nenhum cliente encontrado!',
                        'data' => []
                    ];
                }
            }

            if (isset($client) && $client->count() > 0) {
                $return = [
                    'success' => true,
                    'message' => 'Cliente encontrado com sucesso!',
                    'data' => $client,
                    'htmlClient' => $htmlClient,
                    'htmlAddress' => $htmlAddress,
                    'htmlOrders' => $htmlOrders
                ];
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Nenhum cliente encontrado!',
                    'data' => []
                ];
            }

            return $return;
        } catch (\Exception $e) {
            dd([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Erro ao buscar cliente!',
                'data' => []
            ];
        }
    } // searchClient()

    public function store(ClientStore $data)
    {

        try {

            $user = Auth::user();
            $company = $user->company()->first();

            $client = new Client();
            $client->name = $data->name;

            $slug = Str::slug($data->name);

            if ($client->slug != $slug) {
                $slugExist = $client->where('slug', $slug)->get();
                $qtdSlugs = count($slugExist);
                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }

                $client->slug = $slug;
            }

            $client->phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $data->phone);
            $client->cpf = $data->cpf;
            $client->birth = $data->birth;
            $client->email = $data->email;

            if ($data->password) {
                $client->password = Hash::make($data->password);
            }

            $client->gender = $data->gender;

            if ($data->gender == 'o') {
                $client->gender = $data->input('gender-other');
            }

            $client->company_id = $company->id;
            $client->save();

            return [
                'success' => true,
                'message' => 'Cliente cadastrado com sucesso.',
                'data' => $client
            ];
        } catch (\Exception $e) {
            $error = [
                'File: ' => $e->getFile(),
                'Line: ' => $e->getLine(),
                'Message: ' => $e->getMessage()
            ];
            dd($error);
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar cliente.',
                'data' => []
            ];
        }
    } // store()

    public function update(ClientUpdate $data, Client $client)
    {

        try {

            $client->name = $data->name;

            $slug = Str::slug($data->name);

            if ($client->slug != $slug) {
                // Verificando se ja existe Coupono com o slug
                $slugExist = $client->where('slug', $slug)->get();
                $qtdSlugs = count($slugExist);
                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }

                $client->slug = $slug;
            }

            $client->phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $data->phone);
            $client->cpf = $data->cpf;
            $client->birth = $data->birth;
            $client->email = $data->email;

            if ($data->password) {
                $client->password = Hash::make($data->password);
            }

            $client->gender = $data->gender;

            if ($data->gender == 'o') {
                $client->gender = $data->input('gender-other');
            }

            $client->save();

            return [
                'success' => true,
                'message' => 'Cliente editado com sucesso.',
                'data' => $client
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao editar cliente.',
                'data' => []
            ];
        }
    } // update()

    public function destroy(Client $client)
    {

        try {

            $return = DB::transaction(function () use ($client) {

                if ($client->addresses()->count() > 0) {
                    $client->addresses()->delete();
                }

                $client->delete();

                return [
                    'success' => true,
                    'message' => 'Cliente deletado com sucesso.',
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
                'message' => 'Erro ao tentar apagar cliente.'
            ];
        }
    } // destroy()

}
