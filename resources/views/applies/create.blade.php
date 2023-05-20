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
          $assignJsArr = [];
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
                $json = [];
                if ($detail->has_js == 1)  {
                  if (!in_array($detail->type.'.js', $jsArr)) {
                    array_push($jsArr, $detail->type.'.js');
                  }
                  if ($detail->type == 'content_builder') {
                    if (!in_array('image.js', $jsArr)) {
                      array_push($jsArr, 'image.js');
                    }
                    if (!in_array('ckeditor.js', $jsArr)) {
                      array_push($jsArr, 'ckeditor.js');
                    }
                  }
                  if($detail->type == 'ckeditor') {
                    if (!in_array($detail->field, $fieldNameArr)) {
                      array_push($fieldNameArr, $detail->field);
                    }
                  }
                }
                if (!empty($detail->assign_js)) {
                  array_push($assignJsArr, $detail->assign_js);
                }
                if ($detail->use_component == 1) {
                  if (!in_array($detail->component_name.'.js', $jsArr)) {
                    array_push($jsArr, $detail->component_name.'.js');
                  }
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
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                  @endif
                @else
                  @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
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
                    }
                    $i++;
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
                  @elseif($detail->type == 'multiple_select' || $detail->type == 'ckeditor' || $detail->type == 'multiple_input' || $detail->type == 'content_builder' || $detail->type == 'multiple_img')
                    @if(count($json) > 0)
                      @if (in_array($user->role->systemType->name, $json))
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-sm-12">
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                        </div>
                      </div>
                      <div class="row">
                      @php $i++; @endphp
                      @endif
                    @else
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-sm-12">
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
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
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                        </div>
                        @endif
                      @else
                        <div class="col-sm-6">
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                        </div>
                      @endif
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
      <h3 class="card-title">選擇商品</h3>
      <div style="float: right;">
        <button type="button" class="btn btn-block btn-outline-secondary btn-sm addProducts" style="color:white"><i class="fas fa-plus-circle"></i> 增加商品</button>
      </div>
    </div>
    <div class="card-body" id="productItemArea">
      <div class="card card-secondary disabled" id="car_0" style="margin-top: 10px;">
        <div class="card-header">
          <h3 class="card-title main-title">商品1</h3>
          <div style="float: right;">
            <table>
              <tr>
                <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeSubject" style="color:white;" data-row="0"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="product_type_id_0">商品類別</label>
            <select class="custom-select form-control-border productType" name="products[0][product_type_id]" id="product_type_id_0" data-row="0" required>
              <option value="">請選擇商品類別</option>
              @foreach($types??[] as $type)
              <option value="{{$type->id}}">{{$type->type_name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row productList_0"></div>
      </div>
    </div>
  </div>
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </div>
</form>
@endsection

@section('js')
<script src="/admins/js/components/image.js"></script>
<script src="/admins/plugins/jSignature/jSignature.min.js"></script>
<script>
$(document).ready(function(){
    $(".signBoard").jSignature({ 'width': '100%', 'height': 300});
})
</script>
@endsection