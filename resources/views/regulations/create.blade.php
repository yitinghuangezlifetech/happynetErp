@extends('layouts.main')

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">建立{{$menu->name}}資料</h3>
  </div>
  <form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}" onsubmit="return checkForm()">
    @csrf
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
              @if($detail->super_admin_use == 1 && $user->super_admin == 1)
                @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>'', 'columns'=>$columns])
              @else  
                @if($detail->show_hidden_field == 1)
                  @php
                    $value = null;

                    if ($detail->field == 'user_id') {
                      $value = $user->id;
                    }
                  @endphp
                  @include('components.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
                @else
                  @if($detail->type == 'multiple_input')
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
                  @else
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>'', 'columns'=>$columns])
                  @endif  
                @endif
              @endif  
            @endforeach
        @endif
        <div class="form-group">
          <label class="col-form-label">缺失項目</label>
          @foreach ($failTypes as $type)
          <div class="form-check">
            <input class="form-check-input failType" type="checkbox" name="fails[]" value="{{$type->id}}">
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
@endsection

@section('js')
  @if(count($jsArr) > 0)
    @foreach($jsArr as $js)
    <script src="/admins/js/components/{{$js}}"></script>
    @endforeach
  @endif
<script>
$('body').on('change', '#main_attribute_id', function(){
  var id = $(this).val();

  $('#sub_attribute_id option').remove();
  $('#sub_attribute_id').append('<option value="">請選擇</option>');

  @if(count($subAttributes) > 0)
    switch (id) {
      @foreach($subAttributes as $key=>$list)
        case '{{$key}}':
          @foreach($list as $val)
            $('#sub_attribute_id').append('<option value="{{$val['id']}}">{{$val['name']}}</option>');
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