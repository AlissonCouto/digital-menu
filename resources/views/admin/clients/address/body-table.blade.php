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