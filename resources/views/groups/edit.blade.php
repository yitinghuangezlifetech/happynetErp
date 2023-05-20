@extends('layouts.main')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $data->id)}}">
  @csrf
  @method('put')
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">編輯{{$menu->name}}資料</h3>
  </div>
  <div class="card-body">
    @php
      $jsArr = [];
    @endphp
    @if($menu->menuEditDetails->count() > 0)
        @foreach($menu->menuEditDetails as $detail)
          @php
            if ($detail->has_js == 1)  {
              if (!in_array($detail->type.'.js', $jsArr)) {
                array_push($jsArr, $detail->type.'.js');
              }
            }
          @endphp
          @if($detail->field == 'parent_id')
          <div class="form-group">
            <label for="parent_id">所屬群組</label>
            <select class="custom-select rounded-0" name="parent_id" id="parent_id">
              <option value="">請選擇</option>
              @foreach($groups??[] as $group)
              <option value="{{$group->id}}" @if($data->parent_id==$group->id){{'selected'}}@endif>{{$group->name}}</option>
                @foreach($group->getChilds??[] as $child)
                  @include('groups.option', ['child'=>$child, 'value'=>$data->parent_id])
                @endforeach
              @endforeach
            </select>
          </div>
          @elseif($detail->super_admin_use == 1 && $user->super_admin == 1)
            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
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
              @if($detail->type == 'multiple_input')
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
                  <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                  <div id="{{ $detail->field }}">
                  @foreach($options as $option)
                    @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                  @endforeach
                  </div>
                @endif    
              @else
                @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
              @endif
            @endif
          @endif
        @endforeach
    @endif
  </div>
</div>
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">設定群組權限</h3>
    <div style="float: right;">
      <input type="checkbox" class="form-check-input" id="checkAll">全選
    </div>
  </div>
  <div class="card-body" id="permissionContent"></div>
</div>
<div class="card" id="footerArea">
  <div class="card-footer text-center">
    <button type="submit" class="btn bg-gradient-dark">儲存</button>
    <button type="button" class="btn bg-gradient-secondary deleteBtn"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
    <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
  </div>
</div>
<!-- /.row -->
@endsection

@section('js')
<script>
const checkForm = () => {
  let pass = 2;
  let permissionRows = 0;
  const permissionLen = parseInt($('.menuIem').length);

  $('.menuIem').each(function(){
    if ($(this).prop('checked')) {
      permissionRows++;
    }
  })
  
  if (permissionRows == 0) {
    Swal.fire({
      icon: 'error',
      title: '訊息提示',
      text: '請選擇至少一個群組權限'
    })

    return false;
  }

  return true;
}

$('body').on('click', '#checkAll', function(){
  if (!$(this).prop('checked')) {
    $('.menuIem').attr('checked', false);
  } else {
    $('.menuIem').attr('checked', true);
  }
});

$('body').on('click', '.selectAll', function(){
  const menuId = $(this).data('menuid');

  $(`.childMenu_${menuId}`).each(function(){
    if ($(this).prop('checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  })
})

$(".deleteBtn").click(function(){
  $('#delete_form')[0].action = '{{ route($menu->slug.'.multipleDestroy') }}';
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
          $('#delete_form').html('');
          $('#delete_form').append('@csrf');
          $('#delete_form').append(`<input type="hidden" name="ids[]" value="{{$data->id}}">`);
          $('#delete_form').submit();
        }
    })
})

let checkBtn = (id) => {
  $(`.subMenu_${id}`).each(function(){
    if ($(this).prop('checked')) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  });
}

var init = function(){
  var groupId = '{{$data->id}}';

  $('#permissionContent').html('');

  $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: 'post',
      url: '{{ route('components.getGroupPermissionComponent') }}',
      data: {
        userId: '{{$user->id}}',
        groupId: groupId
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
                  location.href = '{{ route('dashboard') }}'
              }
          })
      }
  })
}
init();
</script>
@endsection