@extends('master')

@section('content')

    <div class="checkout">

        <form class="container" action="{{route('client.address.store')}}" method="post">

            @csrf()

            <div class="infos">
                <div class="header-product">
                    <div>
                        <a href="{{route('checkout')}}"><i class="mdi mdi-arrow-left"></i></a>    
                        Novo endereço
                    </div>
                </div>

                <div class="form-checkout">
                    
                    <h2 class="title">Informe seu endereço</h2>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="description" type="text" name="description" class="form-control" required>
                                    <label for="description">Nome</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-8">
                                <div class="form-floating mb-3">
                                    <input id="street" type="text" name="street" class="form-control" required>
                                    <label for="street">Rua</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-floating mb-3">
                                    <input id="number" type="text" name="number" class="form-control" required>
                                    <label for="number">Número</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="neighborhood" type="text" name="neighborhood" class="form-control" required>
                                    <label for="neighborhood">Bairro</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="reference" type="text" name="reference" class="form-control">
                                    <label for="reference">Ponto de Referência</label>
                                </div>
                            </div>
                        </div>

                </div>

            </div>

            <div class="button">
                <button type="submit" class="add-cart">
                    <span>Salvar</span>
                </button>
            </div>
        </form>

    </div>

@endsection
