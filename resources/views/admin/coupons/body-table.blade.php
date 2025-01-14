@if($entity->count())
@foreach($entity as $row)
<tr>
    <td>{{$row->name}}</td>

    <td>
        {{ $row->discount_type == 'percent' ? 'PERCENTUAL' : 'VALOR' }}
    </td>

    <td>
        @if($row->discount_type == 'value')
        R$ {{number_format($row->value, 2, ',', '.')}}
        @endif

        @if($row->discount_type == 'percent')
        {{number_format($row->value, 2, ',', '.')}}%
        @endif
    </td>

    <td>

        @if($row->validity_type == 'deadline')
        {{date('d/m/Y', strtotime($row->expiration_date))}} às {{$row->expiry_time}}
        @endif

        @if($row->validity_type == 'usage_limit')
        {{$row->usage_limit}} aplicações
        @endif

    </td>

    <td>
        {{ $row->status == 1 ? 'Sim' : 'Não' }} <i class="mdi mdi-circle {{ $row->status == 1 ? '-yes' : '-no' }}"></i>
    </td>

    <td>
        <div class="actions">

            <div class="action details">
                <a href="{{route('coupons.show', $row->id)}}"><i class="mdi mdi-magnify"></i></a>
            </div>

            <div class="action edit">
                <a href="{{route('coupons.edit', $row->id)}}"><i class="mdi mdi-pencil icon"></i></a>
            </div>

            <div class="action delete">
                <a href="{{route('coupons.destroy', $row->id)}}"><i class="mdi mdi-delete icon"></i></a>
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