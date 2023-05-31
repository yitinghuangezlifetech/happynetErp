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
<form id="form" enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $data->id)}}" onsubmit="return checkSign()">
  @csrf
  @method('put')
  <input type="hidden" name="status" id="status" value="3">
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">編輯{{$menu->name}}資料</h3>
    </div>
    <div class="card-body">
        @php
          $jsArr = [];
          $assignJsArr = [];
          $fieldNameArr = [];
          if($menu->seo_enable == 1) {
            array_push($jsArr, 'image.js');
          }
          $i = 1;
        @endphp
        @if($menu->menuEditAllDetails->count() > 0)
            @foreach($menu->menuEditAllDetails as $detail)
              @if($loop->first)
                <div class="row">
              @endif
              @php
                $json = [];
                
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
                if ($detail->use_component == 1) {
                  if (!in_array($detail->component_name.'.js', $jsArr)) {
                    array_push($jsArr, $detail->component_name.'.js');
                  }
                }
                if (!empty($detail->assign_js)) {
                  array_push($assignJsArr, $detail->assign_js);
                }
                if (!empty($detail->applicable_system)) {
                  $json = json_decode($detail->applicable_system, true);
                }
                $halfRows = '';
              @endphp
              @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
              <div class="col-sm-6">
                @if(count($json) > 0)
                  @if (in_array($user->role->systemType->name, $json))
                    @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
                  @endif
                @else
                  @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
                @endif
              </div>
              @else  
                @if($detail->show_hidden_field == 1)
                  @php
                    $value = null;

                    if ($detail->field == 'user_id') {
                      $value = $user->id;
                    }
                    else  if ($detail->field == 'system_type_id') {
                      $value = $user->role->system_type_id;
                    } else {
                      $value = $data->{$detail->field};
                    }
                    $i++;
                  @endphp
                  @include('components.fields.hidden', ['type'=>'edit', 'detail'=>$detail, 'value'=>$value])
                @else
                  @if($detail->use_component == 1)
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      @include('components.'.$detail->component_name, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data])
                    </div>
                  </div>
                  <div class="row">
                  @php $i++; @endphp
                  @elseif($detail->type == 'multiple_input')
                    @php
                      $options = [];
                      
                      if ($detail->has_relationship == 1) {
                          $jsonArr = json_decode($detail->relationship, true);
                          if (is_array($jsonArr) && count($jsonArr) > 0) {
                              $options = app($jsonArr['model'])->where($jsonArr['references_field'], $data->{$detail->foreign_key})->get();
                          }
                      }
                    @endphp
                    @if(count($json) > 0)
                      @if (in_array($user->role->systemType->name, $json))
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                          <div id="{{ $detail->field }}">
                          @if($options->count() > 0)  
                            @foreach($options??[] as $key=>$option)
                              @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                            @endforeach
                          @else
                            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>[], 'index'=>0, 'value'=>null])
                          @endif
                          </div>
                        </div>
                      </div>
                      <div class="row">
                      @php $i++; @endphp
                      @endif
                    @else
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                          <div id="{{ $detail->field }}">
                          @if($options->count() > 0)  
                            @foreach($options??[] as $key=>$option)
                              @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                            @endforeach
                          @else
                            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>[], 'index'=>0, 'value'=>null])
                          @endif
                          </div>
                        </div>
                      </div>
                      <div class="row">
                      @php $i++; @endphp
                    @endif
                  @elseif($detail->type == 'multiple_select' || $detail->type == 'ckeditor' || $detail->type == 'multiple_img')
                    @if(count($json) > 0)
                        @if (in_array($user->role->systemType->name, $json))
                        </div><br>
                        <div class="row">
                          <div class="col-sm-12">
                          @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data, 'value'=>$data->{$detail->field}])
                          </div>
                        </div>
                        <div class="row">
                        @php $i++; @endphp
                        @endif
                      @else
                        </div><br>
                        <div class="row">
                          <div class="col-sm-12">
                          @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data, 'value'=>$data->{$detail->field}])
                          </div>
                        </div>
                        <div class="row">
                        @php $i++; @endphp
                      @endif
                  @else
                    @if($detail->field == 'empty')
                    <div class="col-sm-6"></div>
                    @else
                      @if(count($json) > 0)
                        @if (in_array($user->role->systemType->name, $json))
                        <div class="col-sm-6">
                          @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                        </div>
                        @endif
                      @else
                        @if($detail->field == 'company_seal' || $detail->field == 'company_stamp' || $detail->field == 'sender_sign' || $detail->field == 'customer')
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>{{$detail->show_name}}</label>
                            <img src="{{ $data->{$detail->field} }}" width="150px" height="150px" style="float: right">
                          </div>
                        </div>
                        @else
                          @if($detail->field != 'technician_id' && $detail->field != 'technician_sign' && $detail->field != 'auditor_id' && $detail->field != 'auditor_sign' && $detail->field != 'fail_response')
                          <div class="col-sm-6">
                            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                          </div>
                          @endif
                        @endif
                      @endif
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
  </div>
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">合約商品綁定</h3>
    </div>
    <div class="card-body" id="productsArea">
      @foreach($logs??[] as $k=>$log)
      <div class="card card-secondary disabled" id="car_{{$loop->iteration}}" style="margin-top: 10px;">
        <div class="card-header">
          <h3 class="card-title main-title">商品{{$loop->iteration}}</h3>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="product_type_id_{{$loop->iteration}}">商品類別</label>
            <select class="custom-select form-control-border productType" name="products[{{$loop->iteration}}][product_type_id]" id="product_type_id_{{$loop->iteration}}" data-row="{{$loop->iteration}}" required>
              <option value="">請選擇商品類別</option>
              @foreach($types??[] as $type)
              <option value="{{$type->id}}" @if($k==$type->id){{'selected'}}@endif>{{$type->type_name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row productList_{{$loop->iteration}}">
            <table class="table table-hover text-nowrap">
              <tbody>
                @php
                    $lineCount = 1;
                    $row = $loop->iteration;
                    $products = $obj->getProductsByTypeId($k);
                @endphp
                @foreach($products??[] as $product)
                  @php
                      $rows = $loop->count;
                      $line = ceil($rows / 5);
                  @endphp
                  @if($loop->first)
                  <tr>
                  @endif
                    <td style="text-align: left;max-width:20%; width:20%">
                      <input type="checkbox" class="items" name="products[{{$row}}][items][]" value="{{$product->id}}" @if(isset($log[$product->id])){{'checked'}}@endif>
                      {{$product->name}}
                  </td>
                  @if($loop->iteration % 5 == 0)
                  @php
                      $lineCount++;
                  @endphp
                  </tr>
                  <tr>
                  @endif  
                  @if($loop->last)  
                    @if($line == $lineCount)
                        @php
                            $remain = ($line * 5) - $rows;
                        @endphp
                        @for($i=1;$i<=$remain;$i++)
                        <td style="max-width:20%; width:20%"></td>
                        @endfor
                    @endif
                  @endif
                @endforeach
              </tbody>
          </table>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">合約條文綁定</h3>
    </div>
    <div class="card-body" id="regulationArea">
    @foreach($data->terms??[] as $log)
    <div class="card card-secondary disabled" id="regulation_car_{{$loop->iteration}}" style="margin-top: 10px;">
      <div class="card-header">
        <h3 class="card-title main-title"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文{{$loop->iteration}}</h3>
        <div style="float: right;">
          <table>
            <tr>
              <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeRegulation" style="color:white;" data-row="{{$loop->iteration}}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group" id="regulationItem_{{$loop->iteration}}">
            {{$log->term->title}}：{{$log->term->describe}}
            <input type="hidden" name="regulations[{{$loop->iteration}}][sort]" id="sort_{{$log->sort}}" value="{{$log->sort}}">
            <input type="hidden" class="term_id" name="regulations[{{$loop->iteration}}][term_id]" value="{{$log->term_id}}">
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
    </div>
  </div>
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">確認簽收</button>
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
  @if(count($assignJsArr) > 0)
    @foreach($assignJsArr as $js)
    <script src="/admins/js/assigns/{{$js}}"></script>
    @endforeach
  @endif
  <script src="/admins/plugins/jSignature/jSignature.min.js"></script>
  <script>
  const checkSign = () => {
    const rows = parseInt($('#recipient_sign_area img').length);

    if (rows == 0) {
      Swal.fire({
        icon: 'error',
        title: '訊息提示',
        text: '請請上傳收件人簽名檔'
      })

      return false;
    }

    return true;
  }

  var convertToBase64 = function(areaName, idName) {
      var fieldName = idName.replace('_board', '');
      var $sigdiv = $('#'+idName);
      var datapair = $sigdiv.jSignature("getData", "svgbase64");
      var i = new Image();
      $('#'+areaName).html('');

      i.src = "data:" + datapair[0] + "," + datapair[1];
      $(i).appendTo($("#"+areaName));
      $('#'+areaName).append('<input type="hidden" name="'+fieldName+'" id="'+fieldName+'" value="'+datapair+'">');
      $("#"+idName).hide();
      $("#"+idName).jSignature('reset');
  }

  var resetSign = function(areaName, idName) {
      $('#'+areaName).html('');
      $(`#${idName}_input`).attr('data-default-file', '');
      $("#"+idName).jSignature('reset');
      $("#"+idName).show();

      $('.dropify').dropify({
          messages: {
              'default': '請上傳簽名檔'
          }
      });
  }

  $(document).ready(function(){

    $(".signBoard").jSignature({ 'width': '100%', 'height': 300});

    const init = () => {
      $('body input').attr('disabled', true);
      $('body select').attr('disabled', true);
      $('.removeRegulation').remove();

      $('input[name="_token"]').attr('disabled', false);
      $('input[name="_method"]').attr('disabled', false);
      $('#edit_recipient_id').attr('disabled', false);
      $('#status').attr('disabled', false);

      $('#edit_recipient_id').val('{{$user->id}}');

      $('#edit_recipient_id option').each(function(){
        if ($(this).val() != '{{$user->id}}') {
          $(this).remove();
        }
      })
    }
    init();
  })
  </script>
@endsection