<?php

use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BorderOptionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardModalController;
use App\Http\Controllers\DeliveryChargeController;
use App\Http\Controllers\DeliveryDriveController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PastaOptionController;
use App\Http\Controllers\PizzaSizeController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\OrderNotification;
use App\Http\Controllers\AdminAuthController;

use App\Http\Middleware\RedirectIfAuthenticated;

Route::middleware([RedirectIfAuthenticated::class])->group(function () {

    Route::prefix('admin')->group(function () {
        // Outras rotas de login...
        Route::get('/logout', [AdminAuthController::class, 'logout'])
            ->name('admin.logout');

        Route::get('/dashboard', [PanelController::class, 'dashboard'])->name('dashboard');

        Route::get('/novo-pedido', [OrderController::class, 'create'])->name('order.new');
        Route::get('/editar-pedido/{order}', [OrderController::class, 'edit'])->name('order.edit');
        Route::put('/editar-pedido/{order}', [OrderController::class, 'update'])->name('order.update');

        /* CRIAÇÃO DE PEDIDOS BALCÃO */
        // Busca de clientes no painel de gestão de pedidos
        Route::get('/search-client', [ClientController::class, 'searchClient'])->name('client.search');

        // Busca de produtos no painel de gestão de pedidos
        Route::get('/search-products-list', [ProductController::class, 'searchProductsList'])->name('search.products.list');
        Route::get('/search-product-by-id', [ProductController::class, 'searchProductById'])->name('search.product.by.id');

        /* Rotas dos modais do painel de pedidos */
        Route::put('/dashboard-modal/client/update/{client}', [DashboardModalController::class, 'updateClient'])->name('dashboard.modal.client.update');
        Route::post('/dashboard-modal/address/store', [DashboardModalController::class, 'createAddress'])->name('dashboard.modal.address.store');

        /* Rotas da criação do pedido no painel do BALCÃO */
        Route::post('/order-create', [OrderController::class, 'store'])->name('admin.order.create');

        Route::get('/address/edit/{address}', [DashboardModalController::class, 'editAddress'])->name('dashboard.modal.address.edit');
        Route::put('/address/update/{address}', [DashboardModalController::class, 'updateAddress'])->name('dashboard.modal.address.update');

        Route::get('address/get-by-id/{address}', [DashboardModalController::class, 'getAddresById'])->name('dashboard.modal.address.get-by-id');

        Route::get('/address/delete/{address}', [DashboardModalController::class, 'deleteAddress'])->name('dashboard.modal.address.delete');
        /* CRIAÇÃO DE PEDIDOS BALCÃO */

        /* PRODUTOS */
        Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
        Route::post('/produtos/busca', [ProductController::class, 'search'])->name('products.consult');
        Route::get('/produtos/criar', [ProductController::class, 'create'])->name('products.create');
        Route::post('/produtos/criar', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produtos/editar/{product}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produtos/editar/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/produtos/deletar/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/produtos/{product}', [ProductController::class, 'show'])->name('products.show');
        /* PRODUTOS */

        /* INGREDIENTES */
        Route::get('/ingredientes', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::post('/ingredientes/busca', [IngredientController::class, 'search'])->name('ingredients.consult');
        Route::get('/ingredientes/criar', [IngredientController::class, 'create'])->name('ingredients.create');
        Route::post('/ingredientes/criar', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::get('/ingredientes/editar/{ingredient}', [IngredientController::class, 'edit'])->name('ingredients.edit');
        Route::put('/ingredientes/editar/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
        Route::get('/ingredientes/deletar/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        Route::get('/ingredientes/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
        /* INGREDIENTES */

        /* MASSAS */
        Route::get('/massas', [PastaOptionController::class, 'index'])->name('pastas.index');
        Route::post('/massas/busca', [PastaOptionController::class, 'search'])->name('pastas.consult');
        Route::get('/massas/criar', [PastaOptionController::class, 'create'])->name('pastas.create');
        Route::post('/massas/criar', [PastaOptionController::class, 'store'])->name('pastas.store');
        Route::get('/massas/editar/{pasta}', [PastaOptionController::class, 'edit'])->name('pastas.edit');
        Route::put('/massas/editar/{pasta}', [PastaOptionController::class, 'update'])->name('pastas.update');
        Route::get('/massas/deletar/{pasta}', [PastaOptionController::class, 'destroy'])->name('pastas.destroy');
        Route::get('/massas/{pasta}', [PastaOptionController::class, 'show'])->name('pastas.show');
        /* MASSAS */

        /* BORDAS */
        Route::get('/bordas', [BorderOptionController::class, 'index'])->name('borders.index');
        Route::post('/bordas/busca', [BorderOptionController::class, 'search'])->name('borders.consult');
        Route::get('/bordas/criar', [BorderOptionController::class, 'create'])->name('borders.create');
        Route::post('/bordas/criar', [BorderOptionController::class, 'store'])->name('borders.store');
        Route::get('/bordas/editar/{border}', [BorderOptionController::class, 'edit'])->name('borders.edit');
        Route::put('/bordas/editar/{border}', [BorderOptionController::class, 'update'])->name('borders.update');
        Route::get('/bordas/deletar/{border}', [BorderOptionController::class, 'destroy'])->name('borders.destroy');
        Route::get('/bordas/{border}', [BorderOptionController::class, 'show'])->name('borders.show');
        /* BORDAS */

        /* TAMANHOS */
        Route::get('/tamanhos', [PizzaSizeController::class, 'index'])->name('sizes.index');
        Route::post('/tamanhos/busca', [PizzaSizeController::class, 'search'])->name('sizes.consult');
        Route::get('/tamanhos/criar', [PizzaSizeController::class, 'create'])->name('sizes.create');
        Route::post('/tamanhos/criar', [PizzaSizeController::class, 'store'])->name('sizes.store');
        Route::get('/tamanhos/editar/{size}', [PizzaSizeController::class, 'edit'])->name('sizes.edit');
        Route::put('/tamanhos/editar/{size}', [PizzaSizeController::class, 'update'])->name('sizes.update');
        Route::get('/tamanhos/deletar/{size}', [PizzaSizeController::class, 'destroy'])->name('sizes.destroy');
        Route::get('/tamanhos/{size}', [PizzaSizeController::class, 'show'])->name('sizes.show');
        /* TAMANHOS */

        /* CATEGORIAS */
        Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categorias/busca', [CategoryController::class, 'search'])->name('categories.consult');
        Route::get('/categorias/criar', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categorias/criar', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categorias/editar/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categorias/editar/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/categorias/deletar/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/categorias/{category}', [CategoryController::class, 'show'])->name('categories.show');
        /* CATEGORIAS */

        /* TAXAS DE ENTREGA */
        Route::get('/taxas', [DeliveryChargeController::class, 'index'])->name('fees.index');
        Route::post('/taxas/busca', [DeliveryChargeController::class, 'search'])->name('fees.consult');
        Route::get('/taxas/criar', [DeliveryChargeController::class, 'create'])->name('fees.create');
        Route::post('/taxas/criar', [DeliveryChargeController::class, 'store'])->name('fees.store');
        Route::get('/taxas/editar/{fee}', [DeliveryChargeController::class, 'edit'])->name('fees.edit');
        Route::put('/taxas/editar/{fee}', [DeliveryChargeController::class, 'update'])->name('fees.update');
        Route::get('/taxas/deletar/{fee}', [DeliveryChargeController::class, 'destroy'])->name('fees.destroy');
        Route::get('/taxas/{fee}', [DeliveryChargeController::class, 'show'])->name('fees.show');
        /* TAXAS DE ENTREGA */

        /* ENTREGADORES */
        Route::get('/entregadores', [DeliveryDriveController::class, 'index'])->name('deliverydrivers.index');
        Route::post('/entregadores/busca', [DeliveryDriveController::class, 'search'])->name('deliverydrivers.consult');
        Route::get('/entregadores/criar', [DeliveryDriveController::class, 'create'])->name('deliverydrivers.create');
        Route::post('/entregadores/criar', [DeliveryDriveController::class, 'store'])->name('deliverydrivers.store');
        Route::get('/entregadores/editar/{deliveryman}', [DeliveryDriveController::class, 'edit'])->name('deliverydrivers.edit');
        Route::put('/entregadores/editar/{deliveryman}', [DeliveryDriveController::class, 'update'])->name('deliverydrivers.update');
        Route::get('/entregadores/deletar/{deliveryman}', [DeliveryDriveController::class, 'destroy'])->name('deliverydrivers.destroy');
        Route::get('/entregadores/{deliveryman}', [DeliveryDriveController::class, 'show'])->name('deliverydrivers.show');
        /* ENTREGADORES */

        /* FUNCIONÁRIOS */
        Route::get('/funcionarios', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/funcionarios/busca', [EmployeeController::class, 'search'])->name('employees.consult');
        Route::get('/funcionarios/criar', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/funcionarios/criar', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/funcionarios/editar/{employee}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/funcionarios/editar/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::get('/funcionarios/deletar/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('/funcionarios/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        /* FUNCIONÁRIOS */

        /* CUPONS */
        Route::get('/cupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/cupons/busca', [CouponController::class, 'search'])->name('coupons.consult');
        Route::get('/cupons/criar', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('/cupons/criar', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/cupons/editar/{coupon}', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/cupons/editar/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::get('/cupons/deletar/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::get('/cupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');
        /* CUPONS */

        /* CLIENTES */
        Route::get('/clientes', [ClientController::class, 'index'])->name('clients.index');
        Route::post('/clientes/busca', [ClientController::class, 'search'])->name('clients.consult');
        Route::get('/clientes/criar', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/clientes/criar', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/clientes/editar/{client}', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clientes/editar/{client}', [ClientController::class, 'updateAdmin'])->name('clients.update');
        Route::get('/clientes/deletar/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::get('/clientes/{client}', [ClientController::class, 'show'])->name('clients.show');

        Route::get('/clientes/{client}/enderecos', [ClientAddressController::class, 'index'])->name('clients.address.index');
        Route::post('/clientes/{client}/enderecos/busca', [ClientAddressController::class, 'search'])->name('clients.address.consult');
        Route::get('/clientes/{client}/enderecos/criar', [ClientAddressController::class, 'create'])->name('clients.address.create');
        Route::post('/clientes/{client}/enderecos/criar', [ClientAddressController::class, 'store'])->name('clients.address.store');
        Route::get('/clientes/{client}/enderecos/editar/{address}', [ClientAddressController::class, 'edit'])->name('clients.address.edit');
        Route::put('/clientes/{client}/enderecos/editar/{address}', [ClientAddressController::class, 'update'])->name('clients.address.update');
        Route::get('/clientes/{client}/enderecos/deletar/{address}', [ClientAddressController::class, 'destroy'])->name('clients.address.destroy');
        Route::get('/clientes/{client}/enderecos/{address}', [ClientAddressController::class, 'show'])->name('clients.address.show');
        /* CLIENTES */

        /* PEDIDOS */
        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/pedidos/busca', [OrderController::class, 'search'])->name('orders.consult');
        Route::get('/pedidos/criar', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/pedidos/criar', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/pedidos/editar/{order}', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/pedidos/editar/{order}', [OrderController::class, 'updateAdmin'])->name('orders.update');
        Route::get('/pedidos/deletar/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');

        // Atualização de status
        Route::get('/pedidos/editar-status/{order}', [OrderController::class, 'upateStatus'])->name('orders.status.update');
        // Atualização de status
        /* PEDIDOS */

        /* Relatórios */
        Route::get('/graphics', [PanelController::class, 'graphics'])->name('graphics.index');
        /* Relatórios */

        /* Notificações */
        Route::get('/balcao/notificacao', [OrderNotification::class, 'forAttendant'])->name('notifications.attendant');
        /* Notificações */
    }); // Grupo de rotas do administrador
});

/* Rotas do painel administrativo */

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

/* Rotas do painel administrativo */

/* Rotas do cardápio online */
Route::get('/', [MenuController::class, 'welcome'])->name('welcome');
Route::get('/produto/{product}', [MenuController::class, 'product'])->name('product');
Route::get('/pizzas/{pizza}', [MenuController::class, 'pizzas'])->name('pizzas');
Route::get('/carrinho', [MenuController::class, 'cart'])->name('cart');
Route::get('/finalizar-compra', [MenuController::class, 'checkout'])->name('checkout');

Route::get('/endereco', [MenuController::class, 'address'])->name('address');
Route::post('/endereco', [AddressController::class, 'store'])->name('client.address.store');

Route::get('/pedidos', [MenuController::class, 'orders'])->name('orders');
Route::get('/pedido/{order}', [MenuController::class, 'order'])->name('order');
Route::get('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');



/* Rotas da criação de pedidos */
Route::get('/login', [ClientAuthController::class, 'login'])->name('client.login');
Route::post('/login', [ClientAuthController::class, 'store'])->name('client.login.store');
Route::post('/order-create', [ClientAuthController::class, 'orderStore'])->name('client.order.create');
Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('client.applyCoupon');
/* Rotas da criação de pedidos */

/* Rotas do cardápio online */

/* Rotas do painel de gestão de pedidos */
/*Route::get('/dashboard', [PanelController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/novo-pedido', [OrderController::class, 'create'])->middleware(['auth', 'verified'])->name('order.new');
Route::get('/editar-pedido/{order}', [OrderController::class, 'edit'])->middleware(['auth', 'verified'])->name('order.edit');
Route::put('/editar-pedido/{order}', [OrderController::class, 'update'])->middleware(['auth', 'verified'])->name('order.update');*/

/* Notificações */
Route::get('/cliente/notificacao/{client}', [OrderNotification::class, 'forClient'])->name('notifications.client');
/* Notificações */
/* Rotas do painel de gestão de pedidos */

/* Rotas para validações */
//Route::get('/phone-unique', [ValidateController::class, 'clientPhoneUnique'])->name('validate.client.phone.unique');
/* Rotas para validações */

/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';*/
