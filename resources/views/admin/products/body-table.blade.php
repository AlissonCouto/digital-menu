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