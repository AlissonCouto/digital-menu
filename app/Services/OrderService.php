<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Company;
use App\Models\OrderItem;
use App\Models\Client;
use App\Models\Address;
use App\Models\OrderItemProduct;
use App\Models\AdditionalIngredient;
use App\Models\IngredientRemoved;

use App\Jobs\OrderStatusUpdated;
use App\Http\Requests\AdminEditOrderRequest;

class OrderService
{

    public function upateStatus(Request $data, Order $order)
    {
        try {

            $return = DB::transaction(function () use ($data, $order) {

                $user = Auth::user();
                $company = $user->company()->first();

                $html = '';

                $status = $data->status;

                $order->status = $status;
                $order->save();

                $client = $order->client()->first();

                $orderStatus = new OrderStatus();
                $orderStatus->status = $status;
                $orderStatus->date = date('Y-m-d H:i:s');
                $orderStatus->order_id = $order->id;
                $orderStatus->save();

                //  enum('realized','inanalysis','inproduction','delivery')
                $inanalysis = Order::where([
                    ['status', '=', 'inanalysis'],
                    ['company_id', '=', $company->id]
                ])->get();

                $inproduction = Order::where([
                    ['status', '=', 'inproduction'],
                    ['company_id', '=', $company->id]
                ])->get();

                $delivery = Order::where([
                    ['status', '=', 'ready'],
                    ['company_id', '=', $company->id]
                ])->get();

                $html = view('admin.components.order-lanes')->with(compact(['inanalysis', 'inproduction', 'delivery']))->render();

                $orderStatuses = $order->statuses()->get();
                $htmlStatus = view('components.order-statuses')->with(compact('order', 'status', 'orderStatuses'))->render();

                OrderStatusUpdated::dispatch($client->id, $htmlStatus, $status, $order->id);

                return [
                    'success' => true,
                    'message' => 'Pedido atualizado com sucesso.',
                    'data' => $html
                ];
            });

            return $return;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao tentar atualizar pedido.',
                'dd' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]
            ];
        }
    } // upateStatus()

    public function update(AdminEditOrderRequest $data, Order $order)
    {
        $cart = $data->order;

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

        $order->payment = $formsPayment[$cart['formPayment']];
        $order->delivery_method = $deliveryMethod;
        $order->comments = $data['descriptions'];
        $order->street = $address->street;
        $order->number = $address->number;
        $order->neighborhood = $address->neighborhood;
        $order->reference = $address->reference;
        $order->city = $address->city()->first()->nome;
        $order->uf = $address->city()->first()->uf;
        $order->save();

        /* Apagando relações do pedido */
        $order_items_delete = $order->order_items();

        foreach ($order_items_delete->get() as $order_item) {
            $order_item_products = $order_item->order_item_products();

            foreach ($order_item_products->get() as $product) {
                $additional_ingredients = $product->additional_ingredients();
                $ingredient_removeds = $product->ingredients_removed();

                $additional_ingredients->delete();
                $ingredient_removeds->delete();
            }

            $order_item_products->delete();
        }

        $order_items_delete->delete();

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


        return [
            'success' => true,
            'message' => 'Pedido atualizado com sucesso.'
        ];
    } // update()

    public function destroy(Order $order)
    {

        try {
            $return = DB::transaction(function () use ($order) {
                /* 
                Rotina para apagar pedidos
                */

                /*
                    1 - additional_ingredients
                    2 - ingredient_removeds
                    3 - order_item_products
                    4 - order_statuses
                    5 - order_items
                    
                */

                /*
                    delete from `order_items` where `order_items`.`order_id` = 12 and `order_items`.`order_id` is not null)
                */

                $order_items = $order->order_items();

                foreach ($order_items->get() as $order_item) {
                    $order_item_products = $order_item->order_item_products();

                    foreach ($order_item_products->get() as $product) {
                        $additional_ingredients = $product->additional_ingredients();
                        $ingredient_removeds = $product->ingredients_removed();

                        $additional_ingredients->delete();
                        $ingredient_removeds->delete();
                    }

                    $order_item_products->delete();
                }

                $order->statuses()->delete();
                $order_items->delete();
                $order->delete();

                return [
                    'success' => true,
                    'message' => 'Pedido deletado com sucesso.',
                    'data' => []
                ];
            });

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
                'message' => 'Erro ao tentar apagar pedido.'
            ];
        }
    } // destroy()

}
