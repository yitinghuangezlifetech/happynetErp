@extends('layouts.main')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}">
  @csrf
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">建立{{$menu->name}}資料</h3>
  </div>
  <div class="card-body">
    @php
    $jsArr = [];
    @endphp
    @if($menu->menuCreateDetails->count() > 0)
        @foreach($menu->menuCreateDetails as $detail)
          @php
            if ($detail->has_js == 1)  {
              if (!in_array($detail->type.'.js', $jsArr)) {
                array_push($jsArr, $detail->type.'.js');
              }
            }
          @endphp
          @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
            @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
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
              @if($detail->type == 'multiple_input')
              <div class="form-group">
              <label>{{$detail->show_name}}</label>
                <div id="{{ $detail->field }}">
                  @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                </div>
              </div>
              @else
                @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
              @endif  
            @endif
          @endif  
        @endforeach
    @endif
  </div>
</div>
<div class="card card-info">
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
                  location.href = '{{ route('dashboard') }}'
              }
          })
      }
  })
}
init();
</script>
@endsection