@foreach($cities as $k => $city)
   <option value="{{$city->id}}" {{ $k == 0 ? 'selected' : '' }}>{{$city->nome}}</option>
@endforeach