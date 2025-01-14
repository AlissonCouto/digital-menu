<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Product;
use App\Models\PricePizzaSize;

use App\Services\ProductService;

use App\Http\Requests\ProductStore;
use App\Http\Requests\ProductUpdate;

class ProductController extends Controller
{

    private $service;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
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

        $totalQuery = $company->products()->where($where)
            ->count();

        $entity = $company->products()->where($where)->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('admin.products.body-table')->with('entity', $entity)->render();

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

    public function searchProductsList(Request $request)
    {

        $return = $this->service->searchProductsList($request);

        return json_encode($return);
        die;
    } // searchProduct()

    public function searchProductById(Request $request)
    {

        $return = $this->service->searchProductById($request);

        return json_encode($return);
        die;
    } // searchProductById()

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->products()->get()
            ->count();

        $entity = $company->products()->with(['category', 'pizza_size'])->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $categories = $company->categories()->orderby('name', 'ASC')->get();

        return view('admin.products.index')->with([
            'total' => $totalQuery,
            'entity' => $entity,
            'categories' => $categories,
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
        $company = $user->company()->first();

        $categories = $company->categories()->orderby('name', 'ASC')->get();
        $ingredients = $company->ingredients()->orderby('name', 'ASC')->get();
        $pizza_sizes = $company->pizza_sizes()->orderby('name', 'ASC')->get();
        $borders = $company->borders()->orderby('name', 'ASC')->get();
        $pastas = $company->pastas()->orderby('name', 'ASC')->get();

        return view('admin.products.create')->with(compact('categories', 'ingredients', 'pizza_sizes', 'borders', 'pastas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStore $request)
    {
        $return = $this->service->store($request);
        return Redirect::route('products.index')->with('success', $return);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $category = $product->category()->first();
        $ingredients = $company->ingredients()->orderby('name', 'ASC')->get();
        $pizza_sizes = $company->pizza_sizes()->orderby('name', 'ASC')->get();
        $borders = $company->borders()->orderby('name', 'ASC')->get();
        $pastas = $company->pastas()->orderby('name', 'ASC')->get();

        // Ingredientes selecionados
        $ingredientsCheckeds = $product->ingredients_checked()->toArray();
        $additionalsCheckeds = $product->additionals_checked()->toArray();

        // Preços das pizzas
        $pizzaPrices = [];
        if ($product->category_id == 1) {
            $pizzaPrices = $product->prices();
        }

        return view('admin.products.show')->with(compact('pizzaPrices', 'additionalsCheckeds', 'ingredientsCheckeds', 'product', 'category', 'ingredients', 'pizza_sizes', 'borders', 'pastas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $categories = $company->categories()->orderby('name', 'ASC')->get();
        $ingredients = $company->ingredients()->orderby('name', 'ASC')->get();
        $pizza_sizes = $company->pizza_sizes()->orderby('name', 'ASC')->get();
        $borders = $company->borders()->orderby('name', 'ASC')->get();
        $pastas = $company->pastas()->orderby('name', 'ASC')->get();

        // Ingredientes selecionados
        $ingredientsCheckeds = $product->ingredients_checked()->toArray();
        $additionalsCheckeds = $product->additionals_checked()->toArray();

        // Preços das pizzas
        $pizzaPrices = [];
        if ($product->category_id == 1) {
            $pizzaPrices = $product->prices();
        }

        return view('admin.products.edit')->with(compact('pizzaPrices', 'additionalsCheckeds', 'ingredientsCheckeds', 'product', 'categories', 'ingredients', 'pizza_sizes', 'borders', 'pastas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdate $request, Product $product)
    {
        $return = $this->service->update($request, $product);
        return Redirect::route('products.index')->with('success', $return);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        if ($product) {

            $return = $this->service->destroy($product);
            return Redirect::route('products.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O produto informado não existe.'];
        return Redirect::route('products.index')->with('success', $session);
    }
}
