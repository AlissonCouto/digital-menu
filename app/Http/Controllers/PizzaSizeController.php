<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\PizzaSize;

use App\Services\PizzaSizeService;

use App\Http\Requests\PizzaSizeStore;

class PizzaSizeController extends Controller
{

    private $service;

    public function __construct(PizzaSizeService $pizzaSizeService)
    {
        $this->service = $pizzaSizeService;
    }

    /**
     * Display a listing of the resource through query.
     */
    public function search(Request $request)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';
        //dd($request->query());
        $user = Auth::user();
        $company = $user->company()->first();

        $where[] = ['name', 'like', '%' . $search . '%'];

        /* Filtros */

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->pizza_sizes()->where($where)
            ->count();

        $entity = $company->pizza_sizes()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.sizes.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->pizza_sizes()->get()
            ->count();

        $entity = $company->pizza_sizes()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.sizes.index')->with([
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
        return view('admin.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PizzaSizeStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('sizes.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(PizzaSize $size)
    {
        if ($size) {
            return view('admin.sizes.show')->with(compact('size'));
        }

        $session = ['status' => false, 'message' => 'O tamanho informado não existe.'];
        return Redirect::route('sizes.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, PizzaSize $size)
    {
        if ($size) {
            return view('admin.sizes.edit')->with(compact('size'));
        }

        $session = ['status' => false, 'message' => 'O tamanho informado não existe.'];
        return Redirect::route('sizes.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PizzaSizeStore $request, PizzaSize $size)
    {

        if ($size) {
            $return = $this->service->update($request, $size);
            return Redirect::route('sizes.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O tamanho informado não existe.'];
        return Redirect::route('sizes.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PizzaSize $size)
    {

        if ($size) {

            $return = $this->service->destroy($size);
            return Redirect::route('sizes.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O tamanho informado não existe.'];
        return Redirect::route('sizes.index')->with('success', $session);
    }
}
