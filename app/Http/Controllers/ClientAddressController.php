<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Services\AddressService;

use App\Models\Client;
use App\Models\Address;

class ClientAddressController extends Controller
{

    private $service;


    public function __construct(AddressService $addressService)
    {
        $this->service = $addressService;
    }
    /**
     * Display a listing of the resource through query.
     */
    public function search(Request $request, Client $client)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';

        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $client->addresses()->where('description', 'like', '%' . $search . '%')
            ->orWhere('street', 'like', '%' . $search . '%')
            ->orWhere('number', 'like', '%' . $search . '%')
            ->orWhere('neighborhood', 'like', '%' . $search . '%')
            ->orWhere('reference', 'like', '%' . $search . '%')
            ->count();

        $entity = $client->addresses()->where('description', 'like', '%' . $search . '%')
            ->orWhere('street', 'like', '%' . $search . '%')
            ->orWhere('number', 'like', '%' . $search . '%')
            ->orWhere('neighborhood', 'like', '%' . $search . '%')
            ->orWhere('reference', 'like', '%' . $search . '%')->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.clients.address.body-table')->with(compact('entity', 'client'))->render();

        echo json_encode([
            'total' => $totalQuery,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
            'html' => $html
        ]);
        die;
    } // search()

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Client $client)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $client->addresses()->get()
            ->count();

        $entity = $client->addresses()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.clients.address.index')->with([
            'total' => $totalQuery,
            'client' => $client,
            'entity' => $entity,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
        ]);
    } // index()

    /**
     * Display the specified resource.
     */
    public function show(Client $client, Address $address)
    {
        if ($client && $address) {
            return view('admin.clients.address.show')->with(compact('client', 'address'));
        }

        $session = ['status' => false, 'message' => 'O endereço informado não existe.'];
        return Redirect::route('clients.address.index', $client->id)->with('success', $session);
    } // show()

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Client $client)
    {
        return view('admin.clients.address.create')->with(compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        $service = $this->service->store($request);

        $data = $service['data'];

        $return = [
            'success' => $service['success'],
            'message' => $service['message'],
            'data' => $data->get()
        ];

        return Redirect::route('clients.address.index', $client->id)->with('success', $return);
    } // store()

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Client $client, Address $address)
    {
        if ($client) {

            return view('admin.clients.address.edit')->with(compact('client', 'address'));
        }

        $session = ['status' => false, 'message' => 'O Endereço informado não existe.'];
        return Redirect::route('clients.address.index', $client->id)->with('success', $session);
    } // edit()

    public function update(Request $request, Client $client, Address $address)
    {
        $service = $this->service->update($request, $address);

        $data = $service['data'];

        $return = [
            'success' => $service['success'],
            'message' => $service['message'],
            'data' => $data->get()
        ];

        return Redirect::route('clients.address.index', $client->id)->with('success', $return);
    } // update()

    public function destroy(Client $client, Address $address)
    {

        if ($client && $address) {
            $return = $this->service->delete($address);
            return Redirect::route('clients.address.index', $client->id)->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O endereço informado não existe.'];
        return Redirect::route('clients.address.index', $client->id)->with('success', $session);
    } // show()
}
