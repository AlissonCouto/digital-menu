<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Address;

use App\Services\ClientService;
use App\Services\AddressService;

class DashboardModalController extends Controller
{

    private $clientService;
    private $addressService;

    public function __construct(ClientService $clientService, AddressService $addressService)
    {
        $this->clientService = $clientService;
        $this->addressService = $addressService;
    }

    public function updateClient(Request $request, Client $client)
    {

        $return = $this->clientService->update($request, $client);

        if ($return['success']) {
            $client = $return['data'];
            $success = $return['success'];
            $message = $return['message'];

            $htmlClient = view('components.modal-admin.tabs.client')->with(compact('client', 'success', 'message'))->render();

            $return['htmlClient'] = $htmlClient;
        }

        return json_encode($return);
        die;
    } // updateClient()

    public function createAddress(Request $request)
    {

        $return = $this->addressService->store($request);

        if ($return['success']) {

            if ($request->client_id) {
                $client = Client::find($request->client_id);
                $addresses = $client->addresses()->orderby('id', 'DESC')->get();
            }

            $success = $return['success'];
            $message = $return['message'];
            $htmlAddress = view('components.modal-admin.tabs.address')->with(compact('addresses', 'client', 'success', 'message'))->render();

            $return['htmlAddress'] = $htmlAddress;
        }

        return json_encode($return);
        die;
    } // createAddress()

    public function editAddress(Request $request, Address $address)
    {

        $return = [];

        if ($address) {

            $return['success'] = true;
            $return['message'] = 'Endereço encontrado com sucesso';

            $client = $address->client()->first();
            $addresses = $client->addresses()->orderby('id', 'DESC')->with('city')->get();

            $html = view('components.modal-admin.tabs.address-edit')->with(compact('address', 'addresses'))->render();

            $return['data'] = $html;
        } else {

            $return['success'] = false;
            $return['message'] = 'Falha ao editar o endereço';
            $return['data'] = '';
        }

        return json_encode($return);
        die;
    } // editAddress(Request $request, Address $address)

    public function updateAddress(Request $request, Address $address)
    {

        $return = $this->addressService->update($request, $address);

        if ($return['success']) {

            $client = $address->client()->first();
            $addresses = $client->addresses()->orderby('id', 'DESC')->get();

            $success = $return['success'];
            $message = $return['message'];
            $htmlAddress = view('components.modal-admin.tabs.address-edit')->with(compact('address', 'addresses', 'success', 'message'))->render();

            $return['htmlAddress'] = $htmlAddress;
        }

        return json_encode($return);
        die;
    } // updateAddress(Request $request, Address $address)

    public function deleteAddress(Request $request, Address $address)
    {

        if ($address) {
            $return = $this->addressService->delete($address);

            if ($return['success']) {

                $client = $address->client()->first();
                $addresses = $client->addresses()->orderby('id', 'DESC')->get();

                $success = $return['success'];
                $message = $return['message'];
                $htmlAddress = view('components.modal-admin.tabs.address')->with(compact('client', 'address', 'addresses', 'success', 'message'))->render();

                $return['htmlAddress'] = $htmlAddress;
            }

            return json_encode($return);
            die;
        }
    } // deleteAddress(Request $request, Address $address)

    public function getAddresById(Request $request, Address $address)
    {

        if ($address) {

            $client = $address->client()->first();
            $addresses = $client->addresses()->with('city')->orderby('id', 'DESC')->get();
            $address->city = $address->city()->first();

            $success = true;
            $message = 'Endereço selecionado com sucesso.';
            $htmlAddress = view('components.modal-admin.tabs.address')->with(compact('client', 'address', 'addresses', 'success', 'message'))->render();

            $return = [
                'success' => $success,
                'message' => $message,
                'data' => $address,
                'htmlAddress' => $htmlAddress
            ];
        } else {
            $return = [
                'success' => false,
                'message' => 'Endereço não encontrado.',
                'data' => ''
            ];
        }

        return json_encode($return);
        die;
    } // getAddresById(Request $request, Address $address)

}
