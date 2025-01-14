<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Ingredient;

use App\Services\IngredientService;

use App\Http\Requests\IngredientStore;

class IngredientController extends Controller
{

    private $service;

    public function __construct(IngredientService $ingredientService)
    {
        $this->service = $ingredientService;
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

        $totalQuery = $company->ingredients()->where($where)
            ->count();

        $entity = $company->ingredients()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.ingredients.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->ingredients()->get()
            ->count();

        $entity = $company->ingredients()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.ingredients.index')->with([
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
        return view('admin.ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IngredientStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('ingredients.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        if ($ingredient) {
            return view('admin.ingredients.show')->with(compact('ingredient'));
        }

        $session = ['status' => false, 'message' => 'O ingrediente informado não existe.'];
        return Redirect::route('ingredients.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Ingredient $ingredient)
    {
        if ($ingredient) {
            return view('admin.ingredients.edit')->with(compact('ingredient'));
        }

        $session = ['status' => false, 'message' => 'O ingrediente informado não existe.'];
        return Redirect::route('ingredients.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IngredientStore $request, Ingredient $ingredient)
    {

        if ($ingredient) {
            $return = $this->service->update($request, $ingredient);
            return Redirect::route('ingredients.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O ingrediente informado não existe.'];
        return Redirect::route('ingredients.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {

        if ($ingredient) {

            $return = $this->service->destroy($ingredient);
            return Redirect::route('ingredients.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O ingrediente informado não existe.'];
        return Redirect::route('ingredients.index')->with('success', $session);
    }
}
