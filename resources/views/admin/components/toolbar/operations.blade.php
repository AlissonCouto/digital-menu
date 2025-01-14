<div class="operations">

    <div class="form-container">

        <form action="{{route('leads.search')}}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquise no Google Maps" name="search">

                <select class="form-select" aria-label="Default select example" name="nicheId">
                    <option selected>NICHO...</option>

                    @foreach($niches as $niche)
                    <option value="{{$niche->id}}">{{$niche->name}}</option>
                    @endforeach

                </select>

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>

    <div class="excel-container">

        <a href="{{route('leads.spreadsheet.upload')}}">
            <i class="mdi mdi-file-excel icon"></i>
        </a>

    </div>


</div> <!-- .operations -->