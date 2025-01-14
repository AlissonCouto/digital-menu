import './echo';

jQuery(function($){
    /* Notificações de pedidos em tempo real */

    // Opções de conta
    $('.account .edit-account a').on('click', function(e){

        e.preventDefault();

        $('.account .items').toggleClass('open');

    });

    var $companyId = $('meta[name="company-id"]').attr('content');

    Echo.private(`channel-order-notification-admin.${$companyId}`)
    .listen('.order.notification.admin', event => {
        $('#order-lanes #inanalysis #listing').prepend(event.html);
    });
    /* Notificações de pedidos em tempo real */

    phoneMask();
    priceMask();
    cpfMask();

    // Aplica a máscara em todos os campos que já possuem um valor no carregamento da página
    $('.phoneMask').each(function() {
        var $this = $(this);
        if ($this.val()) {
            $this.trigger('blur'); // Dispara o evento de "blur" para aplicar a máscara no valor já presente
        }
    });

    /* Atualizando status dos pedidos no dashboard */
    $('body').on('click', '.orders .update-status', function(e){

        e.preventDefault();

        let $url = $(this).attr('href');
        let $status = $(this).data('status');

        $.ajax({
            url: $url,
            dataType: 'json',
            type: 'get',
            data: {
                status: $status
            },
            beforeSend: function () {
                $('#order-lanes').html(`
                    <div class="spinner-container">
                            <div class="spinner"></div>
                        </div>`);

                        //setTimeout(function(){}, 100);
            },
            success: function(response){

                if(response.success == true && response.data.length > 0){
                    $('#order-lanes').html(response.data);
                }

            },
            error: function(err){
                console.log('ERRO: ', err);
            }
        });

    });
    /* Atualizando status dos pedidos no dashboard */

    /* Eventos do módulo de cupons */

    $('#validity_type').on('change', function(e){

        if($(this).val() == 'deadline'){

            $('#fields-usage-limit').find('input').val('');

            $('#fields-usage-limit').addClass('d-none');
            $('#fields-deadline').removeClass('d-none');

        }else{

            $('#fields-deadline').find('input').val('');

            $('#fields-deadline').addClass('d-none');
            $('#fields-usage-limit').removeClass('d-none');

        }

    });
    /* Eventos do módulo de cupons */

    /* Eventos do campo de gênero */
    $('#gender').on('change', function(e){

        if($(this).val() == 'o'){
            $('#gender-text').removeClass('d-none');
        }else{
            $('#gender-text input').val('');
            $('#gender-text').addClass('d-none');
        }

    });
    /* Eventos do campo de gênero */

    /* Realizando busca de entidades */
    $('#search-entity').on('submit', function(e){
        e.preventDefault();
        loadProducts($(this).attr('action'), 1);
    });

    /* Filtrando leads */
    $('#categories').on('change', function(e){
        var page = $(this).attr('data-page');
        let link = $('#search-entity').attr('action');
        loadProducts(link, page);
    })
    /* Filtrando leads */
    // Adiciona evento de clique aos links de navegação
    $(document).on('click', '.pagination .prev, .pagination .next', function(event) {
        event.preventDefault();
        if (!$(this).hasClass('-inactive')) {
            var page = $(this).attr('data-page');
            let link = $('#search-entity').attr('action');
            loadProducts(link, page);
        }
    });

    function loadProducts(url, page = 1) {

        var $search = $('#search').val();
        var $url = url;
        var $category_id = $('#categories').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            beforeSend: function () {
                $('.body-table table tbody').html(`
                    <tr>
                        <td colspan="4">
                            <div class="spinner"></div>
                        </td>
                    </tr>`);
            },
            type: "post",
            dataType: "json",
            url: $url,
            data: {
                search: $search,
                category_id: $category_id,
                page: page,
            },
            success: function (response) {
                var html = ``;

                setTimeout(function(){

                    $('.pagination .from').text(response.from);
                    $('.pagination .to').text(response.to);
                    $('.pagination .total').text(response.total);

                    // Atualiza o estado dos botões de navegação
                    if (response.currentPage == 1) {
                        $('.pagination .prev').addClass('-inactive');
                    } else {
                        $('.pagination .prev').removeClass('-inactive').attr('data-page', response.currentPage - 1);
                    }

                    if (response.currentPage == response.lastPage) {
                        $('.pagination .next').addClass('-inactive');
                    } else {
                        $('.pagination .next').removeClass('-inactive').attr('data-page', response.currentPage + 1);
                    }

                    $('#entity-quantity').html(`(${response.total})`);
                    
                    if($('#search-entity').data('entity') != 'orders'){
                        $('.body-table table tbody').html(response.html);
                    }else{
                        $('.body-table .orders-list .row').html(response.html);
                    }
                }, 100);
            },
        });
        
    }
    /* Realizando busca de entidades */

    /* Apagando entidades */
    $('body').on('click', '.body-table .actions .delete a, .link-delete', function(e){
        e.preventDefault()
        
        let $url = $(this).prop('href');

        $('#modal-delete .buttons .delete').attr('data-url', $url);

        toggleModalDeleteStatus();
    });

    $('body').on('click', '#modal-delete .buttons .delete', function(e){
        window.location.href = $(this).data('url');

    });

    $('body').on('click', '#modal-delete .buttons .cancel, #modal-delete .btn-close', function(e){
        $('#modal-delete .buttons .delete').attr('data-url', '');
        toggleModalDeleteStatus();
    });

    function toggleModalDeleteStatus(){
        $('#modal-delete').toggle();
    } // toggleModalStatus()
    /* Apagando entidades */

    /* Abrindo e fechando modal de endereço */
    $('#new-address, #modal-address button.btn-close',).on('click', function(e){
        toggleModalStatus();
    });

    $('.overlayer',).on('click', function(e){
        if(e.target == this){
            toggleModalStatus();
        }
    });

    function toggleModalStatus(){
        $('#modal-address').toggle();
    } // toggleModalStatus()


    /* Controle de popups */
    var $popup = $('.popup');
    if($popup.length > 0){
        setTimeout(function(){
            $popup.fadeOut(400, function(){
                $(this).remove();
            });
        }, 3000);
    }
    /* Controle de popups */

    /* Mudando título do modal em cada opção */
    $('.tabs-resume-orders .nav-link').on('click', function(e){
        let title = '';
        switch($(e.target).attr('id')){
            case 'historic-tab':
                    title = 'Histórico de pedidos';
                break;

            case 'client-tab':
                    title = 'Dados pessoais do cliente';
                break;

            case 'address-tab':
                    title = 'Dados de endereço';
                break;

            default:
        }

        $('.modal-address .modal-title').html(title);
    });
    /* Abrindo e fechando modal de endereço */

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
        }     // phoneMask()

    // 043.925.141-96

    function cpfMask(){

        $('.cpfMask').on('input', function(e){
            var $value = $(this).val().replace(/[ \-\(\)]/g, '');

            if($value.length > 11){
                $(this).val($(this).val().substring(0, 11));
            }

        });

        $('.cpfMask').on('blur', function(e){
            var $value = $(this).val().replace(/[ \-\(\)]/g, '');
            var $number = '';

            $value.split('').forEach((v, i) => {
                switch(i){
                    case 2:
                    case 5:
                        $number += v + '.';
                    break;

                    case 8:
                        $number += v + '-';
                    break;

                    default:
                        $number += v;

                }
            });

            $(this).val($number)

            
        });
    } // cpfMask()

    function priceMask(){

        {
            $('.priceMask').on('input', function(e){
                let value = $(this).val();
                
                // Remove tudo que não é dígito
                value = value.replace(/\D/g, '');

                // Adiciona vírgula para centavos
                value = (value / 100).toFixed(2) + '';

                // Substitui ponto por vírgula
                value = value.replace(".", ",");

                // Adiciona ponto a cada milhar
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // Exibe o valor formatado com o símbolo de R$
                $(this).val(value);
            });
        } // phoneMask()
    

    } // priceMask()
    /* Máscaras */

    /* Alternando visibilidade dos campos para pizzas no cadastro de produtos */
    $('#category_id').on('change', function(e){
        
        if($(this).val() == 1){
            $('#price').closest('.col-12').css('display', 'none');
            $('#file-field').closest('.col-12').css('display', 'none');
            $('#fields-for-pizzas').removeClass('d-none');
        }else{
            $('#price').closest('.col-12').css('display', 'block');
            $('#file-field').closest('.col-12').css('display', 'block');
            $('#fields-for-pizzas').addClass('d-none');
        }

    });
    /* Alternando visibilidade dos campos para pizzas no cadastro de produtos */
});