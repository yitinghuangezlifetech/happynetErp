@extends('layouts.main')
@section('css')
<style>
.ck-editor__editable {
    min-height: 500px;
}
.ms-container {
  width: 100%;
}
</style>
@endsection

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}">
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
                $halfRows = '';
              @endphp
              @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
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
                    else  if ($detail->field == 'home_stay_id') {
                      $value = $user->home_stay_id;
                    }
                  @endphp
                  @include('components.fields.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
                @else
                  @if($detail->type == 'multiple_input')
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>{{$detail->show_name}}</label>
                      <div id="{{ $detail->field }}">
                        @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                      </div>
                    </div>
                  </div>
                  @elseif($detail->type == 'multiple_select')
                  <div class="col-sm-6">
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>null])
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
              @php $i++; @endphp
            @endforeach
        @endif
    </div>
  </div>
  @if($menu->seo_enable == 1)
  @include('components.seo', ['data'=>null])
  @endif
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </div>
</form>
@endsection

@section('js')
  @if(count($jsArr) > 0)
    @foreach($jsArr as $js)
    <script src="/admins/js/components/{{$js}}"></script>
    @endforeach
  @endif
  <script>
  @if($menu->menuCreateDetails->count() > 0)
      @foreach($menu->menuCreateDetails as $detail)
        @switch($detail->type)
          @case('multiple_select')
            $('#{{$detail->field_id}}').multiSelect();
            @break
        @endswitch
      @endforeach
  @endif
  $(document).ready(function(){

    $('#group_id').on('change', function(){
      let id = $(this).val();

      $('#role_id option').remove();
      $('#role_id').append('<option value="">請選擇角色</option>');

      $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('api.selects.getGroupRoles') }}',
            data: {
              group_id: id
            },
            success: function (rs) {
                if (rs.status) {
                  $.each(rs.data, function(){
                    $('#role_id').append(`<option value="${this.id}">${this.name}</option>`);
                  })
                }
            },
            error: function(rs) {
                Swal.fire({
                    icon: 'error',
                    text: rs.responseJSON.message
                })
            }
        })
    })
  })
  </script>
@endsection