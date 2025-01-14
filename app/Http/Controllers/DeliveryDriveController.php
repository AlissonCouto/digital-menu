<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\DeliveryDrive;

use App\Services\DeliveryDriveService;

use App\Http\Requests\DeliveryDriveStore;

class DeliveryDriveController extends Controller
{

    private $service;

    public function __construct(DeliveryDriveService $deliveryDriveService)
    {
        $this->service = $deliveryDriveService;
    }

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

        $totalQuery = $company->delivery_drivers()->with('address')->where($where)
            ->count();

        $entity = $company->delivery_drivers()->with('address')->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.deliverydrivers.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->delivery_drivers()->get()
            ->count();

        $entity = $company->delivery_drivers()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.deliverydrivers.index')->with([
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
        return view('admin.deliverydrivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeliveryDriveStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('deliverydrivers.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryDrive $deliveryman)
    {
        if ($deliveryman) {
            return view('admin.deliverydrivers.show')->with(compact('deliveryman'));
        }

        $session = ['status' => false, 'message' => 'O entregador informado não existe.'];
        return Redirect::route('deliverydrivers.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, DeliveryDrive $deliveryman)
    {
        if ($deliveryman) {

            $address = $deliveryman->address()->first();

            return view('admin.deliverydrivers.edit')->with(compact('deliveryman', 'address'));
        }

        $session = ['status' => false, 'message' => 'O entregador informado não existe.'];
        return Redirect::route('deliverydrivers.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeliveryDriveStore $request, DeliveryDrive $deliveryman)
    {

        if ($deliveryman) {
            $return = $this->service->update($request, $deliveryman);
            return Redirect::route('deliverydrivers.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O entregador informado não existe.'];
        return Redirect::route('deliverydrivers.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryDrive $deliveryman)
    {

        if ($deliveryman) {

            $return = $this->service->destroy($deliveryman);
            return Redirect::route('deliverydrivers.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O entregador informado não existe.'];
        return Redirect::route('deliverydrivers.index')->with('success', $session);
    }
}
