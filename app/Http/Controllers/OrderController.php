<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AdminCreateOrderRequest;
use App\Http\Requests\AdminEditOrderRequest;

use App\Models\OrderItem;
use App\Models\Company;
use App\Models\OrderItemProduct;
use App\Models\OrderStatus;
use App\Models\Address;
use App\Models\Client;
use App\Models\AdditionalIngredient;
use App\Models\IngredientRemoved;

use App\Services\OrderService;

use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{

    private $status;
    private $service;

    public function __construct(OrderService $orderService)
    {

        $this->status = [
            'realized' => 'Realizado',
            'inanalysis' => 'Em análise',
            'inproduction' => 'Em Produção',
            'ready' => 'Pronto',
            'closed' => 'Concluído',
            'rejected' => 'Rejeitado',
            'canceled' => 'Cancelado'
        ];

        $this->service = $orderService;;
    } // __construct()

    public function search(Request $request)
    {

        $search = $request->search;
        $search = $request->search ? $request->search : '';
        $status = $request->category_id ?? null;
        //dd($request->query());
        $user = Auth::user();
        $company = $user->company()->first();


        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        if ($status) {

            $rawQuery = $company->orders()->where('status', '=', $status)
                ->where(function (Builder $query) use ($search) {
                    $query->where('code', 'like', '%' . $search . '%')
                        ->orWhereHas('client', function (Builder $query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
        } else {
            $rawQuery = $company->orders()->where('code', 'like', '%' . $search . '%')
                ->orWhereHas('client', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }



        $entity = $rawQuery->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $totalQuery = $rawQuery->count();

        $html = view('admin.orders.body-table')->with('entity', $entity)->render();

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

    public function upateStatus(Request $request, Order $order)
    {
        if ($order) {
            return $this->service->upateStatus($request, $order);
        }

        return  ['status' => false, 'message' => 'O pedido informado não existe.'];
    } // upateStatus()

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $perPage = 10; // Número de itens por página
        $page = $request->get('page', 1); // Pega o número da página da requisição, padrão é 1

        $totalQuery = $company->orders()->with('client')->get()
            ->count();

        $entity = $company->orders()->with('client')->orderby('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return view('admin.orders.index')->with([
            'total' => $totalQuery,
            'entity' => $entity,
            'status' => $this->status,
            'perPage' => $perPage,
            'currentPage' => $entity->currentPage(),
            'lastPage' => $entity->lastPage(),
            'from' => $entity->firstItem(),
            'to' => $entity->lastItem(),
        ]);
    } // index()

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        $company = $user->company()->first();

        $client = $order->client()->first();
        $address = $client->addresses()->first();
        $status = $this->status;
        $orderStatuses = $order->statuses()->get();

        foreach ($order->order_items()->get() as &$order_item) {
            $order_item->products_items = $order_item->order_item_products()->get();

            foreach ($order_item->products_items as &$order_item_product) {
                $order_item_product->addditionals = $order_item_product->additional_ingredients()->with('ingredient')->get();
                $order_item_product->removeds = $order_item_product->ingredients_removed()->with('ingredient')->get();
            }
        }

        return view('admin.orders.show')->with(compact('order', 'status', 'client', 'address', 'orderStatuses'));
    } // show()

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $deliveryValue = 0;

        $company = Company::find(1);
        $deliveryCharge = $company->delivery_charges()->first();

        if ($deliveryCharge) {
            $deliveryValue = $deliveryCharge->value;
        }

        return view('admin.order.create')->with(compact('deliveryValue'));
    } // create()

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminCreateOrderRequest $request)
    {

        $cart = $request->input('order');

        $user = Auth::user();

        $company = $user->company()->first();

        // Salvando um novo pedido
        $deliveryMethod = $cart['deliveryMethod'];
        $formsPayment = [
            'pix' => 'pix',
            'cash' => 'cash',
            'credit-card' => 'credit',
            'debit-card' => 'debit',
        ];


        $client = Client::find($cart['client']['id']);
        $address = Address::find($cart['address']['id']);

        $order = new Order();
        $order->code = uniqid();
        $order->date = date('Y-m-d H:i:s');
        $order->origin = 'attendant';
        $order->payment = $formsPayment[$cart['formPayment']];
        $order->status = 'inanalysis';
        $order->delivery_method = $deliveryMethod;
        $order->comments = $request['descriptions'];
        $order->street = $address->street;
        $order->number = $address->number;
        $order->neighborhood = $address->neighborhood;
        $order->reference = $address->reference;
        $order->city = $address->city()->first()->nome;
        $order->uf = $address->city()->first()->uf;
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
                $orderItem->category = $item['category'];
                $orderItem->unitary_value = $item['price'];
                $orderItem->total = $item['price'] * $item['quantity'];
                $orderItem->description = isset($item['observations']) ? $item['observations'] : null;
                $orderItem->order_id = $order->id;
                $orderItem->company_id = $company->id;

                // Borda e massa
                if (isset($item['border']['id'])) {
                    $orderItem->border_id = $item['border']['id'];
                }

                if (isset($item['pasta']['id'])) {
                    $orderItem->pasta_id = $item['pasta']['id'];
                }

                if ($item['category'] == 'pizzas') {
                    $orderItem->pizza_size_id = $item['sizeId'];
                }

                $orderItem->save();

                // Verificando quantidade de produtos
                //if (isset($item['products']) && gettype($item['products']) == 'array') {
                if ($item['category'] == 'pizzas') {

                    // Duas pizzas
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
                        // Uma pizza
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
                    }
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

        $order->discount = $cart['discounts'];
        $order->shipping = $shipping;
        $order->subtotal = $subtotal;
        $order->total = $subtotal + $shipping - $cart['discounts'];
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
        $orderStatus->notified = true;
        $orderStatus->order_id = $order->id;
        $orderStatus->save();

        return Redirect::route('dashboard');
    } // store()


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $deliveryValue = 0;

        $company = Company::find(1);
        $deliveryCharge = $company->delivery_charges()->first();

        if ($deliveryCharge) {
            $deliveryValue = $deliveryCharge->value;
        }

        $client = $order->client()->first();

        $addresses = $client->addresses()->orderby('id', 'DESC')->get();
        $orders = $client->orders()->orderby('date', 'DESC')->get();

        $firstRequest = $orders->last();
        $lastRequest = $orders->first();
        $total = $orders->sum('total');

        // Inicialização do carrinho
        $address = $client->addresses()->where([
            ['street', '=', $order->street],
            ['number', '=', $order->number],
            ['neighborhood', '=', $order->neighborhood],
            ['reference', '=', $order->reference]
        ]);

        if ($address->count() > 0) {
            $address = $address->first()->toArray();
        } else {

            if (
                !is_null($order->street) &&
                !is_null($order->number) &&
                !is_null($order->neighborhood)
            ) {
                $address = [
                    'street' => $order->street,
                    'number' => $order->number,
                    'neighborhood' => $order->neighborhood,
                    'reference' => $order->reference,
                ];
            }
        }

        $items = [];

        $listItems = $order->order_items()->get();

        foreach ($listItems as $item) {

            if ($item->size()->count() > 0) {
                $size = $item->size()->first()->toArray();
                $products = $item->products()->get();

                switch ($size['slug']) {
                    case 'broto':
                        $letterSize = 'B';
                        break;

                    case 'pequena':
                        $letterSize = 'P';
                        break;

                    case 'media':
                        $letterSize = 'M';
                        break;
                    default:
                        $letterSize = 'G';
                }

                $itemId = implode('+', $products->pluck('id')->toArray());

                $border = $item->border();

                if ($border->count() > 0) {
                    $border = $border->select('id', 'name', 'price')->first()->toArray();
                } else {
                    $border = [];
                }

                $pasta = $item->pasta();

                if ($pasta->count() > 0) {
                    $pasta = $pasta->select('id', 'name', 'price')->first()->toArray();
                } else {
                    $pasta = [];
                }

                $productsList = [];

                $order_item_products = $item->order_item_products()->get();

                foreach ($order_item_products as $product_item) {
                    $product = $product_item->product()->first();

                    $prices = $product->prices();

                    $additional_ingredients = $product_item->additional_ingredients();
                    $additionals = [];

                    if ($additional_ingredients->count() > 0) {
                        foreach ($additional_ingredients->get() as $additional) {
                            $additionals[] = $additional->ingredient()->select('id', 'name')->first()->toArray();
                        }
                    }

                    $ingredients_removed = $product_item->ingredients_removed();
                    $removedIngredients = [];

                    if ($ingredients_removed->count() > 0) {
                        foreach ($ingredients_removed->get() as $removed) {
                            $removedIngredients[] = $removed->ingredient()->select('id', 'name')->first()->toArray();
                        }
                    }

                    $productsList[] = [
                        'productId' => $product->id,
                        'name' => $product->name,
                        'price' => $prices[$size['id']]['price'],
                        'additionals' => $additionals,
                        'removedIngredients' => $removedIngredients
                    ];
                }

                $items[] = [
                    'id' => $itemId . '+' . $letterSize,
                    'name' => $item['name'],
                    'size' => $letterSize,
                    'sizeId' => $size['id'],
                    'border' => $border,
                    'pasta' => $pasta,
                    'price' => $item['unitary_value'],
                    'quantity' => $item['quantity'],
                    'category' => $item['category'],
                    'products' => $productsList
                ];
            } else {

                $product_item = $item->order_item_products()->first();
                $product = $product_item->product()->first();

                $items[] = [
                    'id' => $product->id,
                    'name' => $item['name'],
                    'price' => $item['unitary_value'],
                    'quantity' => $item['quantity'],
                    'category' => $item['category'],
                ];
            }
        }

        $formsPayment = [
            'pix' => 'pix',
            'cash' => 'cash',
            'credit' => 'credit-card',
            'debit' => 'debit-card',
        ];

        $cart = [
            'client' => $client->select('id', 'name', 'phone')->first()->toArray(),
            'address' => $address,
            'items' => $items,
            'comments_deliveryman' => $order->comments_deliveryman ?? '',
            'comments' => $order->comments ?? '',
            'formPayment' => $formsPayment[$order->payment],
            'deliveryMethod' => $order->delivery_method,
            'discounts' => $order->discount ?? 0,
            'shipping' => $order->shipping ?? 0,
            'subtotal' => $order->subtotal,
            'total' => $order->total
        ];

        return view('admin.order.edit')->with(compact('order', 'deliveryValue', 'orders', 'addresses', 'client', 'firstRequest', 'lastRequest', 'total', 'cart'));
    } // edit()

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminEditOrderRequest $request, Order $order)
    {
        if ($order) {
            $return = $this->service->update($request, $order);
            return Redirect::route('orders.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O pedido informado não existe.'];
        return Redirect::route('orders.index')->with('success', $session);
    } // update()

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order) {

            $return = $this->service->destroy($order);
            return Redirect::route('orders.index')->with('success', $return);
        }

        $session = ['status' => false, 'message' => 'O pedido informado não existe.'];
        return Redirect::route('orders.index')->with('success', $session);
    } // destroy()
}
