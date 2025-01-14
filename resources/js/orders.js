jQuery(function($){

    // Fechando card dos erros do pedido
    $('body').on('click', '#error-order-form #close-errors', function(e){
        $('#error-order-form').remove();
    });

    // Inicializando o pedido no refresh da página
    saveOrder(initializeOrder());
    updateOrder();
    
    /* Salvando pedidos */
    $('#adminOrderCreate').on('submit', function(e){

        e.preventDefault();
        
        let $errors = validationOrderForm();
        
        if($errors.length > 0){
            // Com erros

            var $htmlMessage = '<ul class="list-errors">';

            $errors.forEach(msg => {
                $htmlMessage += `<li>${msg}<li>`;
            });

            $htmlMessage += '</ul>';

            errorOrderForm($htmlMessage);
        }else{
            // Sem erros
            let $order = getOrder();

            $('<input>').attr({
                type: 'hidden',
                name: 'order',
                value: JSON.stringify($order)
            }).appendTo('#adminOrderCreate');

            this.submit();
        }

    });
    /* Salvando pedidos */

    /* Removendo sabores da montagem de pizzas */
    $('body').on('click', '#flavor-remove', function(e){

        // Pegando quantos sabores tem adicionado
        let $currentFlavors = $(this).closest('.flavors').find('.flavor');
        let $flavor = $(this).closest('.flavor');
        let $flavorId = $flavor.attr('id');

        // Limpa área da montagem
        if($currentFlavors.length == 1){
            $('#customizable-items').html(`
                <div class="empty">
                    <strong>Selecione um item personalizável acima</strong>
                    <span>para iniciar a edição</span>
                </div>
            `);
        }else{
            // Remove o item
            $flavor.remove();

            // Remove o modal de ingredientes do item
            $(`#modal-ingredients-${$flavorId}`).remove();
        }

    });
    /* Removendo sabores da montagem de pizzas */

    /* Operações das teclas de seta */
    $(document).keydown(function(e) {
        
        // Verificando onde está o foco
        var $currentField = document.activeElement;
        var $fieldId = $($currentField).attr('id');
        var $selectedField = $('#list-products .item input[name="selected-product"]:checked');

        // Identificando a tecla
        if(e.key == 'ArrowDown'){

            if($fieldId == 'product'){

                let $currentLabel = $selectedField.closest('label');
                let $sisterElement = $currentLabel.next('label');

                if($sisterElement.length > 0){
                    // Desmarcando o campo atual
                    $selectedField.attr('checked', false);
                    $currentLabel.removeClass('-selected');

                    // Marcando o atual

                    $($sisterElement).addClass('-selected');
                    $($sisterElement).find('input').prop('checked', true);
                }

            }else if($fieldId == 'name'){

            }

        }else if(e.key == 'ArrowUp'){
            
            if($fieldId == 'product'){

                let $currentLabel = $selectedField.closest('label');
                let $sisterElement = $currentLabel.prev('label');

                if($sisterElement.length > 0){
                    // Desmarcando o campo atual
                    $selectedField.attr('checked', false);
                    $currentLabel.removeClass('-selected');

                    // Marcando o atual

                    $($sisterElement).addClass('-selected');
                    $($sisterElement).find('input').prop('checked', true);
                }

            }else if($fieldId == 'name'){

            }

        }else if(e.key == 'Enter'){
            
            if($fieldId == 'product'){

                $('.items-container #form-product #product').val('');
                searchProductById();

            }else if($fieldId == 'name'){

            }

        }

    });
    /* Operações das teclas de seta */

    /* Buscando produto ao clicar na lista */
    $('body').on('click', '#list-products .item', function(e){
        // Verifica se o clique veio do label e impede a execução para evitar duplicação
        if (e.target.tagName.toLowerCase() === 'input') {
            setTimeout(() => {
                searchProductById();
            }, 20);
        }

        $('.items-container #form-product #product').val('');
    });

    /* Adicionando novo sabor de pizza */
    $('body').on('click', '#new-flavor', function(e){

        $('#modal-new-flavor').toggle();

    });
    /* Adicionando novo sabor de pizza */
    /* Buscando produto ao clicar na lista */

    /* Salvando item de pizza */
    $('body').on('click', '.create-orders .save-item', function(e){
        addToOrder({
            category: 'pizzas'
        });

        $('.items-container #form-product #product').val('');

        $('#customizable-items').html(`
            <div class="empty">
                <strong>Selecione um item personalizável acima</strong>
                <span>para iniciar a edição</span>
            </div>
        `);
    });

    /* Buscando cliente */

        // Busca pelo campo de whatsapp
        $('.create-orders #whatsapp').on('keydown', function(e){
            
            $('#searchClient #name').val('');
            $('.create-orders .resume-address .address').html('');

            let $field = $(this).val();
            
            if($field.length >= 8){
                if(e.key == 'Enter'){
                    e.preventDefault();

                    searchClient($field);
                }
            }
        });

        // Busca pelo campo de nome
        $('.create-orders #name').on('input', function(e){
            e.preventDefault();

            $('#searchClient #whatsapp').val('');
            $('.create-orders .resume-address .address').html('');

            let $field = $(this).val();

            if($field.length >= 3){
                searchClient($field);
            }
        });

        // Busca ao clicar na lista do campo de nome
        $('body').on('click', '.create-orders #list-clients .item', function(e){
            
            let $field = $(this).data('phone');
            searchClient($field);

            $('.create-orders #list-clients').css('display', 'none');
            $('.create-orders #list-clients').html('');

        });
    /* Buscando cliente */

    /* Buscando produtos */

        $('#form-product').on('submit', function(e){
            
            e.preventDefault();

        });
    
        $('.items-container #product').on('input', function(e){

            e.preventDefault();

            let $field = $(this).val();

            if($field.length >= 3){
                searchProductsList($field);
            }else{
                $('.items-container #form-product #product').removeClass('-error');
                $('.errorflavor').html('');

                $('#list-products').css('display', 'none');
                $('#list-products').html('');
            }

        });
    /* Buscando produtos */

    /* Editar endereço no modal */
        $('body').on('click', '.list-adresses .item .edit', function(e){

            e.preventDefault();

            let $url = $(this).attr('href');

            loadFormUpdateAddress($url);

        });
    /* Editar endereço no modal */

    /* Alterando o endereço do pedido no modal do painel de pedidos */
    $('body').on('change', '.list-adresses .item .field', function(e){

        e.preventDefault();

        let $url = $(this).val();

        $.ajax({
            url: $url,
            dataType: 'json',
            type: 'get',
            success: function(response){

                if(response.success){

                    let $order = getOrder();

                    $order.address = response.data;

                    saveOrder($order);

                    $('.tabs-data-clients #address').html(response.htmlAddress);
                    $(`.tabs-data-clients #address #item-${response.data.id}`).prop('checked', true);
                }

            },
            error: function(err){
                console.log('ERRO: ', err);
            }
        });

    })
    /* Alterando o endeeço do pedido no modal do painel de pedidos */

    /* Deletar endereço no modal */
    $('body').on('click', '.list-adresses .item .delete', function(e){

        e.preventDefault();

        let $url = $(this).attr('href');

        toggleModaDeletelStatus($url);

    });

    // Requisição da deleção
    $('body').on('click', '#modal-address-delete .buttons button.delete', function(e){

        let $url = $('#modal-address-delete #url input').val();

        if($url){
            $.ajax({
                url: $url,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        $('.tabs-data-clients #address').html(response.htmlAddress);
                    }
                },
                error: function(err){
                    console.log('ERRO: ', err);
                }
            });
        }

        toggleModaDeletelStatus();
    });

    // Abrindo modais de massas e bordas
    $('body').on('click', '#modal-options .btn-close, .customizable-items #button-options', function(e){
        $('#modal-options').toggle();
    });

    // Abrindo modais de ingredientes
    $('body').on('click', '.customizable-items .flavors .ingredients', function(e){
        let $modalId = $(this).data('id');
        $(`#${$modalId}`).toggle();
    });

    // Fechando modais de ingredientes
    $('body').on('click', '.modal-ingredients .btn-close', function(e){
        $(this).closest('.modal-ingredients').toggle();
    });

    /* Alterando o valor da entrega */
    $('.create-orders input[name="delivery-value"]').on('input', function(e){

        let $newValue = $(this).val() || 0;

        let $order = getOrder();

        $order.shipping = parseFloat($newValue);

        saveOrder($order);

        updateOrder();

    });

    /* Selecionando forma de entrega */
    $('.create-orders .delivery-options input[name="delivery-method"]').on('change', function(e){

        let $deliveryMethod = $(this).val();

        $('.create-orders .delivery-options label.item').removeClass('-checked');
        $(this).closest('label.item').addClass('-checked');

        let $order = getOrder();

        $order.deliveryMethod = $deliveryMethod;

        if($deliveryMethod == 'delivery'){
            let $deliveryValue = getShippingValue();
            $order.shipping = $deliveryValue;
            //$order.total = $order.subtotal + parseFloat($deliveryValue) - parseFloat($order.discounts);
        }else{
            $order.shipping = 0;
        }

        // Alterando o valor do campo de taxa de entrega
        $('.create-orders input[name="delivery-value"]').val($order.shipping);
        
        saveOrder($order);
        updateOrder();

    });
    /* Selecionando forma de entrega */

    /* Selecionando forma de pagamento */
    $('.create-orders .form-payments input[name="payment-form"]').on('change', function(e){

        $('.create-orders .form-payments label.item').removeClass('-checked');
        $(this).closest('label.item').addClass('-checked');

        let $order = getOrder();

        $order.formPayment = $(this).val();

        saveOrder($order);

    });
    /* Selecionando forma de pagamento */

    /* Capturando comentários do pedido */
    $('.observations textarea').on('input', function(e){

        let $order = getOrder();
        let $attr = $(this).attr('id');

        $order[$attr] = $(this).val();

        saveOrder($order);

    });
    /* Capturando comentários do pedido */

    // Fechando modal de deleção de endereço
    $('body').on('click', '#modal-address-delete button.btn-close, #modal-address-delete .buttons button.cancel', function(e){
        toggleModaDeletelStatus();
    });

    $('.overlayer-address',).on('click', function(e){
        if(e.target == this){
            toggleModaDeletelStatus();
        }
    });

    function toggleModaDeletelStatus($url){
        if(typeof $url != 'undefined'){
            $('#modal-address-delete #url').html(`<input type="hidden" value="${$url}">`);
        }

        $('#modal-address-delete').toggle();
    } // toggleModalStatus()
    /* Deletar endereço no modal */

    /* Atualizando cliente via modal */
    $('body').on('submit', '#updateClientModal', function (e) {
        e.preventDefault();
    
        let $form = $(this);
        let $url = $form.attr('action');
        let $data = $form.serializeArray();
        let $jsonData = {};
    
        $.each($data, function() {
            $jsonData[this.name] = this.value;
        });
    
        $.ajax({
            type: 'PUT',
            url: $url,
            contentType: 'application/json',
            data: JSON.stringify($jsonData),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                
                if(response.success == true){

                    let $client = response.data;
                    // Adicionar cliente e endereço no localStorage()
                    let $order = getOrder();

                    $order.client = {
                        id: $client.id,
                        name: $client.name,
                        phone: $client.phone,
                    };

                    saveOrder($order);
                    updateOrder();

                    // Alimentando os modais
                    if(response.htmlClient.length > 0){
                        $('.tabs-data-clients #client').html(response.htmlClient);
                    }
                }

            },
            error: function(err){
                console.log('ERROR: ', err);
            }
        });
    });
    /* Atualizando cliente via modal */

    /* Criando endereço via modal */
    $('body').on('submit', '#createAddressModal', function (e) {
        e.preventDefault();
    
        let $form = $(this);
        let $url = $form.attr('action');
        let $data = $form.serializeArray();
        let $jsonData = {};
    
        $.each($data, function() {
            $jsonData[this.name] = this.value;
        });
    
        $.ajax({
            type: 'post',
            url: $url,
            contentType: 'application/json',
            data: JSON.stringify($jsonData),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                
                if(response.success == true){
                    // Alimentando os modais
                    if(response.htmlAddress.length > 0){
                        $('.tabs-data-clients #address').html(response.htmlAddress);
                    }
                }

            },
            error: function(err){
                console.log('ERROR: ', err);
            }
        });
    });
    /* Criando endereço via modal */

    /* Editando endereço via modal */
    $('body').on('submit', '#editAddressModal', function (e) {
        e.preventDefault();
    
        let $form = $(this);
        let $url = $form.attr('action');
        let $data = $form.serializeArray();
        let $jsonData = {};
    
        $.each($data, function() {
            $jsonData[this.name] = this.value;
        });
    
        $.ajax({
            type: 'post',
            url: $url,
            contentType: 'application/json',
            data: JSON.stringify($jsonData),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                
                if(response.success == true){
                    // Alimentando os modais
                    if(response.htmlAddress.length > 0){
                        $('.tabs-data-clients #address').html(response.htmlAddress);
                    }
                }

            },
            error: function(err){
                console.log('ERROR: ', err);
            }
        });
    });
    /* Editando endereço via modal */

    /* Eventos na troca de tamanhos de pizzas */
    $('body').on('change', 'input[name="pizza_size"]', function(){

        let $options = $(this).closest('.options');

        $options.find('label').removeClass('-checked');
        $(this).closest('label').addClass('-checked');

        let $borderPrice = parseFloat($('#modal-options input[name="border"]:checked').data('price') || 0);
        let $pastaPrice = parseFloat($('#modal-options input[name="pasta"]:checked').data('price') || 0);

        let $optionsPrice = $(`.create-orders .flavors input[name^="prices"][data-size="${$(this).val()}"]`);
        let $prices = [];
        
        $($optionsPrice).each((i, v) => {
            $prices.push($(v).val());
        });

        let $higherPrice = $prices[0];

        if($prices.length > 1){
            $higherPrice = $prices[0] > $prices[1] ? $prices[0] : $prices[1];
        }

        // BORDA + MASSA + TAMANHO DA PIZZA MAIS CARA
        let $formatedPrice = formatPrice($borderPrice + $pastaPrice + parseFloat($higherPrice));
        $('.create-orders .save-item .text').html(`Salvar item ${$formatedPrice}`);

    });

    /* FUNÇÕES */

    function errorOrderForm($htmlMessage){

        let $cardErrorsNotification = `
            <div id="error-order-form" class="error-order-form">
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

    } // errorOrderForm()

    function validationOrderForm(){

        // * Verificar formas de pagamento;
        let $order = getOrder();
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

                if(!isDecimal(parseFloat(item.price).toFixed(2), 2)){
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
                if(!isDecimal(parseFloat($order.shipping).toFixed(2), 2)){
                    $errors.push('Formato inválido na taxa de entrega.');
                }
            }
           
            if($order.discounts > 0){
                if(!isDecimal(parseFloat($order.discounts).toFixed(2), 2)){
                    $errors.push('Formato inválido no desconto.');
                }
            }

            if(!isDecimal(parseFloat($order.subtotal).toFixed(2), 2)){
                $errors.push('Formato inválido no subtotal do pedido.');
            }

            if(!isDecimal(parseFloat($order.total).toFixed(2), 2)){
                $errors.push('Formato inválido no valor do pedido.');
            }
        }
        

        return $errors;


    } // validationOrderForm()

    function isDecimal(value, max, min = 0){

        //const REGEX = `/^\d+\.\d{${decimalPlaces}}$/`;
        const REGEX = new RegExp(`^\\d+(\\.\\d{${min},${max}})?$`);

        return REGEX.test(value)

    } // isDecimal()

    function getShippingValue(){
        return $('meta[name="deliveryValue"]').attr('content');
    } // getShippingValue()

    function searchProductById(){

        let $url = $('#list-products .item input[name="selected-product"]:checked').data('url');
        let $ids = [];

        // Pegando o ID da primeira pizza se configurado
        let $elementItem = $('.create-orders #customizable-items');

        if($elementItem.find('.body-container').length > 0){
            
            let $flavorId = $elementItem.find('.body .flavors .flavor').attr('id');
            $ids.push($flavorId);

        }

        $ids.push($('#list-products .item input[name="selected-product"]:checked').val());

        $.ajax({
            url: $url,
            dataType: 'json',
            type: 'get',
            data: {
                search: $ids
            },
            success: function(response){
                
                if(response.success == true){

                    $('.items-container #form-product #product').removeClass('-error');
                    $('.errorflavor').html('');
                    $('#list-products').css('display', 'none');
                    $('#list-products').html('');

                    if(response.htmlProduct != undefined){
                        if(response.htmlProduct.length > 0){
                            $('#customizable-items').html(response.htmlProduct);
                        }
                    }

                    if(response.htmlModalOptions != undefined){
                        if(response.htmlModalOptions.length > 0){
                            $('#modais').append(response.htmlModalOptions);
                        }
                    }

                    if(response.category != 'pizzas'){
                        addToOrder(response);
                    }

                }else{

                    /*$('#list-products').css('display', 'none');
                    $('#list-products').html('');*/
                    $('.items-container #form-product #product').addClass('-error');
                    $('.errorflavor').html(response.message);

                }

            },
            error: function(err){
                console.log('ERR ', err);
            }
        });

    } // searchProductById()

    function searchProductsList($field){

        let $url = $('.items-container #form-product').attr('action');
        
        $.ajax({
            url: $url,
            dataType: 'json',
            type: 'get',
            data: {
                search: $field
            },
            success: function(response){

                if(response.success == true){

                    if(response.htmlProducts.length > 0){
                        $('.items-container #form-product #product').removeClass('-error');
                        $('#list-products').css('display', 'block');
                        $('#list-products').html(response.htmlProducts);
                    }

                }else{

                    $('.items-container #form-product #product').addClass('-error');
                    $('#list-products').css('display', 'block');
                    $('#list-products').html('Produto não encontrado.');

                }

            },
            error: function(err){
                console.log('ERRO: ', err);
            }
        });

    } // searchProductsList($field)

    function searchClient($field){

        let $url = $('.create-orders #searchClient').attr('action');
        var $addresses = [];
        var $address = [];

        $.ajax({
            dataType: 'json',
            url: $url,
            data: {
                search: $field,
            },
            success: function(response){

                if(response.success == true){
                    let $client = response.data;
                    
                    if(typeof $client.phone == 'string'){

                        // Alimentando os modais
                        if(response.htmlClient.length > 0){
                            $('.tabs-data-clients #client').html(response.htmlClient);
                        }

                        if(response.htmlAddress.length > 0){
                            $('.tabs-data-clients #address').html(response.htmlAddress);
                        }

                        if(response.htmlOrders.length > 0){
                            $('.tabs-data-clients #historic').html(response.htmlOrders);
                        }

                        $('#searchClient #whatsapp').removeClass('-error');
                        $('#searchClient #name').removeClass('-error');
        
                        $('#searchClient #whatsapp').val($client.phone);
                        $('#searchClient #name').val($client.name);

                        if(typeof $client.addresses != undefined){
                            $addresses = $client.addresses;
                            $address = $client.addresses.length > 0 ? $client.addresses[0]: [];
                        }

                        // Adicionar cliente e endereço no localStorage()
                        let $order = getOrder();

                        $order.client = {
                            id: $client.id,
                            name: $client.name,
                            phone: $client.phone,
                        };
                        
                        $order.address = $address;

                        // Marcando o endereço selecionado no modal
                        //$(`.tabs-data-clients #address #item-${$address.id}`).prop('checked', true);

                        saveOrder($order);
                        updateOrder();

                    }
                    
                    if(typeof $client.phone == 'undefined'){

                        let $html = ``;

                        $client.forEach(function(item){

                            $html += `<div class="item" id="${item.id}" data-phone="${item.phone}">
                                        <span class="name">${item.name}</span>
                                        <span class="phone">${item.phone}</span>
                                      </div>`

                        });

                        $('.create-orders #list-clients').css('display', 'block');
                        $('.create-orders #list-clients').html($html);

                    }
                    
                    $('.create-orders .errorclient').html('');

                }else{
                    $('#searchClient #whatsapp').addClass('-error');
                    $('#searchClient #name').addClass('-error');

                    $('.create-orders .errorclient').html('Cliente não encontrado.');
                }
            },
            error: function(err){
                console.log('ERRO: ', err);
            }
        });
    } // searchClient($field)
    /* FUNÇÕES */

    /* FUNÇÕES DO PEDIDO */
    function clearOrder(){
        localStorage.removeItem('order');

        updateOrder();
    } // clearOrder()

    function getOrder(){

        let initialOrder = initializeOrder();

        return JSON.parse(localStorage.getItem('order')) || initialOrder;

    } // getOrder()

    function initializeOrder(){

        let $deliveryValue = 0;

        let $deliveryMethodChecked = $('.create-orders input[name="delivery-method"]:checked');
        let $formPaymentChecked = $('.create-orders .form-payments input[name="payment-form"]:checked');

        if($deliveryMethodChecked.length > 0){
            $deliveryValue = getShippingValue();
        }

        return {
            client: {},
            address: {},
            items: [],
            comments_deliveryman: '',
            comments: '',
            formPayment: $formPaymentChecked.val(),
            deliveryMethod: $deliveryMethodChecked.val(),
            discounts: 0.0,
            shipping: parseFloat($deliveryValue) || 0.0,
            subtotal: 0.0,
            total: 0.0
        };
    } // initializeOrder()

    function updateOrder(){

        let $order = getOrder();
        let $address = $order.address;
        var $discounts = $order.discounts;
        var $shipping = $order.shipping;
        var $subtotal = 0;
        var $total = 0;
        
        // Atualizar as operações dos itens do pedido
        if($address){
            updateLabelAddress($address);
        }

        $('.create-orders .order-resume .table-items').html('');

        if($order.items.length == 0){

            $discounts = 0;
            $subtotal = 0;
            $total = 0;

            $('#discounts').val('0');
            $('#discount_type').val('value');

            $('.create-orders .clear-button').html(``);

            $('.create-orders .order-resume .table-items').html(`
                <tbody>
                    <tr>
                        <td colspan="4">
                            <div class="empty">
                                <div>Sem itens no pedido</div>
                            </div>
                        </td>
                    </tr>
                </tbody>   
            `);
            
        }else{

            $('.create-orders .clear-button').html(`<a href="#" id="clear-order">
                                <i class="mdi mdi-delete"></i>
                                Limpar Pedido
                            </a>`);

            $('.create-orders .order-resume .table-items').html(`
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qtd</th>
                        <th>Preço</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody></tbody>    
            `);

            var $data = {};
            let items = $order.items;
            
            items.forEach(function(item){
                
                $data.id = item.id;
                $data.quantity = item.quantity;

                if(typeof item.products == 'undefined'){
                    // Adicionando lanche normal

                    $data.title = item.name;
                    $data.price = item.price;
                    $subtotal = $subtotal += (parseFloat($data.price) * $data.quantity);
    
                    addItemCart($data);
                }else{
                    // Adicionando pizza
                    if(item.products.length > 1){    
                        $data.title = item.name;
                        $data.price = item.price;
                        $subtotal = $subtotal += (parseFloat($data.price) * $data.quantity);
                        addItemCart($data);
    
                    }else{
                        // Apenas uma pizza
                        // Definindo nome
                        $data.title = item.name;
    
                        $data.price = item.price;
                        $subtotal = $subtotal += (parseFloat($data.price) * $data.quantity);
    
                        addItemCart($data);
                    }
                }

            });

        }

        $total = $subtotal + parseFloat($shipping) - $discounts;

        $order.total = $total;
        $order.subtotal = $subtotal;
        $order.shipping = $shipping;
        $order.discounts = $discounts;

        saveOrder($order);

        // Atualizando valores do pedido na tela
        $('.create-orders #total-order').html(`Total do Pedido: ${formatPrice($order.total)}`);

        // Atualizando valores na montagem da pizza
        $('.create-orders .save-item .text').html('Salvar item R$ 00,00');

    } // updateOrder()

    function addItemCart($data){
        
        $('.create-orders .order-resume .table-items tbody').append(`
            <tr class="item" data-item-id="${$data.id}">
                <td> <a data-id="${$data.id}" id="remove-item" class="remove-item"><i class="mdi mdi-delete"></i></a> ${$data.title}</td>
                <td class="text-center"><input data-id="${$data.id}" id="update-quantity" min="1" class="update-quantity" type="number" value="${$data.quantity}" /></td>
                <td>${formatPrice($data.price)}</td>
                <td>${formatPrice($data.price * $data.quantity)}</td>
            </tr>
        `);
    } // addItemCart($data)

    function updateQuantity(itemId, quantity){
        
        let $order = getOrder();
        quantity = parseInt(quantity);

        if(quantity > 0){
            $order.items.map(i => {

                if(i.id == itemId){
                    i.quantity = quantity;
                }
    
            });
        }

        saveOrder($order);
        updateOrder();

        setDiscountsFields();

    } // updateQuantity()

    function removeFromOrder(itemId){

        let $order = getOrder();
        
        let items = $order.items.filter(i => i.id != itemId);
        $order.items = items || [];
        
        saveOrder($order);
        updateOrder();

    } // removeFromOrder()

    function formatPrice(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    } // formatPrice(value)

    function updateLabelAddress($address){
        
        if(typeof $address.street != 'undefined'){
            let $reference = $address.reference ? " (" + $address.reference + ")" : "";
            $('.create-orders .resume-address .address').html(`<i class="mdi mdi-map-marker"></i> ${$address.street}, ${$address.number}. ${$address.neighborhood}${$reference} - ${$address.city.nome}/${$address.city.uf}`);
        }else{
            $('.create-orders .resume-address .address').html('');
        }

    } // updateLabelAddress()

    function saveOrder(order){

        localStorage.setItem('order', JSON.stringify(order));

        // Debugando o carrinho
        $('#debug-cart').html(JSON.stringify(getOrder()));

    } // saveOrder()

    function loadFormUpdateAddress($url){

        $.ajax({
            type: 'get',
            url: $url,
            contentType: 'application/json',
            dataType: 'json',
            /*headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },*/
            success: function(response){
                
                if(response.success == true){
                    let $html = response.data;

                    // Alimentando o modal
                    if(response.data.length > 0){
                        $('.tabs-data-clients #address').html($html);
                    }
                }

            },
            error: function(err){
                console.log('ERROR: ', err);
            }
        });

    } // loadFormUpdateAddress()
    /* FUNÇÕES DO PEDIDO */

    /* Adicionando ao pedido */
    $('#addToOrder').on('click', function(e){
        addToOrder();
    });

    // Atualizando quantidade do item do pedido
    $('body').on('input', '#update-quantity', function(e){
        
        let $quantity = $(this).val();
        let $id = $(this).data('id');

        updateQuantity($id, $quantity);
        
    });

    // Removendo item do pedido
    $('body').on('click', '#remove-item', function(e){
        
        let $id = $(this).data('id');

        removeFromOrder($id);
        
    });

    /* Limpando o pedido */
    $('body').on('click', '#clear-order', function(e){
        e.preventDefault();
        clearOrder();
    });

    /* Definindo desconto manulamente */
    // Pelo campo do valor
    $('#discounts').on('change', function(e){
        setDiscountsFields();
    });

    // Pelo campo do tipo de desconto
    $('#discount_type').on('change', function(e){
        setDiscountsFields();
    });

    // Operações dos adicionais das pizzas
    // Das Massas
    $('body').on('change', '#modal-options input[name="pasta"]', function(e){

        let $borderPrice = parseFloat($('#modal-options input[name="border"]:checked').data('price') || 0);

        if($(this).data('price').length > 0){
            let $price = parseFloat($(this).data('price'));
            let $oldPrice = parseFloat($('.create-orders .save-item .text').data('price'));
            let $formatedPrice = formatPrice($oldPrice + $price + $borderPrice);
    
            $('.create-orders .save-item .text').html(`Salvar item ${$formatedPrice}`);
        }else{
            let $oldPrice = parseFloat($('.create-orders .save-item .text').data('price'));
            let $formatedPrice = formatPrice($oldPrice + $borderPrice);
    
            $('.create-orders .save-item .text').html(`Salvar item ${$formatedPrice}`);
        }
    });

    // Das Bordas
    $('body').on('change', '#modal-options input[name="border"]', function(e){
        
        let $pastaPrice = parseFloat($('#modal-options input[name="pasta"]:checked').data('price') || 0);

        if($(this).data('price').length > 0){
            let $price = parseFloat($(this).data('price'));

            let $oldPrice = parseFloat($('.create-orders .save-item .text').data('price'));
            let $formatedPrice = formatPrice($oldPrice + $price + $pastaPrice);
    
            $('.create-orders .save-item .text').html(`Salvar item ${$formatedPrice}`);
        }else{
            let $oldPrice = parseFloat($('.create-orders .save-item .text').data('price'));
            let $formatedPrice = formatPrice($oldPrice + $pastaPrice);
    
            $('.create-orders .save-item .text').html(`Salvar item ${$formatedPrice}`);
        }

    });

    /* Alterando labels das preferências de massa e borda selecionados */
    $('body').on('change', '#modal-options input[name="pasta"], #modal-options input[name="border"]', function(e){

        let $pasta = $('#modal-options input[name="pasta"]:checked');
        let $border = $('#modal-options input[name="border"]:checked');

        let $pastaPrice = parseFloat($pasta.data('price') || 0);
        let $borderPrice = parseFloat($border.data('price') || 0);

        /* Alterando labels da massa selecionada */
        $('.create-orders .preferences .selected').html('');

        if($pasta.data('name') != undefined){
            $('.create-orders .preferences .selected').append(`
        
                <strong>Massa:</strong> <span>${$pasta.data('name')} ${$pastaPrice > 0 ? ': ' + formatPrice($pastaPrice) : ''}</span>
    
            `);
        }

        if($border.data('name') != undefined){
            $('.create-orders .preferences .selected').append(`
        
                <br> <strong>Borda:</strong> <span>${$border.data('name')} ${$borderPrice > 0 ? ': ' + formatPrice($borderPrice) : ''}</span>
    
            `);
        }
        
    });

    function setDiscountsFields(){

        let $discount_type = $('#discount_type').val();
        let $discount_value = $('#discounts').val();

        let $order = getOrder();

        if($discount_value.length > 0){

            if($discount_type == 'value'){
                $order.discounts = parseFloat($discount_value);
            }else if($discount_type == 'percent'){
                $order.discounts = (parseFloat($discount_value) / 100) * ($order.subtotal + $order.shipping);
            }

        }else{
            $('#discounts').val('0');
            $order.discounts = 0;
        }

        if($order.discounts < ($order.subtotal + $order.shipping)){
            saveOrder($order);
            updateOrder();
        }

    } // setDiscountsFields()

    function addToOrder($param){

        var $order = getOrder();
        
        let categoryName = $param.category;

        var img = $('#img').val();
        var newItem = {};
        var observations = $('#observations').val();
        
        /* Pegando a quantidade */
        var quantity = 1;

        // Adicionando pizza
        if(categoryName == 'pizzas'){
         
            // Pegando sabores de pizza
            var pizzas = $('input[name="pizza-checkbox"]:checked');
            let size = $('input[name="pizza_size"]:checked').data('size-name');
            let sizeId = $('input[name="pizza_size"]:checked').val();

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
            let searchItem = $order.items.find(item => item.id === searchItemId);
            
            if(searchItem){
                
                $order.items.map(i => {

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

                        let $borderPrice = parseFloat($('#modal-options input[name="border"]:checked').data('price') || 0);
                        let $pastaPrice = parseFloat($('#modal-options input[name="pasta"]:checked').data('price') || 0);

                        newItem = {
                            id: itemId,
                            name: itemName,
                            size: size,
                            sizeId: sizeId,
                            border: border,
                            pasta: pasta,
                            price: parseFloat(products.price) + $borderPrice + $pastaPrice,
                            img: img,
                            quantity: quantity,
                            category: $param.category,
                            observations: observations,
                            products: products,
                        };

                        $order.items.push(newItem);

                        cardNotification({
                            message: 'Adicionado ao pedido',
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

                        // Selecionando o preço da pizza (o maior entre as duas opções)

                        //Pizza (M) 1/2 Filé Mignon 1/2 Mussarela
                        let itemName = 'Pizza (' + size + ') ';
                        itemName += '1/2 ' + products[0].name + ' 1/2 ' + products[1].name;

                        let $borderPrice = parseFloat($('#modal-options input[name="border"]:checked').data('price') || 0);
                        let $pastaPrice = parseFloat($('#modal-options input[name="pasta"]:checked').data('price') || 0);

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
                            category: $param.category,
                            observations: observations,
                            products: products,
                        };

                        $order.items.push(newItem);
                        cardNotification({
                            message: 'Adicionado ao pedido',
                            icon: '<i class="mdi mdi-check"></i>',
                            type: '-success'
                        });
                        
                    }

                }
                
            }

            $('input[name^="additional"]').prop('checked', false);
            $('input[name="pizza-checkbox"]').prop('checked', false);

        }else{
            
            // Procurando item
            let $product = $param.data[0];
            
            let searchItemId = $product.id;
            let searchItem = $order.items.find(item => item.id === searchItemId);
                        
            // Se o lanche já está no pedido
            if(searchItem){
                
                $order.items.map(i => {

                    if(i.id == searchItemId){
                        i.quantity += quantity;
                    }

                });

                cardNotification({
                    message: 'Adicionado ao pedido',
                    icon: '<i class="mdi mdi-check"></i>',
                    type: '-success'
                });

            }else{
                // Se o lanche ainda não está no pedido
                // Adicionando produtos simples
                let itemId = $product.id;
                let itemPrice = $product.price;
                let itemName = $product.name;

                newItem = {
                    id: itemId,
                    name: itemName,
                    price: itemPrice,
                    quantity: quantity,
                    category: $param.category,
                    observations: observations,
                };

                $order.items.push(newItem);
            }

            cardNotification({
                message: 'Adicionado ao pedido',
                icon: '<i class="mdi mdi-check"></i>',
                type: '-success'
            });
        }


        saveOrder($order);
        updateOrder();

    } // addToOrder()

    function addSinglePizza(pizza){
        let id = $(pizza).val();

        let name = $(pizza).data('name');
        //let price = $(pizza).data('price');

        let size = $('input[name="pizza_size"]:checked').val();
        let price = $(`input[name="prices[${id}][${size}]"]`).val();

        // pegando adicionais
        let additionalsList = $('input[name="additional['+$(pizza).val()+']"]:checked');
        
        let additionals = [];

        if(additionalsList.length > 0){
            additionalsList.each(function(i){
                additionals.push({
                    id: $(this).val(),
                    name: $(this).data('name')
                });
            });            
        }

        let removedIngredientsList = $('input[name="ingredient['+$(pizza).val()+']"]:not(:checked)');
        let removedIngredients = [];

        if(removedIngredientsList.length > 0){
            removedIngredientsList.each(function(i){
                removedIngredients.push({
                    id: $(this).val(),
                    name: $(this).data('name')
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
        /*$('#card-notification').effect('bounce', {
            times: 3
        }, 300);*/
        
        setTimeout(function () {
            $('#card-notification').remove();
        }, 3000);
    } // cardNotification()
    /* Adicionando ao pedido */
});