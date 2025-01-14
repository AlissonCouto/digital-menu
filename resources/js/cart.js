jQuery(function($){

    phoneMask();

    // Aplica a máscara em todos os campos que já possuem um valor no carregamento da página
    $('.phoneMask').each(function() {
        var $this = $(this);
        if ($this.val()) {
            $this.trigger('blur'); // Dispara o evento de "blur" para aplicar a máscara no valor já presente
        }
    });

    /* Salvando pedidos */
        $('#clientOrderCreate').on('submit', function(e){
            e.preventDefault();

            //let $errors = validationCartForm();
            let $errors = [];

            let rules;

            // Dados pessoais
            let iName = $('#name').val();
            let iWhatsapp = $('#whatsapp').val();
            let iPassword = $('#password').val();
            let iStreet = $('#street').val();
            let iNumber = $('#number').val();
            let iNighborhood = $('#neighborhood').val();
            let iReference = $('#reference').val();

            const data = {
                name: iName,
                whatsapp: iWhatsapp,
                password: iPassword,
                street: iStreet,
                number: iNumber,
                neighborhood: iNighborhood,
                reference: iReference,
            };

            rules = {
                name: 'required|string|min:3|max:255',
                whatsapp: 'required|string|min:15|max:16',
            };

            // Dados de endereço - Se deslogado
            if($('.form-checkout #street').length > 0){
                rules.password = 'required|strong_password';

                if($('input[name="address"]').val() == 'delivery'){
                    rules.street = 'required|string|min:5';
                    rules.number = 'required|integer';
                    rules.neighborhood = 'required|string|min:5';
                    rules.reference = 'nullable|string|min:5';
                }
            }else{
                rules.whatsapp = 'required|string|min:15|max:16';
            }

            let $errorsForm = validateData(data, rules);

            $('.form-floating input').removeClass('is-invalid');
            $('.field-error').html('');

            if (Object.keys($errorsForm).length > 0) {
                $.each($errorsForm, function (index, value) {
                    $('.form-floating #' + index).addClass('is-invalid');
                    $('#' + index).closest('.form-floating').next('.field-error').html(value);
                });
            }

            if($errors.length > 0){

                // Com erros
                var $htmlMessage = '<ul class="list-errors">';

                $errors.forEach(msg => {
                    $htmlMessage += `<li>${msg}<li>`;
                });

                $htmlMessage += '</ul>';

                errorCartForm($htmlMessage);

            }else{

                let $cart = getCart();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'cart',
                    value: JSON.stringify($cart)
                }).appendTo('#clientOrderCreate');

                console.log('CARRINHO ', $cart);

                this.submit();

            }

        });
    /* Salvando pedidos */

    /* Inicializando o valor da entrega no refresh da página de checkout */
    updateShippingValue('delivery');

    if($('meta[name="uri"]').attr('content') == 'finalizar-compra'){
        let $op = $('input[name="address"]').val();
        updateShippingValue($op);
    }

    /* Inicializando o valor da entrega no refresh da página de checkout */

    /* Atualizando o valor de entrega */
        $('input[name="address"]').on('change', function(e){
            
            let $op = $(this).val();
            updateShippingValue($op);
            
        });

        function updateShippingValue($op){

            let $cart = getCart();
            let $deliveryValue = getShippingValue();
            
            if($op == 'delivery'){
                $cart.shipping = $deliveryValue;
            }else{
                $cart.shipping = 0;
            }

            saveCart($cart);
            updateCart();
        } // updateShippingValue()
    /* Atualizando o valor de entrega */

    /* Inicializando carrinho */
    updateCart();

    $('body').on('input', '#observations', function(){
        let $total = $(this).val().length;

        if($total <= 200){
            $(this).css('border', '1px solid #808080');
            $('.observations .caracteres').html($total + '/200');
        }else{
            $(this).css('border', '1px solid #fb1432');
            $(this).val($(this).val().substring(0, 200));
        }
    });

    $('body').on('click', '.add-comments', function(e){
        
        let id = $(this).closest('.item').data('item-id');

        openModalComments(id);
    });

    $('body').on('click', '#modal-comments .btn-modal', function(e){
        
        let id = $(this).closest('#modal-comments').attr('data-id');
        saveComments(id);
    });

    $('body').on('click', '#modal-comments .btn-close', function(){
        closeModalComments();
    });

    $('.overlayer').on('click', function(e){
        if(e.target == this){
            closeModalComments();
        }
    });
    
    /* Abre e fecha carrinho */
    $('.open-cart, .close-cart').on('click', function () {
        cartStatus();
    });
    /* Abre e fecha carrinho */

    /* Limpa carrinho */
    $('.clear-cart').on('click', function(){
        clearCart();
        updateCart();
    });

    /* Adicionando ao carrinho */
    $('#addToCart').on('click', function(e){
        addToCart();
    });
    /* Adicionando ao carrinho */

    /* Adicionar ou Remover 1 */
    $('body').on('click', '.quantity-add, .quantity-remove', function(){
        let op = $(this).data('op');
        let id = $(this).closest('.item').data('item-id');

        updateQuantity(id, op, 1);
    });

    /* Pegando a quantidade */
    $('body').on('click', '.single-product .quantity-footer .add, .single-product .quantity-footer .rem', function(){
        let op = $(this).data('op');
        let qtd = parseInt($('.single-product .quantity-footer .total').text());

        if(op == 'add'){
            if(qtd >= 1){
                qtd += 1;
            }
        }else{
            if(qtd > 1){
                qtd -= 1;
            }
        }

        $('.single-product .quantity-footer .total').html(qtd);

        /* Mudando o preço no botão */
        var currentCategory = $('#categoryName').val();
        if(currentCategory == 'pizzas'){
            
            let productsList = $('input[name="pizza-checkbox"]:checked');
            var biggestPrice = 0;

            productsList.each(function(){
                if(biggestPrice < $(this).data('price')){
                    biggestPrice = $(this).data('price');
                }                       
            });

        }else{
            var biggestPrice = $('.single-product .meta-infos .price').data('price');
        }
        
        let $borderPrice = parseFloat($('.borders-pastas input[name="border"]:checked').data('price') || 0);
        let $pastaPrice = parseFloat($('.borders-pastas input[name="pasta"]:checked').data('price') || 0);
        
        $('.single-product #addToCart .value').html(formatPrice((parseFloat(biggestPrice) + $borderPrice + $pastaPrice) * qtd));
        /* Mudando o preço no botão */
        
    });
    
    $('input[name="pizza-checkbox"]').on('change', function(){

        let qtd = parseInt($('.single-product .quantity-footer .total').text());

        let productsList = $('input[name="pizza-checkbox"]:checked');
        var biggestPrice = 0;

        productsList.each(function(){
            if(biggestPrice < $(this).data('price')){
                biggestPrice = $(this).data('price');
            }                       
        });

        let $borderPrice = parseFloat($('.borders-pastas input[name="border"]:checked').data('price') || 0);
        let $pastaPrice = parseFloat($('.borders-pastas input[name="pasta"]:checked').data('price') || 0);

        $('.single-product #addToCart .value').html(formatPrice((parseFloat(biggestPrice) + $borderPrice + $pastaPrice) * qtd));

    });

    /* Atualizando valores na tela através do item ao mudar opções de borda e massa */
    $('.borders-pastas input[name="border"]').on('change', function(e){

        let qtd = parseInt($('.single-product .quantity-footer .total').text());

        let productsList = $('input[name="pizza-checkbox"]:checked');
        var biggestPrice = 0;

        productsList.each(function(){
            if(biggestPrice < $(this).data('price')){
                biggestPrice = $(this).data('price');
            }                       
        });

        let $borderPrice = parseFloat($(this).data('price')) || 0;
        let $pastaPrice = parseFloat($('.borders-pastas input[name="pasta"]:checked').data('price') || 0);

        $('.single-product #addToCart .value').html(formatPrice((parseFloat(biggestPrice) + $borderPrice + $pastaPrice) * qtd));

    });

    $('.borders-pastas input[name="pasta"]').on('change', function(e){

        let qtd = parseInt($('.single-product .quantity-footer .total').text());

        let productsList = $('input[name="pizza-checkbox"]:checked');
        var biggestPrice = 0;

        productsList.each(function(){
            if(biggestPrice < $(this).data('price')){
                biggestPrice = $(this).data('price');
            }                       
        });

        let $borderPrice = parseFloat($('.borders-pastas input[name="border"]:checked').data('price') || 0);
        let $pastaPrice = parseFloat($(this).data('price')) || 0;

        $('.single-product #addToCart .value').html(formatPrice((parseFloat(biggestPrice) + $borderPrice + $pastaPrice) * qtd));

    });
    /* Atualizando valores na tela através do item ao mudar opções de borda e massa */

    /* Adicionar ou Remover 1 */

    $('body').on('click', '.remove-item-cart', function(){
        let id = $(this).closest('.item').data('item-id');

        removeFromCart(id);
    });

    $('#coupon').on('input', function(e){
        
        if($(this).val().length > 3){

            let $name = $(this).val();
            let $cart = getCart();

            let $totalCoupon = $cart.subtotal + parseFloat($cart.shipping);

            applyCoupon($name, $totalCoupon);
        }

    });

    /* Funções */
    
    function errorCartForm($htmlMessage){

        let $cardErrorsNotification = `
            <div id="error-cart-form" class="error-cart-form">
                <div class="header">
                    <div class="icon rounded-circle">
                        <span><i class="mdi mdi-close"></i></span>
                    </div>
    
                    <button type="button" id="close-errors">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                
                ${$htmlMessage}
            </div>
        `;

        $('body').append($cardErrorsNotification);

        setTimeout(function () {
            $('#error-order-form').remove();
        }, 20000);

    } // errorCartForm()
    
    function validationCartForm(){

        // * Verificar formas de pagamento;
        let $order = getCart();
        let $errors = [];

        if(
            $order.formPayment != 'cash' &&
            $order.formPayment != 'credit-card'  &&
            $order.formPayment != 'debit-card'
        ){
            $errors.push('Forma de pagamento inválida.');
        }

        // * Verificar meios de entrega;        
        if(
            $order.deliveryMethod != 'delivery' &&
            $order.deliveryMethod != 'withdrawal'  &&
            $order.deliveryMethod != 'comeget'
        ){
            $errors.push('Meio de entrega inválido.');
        }
        
        // * Verificar se informou um cliente;
        if(Object.keys($order.client).length == 0){
            $errors.push('Selecione um cliente.');
        }
        
        // * Verificar se informou um endereço;
        if(Object.keys($order.address).length == 0){
            $errors.push('Selecione um endereço.');
        }

        // * Verificar se tem itens no pedido;
        if($order.items.length == 0){
            $errors.push('O pedido está vazio.');
        }else{

            // * Verificar formatos dos preços;
            // **** Dos itens;

            $order.items.forEach(item => {

                if(item.price.length == 0){
                    $errors.push('Informe um preço para o item ' + item.name + '.');
                }

                if(!isDecimal(item.price, 2)){
                    $errors.push('Verifique o preço do item ' + item.name + '.');
                }
                
                 // * Verificar a validade das quantidades dos itens;
                 if(
                    typeof item.quantity != 'number' ||
                    item.quantity <= 0
                ){
                    $errors.push('Verifique a quantidade do item ' + item.name + '.');
                 }

            });

            // **** Da taxa de entrega, descontos, subtotal e total
            if($order.shipping > 0){
                if(!isDecimal($order.shipping, 2)){
                    $errors.push('Formato inválido na taxa de entrega.');
                }
            }
           
            if($order.discounts > 0){
                if(!isDecimal($order.discounts, 2)){
                    $errors.push('Formato inválido no desconto.');
                }
            }

            if(!isDecimal($order.subtotal, 2)){
                $errors.push('Formato inválido no subtotal do pedido.');
            }

            if(!isDecimal($order.total, 2)){
                $errors.push('Formato inválido no valor do pedido.');
            }
        }
        

        return $errors;


    } // validationCartForm()

    function applyCoupon($name, $total){

        let $root = $('meta[name="root"]').attr('content');
        let $url = $root + '/apply-coupon';
        
        $.ajax({
            url: $url,
            type: 'get',
            dataType: 'json',
            data: {
                name: $name,
                total: $total
            },
            success: function(response){

                let $message = $('#coupon').closest('.coupon').find('.message');

                $message.removeClass('success');
                $message.removeClass('error');

                $('#coupon').removeClass('bordersuccess');
                $('#coupon').removeClass('bordererror');

                if(response.success){
                    $('#coupon').addClass('bordersuccess');

                    let $cart = getCart();

                    let $coupon = {
                        name: $name,
                        discount_type: response.discount_type,
                        discount: parseFloat(response.discount),
                        value: parseFloat(response.value),
                    };

                    $cart.discounts = $coupon.discount;
                    $cart.coupon = $coupon;
                    saveCart($cart);
                    updateCart();

                }else{
                    $('#coupon').addClass('bordererror');
                }

                $message.addClass(response.success == true ? 'success' : 'error');
                $message.html(response.message);

            },
            error: function(err){
                console.log('ERRO: ', err);
            }
        });

    } // appplyCoupon()

    function addToCart(){

        var cart = getCart();

        var img = $('#img').val();
        var newItem = {};
        var observations = $('#observations').val();
        
        let categoryName = $('#categoryName').val();
        var category = {
            id: $('#categoryId').val(),
            name: categoryName
        };

        /* Pegando a quantidade */
        var quantity = parseInt($('.single-product .quantity-footer .total').text());

        // Adicionando pizza
        if(categoryName == 'pizzas'){
            
            // Pegando sabores de pizza
            var pizzas = $('input[name="pizza-checkbox"]:checked');
            let size = $('#size').val();
            let sizeId = $('#sizeId').val();

            var borderChecked = $('input[name="border"]:checked');
            let border = {
                id: borderChecked.val(),
                name: borderChecked.data('name'),
                price: borderChecked.data('price')
            };

            var pastaChecked = $('input[name="pasta"]:checked');
            let pasta = {
                id: pastaChecked.val(),
                name: pastaChecked.data('name'),
                price: pastaChecked.data('price')
            };

            switch(size){
                case 'Grande':
                    size = 'G';
                break;

                case 'Média':
                    size = 'M';
                break;

                default:
                    size = 'B';
            }

            // Se já tem um item no carrinho
            // Apenas altera quantidade
            let searchItemId = 0;

            if(pizzas.length == 1){
                searchItemId = $(pizzas[0]).val() + '+' + size;
            }else{
                searchItemId = $(pizzas[0]).val() + '+' + $(pizzas[1]).val() + '+' + size;
            }
            
            // Procurando item
            let searchItem = cart.items.find(item => item.id === searchItemId);
            
            if(searchItem){
                
                cart.items.map(i => {

                    if(i.id == searchItemId){
                        i.quantity += quantity;
                    }

                });

                cardNotification({
                    message: 'Adicionado ao carrinho',
                    icon: '<i class="mdi mdi-check"></i>',
                    type: '-success'
                });

            }else{
                // Adiciona o novo item no carrinho
                
                if(pizzas.length == 0){
                    cardNotification({
                        message: 'Selecione ao menos um sabor',
                        icon: '<i class="mdi mdi-close"></i>',
                        type: '-error'
                    });
                }else if(pizzas.length > 2){
                    cardNotification({
                        message: 'Escolha no máximo dois sabores',
                        icon: '<i class="mdi mdi-close"></i>',
                        type: '-error'
                    });
                }else{
                    
                    var products = [];
                    
                    // Verificando quantos sabores
                    if(pizzas.length == 1){
                        pizzas.each(function(){
                            products = addSinglePizza(this);                            
                        });
                        
                        let itemId = products.productId + "+" + size;
                        let itemName = 'Pizza (' + size + ') ' + products.name;

                        let $borderPrice = parseFloat($('.borders-pastas input[name="border"]:checked').data('price') || 0);
                        let $pastaPrice = parseFloat($('.borders-pastas input[name="pasta"]:checked').data('price') || 0);

                        newItem = {
                            id: itemId,
                            name: itemName,
                            size: size,
                            sizeId: sizeId,
                            border: border,
                            pasta: pasta,
                            //price: products.price,
                            price: parseFloat(products.price) + $borderPrice + $pastaPrice,
                            img: img,
                            quantity: quantity,
                            category: category,
                            observations: observations,
                            products: products,
                        };

                        cart.items.push(newItem);

                        cardNotification({
                            message: 'Adicionado ao carrinho',
                            icon: '<i class="mdi mdi-check"></i>',
                            type: '-success'
                        });
                        
                    }else{
                        //  Dois sabores
                        pizzas.each(function(){
                            let item = addSinglePizza(this);
                            products.push(item);
                        });
                        
                        let itemId = products[0].productId + "+" + products[1].productId + '+' + size;
                        let itemPrice = products[0].price > products[1].price ? products[0].price : products[1].price;

                        //Pizza (M) 1/2 Filé Mignon 1/2 Mussarela
                        let itemName = 'Pizza (' + size + ') ';
                        itemName += '1/2 ' + products[0].name + ' 1/2 ' + products[1].name;

                        let $borderPrice = parseFloat($('.borders-pastas input[name="border"]:checked').data('price') || 0);
                        let $pastaPrice = parseFloat($('.borders-pastas input[name="pasta"]:checked').data('price') || 0);

                        newItem = {
                            id: itemId,
                            name: itemName,
                            size: size,
                            sizeId: sizeId,
                            price: parseFloat(itemPrice) + $borderPrice + $pastaPrice,
                            border: border,
                            pasta: pasta,
                            img: img,
                            quantity: quantity,
                            category: category,
                            observations: observations,
                            products: products,
                        };

                        cart.items.push(newItem);
                        cardNotification({
                            message: 'Adicionado ao carrinho',
                            icon: '<i class="mdi mdi-check"></i>',
                            type: '-success'
                        });
                        
                    }

                }
                
            }

            $('input[name^="additional"]').prop('checked', false);
            $('.borders-pastas input[name="pasta"]:first').prop('checked', true);
            $('.borders-pastas input[name="border"]').prop('checked', false);
            $('input[name="pizza-checkbox"]').prop('checked', false);
            $('.single-product #observations').val('');
            $('.single-product .observations .caracteres').html('0/200');
            $('.single-product .quantity-footer .total').text('1');

        }else{
            
            // Procurando item
            let searchItemId = $('#productId').val();
            let searchItem = cart.items.find(item => item.id === searchItemId);
                        
            if(searchItem){
                
                cart.items.map(i => {

                    if(i.id == searchItemId){
                        i.quantity += quantity;
                    }

                });

                cardNotification({
                    message: 'Adicionado ao carrinho',
                    icon: '<i class="mdi mdi-check"></i>',
                    type: '-success'
                });

            }else{
                // Adicionando produtos simples
                let itemId = $('.single-product #productId').val();
                let itemPrice = $('.single-product .meta-infos .price').data('price');
                let itemName = $('.single-product .meta-infos .title').data('name');

                newItem = {
                    id: itemId,
                    name: itemName,
                    price: itemPrice,
                    img: img,
                    quantity: quantity,
                    category: category,
                    observations: observations,
                };

                cart.items.push(newItem);
            }

            cardNotification({
                message: 'Adicionado ao carrinho',
                icon: '<i class="mdi mdi-check"></i>',
                type: '-success'
            });
        }


        saveCart(cart);
        updateCart();

    } // addToCart()

    function cardNotification($data){
        $('body').append(`
            <div id="card-notification" class="card-notification">
                <div
                class="icon rounded-circle ${$data.type}">
                <span>${$data.icon}</span>
                </div>
                ${$data.message}
            </div>
        `);

        /* popup de notificação */
        $('#card-notification').effect('bounce', {
            times: 3
        }, 300);
        
        setTimeout(function () {
            $('#card-notification').remove();
        }, 3000);
    } // cardNotification()

    function addSinglePizza(pizza){
        let id = $(pizza).val();

        let name = $(pizza).data('name');
        let price = $(pizza).data('price');

        // pegando adicionais
        let additionalsList = $('input[name="additional['+$(pizza).val()+']"]:checked');
        let additionals = [];

        if(additionalsList.length > 0){
            additionalsList.each(function(additionalIndex, additionalItem){
                additionals.push({
                    id: $(additionalItem).val(),
                    name: $(additionalItem).data('name')
                });
            });
        }

        let removedIngredientsList = $('input[name="ingredient['+$(pizza).val()+']"]:not(:checked)');
        let removedIngredients = [];

        if(removedIngredientsList.length > 0){
            removedIngredientsList.each(function(removedIndex, removedItem){
                removedIngredients.push({
                    id: $(removedItem).val(),
                    name: $(removedItem).data('name')
                });
            });
        }
        
        return {
                productId: id,
                name: name,
                price: price,
                additionals: additionals,
                removedIngredients: removedIngredients,
            };
    } // addSinglePizza()

    function removeFromCart(itemId){

        let cart = getCart();
        
        let items = cart.items.filter(i => i.id != itemId);
        cart.items = items || [];
        
        saveCart(cart);
        updateCart();

    } // removeFromCart()

    function updateQuantity(itemId, op, quantity){
        
        let cart = getCart();
        quantity = parseInt(quantity);

        cart.items.map(i => {

            if(i.id == itemId){

                if(op == 'add'){
                    if(quantity >= 1){
                        i.quantity += quantity;
                    }
                }else{
                    if(quantity >= 1){
                        if(i.quantity > 1){
                            i.quantity -= quantity;
                        }else{
                            cart.items.splice(itemId, 1);
                        }
                    }
                }
            }

        });

        saveCart(cart);
        updateCart();

    } // updateQuantity()

    function clearCart(){
        localStorage.removeItem('cart');
    } // clearCart()

    function getShippingValue(){
        return $('meta[name="deliveryValue"]').attr('content');
    }

    function getCart(){

        let $deliveryValue = getShippingValue();

        let initialCart = {
            items: [],
            coupon: {},
            formPayment: null,
            deliveryMethod: null,
            discounts: 0,
            shipping: parseFloat($deliveryValue) || 0,
            subtotal: 0,
            total: 0
        };

        return JSON.parse(localStorage.getItem('cart')) || initialCart;
    } // getCart()

    function saveCart(cart){
        localStorage.setItem('cart', JSON.stringify(cart));
    } // saveCart()

    function cartStatus() {
        $('#cart').toggleClass('opened');
    } // cartStatus()

    function updatingAppliedCoupon(){
        let cart = getCart();
        var $discounts = cart.discounts;
        var $shipping = cart.shipping;
        var $subtotal = 0;
        var $total = 0;

        $('#cart .cart-body .items').html('');
        
        if(cart.items.length == 0){
            $('#cart .cart-body .items').html(`
                <div class="w-100 d-flex align-items-center justify-content-center">
                    <h1 class="text-center empty-cart">Carrinho vazio</h1>
                </div>
            `);
        }else{

            var $data = {};
            let items = cart.items;
            
            items.forEach(function(item){
                
                $data.id = item.id;
                $data.quantity = item.quantity;
                $data.img = item.img;

                if(typeof item.products == 'undefined'){
                    // Adicionando lanche normal

                    $data.title = item.name;
                    $data.price = item.price * $data.quantity;
                    $subtotal = $subtotal += $data.price;
    
                    addItemCart($data);
                }else{
                    // Adicionando pizza
                    if(item.products.length > 1){    
                        $data.title = item.name;
                        $data.price = item.price * $data.quantity;
                        $subtotal = $subtotal += $data.price;
                        addItemCart($data);
    
                    }else{
                        // Apenas uma pizza
                        // Definindo nome
                        $data.title = item.name;
    
                        $data.price = item.price * $data.quantity;
                        $subtotal = $subtotal += $data.price;
    
                        addItemCart($data);
                    }
                }

            });
        }

        // Atualizar totais

        $total = $subtotal + parseFloat($shipping) - $discounts;

        cart.total = $total;
        cart.subtotal = $subtotal;
        cart.shipping = $shipping;
        cart.discounts = $discounts;

        saveCart(cart);

        $('#cart .cart-footer .total .value').html(formatPrice($total));
        $('#cart .cart-footer .shipping .value').html(formatPrice($shipping));
        $('#cart .cart-footer .subtotal .value').html(formatPrice($subtotal));
        $('.menu .item-cart .total').html(getTotalItemsCart());

        if($('meta[name="uri"]').attr('content') == 'finalizar-compra'){
            updatePageCheckout($total, $subtotal, $shipping, $discounts);
            //$('#coupon').value(cart.coupon.name);
        }

        if($('meta[name="uri"]').attr('content') == 'carrinho'){
            updatePageCart();
        }
    } // updatingAppliedCoupon()

    async function updateCart(){

        let cart = getCart();
        var $discounts = cart.discounts;
        var $shipping = cart.shipping;
        var $subtotal = 0;
        var $total = 0;

        $('#cart .cart-body .items').html('');
        
        if(cart.items.length == 0){
            $('#cart .cart-body .items').html(`
                <div class="w-100 d-flex align-items-center justify-content-center">
                    <h1 class="text-center empty-cart">Carrinho vazio</h1>
                </div>
            `);
        }else{

            var $data = {};
            let items = cart.items;
            
            items.forEach(function(item){
                
                $data.id = item.id;
                $data.quantity = item.quantity;
                $data.img = item.img;

                if(typeof item.products == 'undefined'){
                    // Adicionando lanche normal

                    $data.title = item.name;
                    $data.price = item.price * $data.quantity;
                    $subtotal = $subtotal += $data.price;
    
                    addItemCart($data);
                }else{
                    // Adicionando pizza
                    if(item.products.length > 1){    
                        $data.title = item.name;
                        $data.price = item.price * $data.quantity;
                        $subtotal = $subtotal += $data.price;
                        addItemCart($data);
    
                    }else{
                        // Apenas uma pizza
                        // Definindo nome
                        $data.title = item.name;
    
                        $data.price = item.price * $data.quantity;
                        $subtotal = $subtotal += $data.price;
    
                        addItemCart($data);
                    }
                }

            });
        }

        /* Atualizando o valor do desconto */
        let $coupon = cart.coupon;
        let $name = $coupon.name;
        let $totalCoupon = $subtotal + parseFloat(cart.shipping);

        $discounts = calculateDiscount($coupon, $totalCoupon);
        /* Atualizando o valor do desconto */

        // Atualizar totais

        $total = $subtotal + parseFloat($shipping) - $discounts;

        cart.total = $total;
        cart.subtotal = $subtotal;
        cart.shipping = $shipping;
        cart.discounts = $discounts;

        saveCart(cart);

        $('#cart .cart-footer .total .value').html(formatPrice($total));
        $('#cart .cart-footer .shipping .value').html(formatPrice($shipping));
        $('#cart .cart-footer .subtotal .value').html(formatPrice($subtotal));
        $('.menu .item-cart .total').html(getTotalItemsCart());

        if($('meta[name="uri"]').attr('content') == 'finalizar-compra'){
            updatePageCheckout($total, $subtotal, $shipping, $discounts);
            //$('#coupon').value(cart.coupon.name);
        }

        if($('meta[name="uri"]').attr('content') == 'carrinho'){
            updatePageCart();
        }

    } // updateCart()

    function calculateDiscount($coupon, $total){

        if($coupon.discount_type == 'value'){
            return $coupon.discount;
        }

        if($coupon.discount_type == 'percent'){
            return $total * ($coupon.value / 100);
        }

        return 0;
    }

    function updatePageCheckout($total, $subtotal, $shipping, $discounts){
        $('.checkout .values .subtotal .value').html(formatPrice($subtotal));
        $('.checkout .values .shipping .value').html(formatPrice($shipping));
        $('.checkout .values .discount .value').html(formatPrice($discounts));
        $('.checkout .values .total .value').html(formatPrice($total));
    } // updatePageCheckout()

    function updatePageCart(){

        var cartItems = $('.page-cart .cart-items');
        cartItems.html('');

        let cart = getCart();

        if(cart.items.length > 0){

            cart.items.forEach(function(item){
                
                cartItems.append(`
                    <div class="item" data-item-id="${item.id}">

                        <div class="meta-infos ">

                            <div class="title">${item.quantity}x - ${item.name}</div>
                            <div class="price">${formatPrice(item.price * item.quantity)}</div>
                            <a class="add-comments">Adicionar observação</a>

                        </div>

                        <div class="button">

                            <div class="quantity">
                                <button class="rem quantity-remove" data-op="rm">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                                <span class="total quantity-value">${item.quantity}</span>
                                <button class="add quantity-add"  data-op="add">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>

                        </div>

                    </div>
                `);
            });

            /* Atualizando total */
            $('.button .add-cart .value').html(formatPrice(cart.total));

        }else{
            cartItems.html('<div class="item"><h1>Carrinho vazio</h1></div>');
        }

    } // updatePageCart()
    
    function getTotalItemsCart(){
        var totalItems = 0;
        getCart().items.forEach(item => {
            totalItems += parseInt(item.quantity);
        });

        return totalItems;
    } // getTotalItemsCart()

    function addItemCart($data){
        
        $('#cart .cart-body .items').append(`
                <div class="item" data-item-id="${$data.id}">
                    <div class="img">
                        <img src="${$data.img}" alt="Imagem do produto" class="image-fluid">
                    </div>
            
                    <div class="meta-infos">
                        <div class="line-1">
                            <div class="title">${$data.title}</div>
                            <button type="button" class="remove remove-item-cart"><i class="mdi mdi-delete"></i></button>
                        </div>
                
                        <div class="line-2">
                            <div class="quantity-control">
                                <button type="button" class="quantity-remove" data-op="rm">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                
                                <span class="quantity-value">${$data.quantity}</span>
                                        
                                <button type="button" class="quantity-add" data-op="add">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                
                            <div class="prices">
                                <div class="price"><span class="number">${formatPrice($data.price)}</span></div>
                            </div>
                        </div>
                    </div> <!-- .meta-infos -->
                </div> <!-- .item -->
            `);
    } // addItemCart($data)

    function formatPrice(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    } // formatPrice(value)

    function openModalComments(id){

        let modal = $('#modal-comments');

        // Adicionar id ao modal
        modal.attr('data-id', id);

        // Adicionando observação no item correspondente.
        let cart = getCart();
        let item = cart.items.find(item => item.id == id);

        modal.find('.observations textarea').val(item.observations);


        modal.toggle();
    } // openModalComments()

    function closeModalComments(){

        let modal = $('#modal-comments');

        // Limpar campo e id
        modal.attr('data-id', '0');
        modal.find('.observations textarea').val('');

        modal.toggle();
       

    } // closeModalComments()

    function saveComments(id){

        let modal = $('#modal-comments');
        var itemId = id;

        // Alterando o texto no carrinho
        let cart = getCart();
        cart.items.map(item => {

            if(item.id == itemId){
                let text = modal.find('.observations textarea').val();

                item.observations = text;
            }
        });

        saveCart(cart);
        // Limpar campo e id
        modal.attr('data-id', '0');
        modal.find('.observations textarea').val('');

        modal.toggle();

    } // saveComments(id)

    function validateData(data, rules) {
        const errors = {};

        $('.field-error').html('');
        var $url = $('meta[name="root"]').attr('content');

        for (const field in rules) {
            const fieldRules = rules[field];

            for (const rule of fieldRules.split('|')) {
                const [ruleName, ruleParams] = rule.split(':');
                switch (ruleName) {
                    case 'required':
                        if (!data[field]) {
                            errors[field] = `O campo ${field} é obrigatório.`;
                        }
                        break;

                    case 'strong_password':
                        // Verifica se a senha atende aos critérios de segurança
                        const uppercaseRegex = /[A-Z]/;
                        const lowercaseRegex = /[a-z]/;
                        const numberRegex = /[0-9]/;
                        const specialCharsRegex = /[^\w]/;
                        const minChars = 8;

                        const hasUppercase = uppercaseRegex.test(data[field]);
                        const hasLowercase = lowercaseRegex.test(data[field]);
                        const hasNumber = numberRegex.test(data[field]);
                        const hasSpecialChars = specialCharsRegex.test(data[field]);
                        const hasMinChars = data[field].length >= minChars;

                        if (!(hasUppercase && hasLowercase && hasNumber && hasSpecialChars && hasMinChars)) {
                            errors[field] = `A senha no campo ${field} não atende aos critérios de segurança.`;
                        }
                        break;

                    case 'same':
                        // Verifica se o campo é igual ao campo especificado em ruleParams
                        if (data[field] !== data[ruleParams]) {
                            errors[field] = `O campo ${field} deve ser igual ao campo ${ruleParams}.`;
                        }
                        break;

                    case 'string':
                        if (data[field] && typeof data[field] !== 'string') {
                            errors[field] = `O campo ${field} deve ser uma string.`;
                        }
                        break;
                    case 'min':
                        if (data[field] && data[field].length < ruleParams) {
                            errors[field] = `O campo ${field} deve ter no mínimo ${ruleParams} caracteres.`;
                        }
                        break;
                    case 'max':
                        if (data[field] && data[field].length > ruleParams) {
                            errors[field] = `O campo ${field} deve ter no máximo ${ruleParams} caracteres.`;
                        }
                        break;
                    case 'nullable':
                        // Verifica se o campo é nulo ou indefinido
                        if (data[field] !== null && typeof data[field] !== 'undefined') {
                            break;
                        }
                        // Se o campo é nulo, pula para a próxima regra
                        continue;
                    case 'date':
                        if (data[field] && !/^\d{4}-\d{2}-\d{2}$/.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de data válido (YYYY-MM-DD).`;
                        }
                        break;
                    case 'date_format':
                        const dateFormat = ruleParams.toUpperCase();
                        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;

                        if (data[field] && !dateRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de data válido (${dateFormat}).`;
                        }
                        break;
                    case 'before_or_equal':
                        const limitDate = new Date();
                        limitDate.setFullYear(limitDate.getFullYear() - 18);
                        const fieldValue = new Date(data[field]);
                        if (data[field] && fieldValue > limitDate) {
                            errors[field] = `O campo ${field} deve ser uma data anterior ou igual a ${limitDate.toISOString().split('T')[0]}.`;
                        }
                        break;
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (data[field] && !emailRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve ser um email válido.`;
                        }
                        break;
                    case 'cpf':
                        const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
                        if (data[field] && !cpfRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de CPF válido (XXX.XXX.XXX-XX).`;
                        }
                        break;
                    case 'cnpj':
                        const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
                        if (data[field] && !cnpjRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de CNPJ válido (XX.XXX.XXX/XXXX-XX).`;
                        }
                        break;
                    case 'unique':
                        if (field === 'email') {
                            $.ajax({
                                beforeSend: function () {
                                    //selectTamanho.children("option:first").text("Aguarde...");
                                },
                                type: 'get',
                                dataType: 'json',
                                url: $url + '/email-unique',
                                data: {
                                    email: data[field],
                                },
                                success: function (response) {
                                    if (response && response.success === false) {
                                        errors[field] = `O campo ${field} já está cadastrado.`;
                                    }
                                },
                                async: false, // Define a requisição AJAX como síncrona
                            });
                        }

                        if (field == 'cpf') {
                            $.ajax({
                                beforeSend: function () {
                                    //selectTamanho.children("option:first").text("Aguarde...");
                                },
                                type: 'get',
                                dataType: 'json',
                                url: $url + '/cpf-unique',
                                data: {
                                    cpf: data[field],
                                },
                                success: function (response) {
                                    if (response && response.success === false) {
                                        errors[field] = `O campo ${field} já está cadastrado.`;
                                    }
                                },
                                async: false, // Define a requisição AJAX como síncrona
                            });
                        }

                        if (field == 'whatsapp' || field == 'phone') {
                            $.ajax({
                                beforeSend: function () {
                                    //selectTamanho.children("option:first").text("Aguarde...");
                                },
                                type: 'get',
                                dataType: 'json',
                                url: $url + '/phone-unique',
                                data: {
                                    phone: data[field],
                                },
                                success: function (response) {
                                    if (response && response.success === false) {
                                        errors[field] = `O campo ${field} já está cadastrado.`;
                                    }
                                },
                                async: false, // Define a requisição AJAX como síncrona
                            });
                        }
                        break;

                    case 'numeric':
                        if (data[field] && isNaN(data[field])) {
                            errors[field] = `O campo ${field} deve ser um valor numérico.`;
                        }
                        break;

                    case 'digits_between':
                        const [minDigits, maxDigits] = ruleParams.split(',');
                        if (data[field] && (data[field].length < minDigits || data[field].length > maxDigits)) {
                            errors[field] = `O campo ${field} deve ter entre ${minDigits} e ${maxDigits} dígitos.`;
                        }
                        break;

                    case 'digits':
                        if (data[field] && data[field].length !== parseInt(ruleParams)) {
                            errors[field] = `O campo ${field} deve ter exatamente ${ruleParams} dígitos.`;
                        }
                        break;

                    case 'exists':
                        let dataQuery = '';
                        if (field === 'inputCity') {
                            dataQuery = {
                                id: data[field],
                                entity: 'city'
                            };
                        }

                        if (field === 'inputState') {
                            dataQuery = {
                                uf: data[field],
                                entity: 'state'
                            };
                        }

                        $.ajax({
                            beforeSend: function () {
                                //selectTamanho.children("option:first").text("Aguarde...");
                            },
                            type: 'get',
                            dataType: 'json',
                            url: '/rule-exists',
                            data: dataQuery,
                            success: function (response) {
                                if (response && response.success === false) {
                                    errors[field] = `O campo ${field} é inválido.`;
                                }
                            },
                            async: false, // Define a requisição AJAX como síncrona
                        });
                        break;
                }
            }
        }

        return errors;
    } // validateData()

    /*function phoneMask(){

        $('.phoneMask').on('blur', function(e){
            var $value = $(this).val().replace(/[ \-\(\)]/g, '');
            var $number = '';

            if($value.length == 11){
                $value.split('').forEach((v, i) => {
                    switch(i){
                        case 0:
                            $number = '(' + v;
                            break;
                        case 1:
                            $number += v + ') ';
                        break;
    
                        case 2:
                            $number += v + ' ';
                        break;
    
                        case 6:
                            $number += v + ' ';
                        break;
    
                        default:
                            $number += v;
    
                    }
                });
            }else{
                $value.split('').forEach((v, i) => {
                    switch(i){
                        case 0:
                            $number = '(' + v;
                            break;
                        case 1:
                            $number += v + ') ';
                        break;
    
                        case 2:
                            $number += '9 ' + v;
                        break;

                        case 5:
                            $number += v + ' ';
                        break;
    
                        default:
                            $number += v;
    
                    }
                });
            }

            $(this).val($number)
        });
    }*/

        function phoneMask() {
            $('.phoneMask').on('input', function(e) {
                // Remover qualquer caractere que não seja número
                this.value = this.value.replace(/\D/g, '');
            });
    
            $('.phoneMask').on('blur', function(e) {
                var $value = $(this).val().replace(/\D/g, ''); // Filtra apenas números
                var $number = '';
    
                if ($value.length === 11) {
                    // Máscara para números com 11 dígitos
                    $number = '(' + $value.substring(0, 2) + ') ' + $value.substring(2, 3) + ' ' + $value.substring(3, 7) + '-' + $value.substring(7, 11);
                } else if ($value.length === 10) {
                    // Máscara para números com 10 dígitos
                    $number = '(' + $value.substring(0, 2) + ') 9 ' + $value.substring(2, 6) + '-' + $value.substring(6, 10);
                }
    
                // Atualizar o valor com a máscara
                $(this).val($number);
            });
        } // phoneMask()
    /* Máscaras */
    /* Funções */

});