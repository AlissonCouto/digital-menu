@isset($success)
    @if($success)
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-{{$success == true ? 'success' : 'danger'}}">{{$message}}</div>
            </div>
        </div>
    @endif
@endisset

<form class="row order-form" method="post" id="editAddressModal" action="{{route('dashboard.modal.address.update', $address->id)}}">
    @csrf()
    @method('PUT')

    <div class="col-12 mt-3">
        <label for="main" class="label-form mb-2">Principal?</label>
        <select name="main" id="main" class="form-control">
            <option value="1" {{$address->main === 1 ? 'selected' : ''}}>Sim</option>
            <option value="0" {{$address->main === 0 ? 'selected' : ''}}>Não</option>
        </select>
    </div>

    <div class="col-12 mt-3">
        <input id="description" type="text" name="description" class="form-control" placeholder="Nome" value="{{$address->description}}">
    </div>

    <div class="col-12 col-md-4 mt-3">
        <input id="street" type="text" name="street" class="form-control" placeholder="Rua" value="{{$address->street}}">
    </div>

    <div class="col-12 col-md-4 mt-3">
        <input id="number" type="text" name="number" class="form-control" placeholder="Número" value="{{$address->number}}">
    </div>

    <div class="col-12 col-md-4 mt-3">
        <input id="neighborhood" type="text" name="neighborhood" class="form-control" placeholder="Bairro" value="{{$address->neighborhood}}">
    </div>

    <div class="col-12 col-md-10 mt-3">
        <input id="reference" type="text" name="reference" class="form-control" placeholder="Ponto de Referência" value="{{$address->reference}}">
    </div>

    <div class="col-12 col-md-2  mt-3">
        <button type="submit" class="save">Salvar</button>
    </div>
</form>

@isset($addresses)
<div class="row">
    <div class="col-12">
        <div class="list-adresses">
        @foreach($addresses as $row)
            <div class="item">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="name">{{$row->description}} &nbsp;&nbsp;
                        <a href="{{route('dashboard.modal.address.delete', $row->id)}}" class="delete"><i class="mdi mdi-delete"></i></a>
                        <a href="{{route('dashboard.modal.address.edit', $row->id)}}" class="edit"><i class="mdi mdi-pencil"></i></a>
                </div>
                    <input type="radio" name="address" value="{{$row->id}}" id="item-{{$row->id}}" class="field" {{ $row->main == 1 ? 'checked' : '' }}>
                </div>
                <div class="address">
                    <div>{{$row->street}}, {{$row->number}}</div>
                    <div>{{$row->neighborhood}} - {{$row->city->nome}}/{{$row->city->uf}}</div>
                    
                    

                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@else
<div class="row historic">
    <div class="col-12">
        <div class="historic-header">
            <div class="client">
                Selecione um cliente para visualizar os dados.
            </div> <!-- .client -->
        </div>
    </div>
</div>
@endisset