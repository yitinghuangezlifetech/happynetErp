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
<form id="form" enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $data->id)}}">
  @csrf
  @method('put')
  <input type="hidden" name="status" id="status" value="{{$data->status}}">
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
        @if($menu->menuEditDetails->count() > 0)
            @foreach($menu->menuEditDetails as $detail)
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
                      <div class="col-sm-6">
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
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
              @php $i++; @endphp
            @endforeach
        @endif
    </div>
  </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">合約商品金額設定</h3>
    </div>
    <div class="card-body" id="productsArea">
      @if(!empty($data->contract_id))
        @php $i = 0; @endphp
        @foreach($data->contract->productTypeLogs??[] as $log)
        <div class="card card-secondary disabled" style="margin-top: 10px;">
          <div class="card-header">
            <h3 class="card-title main-title">{{$log->productType->type_name}}</h3>
          </div>
          <div class="card-body">
            <div class="form-group row productList_{{$loop->iteration}}">
              @if($log->productType->type_name == '語音服務')
              <table class="table table-hover table-bordered text-nowrap">
                <thead>
                  <tr style="background-color: yellowgreen">
                    <td class="text-center">服務名稱</td>
                    <td class="text-center">撥打對象</td>
                    <td class="text-center">通話費率</td>
                    <td class="text-center">折讓(%)</td>
                    <td class="text-center">折後費率</td>
                    <td class="text-center">計費單位</td>
                    <td class="text-center">服務帳號</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach($log->productLogs??[] as $k=>$record)
                    <tr>
                      <td class="text-center" style="vertical-align: middle" rowspan="{{$record->product->feeRate->logs->count()+1}}">
                        <input type="checkbox" name="products[{{$i}}][product_id]" value="{{$record->product_id}}" @if(isset($applyLogs[$log->product_type_id][$record->product_id])){{'checked'}}@endif>{{$record->product->name}}
                        <input type="hidden" name="products[{{$i}}][contract_id]" value="{{$data->id}}">
                        <input type="hidden" name="products[{{$i}}][product_type_id]" value="{{$log->product_type_id}}">
                      </td>
                    </tr>  
                    @foreach($record->product->feeRate->logs??[] as $k1=>$feeRateLog)
                    <tr>
                      <td class="text-center" style="vertical-align: middle">
                        <input type="checkbox" name="products[{{$i}}][feeRates][{{$k1}}][call_target_id]" value="{{$feeRateLog->call_target_id}}" @if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])){{'checked'}}@endif>{{$feeRateLog->target->type_name}}
                      </td>
                      <td class="text-center" style="vertical-align: middle">
                        {{$feeRateLog->call_rate}}
                        <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][call_rate]" id="call_rate_{{$feeRateLog->id}}" value="{{$feeRateLog->call_rate}}">
                      </td>
                      <td class="text-center" style="vertical-align: middle">
                        @php
                          $value = '';
                          if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                            $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['discount'];
                          }
                        @endphp
                        <input type="number" class="form-control feeRateDiscount" name="products[{{$i}}][feeRates][{{$k1}}][discount]" data-logid="{{$feeRateLog->id}}" value="{{$value}}">
                      </td>
                      <td class="text-center" style="vertical-align: middle">
                        @php
                          $value = '';
                          if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                            $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['amount'];
                          }
                        @endphp
                        <input type="number" class="form-control" name="products[{{$i}}][feeRates][{{$k1}}][amount]" id="feeRateAmount_{{$feeRateLog->id}}" data-logid="{{$feeRateLog->id}}" value="{{$value}}" readonly>
                      </td>
                      <td class="text-center" style="vertical-align: middle">
                        {{$log->parameter}}
                        @if($log->charge_unit == 1)
                        秒鐘
                        @else
                        分鐘
                        @endif
                        <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][charge_unit]" value="{{$feeRateLog->charge_unit}}">
                        <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][parameter]" value="{{$feeRateLog->parameter}}">
                      </td>
                      <td class="text-center table-responsive" style="vertical-align: middle">
                        @if($k==0 && $k1 == 0)
                        <span id="userAccount"></span><br><br>
                        <table class="table table-bordered text-nowrap">
                          <tr>
                            <td class="text-center" style="background-color: #d1cbba">顥示號碼</td>
                          </tr>
                          <tr>
                            <td class="text-center" id="telecom_number"></td>
                          </tr>
                        </table>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                    @php $i++; @endphp
                  @endforeach
                </tbody>
              </table>
              @else
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr style="background-color: yellowgreen">
                    <td class="text-center">品名/型號/規格</td>
                    <td class="text-center">數量</td>
                    <td class="text-center">月租</td>
                    <td class="text-center">折讓(%)</td>
                    <td class="text-center">費用</td>
                    <td class="text-center">保證金</td>
                    <td class="text-center">備註</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach($log->productLogs??[] as $k=>$record)
                  <tr>
                    <td class="text-center">
                      {{$record->product->name}}
                      <input type="hidden" name="products[{{$i}}][contract_id]" value="{{$data->id}}">
                      <input type="hidden" name="products[{{$i}}][product_type_id]" value="{{$log->product_type_id}}">
                      <input type="hidden" name="products[{{$i}}][product_id]" value="{{$record->product_id}}">
                    </td>
                    <td class="text-center">
                      @php
                        $value = '';
                        if(isset($applyLogs[$log->product_type_id][$record->product_id]['qty'])) {
                          $value = $applyLogs[$log->product_type_id][$record->product_id]['qty'];
                        }
                      @endphp
                      <input type="number" class="form-control qty" id="qty_{{$record->product_id}}" data-productid="{{$record->product_id}}" name="products[{{$i}}][qty]" value="{{$value}}">
                    </td>
                    <td class="text-center">
                      {{$record->product->rent_month}}
                      <input type="hidden" id="rent_month_{{$record->product_id}}" name="products[{{$i}}][rent_month]" value="{{$record->product->rent_month}}">
                    </td>
                    <td class="text-center">
                      @php
                        $value = '';
                        if(isset($applyLogs[$log->product_type_id][$record->product_id]['discount'])) {
                          $value = $applyLogs[$log->product_type_id][$record->product_id]['discount'];
                        }
                      @endphp
                      <input type="text" class="form-control discount" id="discount_{{$record->product_id}}" name="products[{{$i}}][discount]" value="{{$value}}" data-productid="{{$record->product_id}}">
                    </td>
                    <td class="text-center">
                      @php
                        $value = '';
                        if(isset($applyLogs[$log->product_type_id][$record->product_id]['amount'])) {
                          $value = $applyLogs[$log->product_type_id][$record->product_id]['amount'];
                        }
                      @endphp
                      <input type="text" class="form-control" id="amount_{{$record->product_id}}" name="products[{{$i}}][amount]" value="{{$value}}" readonly>
                    </td>
                    <td class="text-center">
                      @php
                        $value = '';
                        if(isset($applyLogs[$log->product_type_id][$record->product_id]['security_deposit'])) {
                          $value = $applyLogs[$log->product_type_id][$record->product_id]['security_deposit'];
                        }
                      @endphp
                      <input type="text" class="form-control" name="products[{{$i}}][security_deposit]" value="{{$value}}">
                    </td>
                    <td class="text-center">
                      @php
                        $value = '';
                        if(isset($applyLogs[$log->product_type_id][$record->product_id]['note'])) {
                          $value = $applyLogs[$log->product_type_id][$record->product_id]['note'];
                        }
                      @endphp
                      <input type="text" class="form-control" name="products[{{$i}}][note]" value="{{$value}}">
                    </td>
                  </tr>
                  @php $i++; @endphp
                  @endforeach
                </tbody>
              </table>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      @else

      @endif
    </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">合約條文</h3>
    </div>
    <div class="card-body" id="regulationArea">
      <div class="card card-secondary disabled" style="margin-top: 10px;">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              @foreach($data->terms??[] as $log)
              <div class="form-group">
              {{$log->term->title}}：{{$log->term->describe}}<br>@if(!empty($log->term->content))<br>@endif
              {!! $log->term->content !!}
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
      <button type="button" class="btn bg-gradient-secondary" id="sendApply">送件</button>
      <button type="button" class="btn bg-gradient-secondary deleteBtn"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </div>
</form>
<form id="delete_form" method="POST" style="display: none;"></form>
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
  <script src="/admins/plugins/sortable/Sortable.js"></script>
  <script src="/admins/plugins/jSignature/jSignature.min.js"></script>
  <script>
  let productTypeArr = [];
  let terms = [];

  @foreach($data->terms??[] as $log)
    terms.push('{{$log->term_id}}');
  @endforeach

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
    @if($menu->menuCreateDetails->count() > 0)
        @foreach($menu->menuCreateDetails as $detail)
          @switch($detail->type)
            @case('multiple_select')
              $('#{{$detail->field_id}}').multiSelect();
              @break
          @endswitch
        @endforeach
    @endif

    const init = () => {
      const userId = $('#edit_user_id').val();
      const userAccount = $('#edit_user_id option:selected').text();
      $('#sender_id').val('{{$user->id}}');

      $('#sender_id option').each(function(){
        if ($(this).val() != '{{$user->id}}') {
          $(this).remove();
        }
      })

      @if(!empty($data->contract_id))
      $('#edit_project_id').attr('disabled', true);
      @elseif(!empty($data->project_id))
      $('#edit_contract_id').attr('disabled', true);
      @endif

      $('#userAccount').text(userAccount);

      $.ajax({
            headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
            method: 'post',
            url: '{{ route('api.public.getUserInfo') }}',
            data: {
              user_id: userId
            },
            success: function(rs) {
              $('#telecom_number').text(rs.data.telecom_number);
            },
            error: function(rs) {
              Swal.fire({
                icon: 'error',
                title: '訊息提示',
                text: rs.responseJSON.message
              })
            }
        })
    }
    init();

    $('body').on('change', '.qty', function(){
      const qty = parseInt($(this).val());
      const productId = $(this).data('productid');
      const discount = $(`#discount_${productId}`).val();
      const rent = $(`#rent_month_${productId}`).val();
      let price;

      if (discount == '') {
        price = rent * qty;
        $(`#amount_${productId}`).val(price);
      } else {
        price = Math.round( rent * (discount / 100)) * qty;
        $(`#amount_${productId}`).val(price);
      }
    })

    $('body').on('change', '.discount', function(){
      const discount = parseInt($(this).val());
      const productId = $(this).data('productid');
      const qty = parseInt($(`#qty_${productId}`).val());
      const rent = $(`#rent_month_${productId}`).val();
      let price;

      if (discount == '') {
        price = rent * qty;
        $(`#amount_${productId}`).val(rent);
      } else {
        price = Math.round( rent * (discount / 100)) * qty;
        $(`#amount_${productId}`).val(price);
      }
    })

    $('body').on('change', '.feeRateDiscount', function(){
      const logId = $(this).data('logid');
      const discount = parseInt($(this).val());
      const rate = $(`#call_rate_${logId}`).val();
      let amount = rate * (discount / 100);

      $(`#feeRateAmount_${logId}`).val(amount);
    })

    $('#sendApply').click(function(){
      $('#status').val(5);
      $('#form').submit();
    })

    $('#edit_contract_id').change(function(){
      const id = $(this).val();
      const selected = $(this).find('option:selected');
      const contractName = selected.text();
      const planTypeId = selected.data('plantype');
      const applyTypeId = selected.data('applytype');

      if (id == '') {
        $('#edit_project_id').attr('disabled', false);
        $('#edit_contract_name').val('');
        $('#productsArea').html('');
        $('#regulationArea').html('');

        //方案類別
        $('#edit_plan_type_id').val('');
        $('#edit_plan_type_id option').remove();
        $('#edit_plan_type_id').append(`<option value="">請選擇</option>`);
        @foreach($planTypes??[] as $type)
        $('#edit_plan_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

        //申請類別
        $('#edit_apply_type_id').val('');
        $('#edit_apply_type_id option').remove();
        $('#edit_apply_type_id').append(`<option value="">請選擇</option>`);
        @foreach($applyTypes??[] as $type)
        $('#edit_apply_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

      } else {
        $('#edit_project_id').attr('disabled', true);
        $('#edit_contract_name').val(contractName);
        $('#edit_plan_type_id').val(planTypeId);
        $('#edit_apply_type_id').val(applyTypeId);

        $('#edit_plan_type_id option').each(function(){
          if ($(this).val() != planTypeId) {
            $(this).remove();
          }
        })

        $('#edit_apply_type_id option').each(function(){
          if ($(this).val() != applyTypeId) {
            $(this).remove();
          }
        })

        $.ajax({
            headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
            method: 'post',
            url: '{{ route('api.public.getContractProducts') }}',
            data: {
              contract_id: id
            },
            success: function(rs) {
              $('#productsArea').append(rs.data);
            },
            error: function(rs) {
              Swal.fire({
                icon: 'error',
                title: '訊息提示',
                text: rs.responseJSON.message
              })
            }
        })

        $.ajax({
            headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
            method: 'post',
            url: '{{ route('api.public.getContractRegulations') }}',
            data: {
              contract_id: id
            },
            success: function(rs) {
              $('#regulationArea').append(rs.data);
            },
            error: function(rs) {
              Swal.fire({
                icon: 'error',
                title: '訊息提示',
                text: rs.responseJSON.message
              })
            }
        })
      }  
    })

    $('#edit_project_id').change(function(){
      const id = $(this).val();
      const selected = $(this).find('option:selected');
      const contractName = selected.text();
      const applyTypeId = selected.data('applytype');

      $('#productsArea').html('');
      $('#regulationArea').html('');

      if (id == '') {
        $('#edit_contract_id').attr('disabled', false);
        $('#edit_contract_name').val('');

        //申請類別
        $('#edit_apply_type_id').val('');
        $('#edit_apply_type_id option').remove();
        $('#edit_apply_type_id').append(`<option value="">請選擇</option>`);
        @foreach($applyTypes??[] as $type)
        $('#edit_apply_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

      } else {

        $('#edit_contract_id').attr('disabled', true);
        $('#edit_contract_name').val(contractName);

        $('#edit_apply_type_id').val(applyTypeId);

        $('#edit_apply_type_id option').each(function(){
          if ($(this).val() != applyTypeId) {
            $(this).remove();
          }
        })

        $.ajax({
            headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
            method: 'post',
            url: '{{ route('api.public.getProjectProducts') }}',
            data: {
              project_id: id
            },
            success: function(rs) {
              $('#productsArea').append(rs.data);
            },
            error: function(rs) {
              Swal.fire({
                icon: 'error',
                title: '訊息提示',
                text: rs.responseJSON.message
              })
            }
        })
      }  
    })

    $('#edit_identity_id').change(function(){
      const id = $(this).val();
      
      $('#edit_organization_id option').remove();
      $('#edit_organization_id').append(`<option value="">請選擇</option>`);

      $.ajax({
          headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
          method: 'post',
          url: '{{ route('api.public.getOrganizationByIdentity') }}',
          data: {
            identity: id
          },
          success: function(rs) {
            let options = '';

            rs.data.forEach(function(info){
              options += `<option value="${info.id}">${info.name}</option>`;
            })

            $('#edit_organization_id').append(options);
          },
          error: function(rs) {
            Swal.fire({
              icon: 'error',
              title: '訊息提示',
              text: rs.responseJSON.message
            })
          }
      })
    })

    $('#edit_organization_id').change(function(){
      const id = $(this).val();

      $('#user_id option').remove();
      $('#user_id').append(`<option value="">請選擇</option>`);

      $.ajax({
          headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
          method: 'post',
          url: '{{ route('api.public.getOrganizationUsers') }}',
          data: {
            organization_id: id
          },
          success: function(rs) {
            let options = '';

            rs.data.forEach(function(info){
              options += `<option value="${info.id}">${info.name}(帳號:${info.account})</option>`;
            })

            $('#edit_user_id').append(options);
          },
          error: function(rs) {
            Swal.fire({
              icon: 'error',
              title: '訊息提示',
              text: rs.responseJSON.message
            })
          }
      })

      $.ajax({
          headers: { 'apikey': '{{env('HAPPYNET_APIKEY')}}'  },
          method: 'post',
          url: '{{ route('api.public.getOrganization') }}',
          data: {
            id: id
          },
          success: function(rs) {
            $('#edit_system_no').attr('readonly', true);
            $('#edit_system_no').val(rs.data.system_no);
          },
          error: function(rs) {

            $('#edit_system_no').attr('readonly', false);

            Swal.fire({
              icon: 'error',
              title: '訊息提示',
              text: rs.responseJSON.message
            })
          }
      })

    })

    $(".signBoard").jSignature({ 'width': '100%', 'height': 300});

    var items = document.getElementById('regulationArea');
    new Sortable(regulationArea, {
        animation: 150,
        ghostClass: 'blue-background-class',
        onEnd: function (/**Event*/evt) {
          var sort = 0;
          var number = 1;
          $('#regulationArea .main-title').each(function(){
            $(this).html(`<i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文${number}`);
            number++;
          })
          $('#regulationArea input[id^="sort_"]').each(function(){
            $(this).val(sort);
            sort++;
          })
        },
    });

    $('body').on('click', '.addProducts', function(){
      const saleTypeId = $('#edit_sales_type_id').val();
      let row = 0;
      let str = '';

      do {
        row++;
      }while($(`#car_${row}`).length > 0);

      if (saleTypeId == '') {
        Swal.fire({
          icon: 'error',
          title: '訊息提示',
          text: '請選擇銷售模式'
        })
      } else {
        str += `
        <div class="card card-secondary disabled" id="car_${row}" style="margin-top: 10px;">
          <div class="card-header">
            <h3 class="card-title main-title">商品</h3>
            <div style="float: right;">
              <table>
                <tr>
                  <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeProduct" style="color:white;" data-row="${row}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group row">
              <label for="product_type_id_${row}">商品類別</label>
              <select class="custom-select form-control-border productType" name="products[${row}][product_type_id]" id="product_type_id_${row}" data-row="${row}" required>
                <option value="">請選擇商品類別</option>
                @foreach($types??[] as $type)
                <option value="{{$type->id}}">{{$type->type_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group row productList_${row}"></div>
        </div>
        `;

        $('#productsArea').prepend(str);

        let number = 1;
        $('#productsArea .main-title').each(function(){
          $(this).html(`商品${number}`);
          number++;
        })
      }
    })

    $('body').on('change', '.productType', function(){
      const saleTypeId = $('#sales_type_id').val();
      const id = $(this).val();
      const row = $(this).data('row');

      $(`#productList_${row}`).html('');

      if (productTypeArr.includes(id)) {
        $(this).val('');

        Swal.fire({
          icon: 'error',
          title: '訊息提示',
          text: '您已選了該類型的商品, 請重新選擇'
        })
      } else {
        if (saleTypeId == '') {
          Swal.fire({
            icon: 'error',
            title: '訊息提示',
            text: '請選擇銷售模式'
          })
        } else {
          $.ajax({
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              method: 'post',
              url: '{{ route('tables.getProductsByFilters') }}',
              data: {
                sales_type_id: saleTypeId,
                product_type_id: id,
                row: row
              },
              success: function(rs) {
                productTypeArr.push(id);

                $(`.productList_${row}`).html(rs.data);
              }
          })
        }
      }
    })

    $('body').on('click', '.removeProduct', function(){
      const row = $(this).data('row');
      const typeId = $(`#product_type_id_${row}`).val();
      let index = productTypeArr.indexOf(typeId);

      if (index !== -1) {
        productTypeArr.splice(index, 1);
      }

      $(`#car_${row}`).remove();

      let number = 1;
      $('#productsArea .main-title').each(function(){
        $(this).html(`商品${number}`);
        number++;
      })
    })

    $('body').on('click', '.addRegulation', function(){
      const saleTypeId = $('#sales_type_id').val();
      let row = 0;
      let str = '';

      do {
        row++;
      }while($(`#regulation_car_${row}`).length > 0);

      if (saleTypeId == '') {
        Swal.fire({
          icon: 'error',
          title: '訊息提示',
          text: '請選擇銷售模式'
        })
      } else {
        str += `
        <div class="card card-secondary disabled" id="regulation_car_${row}" style="margin-top: 10px;">
          <div class="card-header">
            <h3 class="card-title main-title"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文</h3>
            <div style="float: right;">
              <table>
                <tr>
                  <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeRegulation" style="color:white;" data-row="${row}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="card-body">
            <input type="hidden" name="regulations[${row}][sort]" id="sort_${row}" value="${row}">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="term_type_id_${row}">條文類型</label>
                  <select class="custom-select form-control-border termType" name="regulations[${row}][term_type_id]" id="term_type_id_${row}" data-row="${row}" required>
                    <option value="">請選擇條文類型</option>
                    @foreach($termTypes??[] as $type)
                    <option value="{{$type->id}}">{{$type->type_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="regulation_product_type_id_${row}">適用商品類型</label>
                  <select class="custom-select form-control-border regulationProductType" name="regulations[${row}][product_type_id]" id="regulation_product_type_id_${row}" data-row="${row}">
                    <option value="">請選擇商品類別</option>
                    @foreach($types??[] as $type)
                    <option value="{{$type->id}}">{{$type->type_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" id="regulationItem_${row}"></div>
              </div>
            </div>
        </div>
        `;

        $('#regulationArea').prepend(str);

        let number = 1;
        let sort = 0;
        $('#regulationArea .main-title').each(function(){
          $(this).html(`<i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文${number}`);
          number++;
        })

        $('#regulationArea input[id^="sort_"]').each(function(){
            $(this).val(sort);
            sort++;
        })
      }
    })

    $('body').on('change', '.termType', function(){
      const id = $(this).val();
      const row = $(this).data('row');
      const saleTypeId = $('#sales_type_id').val();
      const productTypeId = $(`#regulation_product_type_id_${row}`).val();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          method: 'post',
          url: '{{ route('tables.getTermsByFilters') }}',
          data: {
            sales_type_id: saleTypeId,
            term_type_id: id,
            product_type_id: productTypeId,
            row: row
          },
          success: function(rs) {
            $(`#regulationItem_${row}`).html(rs.data);

            if (terms.length > 0) {
              terms.forEach(function(term){
                $(`#term_id_${term}`).remove();
              })
            }
          }
      })
    })

    $('body').on('click', '.term_id', function(){
      terms = [];

      $('.term_id').each(function(){
        if ($(this).prop('checked')) {
          terms.push($(this).val())
        }
      })
    })

    $('body').on('click', '.removeRegulation', function(){
      const row = $(this).data('row');

      $(`#regulationItem_${row} .term_id`).each(function(){
        if ($(this).prop('type') == 'checkobx') {
          if ($(this).prop('checked')) {
            let index = terms.indexOf($(this).val());

            if (index !== -1) {
              terms.splice(index, 1);
            }
          }
        } else {
          let index = terms.indexOf($(this).val());

          if (index !== -1) {
            terms.splice(index, 1);
          }
        }
      })

      $(`#regulation_car_${row}`).remove();

      let number = 1;
      $('#regulationArea .main-title').each(function(){
        $(this).html(`<i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文${number}`);
        number++;
      })
    })
  })
  </script>
@endsection