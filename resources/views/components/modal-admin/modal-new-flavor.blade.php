<div id="modal-new-flavor" class="modal modal-address" tabindex="-1">
    <div class="overlayer-address">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-cart-plus"></i> Selecionar um novo sabor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mx-auto">

                        <div class="items-container">
                            <div class="header">
                                <form id="form-product" class="form-product" action="{{route('search.products.list')}}" method="get">
                                    <input type="text" id="product" name="product" placeholder="Nome do item" autocomplete="off">
                                </form>
                            </div> <!-- .header -->
                        </div> <!-- .items-container -->

                        <div class="position-relative">
                            <div id="list-products" class="list-products"></div>
                        </div>

                        <div class="buttons">
                            <button type="button" class="cancel">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>