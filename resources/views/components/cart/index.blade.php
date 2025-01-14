<div id="cart" class="cart cart-mask">
    <div class="cart-wrapper cartmodal floating-cart">
        <div class="cart-container">
            <div class="cart-header">
                <h3><i class="mdi mdi-cart"></i> Seu carrinho</h3>
                <a class="btn-close-cart close-cart"><i class="mdi mdi-close"></i></a>
            </div>
            <!-- CABEÇALHO -->

            <div class="cart-body">
                <div class="items">
                    
                </div>
            </div>
            <!-- ITENS DO CARRINHO -->

            <div class="cart-footer">
                <div class="shipping">
                    <span class="lab">Entrega</span>
                    <span class="value"><span class="number">0,00</span></span>
                </div>

                <div class="subtotal">
                    <span class="lab">Subtotal</span>
                    <span class="value">R$ <span class="number">0,00</span></span>
                </div>

                <div class="total">
                    <span class="lab">Total</span>
                    <span class="value"><span class="number">0,00</span></span>
                </div>
                
                <div class="cart-tools">
                    <div class="cart-clear">
                        <button type="button" class="clear-cart"><i class="mdi mdi-delete"></i> Limpar</button>
                    </div>

                    <div class="cart-more">
                        <a href="{{route('welcome')}}">Comprar mais</a>
                    </div>
                </div>

                <div class="cart-next">
                    <a href="{{route('cart')}}" class="btn-checkout">Finalizar compra</a>
                </div>
            </div>
            <!-- RODAPÉ -->
        </div>
    </div>
</div>