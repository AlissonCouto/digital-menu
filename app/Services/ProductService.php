<?php

namespace App\Services;

use App\Models\PizzaSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\BorderOption;
use App\Models\PastaOption;
use App\Models\ProductIngredient;
use App\Models\ProductAdditional;
use App\Models\PricePizzaSize;

use App\Http\Requests\ProductStore;
use App\Http\Requests\ProductUpdate;

use App\Services\StorageService;


class ProductService
{

    private $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function store(ProductStore $data)
    {
        try {

            $return = DB::transaction(function () use ($data) {
                $user = Auth::user();
                $company = $user->company()->first();

                $product = new Product();
                $product->name = $data->name;

                $slug = Str::slug($data->name);
                // Verificando se ja existe empresa com o slug
                $slugExist = $company->products()->where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();
                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }
                $product->slug = $slug;

                $product->description = $data->description;
                $product->price = number_format($data->price, 2, '.', '');
                $product->category_id = $data->category_id;

                /* Operações de upload da imagem */
                if ($data->hasFile('file')) {
                    $file = $data['file'];
                    $upload = $this->storageService->upload($file, 'products');

                    if ($upload) {
                        $product->img = $upload;
                    }
                }
                /* Operações de upload da imagem */

                $product->menu = $data->menu;
                $product->status = $data->status;
                $product->company_id = $company->id;
                $product->save();

                /* Salvando ingredientes */
                $ingredients = $data->ingredients;

                foreach ($ingredients as $ingredient) {
                    $productIngredient = new ProductIngredient();
                    $productIngredient->product_id = $product->id;
                    $productIngredient->ingredient_id = $ingredient;
                    $productIngredient->save();
                }
                /* Salvando ingredientes */

                if ($product->category_id == 1) {

                    /* Salvando adicionais */
                    if ($data->additionals) {
                        foreach ($data->additionals as $additional) {
                            $productAdditional = new ProductAdditional();
                            $productAdditional->product_id = $product->id;
                            $productAdditional->ingredient_id = $additional;
                            $productAdditional->save();
                        }
                    }
                    /* Salvando adicionais */

                    /* Salvando preços dos tamanhos */
                    foreach ($data->pizza_size as $k => $value) {
                        $price = new PricePizzaSize();
                        $price->pizza_size_id = $k;
                        $price->product_id = $product->id;
                        $price->price = number_format($value, 2, '.', '');
                        $price->save();
                    }
                    /* Salvando preços dos tamanhos */
                } // if() - Pizza

                return [
                    'success' => true,
                    'message' => 'Produto cadastrado com sucesso!',
                    'data' => $product
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar produto!',
                'data' => []
            ];
        }
    } // store()

    public function update(ProductUpdate $data, Product $product)
    {

        try {

            $return = DB::transaction(function () use ($data, $product) {
                $user = Auth::user();
                $company = $user->company()->first();

                $product->name = $data->name;

                $slug = Str::slug($data->name);

                if ($product->slug != $slug) {
                    // Verificando se ja existe empresa com o slug
                    $slugExist = $company->products()->where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();
                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }
                    $product->slug = $slug;
                }


                $product->description = $data->description;
                $product->price = number_format($data->price, 2, '.', '');
                $product->category_id = $data->category_id;

                /* Operações de upload da imagem */
                if ($data->hasFile('file')) {

                    // Apagando imagens atuais
                    Storage::disk('public')->delete('products/' . $data->old_file);
                    Storage::disk('public')->delete('products/crops/' . $data->old_file);

                    $file = $data['file'];
                    $upload = $this->storageService->upload($file, 'products');

                    if ($upload) {
                        $product->img = $upload;
                    }
                }
                /* Operações de upload da imagem */

                $product->menu = $data->menu;
                $product->status = $data->status;
                $product->company_id = $company->id;
                $product->save();

                /* Salvando ingredientes */
                ProductIngredient::where('product_id', $product->id)->delete();
                $ingredients = $data->ingredients;

                foreach ($ingredients as $ingredient) {
                    $productIngredient = new ProductIngredient();
                    $productIngredient->product_id = $product->id;
                    $productIngredient->ingredient_id = $ingredient;
                    $productIngredient->save();
                }
                /* Salvando ingredientes */

                if ($product->category_id == 1) {

                    $additionalsSaved = ProductAdditional::where('product_id', $product->id);

                    if ($additionalsSaved->count() > 0) {
                        $additionalsSaved->delete();
                    }

                    /* Salvando adicionais */
                    if ($data->additionals) {
                        foreach ($data->additionals as $additional) {
                            $productAdditional = new ProductAdditional();
                            $productAdditional->product_id = $product->id;
                            $productAdditional->ingredient_id = $additional;
                            $productAdditional->save();
                        }
                    }
                    /* Salvando adicionais */

                    $pricesSaved = PricePizzaSize::where('product_id', $product->id);

                    if ($pricesSaved->count() > 0) {
                        $pricesSaved->delete();
                    }

                    /* Salvando preços dos tamanhos */
                    foreach ($data->pizza_size as $k => $value) {
                        if (str_replace(['.', ','], ['', '.'], $value) > 0) {
                            $price = new PricePizzaSize();
                            $price->pizza_size_id = $k;
                            $price->product_id = $product->id;
                            $price->price = number_format(str_replace(['.', ','], ['', '.'], $value), 2, '.', '');
                            $price->save();
                        }
                    }
                    /* Salvando preços dos tamanhos */
                } // if() - Pizza

                return [
                    'success' => true,
                    'message' => 'Produto editado com sucesso!',
                    'data' => $product
                ];
            });

            return $return;
        } catch (\Exception $e) {
            //dd('AQUI', $e->getFile() . '<br>' . $e->getLine() . '<br>' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao editar produto!',
                'data' => []
            ];
        }
    } // update()

    public function searchProductsList(Request $data)
    {

        try {

            $return = [];

            // Pegando html dos modais
            $htmlProducts = '';

            $search = $data->search;

            $products = Product::where([
                ['name', 'like', '%' . $search . '%']
            ])->orWhere([
                ['id', 'like', '%' . $search . '%']
            ])->with('category')->get();

            if ($products->count() > 0) {

                $htmlProducts = view('components.products.search.list')->with(compact('products'))->render();

                $return = [
                    'success' => true,
                    'message' => 'Produtos retornados com sucesso!',
                    'data' => $products,
                    'htmlProducts' => $htmlProducts,
                ];
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Nenhum produto encontrado!',
                    'data' => []
                ];
            }

            return $return;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao pesquisar por produto.'
            ];
        }
    } // searchProductsList()

    public function searchProductById(Request $data)
    {
        try {

            $return = [
                'success' => true,
                'message' => 'Produtos retornados com sucesso!'
            ];

            // Pegando html dos modais
            $htmlProduct = '';
            $htmlModalOptions = '';

            $search = $data->search;
            $search = array_map('intval', $search);

            $products = Product::whereIn('id', $search)->with(['ingredients', 'additionals'])->get();

            if ($products->count() > 0) {

                $category = $products[0]->category()->first();

                if (count($search) == 2) {
                    $categoryFlavor2 = $products[1]->category()->first();

                    if ($categoryFlavor2->id != 1) {
                        return [
                            'success' => false,
                            'message' => 'O segundo sabor deve ser uma pizza!',
                            'data' => []
                        ];
                    }
                }

                // Alternar entre pizzas e lanches normais
                if ($category->id == 1) {
                    $sizes = $products[0]->pizza_size()->get();

                    foreach ($products as $p) {
                        $p->prices = $p->prices();
                    }

                    $totalValueItem = 0;
                    if ($products->count() == 1) {
                        $totalValueItem = collect($products[0]->prices)->first()['price'];
                    } else if ($products->count() == 2) {
                        $price1 = collect($products[0]->prices)->first()['price'];
                        $price2 = collect($products[1]->prices)->first()['price'];
                        $totalValueItem = $price1 > $price1 ? $price1 : $price2;
                    } else {
                        $totalValueItem = 0;
                    }

                    $borders = BorderOption::get();
                    $pastas = PastaOption::get();

                    $htmlModalOptions = view('components.modal-admin.modal-options')->with(compact('borders', 'pastas'))->render();

                    $htmlProduct = view('components.products.customizable-items.body')->with(compact('products', 'sizes', 'totalValueItem'))->render();

                    $return['htmlProduct'] = $htmlProduct;
                    $return['htmlModalOptions'] = $htmlModalOptions;
                }

                $return['category'] = $category->slug;
                $return['data'] = $products;
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Nenhum produto encontrado!',
                    'data' => []
                ];
            }

            return $return;
        } catch (\Exception $e) {

            /*$error = [
                'File: ' => $e->getFile(),
                'Line: ' => $e->getLine(),
                'Message: ' => $e->getMessage()
            ];
            dd($error);*/

            return [
                'success' => false,
                'message' => 'Erro ao pesquisar por produto.'
            ];
        }
    } // searchProductById()

    public function destroy(Product $product)
    {

        try {

            $return = DB::transaction(function () use ($product) {
                /* 
                Rotina para apagar produtos
                */

                // Verificando se está associado a pedidos
                if ($product->order_items()->count() > 0) {
                    return [
                        'success' => false,
                        'message' => 'Não é possível apagar produtos associados a pedidos.',
                        'data' => []
                    ];
                }

                // * Apagar imagem do disco
                Storage::disk('public')->delete('products/' . $product->img);
                Storage::disk('public')->delete('products/crops/' . $product->img);

                // Apagar ingredientes
                ProductIngredient::where('product_id', $product->id)->delete();

                // PARA PIZZAS
                if ($product->category_id == 1) {
                    // * Apagar relação dos preços dos tamanhos
                    $pricesSaved = PricePizzaSize::where('product_id', $product->id);
                    if ($pricesSaved->count() > 0) {
                        $pricesSaved->delete();
                    }

                    // * Apagar relação dos adicionais
                    $additionalsSaved = ProductAdditional::where('product_id', $product->id);
                    if ($additionalsSaved->count() > 0) {
                        $additionalsSaved->delete();
                    }
                } // Se pizza

                $product->delete();

                return [
                    'success' => true,
                    'message' => 'Produto deletado com sucesso.',
                    'data' => []
                ];
            });

            return $return;
        } catch (\Exception $e) {

            $error = [
                'File: ' => $e->getFile(),
                'Line: ' => $e->getLine(),
                'Message: ' => $e->getMessage()
            ];
            dd($error);

            return [
                'success' => false,
                'message' => 'Erro ao tentar apagar produto.'
            ];
        }
    } // destroy()

}
