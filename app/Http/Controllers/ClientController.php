<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Client;

use App\Http\Requests\ClientStore;
use App\Http\Requests\ClientUpdate;

use App\Services\ClientService;

class ClientController extends Controller
{

    private $service;

    public function __construct(ClientService $clientService)
    {
        $this->service = $clientService;
    }

    public function searchClient(Request $request)
    {

        $return = $this->service->searchClient($request);

        return json_encode($return);
        die;
    } // searchClient()

    /**
     * Display a listing of the resource through query.
     */
    public function search(Request $request)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';

        $user = Auth::user();
        $company = $user->company()->first();


        $where[] = ['name', 'like', '%' . $search . '%'];

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->clients()->where($where)
            ->count();

        $entity = $company->clients()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.clients.body-table')->with('entity', $entity)->render();

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
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->employees()->get()
            ->count();

        $entity = $company->clients()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.clients.index')->with([
            'total' => $totalQuery,
            'entity' => $entity,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('clients.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        if ($client) {
            return view('admin.clients.show')->with(compact('client'));
        }

        $session = ['status' => false, 'message' => 'O cliente informado não existe.'];
        return Redirect::route('clients.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Client $client)
    {
        if ($client) {

            return view('admin.clients.edit')->with(compact('client'));
        }

        $session = ['status' => false, 'message' => 'O cliente informado não existe.'];
        return Redirect::route('clients.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdate $request, Client $client)
    {
        $return = $this->service->update($request, $client);

        return json_encode($return);
        die;
    }

    public function updateAdmin(ClientUpdate $request, Client $client)
    {
        $return = $this->service->update($request, $client);
        return Redirect::route('clients.index')->with('success', $return);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {

        if ($client) {
            $return = $this->service->destroy($client);
            return Redirect::route('clients.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O cliente informado não existe.'];
        return Redirect::route('clients.index')->with('success', $session);
    }
}
