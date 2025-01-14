<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Models\Client;
use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\PricePizzaSize;
use App\Models\PizzaSize;
use App\Models\BorderOption;
use App\Models\PastaOption;
use App\Models\Order;
use App\Models\Company;

class MenuController extends Controller
{

    private $categories;
    private $status;
    private $user;

    public function __construct()
    {

        $categories = Category::get();

        $this->status = [
            'realized' => 'Realizado',
            'inanalysis' => 'Em análise',
            'inproduction' => 'Em Produção',
            'ready' => 'Pronto',
            'closed' => 'Concluído',
            'rejected' => 'Rejeitado',
            'canceled' => 'Cancelado'
        ];

        $this->user = Auth::guard('client')->user();

        $deliveryValue = 0;

        $company = Company::find(1);
        $deliveryCharge = $company->delivery_charges()->first();

        if ($deliveryCharge) {
            $deliveryValue = $deliveryCharge->value;

            View::share('deliveryValue', $deliveryValue);
        }

        View::share('categories', $categories);
    } // __construct()

    public function welcome()
    {
        return view('welcome');
    } // welcome()

    public function product(Request $request, $slug)
    {

        $product = Product::where([
            ['slug', '=', $slug]
        ])->first();

        $category = $product->category()->first();
        $compact = ['product', 'category'];

        if ($product) {

            if ($category->slug == 'pizzas') {
                $flavors = Product::where([
                    ['slug', '!=', $slug],
                    ['category_id', '=', $category->id]
                ])->get();

                $borders = BorderOption::get();
                $pastas = PastaOption::get();

                $ingredients = $product->ingredients()->get();
                $additionals = $product->additionals()->get();

                $compact[] = 'flavors';
                $compact[] = 'borders';
                $compact[] = 'pastas';
                $compact[] = 'ingredients';
                $compact[] = 'additionals';
            }

            return view('product')->with(compact(...$compact));
        }

        return view('welcome');
    } // product()

    public function pizzas(Request $request, $slug)
    {

        $size = PizzaSize::where([
            ['slug', '=', $slug]
        ])->first();

        $category = Category::find(1);
        $compact = ['size', 'category'];

        if ($size) {
            $flavors = $size->products()->get();

            if ($flavors) {

                foreach ($flavors as $prod) {
                    $pizzaSize = PricePizzaSize::where([
                        ['product_id', '=', $prod->id],
                        ['pizza_size_id', '=', $size->id]
                    ])->first();

                    $prod->price = $pizzaSize->price;
                }
            }

            $borders = BorderOption::get();
            $pastas = PastaOption::get();

            //$ingredients = Ingredient::get();
            //$additionals = Ingredient::get();

            $compact[] = 'flavors';
            $compact[] = 'borders';
            $compact[] = 'pastas';
            //$compact[] = 'ingredients';
            //$compact[] = 'additionals';

            return view('product')->with(compact(...$compact));
        }

        return view('welcome');
    } // pizzas()

    public function cart(Request $request)
    {
        return view('cart');
    } // cart()

    public function checkout(Request $request)
    {
        return view('checkout');
    } // checkout()

    public function address(Request $request)
    {
        return view('address');
    } // address()

    public function orders(Request $request)
    {

        if ($this->user) {
            $client = Client::where([
                ['id', '=', $this->user->id]
            ])->first();

            $orders = $client->orders()->get();

            $status = $this->status;

            return view('orders')->with(compact('client', 'orders', 'status'));
        }

        return view('client.login');
    } // orders()

    public function order(Request $request, Order $order)
    {

        if ($this->user) {

            if ($order) {
                $client = Client::where([
                    ['id', '=', $this->user->id]
                ])->first();

                $address = $client->addresses()->first();

                $status = $this->status;

                $orderStatuses = $order->statuses()->get();

                return view('order')->with(compact('order', 'status', 'client', 'address', 'orderStatuses'));
            }
        }

        return view('client.login');
    } // order()
}
