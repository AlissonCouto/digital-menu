@if($entity->count())
@foreach($entity as $row)
<tr>
    <td>{{$row->name}}</td>
    <td>{{$row->birth}}</td>
    <td>{{$row->cpf}}</td>
    <td>{{$row->telefone}}</td>
    <td>{{$row->email}}</td>

    <td>
        <div class="actions">

            <div class="action details">
                <a href="{{route('deliverydrivers.show', $row->id)}}"><i class="mdi mdi-magnify"></i></a>
            </div>

            <div class="action edit">
                <a href="{{route('deliverydrivers.edit', $row->id)}}"><i class="mdi mdi-pencil icon"></i></a>
            </div>

            <div class="action delete">
                <a href="{{route('deliverydrivers.destroy', $row->id)}}"><i class="mdi mdi-delete icon"></i></a>
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