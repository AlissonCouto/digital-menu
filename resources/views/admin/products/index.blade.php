@extends('admin.master')

@section('content')
<div class="toolbar d-flex align-items-center justify-content-end">
    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')
</div><!-- .toolbar -->

@if(session()->has('success'))
<div class="col-12 m-b-5">
    <div class="popup popup-{{ session('success')['success'] ? 'success' : 'danger' }}">
        {{ session('success')['message'] }}
    </div>
</div>
@endif

<div class="table-container">

    <div class="header-table">

        <div class="meta-infos">

            <h2 class="title">Produtos <span id="entity-quantity">({{ $total }})</span></h2>

            <div class="filter">

                <form class="search-container" action="{{route('products.consult')}}" method="post" id="search-entity">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pesquisar" name="search" id="search">

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <a href="{{route('products.create')}}" class="new-button">Novo</i></a>

            </div>

        </div> <!-- .meta-infos -->

        <div class="pagination">
            <div class="from">{{ $entity->firstItem() }}</div> -
            <div class="to">{{ $entity->lastItem() }}</div>
            <span>de</span>
            <div class="total">{{ $entity->total() }}</div>

            <div class="navigations">
                <a href="#" class="prev {{ $entity->onFirstPage() ? '-inactive' : '' }}" data-page="{{ $entity->currentPage() - 1 }}">
                    <i class="mdi mdi-chevron-left icon"></i>
                </a>

                <a href="#" class="next {{ $entity->hasMorePages() ? '' : '-inactive' }}" data-page="{{ $entity->currentPage() + 1 }}">
                    <i class="mdi mdi-chevron-right icon"></i>
                </a>
            </div>
        </div> <!-- pagination -->

    </div> <!-- .header-table -->

    <div class="header-table">

        <div class="meta-infos">

            @if($categories)
            <div class="filter">

                <div class="form-floating">
                    <select id="categories" class="form-control" name="categories">
                        <option value="" selected>Todas...</option>

                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    <label for="categories">Categoria</label>
                </div>

            </div>
            @endif

        </div>

    </div>

    <div class="body-table">

        <table class="table responsive-table table-striped">
            <thead>

                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Ativo?</th>
                    <th>Cardápio?</th>
                    <th>Categoria</th>
                </tr>

            </thead>

            <tbody>

                @if($entity->count())
                @foreach($entity as $row)
                <tr>
                    <td>
                        @if($row->img)
                        <img src="{{asset('storage/products/crops/' . $row->img)}}" alt="{{$row->name}}">
                        @else
                        -----
                        @endif
                    </td>
                    <td>{{$row->name}}</td>
                    <td class="col-prices">

                        @if($row->category->id == 1)

                        @if($row->pizza_size()->count() > 0)
                        @foreach($row->pizza_size()->get() as $ps)
                        <span class="size">{{$ps->name}}</span>
                        <span class="price">R$ {{number_format($row->prices()[$ps->id]['price'], 2, ',', '.')}}</span>
                        @endforeach
                        @else
                        --
                        @endif

                        @else
                        R$ {{number_format($row->price, 2, ',', '.')}}
                        @endif

                    </td>
                    <td>
                        {{ $row->status == 1 ? 'Sim' : 'Não' }} <i class="mdi mdi-circle {{ $row->status == 1 ? '-yes' : '-no' }}"></i>
                    </td>

                    <td>
                        {{ $row->menu == 1 ? 'Sim' : 'Não' }} <i class="mdi mdi-circle {{ $row->status == 1 ? '-yes' : '-no' }}"></i>
                    </td>

                    <td>{{$row->category->name}}</td>

                    <td>
                        <div class="actions">

                            <div class="action details">
                                <a href="{{route('products.show', $row->id)}}"><i class="mdi mdi-magnify"></i></a>
                            </div>

                            <div class="action edit">
                                <a href="{{route('products.edit', $row->id)}}"><i class="mdi mdi-pencil icon"></i></a>
                            </div>

                            <div class="action delete">
                                <a href="{{route('products.destroy', $row->id)}}"><i class="mdi mdi-delete icon"></i></a>
                            </div>

                        </div>
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center pt-5">
                        <h1 class="h1">Nenhum registro encontrado</h1>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

    </div>

</div>

@endsection