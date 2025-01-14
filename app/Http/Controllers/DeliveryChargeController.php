<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\DeliveryCharge;

use App\Services\DeliveryChargeService;

use App\Http\Requests\DeliveryChargesStore;

class DeliveryChargeController extends Controller
{

    private $service;

    public function __construct(DeliveryChargeService $deliveryChargeService)
    {
        $this->service = $deliveryChargeService;
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

        $totalQuery = $company->delivery_charges()->where($where)
            ->count();

        $entity = $company->delivery_charges()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.fees.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->delivery_charges()->get()
            ->count();

        $entity = $company->delivery_charges()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.fees.index')->with([
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
        return view('admin.fees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeliveryChargesStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('fees.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryCharge $fee)
    {
        if ($fee) {
            return view('admin.fees.show')->with(compact('fee'));
        }

        $session = ['status' => false, 'message' => 'A taxa informada não existe.'];
        return Redirect::route('fees.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, DeliveryCharge $fee)
    {
        if ($fee) {
            return view('admin.fees.edit')->with(compact('fee'));
        }

        $session = ['status' => false, 'message' => 'A taxa informada não existe.'];
        return Redirect::route('fees.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeliveryChargesStore $request, DeliveryCharge $fee)
    {

        if ($fee) {
            $return = $this->service->update($request, $fee);
            return Redirect::route('fees.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A taxa informada não existe.'];
        return Redirect::route('fees.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryCharge $fee)
    {

        if ($fee) {

            $return = $this->service->destroy($fee);
            return Redirect::route('fees.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A taxa informada não existe.'];
        return Redirect::route('fees.index')->with('success', $session);
    }
}
