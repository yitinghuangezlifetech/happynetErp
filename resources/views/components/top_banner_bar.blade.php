<div class="card-header">
    @if ($menu->slug == 'users')
        @can('create_' . $menu->slug, app($menu->model))
            <button type="button" class="btn bg-gradient-dark btn-sm" style="float: left; margin-left: 5px;"
                onclick="location.href='{{ route($menu->slug . '.create') }}'"><i
                    class="fas fa-plus"></i>&nbsp;建立{{ $menu->name }}資料</button>
        @endcan
    @elseif($menu->slug != 'users')
        @can('create_' . $menu->slug, app($menu->model))
            <button type="button" class="btn bg-gradient-dark btn-sm" style="float: left; margin-left: 5px;"
                onclick="location.href='{{ route($menu->slug . '.create') }}'"><i
                    class="fas fa-plus"></i>&nbsp;建立{{ $menu->name }}資料</button>
        @endcan
    @endif
    @if ($menu->slug == 'users')
        @can('delete_' . $menu->slug, app($menu->model))
            <button type="button" class="btn bg-gradient-secondary btn-sm deleteBtn"
                style="float: left; margin-left: 5px;"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
        @endcan
    @elseif($menu->slug != 'users')
        @can('delete_' . $menu->slug, app($menu->model))
            <button type="button" class="btn bg-gradient-secondary btn-sm deleteBtn"
                style="float: left; margin-left: 5px;"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
        @endcan
    @endif
    @if ($menu->export_data == 1)
        <button type="button" class="btn bg-gradient-secondary btn-sm exportBtn"
            style="float: left; margin-left: 5px;"><i class="fa fa-download"></i>&nbsp;資料匯出</button>
    @endif
    @if ($menu->import_data == 1)
        <button type="button" class="btn bg-gradient-secondary btn-sm importBtn"
            style="float: left; margin-left: 5px;"><i class="fas fa-file-import"></i>&nbsp;資料匯入</button>
    @endif
    @if ($menu->sync_data == 1)
        <button type="button" class="btn bg-gradient-secondary btn-sm syncDataBtn"
            style="float: left; margin-left: 5px;"><i class="fas fa-file-import"></i>&nbsp;資料同步</button>
    @endif
</div>
