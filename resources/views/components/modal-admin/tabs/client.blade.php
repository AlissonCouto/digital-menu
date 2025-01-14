@isset($client)

@isset($success)
    @if($success)
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-{{$success == true ? 'success' : 'danger'}}">{{$message}}</div>
            </div>
        </div>
    @endif
@endisset

<form class="row order-form" method="post" id="updateClientModal" action="{{route('dashboard.modal.client.update', $client->id)}}">
    @csrf()    
    @method('PUT')
    <div class="col-12 col-md-8 mt-3">
        <input id="name" type="text" name="name" class="form-control" placeholder="Nome do cliente" value="{{isset($client) ? $client->name : ''}}">
    </div>

    <div class="col-12 col-md-4 mt-3">
        <input id="phone" type="text" name="phone" class="form-control" placeholder="Whatsapp" value="{{isset($client) ? $client->phone : ''}}">
    </div>

    <div class="col-12 col-md-6 mt-3">
        <select name="gender" id="gender" class="form-control">
            <option value="m" {{isset($client) && $client->gender == 'm' ? 'selected' : ''}}>Masculino</option>
            <option value="f" {{isset($client) && $client->gender == 'f' ? 'selected' : ''}}>Feminino</option>
            <option value="o" {{isset($client) && $client->gender == 'o' ? 'selected' : ''}}>Outros</option>
        </select>
    </div>

    <div class="col-12 col-md-6 mt-3">
        <input id="email" type="text" name="email" class="form-control" placeholder="E-mail" value="{{isset($client) ? $client->email : ''}}">
    </div>

    <div class="col-12 col-md-6 my-3">
        <input id="birth" type="date" name="birth" class="form-control" placeholder="Aniversário" value="{{isset($client) ? $client->birth : ''}}">
    </div>

    <div class="col-12 col-md-6 my-3">
        <input id="cpf" type="text" name="cpf" class="form-control" placeholder="CPF" value="{{isset($client) ? $client->cpf : ''}}">
    </div>

    <div class="col-12 my-3">
        <input id="password" type="password" name="password" class="form-control" placeholder="Senha">
    </div>

    <div class="col-12 col-md-2">
        <button type="submit" class="save">Salvar</button>
    </div>
</form>
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