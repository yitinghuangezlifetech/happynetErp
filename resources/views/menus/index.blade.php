@extends('layouts.main')

@section('css')
<link href="/admins/css/nestable.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        @can('create_menus', App\Models\Menu::class)
        <button type="button" class="btn bg-gradient-secondary btn-sm" style="float: left; margin-left: 5px;" onClick="location.href='{{route('menus.create')}}'">建立目錄資料</button>
        @endcan
        <button type="button" class="btn bg-gradient-secondary btn-sm closeMenu" style="float: left; margin-left: 5px;" onclick="closeMenu()">全部收合</button>
        <button type="button" class="btn bg-gradient-secondary btn-sm openMenu" style="float: left; margin-left: 5px; display: none" onclick="openMenu()">全部打開</button>
      </div>
      <div class="card-body">
        <div class="dd" id="nestable">
          <ol class="dd-list">
              @foreach($list??[] as $data)
              <li class="dd-item" data-id="{{$data->id}}">
                  <div class="dd-handle" style="vertical-align: middle">
                      {{$data->menu_name}}
                      <div class="btn-group m-b-10 chili-btn-group" style="float: right!important;">
                        <i class="fas fa-edit btn" title="編輯" onmousedown="location.href='{{route('menus.edit', $data->id)}}'"></i>
                        <i class="fas fa-trash-alt btn delBtn" title="刪除" data-id="{{ $data->id }}" onmousedown="menuDelete('{{ $data->id }}')"></i>
                      </div>
                  </div>
                  @if($data->getChilds->isNotEmpty())
                      <ol class="dd-list">
                      @foreach($data->getChilds as $child)
                          <li class="dd-item" data-id="{{$child->id}}">
                              <div class="dd-handle" style="z-index: 0;vertical-align: middle">
                                  {{$child->menu_name}}
                                  <div class="btn-group m-b-10 chili-btn-group" style="float: right!important;">
                                    @can('edit_menus', App\Models\Menu::class)
                                    <i class="fas fa-edit btn" title="編輯" onmousedown="location.href='{{route('menus.edit', $child->id)}}'"></i>
                                    @endcan
                                    @can('delete_menus', App\Models\Menu::class)
                                    <i class="fas fa-trash-alt btn delBtn" title="刪除" data-id="{{ $child->id }}" onmousedown="menuDelete('{{ $child->id }}')"></i>
                                    @endcan
                                  </div>
                              </div>
                          </li>
                      @endforeach
                       </ol>
                  @endif
              </li>
              @endforeach
          </ol>
      </div>
    </div>
    <!-- /.card -->
  </div>
</div>
<form id="delete_form" method="POST" style="display: none;">
  
</form>
@php
$jsArr = [];
if($menu->menuEditDetails->count() > 0) {
    foreach($menu->menuEditDetails as $detail) {
        if ($detail->has_js == 1)  {
            if (!in_array($detail->type.'.js', $jsArr)) {
            array_push($jsArr, $detail->type.'.js');
            }
        } 
    }
}
@endphp
@endsection
  
@section('js')
@if(count($jsArr) > 0)
    @foreach($jsArr as $js)
    <script src="/admins/js/components/{{$js}}"></script>
    @endforeach
@endif
<script src="/admins/js/jquery.nestable.js"></script>
<script type="text/javascript">
let closeMenu = () => {
    $('#nestable ol li button').each( (i, obj) => {
        if (obj.dataset.action == 'collapse') {
            obj.click();
        }
    });

    $('.closeMenu').hide();
    $('.openMenu').show();
}

let openMenu = () => {
    $('#nestable ol li button').each( (i, obj) => {
        if (obj.dataset.action == 'expand') {
            obj.click();
        }
    })

    $('.closeMenu').show();
    $('.openMenu').hide();
}

var menuDelete = function(id) {
    $('#delete_form')[0].action = '{{ route('menus.destroy', '__id') }}'.replace('__id', id);
    Swal.fire({
        type: 'warning',
        title: '訊息提示',
        text: "是否要刪除資料？",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '是的, 刪除資料!',
        cancelButtonText: '取消',
    }).then((result) => {
        if (result.value) {
            $('#delete_form').append('@csrf');
            $('#delete_form').append('@method("delete")');
            $('#delete_form').submit();
        }
    })
}
$(document).ready(function(){
    $('.dd').nestable({
        maxDepth: 2
    })
    .on('change', function(){
        let data = $('.dd').nestable('serialize');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('menus.sort') }}',
            data: {
                'data': data
            },
            cache: false,
            async: false,
            error: function(res) {
                Swal.fire({
                    icon: 'error',
                    text: res.responseJSON.message,
                    showCancelButton: false,
                    confirmButtonText: '確認',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '{{ route('menus.index') }}'
                    }
                })
            }
        })
    });
});
</script>

@endsection