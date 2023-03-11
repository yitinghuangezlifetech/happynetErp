@extends('layouts.main')

@section('content')
<form method="POST" action="{{ route('roles.update', $data->id) }}">
  @csrf
  @method('put')
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">編輯{{$menu->name}}資料</h3>
    </div>
    <div class="card-body">
      @php
      $jsArr = [];
      $fieldNameArr = [];
      if($menu->seo_enable == 1) {
        array_push($jsArr, 'image.js');
      } 
      $i = 1;
      @endphp
      @if($menu->menuEditDetails->count() > 0)
          @foreach($menu->menuEditDetails as $detail)
            @if($loop->first)
              <div class="row">
            @endif
            @php
              if ($detail->has_js == 1)  {
                if (!in_array($detail->type.'.js', $jsArr)) {
                  array_push($jsArr, $detail->type.'.js');
                }
                if($detail->type == 'ckeditor') {
                  if (!in_array('edit_'.$detail->field, $fieldNameArr)) {
                    array_push($fieldNameArr, $detail->field);
                  }
                }
              }
            @endphp
            @if($detail->super_admin_use == 1 && $user->super_admin == 1)
            <div class="col-sm-6">
              @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
            </div>
            @else  
              @if($detail->show_hidden_field == 1)
                @php
                  $value = null;
  
                  if ($detail->field == 'user_id') {
                    $value = $user->id;
                  } else {
                    $value = $data->{$detail->field};
                  }
                @endphp
                @include('components.fields.hidden', ['type'=>'edit', 'detail'=>$detail, 'value'=>$value])
              @else
                @switch($detail->type)
                  @case('multiple_input')
                    @php
                      $options = [];
                      
                      if ($detail->has_relationship == 1) {
                          $json = json_decode($detail->relationship, true);
                          if (is_array($json) && count($json) > 0) {
                              $options = app($json['model'])->where($json['references_field'], $data->{$detail->foreign_key})->get();
                          }
                      }
                    @endphp
                    @if(!empty($options) && $options->count() > 0)
                    <div class="col-sm-6">
                      <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                      <div id="{{ $detail->field }}">
                      @foreach($options as $option)
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                      @endforeach
                      </div>
                    </div>  
                    @endif
                    @break
                  @case('multiple_select')
                    <div class="col-sm-6">
                      @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data])
                    </div>
                    @break;
                  @case('ckeditor')
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                      </div>
                    </div>
                    <div class="row">
                    @php $i++; @endphp
                    @break;  
                  @case('empty')
                    <div class="col-sm-6"></div>
                    @break;
                  @case('component')
                    @include('components.'.$detail->component_name, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data])
                    @break
                  @default
                    <div class="col-sm-6">
                      @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                    </div>
                    @break;
                @endswitch
              @endif
            @endif
            @if($i % 2 == 0)
            </div>
            <div class="row">
            @endif
            @if($loop->last)
            </div>
            @endif
            @php $i++; @endphp
          @endforeach
      @endif
    </div>
  </div>
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">設定角色權限</h3>
    <div style="float: right;">
      <input type="checkbox" class="form-check-input" id="checkAll">全選
    </div>
  </div>
  <div class="card-body" id="permissionContent"></div>
</div>
<div class="card">
  <div class="card-footer text-center">
    <button type="submit" class="btn bg-gradient-dark">儲存</button>
    <button type="button" class="btn bg-gradient-secondary deleteBtn"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
    <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
  </div>
</div>
</form>
<form id="delete_form" method="POST" style="display: none;">
@csrf  
@method('delete')
</form>
@endsection

@section('js')
<script src="/admins/js/jquery.multi-select.js"></script>
<script>
$('#pre-selected-options').multiSelect();

$(".deleteBtn").click(function(){
  $('#delete_form')[0].action = '{{ route($menu->slug.'.destroy', $data->id) }}';
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
          $('#delete_form').submit();
        }
    })
})

$('body').on('click', '.checkBtn', function(){
  var id = $(this).data('id');

  $('.subMenu_'+id).each(function(){
    if ($(this).prop('checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  })
})

$('body').on('click', '#checkAll', function(){

  if ($(this).prop('checked')) {
    $('.menuIem').each(function(){
      $(this).prop('checked', true)
    })
  } else {
    $('.menuIem').each(function(){
      $(this).prop('checked', false)
    })
  }
});

let checkBtn = (id) => {
  $(`.subMenu_${id}`).each(function(){
    if ($(this).prop('checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  });
}

@if($user->role->super_admin == 1)
$('#group_id').change(function(){
    var groupId = $(this).val();

    $('#permissionContent').html('');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        url: '{{ route('components.getRolePermissionComponent') }}',
        data: {
          userId: '{{$user->id}}',
          groupId: '{{$data->group_id}}',
          roleID: '{{$data->id}}',
          type: 'edit'
        },
        success: function (res) {
            if (res.status) {
              $('#permissionContent').html(res.data);
            }
        },
        error: function(rs) {
            Swal.fire({
                icon: 'error',
                text: rs.responseJSON.message,
                showCancelButton: false,
                confirmButtonText: '確認',
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{ route($menu->slug.'.index') }}'
                }
            })
        }
    })
});
@endif

var init = function(){
  $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: 'post',
      url: '{{ route('components.getRolePermissionComponent') }}',
      data: {
        userId: '{{$user->id}}',
        groupId: '{{$data->group_id}}',
        roleID: '{{$data->id}}',
        type: 'edit'
      },
      success: function (res) {
          if (res.status) {
            $('#permissionContent').html(res.data);
          }
      },
      error: function(rs) {
          Swal.fire({
              icon: 'error',
              text: rs.responseJSON.message,
              showCancelButton: false,
              confirmButtonText: '確認',
          }).then((result) => {
              if (result.isConfirmed) {
                  location.href = '{{ route($menu->slug.'.index') }}'
              }
          })
      }
  })
}  
init();
</script>
@endsection