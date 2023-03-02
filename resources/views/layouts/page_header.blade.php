<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
            @foreach($breadcrumb as $item)
            <li class="breadcrumb-item @if($item['active']){{'active'}}@endif">
              @if($item['breadUrl'])
              <a href="{{$item['breadUrl']}}">{{$item['name']}}</a>
              @else
              {{$item['name']}}
              @endif
            </li>
            @endforeach
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>