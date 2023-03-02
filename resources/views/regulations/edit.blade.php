@extends('layouts.main')

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">編輯{{$menu->name}}資料</h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $id)}}" onsubmit="return checkForm()">
    @csrf
    @method('put')
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
              @if($detail->super_admin_use == 1 && $user->super_admin == 1)
                @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}, 'columns'=>$columns])
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
                      @foreach($options as $key=>$option)
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}, 'columns'=>$columns])
                      @endforeach
                      </div>
                    @else
                    <div class="form-group">
                      <label>
                        @if(isset($columns[$detail->field]))
                          {{$columns[$detail->field]}}
                        @else
                          {{$detail->show_name}}
                        @endif
                      </label>
                        <div id="{{ $detail->field }}">
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null, 'columns'=>$columns])
                        </div>
                    </div>
                    @endif    
                  @else
                    @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}, 'columns'=>$columns])
                  @endif
                @endif
              @endif
            @endforeach
        @endif
        <div class="form-group">
          <label class="col-form-label">缺失項目</label>
          @foreach ($failTypes as $type)
          @php
            $regulationId = null;
            $log = $type->hasRegulation($data->id, $type->id);
            if($log) {
              $regulationId = $log->regulation_id;
            }
          @endphp
          <div class="form-check">
            <input class="form-check-input failType" type="checkbox" name="fails[]" value="{{$type->id}}" @if($regulationId == $data->id){{'checked'}}@endif>
            <label class="form-check-label">{{$type->name}}</label>
          </div>
          @endforeach
        </div>
    </div>
    <div class="card-footer text-center">
      <button type="submit" class="btn btn-primary">儲存</button>
    </div>
  </form>
</div>
<!-- /.row -->
@endsection

@section('js')
  @if(count($jsArr) > 0)
    @foreach($jsArr as $js)
    <script src="/js/components/{{$js}}"></script>
    @endforeach
  @endif
<script>
  $('body').on('change', '#edit_main_attribute_id', function(){
    var id = $(this).val();
  
    $('#edit_sub_attribute_id option').remove();
    $('#edit_sub_attribute_id').append('<option value="">請選擇</option>');
  
    @if(count($subAttributes) > 0)
      switch (id) {
        @foreach($subAttributes as $key=>$list)
          case '{{$key}}':
            @foreach($list as $val)
              $('#edit_sub_attribute_id').append('<option value="{{$val['id']}}">{{$val['name']}}</option>');
            @endforeach
            break;
        @endforeach
        default:
          Swal.fire({
            icon: 'error',
            title: '訊息提示',
            text: '該屬性底下沒有次屬性'
          })
          break;
      }
    @endif
  }) 
var checkForm = function(){
  var items = 0;
  $('.failType').each(function(){
    if($(this).prop('checked')) {
      items++;
    }
  })
  if(items == 0) {
    Swal.fire({
      icon: 'error',
      title: '訊息提示',
      text: '請選擇條文對應之缺失項目'
    })
    return false;
  }
  return true;
}
  </script>    
@endsection