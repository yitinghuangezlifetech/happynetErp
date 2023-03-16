@php $i = 1; @endphp
@foreach($menuItems??[] as $key=>$menu)
    @if($i == 1)
        <div class="row">
    @endif
    <div class="col-md-4">
        <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{ $menu->menu_name }}</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" onclick="checkBtn('{{$menu->id}}')" >
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($menu->getChilds->count() > 0)
            <table class="table">
            <tbody>
            @foreach($menu->getChilds as $child)
                <tr>
                    <td style="vertical-align: middle">{{ $child->menu_name }}</td>
                    <td>
                        @foreach($child->permissions??[] as $item)
                        @php $action = explode('_', $item->code); @endphp
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input menuIem subMenu_{{$menu->id}}" id="{{ $item->code }}" name="permissions[]" value="{{ $item->id }}" @if(isset($hasPermissions[$item->id])){{'checked'}}@endif>
                        <label class="form-check-label" for="{{ $item->code }}">{{ ucwords($action[0]) }}</label>
                        </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            @endif
        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    @if($i % 3 == 0 )  
        </div>
        <div class="row">
    @endif
    @if($loop->last)
        </div>
    @endif
    @php $i++; @endphp
@endforeach