<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\PastaOption;

use App\Services\PastaService;

use App\Http\Requests\PastaStore;

class PastaOptionController extends Controller
{

    private $service;

    public function __construct(PastaService $pastaService)
    {
        $this->service = $pastaService;
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
        $category_id = $request->category_id ?? null;

        $where[] = ['name', 'like', '%' . $search . '%'];

        if ($category_id) {
            $where[] = ['category_id', '=', $category_id];
        }

        /* Filtros */

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->pastas()->where($where)
            ->count();

        $entity = $company->pastas()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.pastas.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->pastas()->get()
            ->count();

        $entity = $company->pastas()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.pastas.index')->with([
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
        return view('admin.pastas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PastaStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('pastas.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(PastaOption $pasta)
    {
        if ($pasta) {
            return view('admin.pastas.show')->with(compact('pasta'));
        }

        $session = ['status' => false, 'message' => 'A massa informada não existe.'];
        return Redirect::route('pastas.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, PastaOption $pasta)
    {
        if ($pasta) {
            return view('admin.pastas.edit')->with(compact('pasta'));
        }

        $session = ['status' => false, 'message' => 'A massa informada não existe.'];
        return Redirect::route('pastas.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PastaStore $request, PastaOption $pasta)
    {

        if ($pasta) {
            $return = $this->service->update($request, $pasta);
            return Redirect::route('pastas.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A massa informada não existe.'];
        return Redirect::route('pastas.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PastaOption $pasta)
    {

        if ($pasta) {

            $return = $this->service->destroy($pasta);
            return Redirect::route('pastas.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A massa informada não existe.'];
        return Redirect::route('pastas.index')->with('success', $session);
    }
}
