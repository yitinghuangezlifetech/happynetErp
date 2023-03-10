@extends('layouts.main')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}" onsubmit="return checkForm()">
  @csrf
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">建立{{$menu->name}}資料</h3>
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
    @if($menu->menuCreateDetails->count() > 0)
        @foreach($menu->menuCreateDetails as $detail)
          @if($loop->first)
          <div class="row">
          @endif
          @php
            if ($detail->has_js == 1)  {
              if (!in_array($detail->type.'.js', $jsArr)) {
                array_push($jsArr, $detail->type.'.js');
              }
              if($detail->type == 'ckeditor') {
                if (!in_array($detail->field, $fieldNameArr)) {
                  array_push($fieldNameArr, $detail->field);
                }
              }
            }
            if ($detail->use_component == 1) {
              if (!in_array($detail->component_name.'.js', $jsArr)) {
                array_push($jsArr, $detail->component_name.'.js');
              }
            }
            $halfRows = '';
          @endphp
          @if($detail->super_admin_use == 1 && $user->super_admin == 1)
          <div class="col-sm-6">
            @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
          </div>  
          @else  
            @if($detail->show_hidden_field == 1)
              @php
                $value = null;

                if ($detail->field == 'user_id') {
                  $value = $user->id;
                }
              @endphp
              @include('components.fields.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
            @else
              @if($detail->use_component == 1)
              </div>
              <div class="row">
                <div class="col-sm-6">
                  @include('components.'.$detail->component_name, ['detail'=>$detail])
                </div>
              </div>
              <div class="row">
              @php $i++; @endphp
              @elseif($detail->type == 'multiple_input')
              <div class="col-sm-6">
                <div class="form-group">
                  <label>{{$detail->show_name}}</label>
                  <div id="{{ $detail->field }}">
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                  </div>
                </div>
              </div>
              @elseif($detail->type == 'ckeditor')
              </div>
              <div class="row">
                <div class="col-sm-12">
                  @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                </div>
              </div>
              <div class="row">
                @php $i++; @endphp
              @elseif($detail->type == 'multiple_select')
              @else
                @if($detail->field == 'empty')
                  <div class="col-sm-6"></div>
                  @else
                  <div class="col-sm-6">
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                  </div>
                  @endif
                @endif  
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
    <h3 class="card-title">設定群組權限</h3>
    <div style="float: right;">
      <input type="checkbox" class="form-check-input" id="checkAll">全選
    </div>
  </div>
  <div class="card-body" id="permissionContent"></div>
</div>
<div class="card">
  <div class="card-footer text-center">
    <button type="submit" class="btn bg-gradient-dark">儲存</button>
    <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
  </div>
</div>
</form>
@endsection

@section('js')
<script>
const checkForm = () => {
  let pass = 2;
  let typeRows = 0;
  let permissionRows = 0;
  const typeLen = parseInt($('.systemType').length);
  const permissionLen = parseInt($('.menuIem').length);

  $('.systemType').each(function(){
    if ($(this).prop('checked')) {
      typeRows++;
    }
  })

  $('.menuIem').each(function(){
    if ($(this).prop('checked')) {
      permissionRows++;
    }
  })

  if (typeRows == 0) {
    Swal.fire({
      icon: 'error',
      title: '訊息提示',
      text: '請選擇至少一個所屬系統類別'
    })

    return false;
  }

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

$('body').on('keyup', '#name', function(){
  if ($('.menuIem').length == 0) {
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'get',
        url: '{{ route('components.getPermissionComponent') }}',
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
})

$('body').on('click', '#checkAll', function(){
  if (!$(this).prop('checked')) {
    $('.menuIem').attr('checked', false);
  } else {
    $('.menuIem').attr('checked', true);
  }
});

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
$('#parent_id').change(function(){
    var groupId = $(this).val();

    $('#permissionContent').html('');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        url: '{{ route('components.getGroupPermissionComponent') }}',
        data: {
          userId: '{{$user->id}}',
          groupId: groupId,
          type: 'create'
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

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: 'post',
      url: '{{ route('api.system_types.getGroupSystemTypes') }}',
      data: {
        groupId: groupId,
      },
      success: function (rs) {
        if (rs.status) {
          rs.data.forEach(function(v){
            console.log(v);
            if($(`#td_${v.id}`).length > 0) {
              $(`#td_${v.id}`).show();
            }
          })
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
@else
var init = function(){
  var groupId = '{{$user->role->group_id}}';

  $('#permissionContent').html('');

  $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: 'post',
      url: '{{ route('components.getGroupPermissionComponent') }}',
      data: {
        userId: '{{$user->id}}',
        groupId: groupId,
        type: 'create'
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

  $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: 'post',
      url: '{{ route('api.system_types.getGroupSystemTypes') }}',
      data: {
        groupId: groupId,
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
@endif
</script>
@endsection