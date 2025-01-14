<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;

use App\Services\CategoryService;

use App\Http\Requests\CategoryStore;

class CategoryController extends Controller
{

    private $service;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
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

        $totalQuery = $company->categories()->where($where)
            ->count();

        $entity = $company->categories()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.categories.body-table')->with('entity', $entity)->render();

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

        $totalQuery = $company->categories()->get()
            ->count();

        $entity = $company->categories()->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);


        return view('admin.categories.index')->with([
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
        $user = Auth::user();

        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('categories.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if ($category) {
            $user = Auth::user();
            $company = $user->company()->first();

            return view('admin.categories.show')->with(compact('category'));
        }

        $session = ['status' => false, 'message' => 'A categoria informada não existe.'];
        return Redirect::route('categories.index')->with('success', $session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Category $category)
    {
        if ($category) {
            $user = Auth::user();
            $company = $user->company()->first();

            return view('admin.categories.edit')->with(compact('category'));
        }

        $session = ['status' => false, 'message' => 'A categoria informada não existe.'];
        return Redirect::route('categories.index')->with('success', $session);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryStore $request, Category $category)
    {
        if ($category) {
            $return = $this->service->update($request, $category);
            return Redirect::route('categories.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A categoria informada não existe.'];
        return Redirect::route('categories.index')->with('success', $session);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        if ($category) {

            $return = $this->service->destroy($category);
            return Redirect::route('categories.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'A categoria informada não existe.'];
        return Redirect::route('categories.index')->with('success', $session);
    }
}
