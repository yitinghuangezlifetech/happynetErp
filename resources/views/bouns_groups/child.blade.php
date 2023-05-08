<ol class="dd-list">
    @foreach($data->getChilds as $child)
        <li class="dd-item" data-id="{{$child->id}}">
            <div class="dd-handle" style="z-index: 0;vertical-align: middle">
              @php
              $str = '';
              $com = '';
              foreach ($child->logs??[] as $log)
              {
                  $str .= $com.$log->funcType->type_name.'('.$log->bonus.'%)';
                  $com = '，';
              }
              @endphp
                {{$child->name}}：{{$str}}
                <div class="btn-group m-b-10 chili-btn-group" style="float: right!important;">
                  @can('edit_bouns_groups', App\Models\BounsGroup::class)
                  <i class="fas fa-edit btn" title="編輯" onmousedown="location.href='{{route('bouns_groups.edit', $child->id)}}'"></i>
                  @endcan
                  @can('delete_bouns_groups', App\Models\BounsGroup::class)
                  <i class="fas fa-trash-alt btn delBtn" title="刪除" data-id="{{ $child->id }}" onmousedown="menuDelete('{{ $child->id }}')"></i>
                  @endcan
                </div>
            </div>
            @include('bouns_groups.child', ['data'=>$child])
        </li>
    @endforeach
</ol>