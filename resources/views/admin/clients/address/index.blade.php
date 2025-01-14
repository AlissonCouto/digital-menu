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

            <h2 class="title">Endereços de {{$client->name}}:<span id="entity-quantity">({{ $total }})</span></h2>

            <div class="filter">

                <form class="search-container" action="{{route('clients.address.consult', $client->id)}}" method="post" id="search-entity">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pesquisar" name="search" id="search">

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <a href="{{route('clients.address.create', $client->id)}}" class="new-button">Novo</a>

            </div>

            <a href="{{route('clients.index')}}" class="c-button btn-default"> <i class="mdi mdi-arrow-left"></i> Voltar</i></a>

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

    <div class="body-table">

        <table class="table responsive-table table-striped">
            <thead>

                <tr>
                    <th>Nome</th>
                    <th>Rua</th>
                    <th>Número</th>
                    <th>Bairro</th>
                    <th>Referência</th>
                    <th>Principal?</th>
                </tr>

            </thead>

            <tbody>

                @if($entity->count())
                @foreach($entity as $row)
                <tr>
                    <td>{{$row->description}}</td>
                    <td>{{$row->street ?? '----'}}</td>
                    <td>{{$row->number ?? '----'}}</td>
                    <td>{{$row->neighborhood ?? '----'}}</td>
                    <td>{{$row->reference ?? '----'}}</td>
                    <td>{{$row->main == 1 ? 'Sim' : 'Não' }}</td>

                    <td>
                        <div class="actions">

                            <div class="action details">
                                <a href="{{route('clients.address.show', ['client' => $client->id, 'address' => $row->id])}}"><i class="mdi mdi-magnify"></i></a>
                            </div>

                            <div class="action edit">
                                <a href="{{route('clients.address.edit', ['client' => $client->id, 'address' => $row->id])}}"><i class="mdi mdi-pencil icon"></i></a>
                            </div>

                            <div class="action delete">
                                <a href="{{route('clients.address.destroy', ['client' => $client->id, 'address' => $row->id])}}"><i class="mdi mdi-delete icon"></i></a>
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