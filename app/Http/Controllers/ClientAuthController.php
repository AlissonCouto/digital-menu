<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

use App\Models\Client;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemProduct;
use App\Models\AdditionalIngredient;
use App\Models\IngredientRemoved;
use App\Models\OrderStatus;
use App\Models\Company;

use App\Jobs\OrderNotificationAdmin;

use App\Http\Requests\MenuCreateOrderRequest;

use App\Services\CouponService;


class ClientAuthController extends Controller
{

    private $couponService;

    public function __construct(CouponService $couponService)
    {

        $this->couponService = $couponService;
    } // __construct()

    public function login(Request $request)
    {

        return view('client.login');
    }

    public function orderStore(MenuCreateOrderRequest $request)
    {

        $logged = Auth::guard('client')->check();

        $cart = $request->input('cart');

        $phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $request->phone);
        $discounts = 0;
        $shipping = 0;

        $deliveryMethod = $request->address;

        $client = Client::where([
            ['phone', '=', $phone]
        ])->first();

        // Usuário deslogado
        if (!$logged) {
            /*
                - Se o cliente não existe;
                    - O sistema salva no banco de dados;
            */
            if (is_null($client)) {

                // Cadastrando o cliente
                $client = new Client();
                $client->name = $request->name;

                $slug = Str::slug($request->name);

                // Verificando se já existe empresa com o slug
                $slugExist = Client::where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();

                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }

                $client->slug = $slug;

                $client->phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $request->phone);
                $client->password = Hash::make($request->password);
                $client->points = 0;
                $client->company_id = 1;
                $client->save();

                // Salvando o Endereço
                if ($deliveryMethod == 'delivery') {
                    $address = new Address();
                    $address->street = $request->street;
                    $address->neighborhood = $request->neighborhood;
                    $address->reference = $request->reference;
                    $address->number = $request->number;
                    $address->client_id = $client->id;
                    $address->city_id = 1;
                    $address->company_id = 1;
                    $address->save();
                }
            } else {

                /*
                    - Se o cliente existe;
                        - O sistema atualiza o cliente já existente;
                */
                $client->name = $request->name;

                $slug = Str::slug($request->name);

                if ($slug != $client->slug) {
                    // Verificando se já existe empresa com o slug
                    $slugExist = Client::where([
                        ['slug', 'like', '%' . $slug . '%']
                    ])->get();

                    $qtdSlugs = count($slugExist);

                    if ($qtdSlugs >= 1) {
                        $slug .= "-" . $qtdSlugs;
                    }

                    $client->slug = $slug;
                }

                $client->phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $request->phone);
                $client->password = Hash::make($request->password);
                $client->save();

                $company = $client->company()->first();

                // Salvando o Endereço
                if ($deliveryMethod == 'delivery') {
                    $address = new Address();
                    $address->street = $request->street;
                    $address->neighborhood = $request->neighborhood;
                    $address->reference = $request->reference;
                    $address->number = $request->number;
                    $address->client_id = $client->id;
                    $address->city_id = $company->addresses()->first()->id;
                    $address->company_id = $company->id;
                    $address->save();
                }
            }
        } else {

            // Usuário logado

            // Alterando dados do cliente
            $user = Auth::guard('client')->user();

            $client = Client::find($user->id);

            $client->name = $request->name;

            $slug = Str::slug($request->name);

            if ($slug != $client->slug) {
                // Verificando se já existe empresa com o slug
                $slugExist = Client::where([
                    ['slug', 'like', '%' . $slug . '%']
                ])->get();

                $qtdSlugs = count($slugExist);

                if ($qtdSlugs >= 1) {
                    $slug .= "-" . $qtdSlugs;
                }

                $client->slug = $slug;
            }

            $client->phone = str_replace(['(', ')', '-', ' '], ['', '', '', ''], $client->phone);

            if ($request->password) {
                $client->password = Hash::make($request->password);
            }

            $client->save();

            // Pegando o endereço caso seja um id no $deliveryMethod
            if ($deliveryMethod != 'comeget' && $deliveryMethod != 'withdrawal' && is_numeric($deliveryMethod)) {

                $address = Address::find($deliveryMethod);

                $deliveryMethod = 'delivery';
            }
        } // if(!$logged)

        $company = $client->company()->first();

        // Salvando um novo pedido
        $formsPayment = [
            1 => 'pix',
            2 => 'cash',
            3 => 'credit',
            4 => 'debit',
        ];

        $order = new Order();
        $order->code = uniqid();
        $order->date = date('Y-m-d H:i:s');
        $order->origin = 'client';
        $order->payment = $formsPayment[$request->payments];
        $order->status = 'inanalysis';
        $order->delivery_method = $deliveryMethod;
        $order->comments = $request->descriptions;
        $order->street = $address->street ?? null;
        $order->number = $address->number ?? null;
        $order->neighborhood = $address->neighborhood ?? null;
        $order->reference = $address->reference ?? null;
        $order->city = isset($address) ? $address->city()->first()->nome : null;
        $order->uf = isset($address) ? $address->city()->first()->uf : null;
        $order->client_id = $client->id;
        $order->company_id = $company->id;
        $order->save();

        $subtotal = 0;

        if ($cart['items']) {
            foreach ($cart['items'] as $item) {

                $subtotal += $item['price'] * $item['quantity'];

                $orderItem = new OrderItem();
                $orderItem->name = $item['name'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->category = $item['category']['name'];
                $orderItem->unitary_value = $item['price'];
                $orderItem->total = $item['price'] * $item['quantity'];
                $orderItem->description = $item['observations'] ? $item['observations'] : null;
                $orderItem->order_id = $order->id;
                $orderItem->company_id = $company->id;

                // Borda e massa
                if (isset($item['border']['id'])) {
                    $orderItem->border_id = $item['border']['id'];
                }

                if (isset($item['pasta']['id'])) {
                    $orderItem->pasta_id = $item['pasta']['id'];
                }

                if ($item['category']['name'] == 'pizzas') {
                    $orderItem->pizza_size_id = $item['sizeId'];
                }

                $orderItem->save();

                // Verificando quantidade de produtos
                if ($item['category']['name'] == 'pizzas') {
                    if (count($item['products']) == 2) {

                        foreach ($item['products'] as $product) {
                            $orderItemProduct = new OrderItemProduct();
                            $orderItemProduct->order_item_id = $orderItem->id;
                            $orderItemProduct->product_id = $product['productId'];
                            $orderItemProduct->save();

                            // Adicionais e removidos
                            if ($product['additionals']) {
                                foreach ($product['additionals'] as $ingredient) {
                                    $additionalIngredient = new AdditionalIngredient();
                                    $additionalIngredient->order_item_product_id = $orderItemProduct->id;
                                    $additionalIngredient->ingredient_id = $ingredient['id'];
                                    $additionalIngredient->save();
                                }
                            }

                            if ($product['removedIngredients']) {
                                foreach ($product['removedIngredients'] as $ingredient) {
                                    $ingredientRemoved = new IngredientRemoved();
                                    $ingredientRemoved->order_item_product_id = $orderItemProduct->id;
                                    $ingredientRemoved->ingredient_id = $ingredient['id'];
                                    $ingredientRemoved->save();
                                }
                            }
                        } // foreach()

                    } else {
                        $orderItemProduct = new OrderItemProduct();
                        $orderItemProduct->order_item_id = $orderItem->id;
                        $orderItemProduct->product_id = $item['products']['productId'];
                        $orderItemProduct->save();

                        // Adicionais e removidos
                        if ($item['products']['additionals']) {
                            foreach ($item['products']['additionals'] as $ingredient) {
                                $additionalIngredient = new AdditionalIngredient();
                                $additionalIngredient->order_item_product_id = $orderItemProduct->id;
                                $additionalIngredient->ingredient_id = $ingredient['id'];
                                $additionalIngredient->save();
                            }
                        }

                        if ($item['products']['removedIngredients']) {
                            foreach ($item['products']['removedIngredients'] as $ingredient) {
                                $ingredientRemoved = new IngredientRemoved();
                                $ingredientRemoved->order_item_product_id = $orderItemProduct->id;
                                $ingredientRemoved->ingredient_id = $ingredient['id'];
                                $ingredientRemoved->save();
                            }
                        }
                    } // if() - Se tem 1 ou 2 produtos no item do pedido
                } else {
                    // Outras categorias de produtos

                    $orderItemProduct = new OrderItemProduct();
                    $orderItemProduct->order_item_id = $orderItem->id;
                    $orderItemProduct->product_id = $item['id'];
                    $orderItemProduct->save();
                }
            } // foreach() - Percorrendo itens do carrinho

        } // if() - Se tem itens no carrinho

        /* Cupom e entrega */

        if ($deliveryMethod == 'delivery') {
            $company = Company::find($company->id);
            $deliveryCharge = $company->delivery_charges()->first();

            if ($deliveryCharge) {
                $shipping = $deliveryCharge->value;
            }
        }

        /* Cupom */
        $validityCoupon = $this->couponService->validateCoupon($request->coupon, $shipping + $subtotal);

        if ($validityCoupon['success']) {
            $discounts = $validityCoupon['discount'];
        }

        $order->discount = $discounts;
        $order->shipping = $shipping;
        $order->subtotal = $subtotal;
        $order->total = $subtotal + $shipping - $discounts;
        $order->save();


        // Etapas do pedido
        $orderStatus = new OrderStatus();
        $orderStatus->status = 'realized';
        $orderStatus->date = $order->date;
        $orderStatus->notified = true;
        $orderStatus->order_id = $order->id;
        $orderStatus->save();

        $orderStatus = new OrderStatus();
        $orderStatus->status = 'inanalysis';
        $orderStatus->date = $order->date;
        $orderStatus->notified = false;
        $orderStatus->order_id = $order->id;
        $orderStatus->save();

        if (!auth('client')->check()) {
            Auth::guard('client')->login($client);
        }

        OrderNotificationAdmin::dispatch($company->id, $order);

        return Redirect::route('orders');
    } // orderStore()

    public function store(Request $request)
    {
        // Validações
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required',
        ]);

        // Limpar o formato do telefone
        $phone = str_replace(['(', ')', '-', ' '], '', $request->phone);

        // Tentar autenticar usando o telefone e a senha
        if (Auth::guard('client')->attempt(['phone' => $phone, 'password' => $request->password])) {
            // Autenticação bem-sucedida
            return redirect()->intended(route('orders'));
        }

        // Autenticação falhou
        throw ValidationException::withMessages([
            'phone' => __('auth.failed'),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clientes',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = new Client();
        $client->name = $request->name;

        $slug = Str::slug($request->name);

        // Verificando se ja existe empresa com o slug
        $slugExist = Client::where([
            ['slug', 'like', '%' . $slug . '%']
        ])->get();

        $qtdSlugs = count($slugExist);

        if ($qtdSlugs >= 1) {
            $slug .= "-" . $qtdSlugs;
        }

        $client->slug = $slug;

        $client->email = $request->email;
        $client->password = Hash::make($request->password);
        $client->save();

        Auth::guard('client')->login($client);

        return redirect()->route('client.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        /*$request->session()->invalidate();
        $request->session()->regenerateToken();*/

        return redirect()->route('client.login');
    }
}
