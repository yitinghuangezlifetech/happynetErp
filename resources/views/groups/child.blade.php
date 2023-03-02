@if($data->getChilds->isNotEmpty())
    <ol class="dd-list">
    @foreach($data->getChilds as $child)
        <li class="dd-item" data-id="{{$child->id}}">
            <div class="dd-handle" style="z-index: 0;vertical-align: middle">
                {{$child->name}}
                <div class="btn-group m-b-10 chili-btn-group" style="float: right!important;">
                @can('edit_groups', App\Models\Group::class)
                <i class="fas fa-edit btn" title="編輯" onmousedown="location.href='{{route('groups.edit', $child->id)}}'"></i>
                @endcan
                @can('delete_groups', App\Models\Group::class)
                <i class="fas fa-trash-alt btn delBtn" title="刪除" data-id="{{ $child->id }}" onmousedown="menuDelete('{{ $child->id }}')"></i>
                @endcan
                </div>
            </div>
            @if($child->getChilds->isNotEmpty())
                @include('groups.child', ['data'=>$child])
            @endif
        </li>
    @endforeach
    </ol>
@endif