<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Services\BorderService;

use App\Http\Requests\BorderStore;
use App\Models\BorderOption;

class BorderOptionController extends Controller
{

    private $service;

    public function __construct(BorderService $borderService)
    {
        $this->service = $borderService;
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

        /* Filtros */

        $where[] = ['name', 'like', '%' . $search . '%'];

        /* Filtros */

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->borders()->where($where)
            ->count();

        $entity = $company->borders()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.borders.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->borders()->get()
            ->count();

        $entity = $company->borders()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.borders.index')->with([
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
        return view('admin.borders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BorderStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('borders.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(BorderOption $border)
    {
        if ($border) {
            return view('admin.borders.show')->with(compact('border'));
        }

        $session = ['status' => false, 'message' => 'A borda informada não existe.'];
        return Redirect::route('borders.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, BorderOption $border)
    {
        if ($border) {
            return view('admin.borders.edit')->with(compact('border'));
        }

        $session = ['status' => false, 'message' => 'A borda informada não existe.'];
        return Redirect::route('borders.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BorderStore $request, BorderOption $border)
    {

        if ($border) {
            $return = $this->service->update($request, $border);
            return Redirect::route('borders.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A borda informada não existe.'];
        return Redirect::route('borders.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorderOption $border)
    {

        if ($border) {

            $return = $this->service->destroy($border);
            return Redirect::route('borders.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A borda informada não existe.'];
        return Redirect::route('borders.index')->with('success', $session);
    }
}
