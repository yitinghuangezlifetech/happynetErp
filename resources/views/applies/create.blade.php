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
                    <div class="col-sm-6">
                      @if($detail->field == 'contract_id')
                      <div class="form-group">
                        <label for="contract_id">{{$detail->show_name}}</label>
                        <select class="form-control" name="contract_id" id="contract_id">
                            <option value="">請選擇</option>
                            @foreach($contracts??[] as $contract)
                            <option value="{{$contract->id}}" data-plantype="{{$contract->plan_type_id}}" data-applytype="{{$contract->apply_type_id}}">{{$contract->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      @elseif($detail->field == 'project_id')
                      <div class="form-group">
                        <label for="project_id">{{$detail->show_name}}</label>
                        <select class="form-control" name="project_id" id="project_id">
                            <option value="">請選擇</option>
                            @foreach($projects??[] as $project)
                            <option value="{{$project->id}}" data-applytype="{{$project->apply_type_id}}">{{$project->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      @else
                        @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                      @endif  
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
      <h3 class="card-title">合約商品綁定</h3>
      <div style="float: right;">
        <button type="button" class="btn btn-block btn-outline-secondary btn-sm addProducts" style="color:white"><i class="fas fa-plus-circle"></i> 增加商品</button>
      </div>
    </div>
    <div class="card-body" id="productsArea"></div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">合約條文綁定</h3>
      <div style="float: right;">
        <button type="button" class="btn btn-block btn-outline-secondary btn-sm addRegulation" style="color:white"><i class="fas fa-plus-circle"></i> 增加條文</button>
      </div>
    </div>
    <div class="card-body" id="regulationArea"></div>
  </div>
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">送件</button>
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
    @foreach($jsArr as $js)
    <script src="/admins/js/assigns/{{$js}}"></script>
    @endforeach
  @endif
  <script src="/admins/plugins/sortable/Sortable.js"></script>
  <script src="/admins/plugins/jSignature/jSignature.min.js"></script>
  <script>
  let productTypeArr = [];
  let terms = [];

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
      $("#"+idName).jSignature('reset');
      $("#"+idName).show();
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
      $('#sender_id').val('{{$user->id}}');

      $('#sender_id option').each(function(){
        if ($(this).val() != '{{$user->id}}') {
          $(this).remove();
        }
      })
    }
    init();

    $('#contract_id').change(function(){
      const id = $(this).val();
      const selected = $(this).find('option:selected');
      const contractName = selected.text();
      const planTypeId = selected.data('plantype');
      const applyTypeId = selected.data('applytype');

      if (id == '') {
        $('#project_id').attr('disabled', false);
        $('#contract_name').val('');

        //方案類別
        $('#plan_type_id').val('');
        $('#plan_type_id option').remove();
        $('#plan_type_id').append(`<option value="">請選擇</option>`);
        @foreach($planTypes??[] as $type)
        $('#plan_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

        //申請類別
        $('#apply_type_id').val('');
        $('#apply_type_id option').remove();
        $('#apply_type_id').append(`<option value="">請選擇</option>`);
        @foreach($applyTypes??[] as $type)
        $('#apply_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

      } else {
        $('#project_id').attr('disabled', true);
        $('#contract_name').val(contractName);
        $('#plan_type_id').val(planTypeId);
        $('#apply_type_id').val(applyTypeId);

        $('#plan_type_id option').each(function(){
          if ($(this).val() != planTypeId) {
            $(this).remove();
          }
        })

        $('#apply_type_id option').each(function(){
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

    $('#project_id').change(function(){
      const id = $(this).val();
      const selected = $(this).find('option:selected');
      const contractName = selected.text();
      const applyTypeId = selected.data('applytype');

      if (id == '') {
        $('#contract_id').attr('disabled', false);
        $('#contract_name').val('');

        //申請類別
        $('#apply_type_id').val('');
        $('#apply_type_id option').remove();
        $('#apply_type_id').append(`<option value="">請選擇</option>`);
        @foreach($applyTypes??[] as $type)
        $('#apply_type_id').append(`<option value="{{$type->id}}">{{$type->type_name}}</option>`);
        @endforeach

      } else {

        $('#contract_id').attr('disabled', true);
        $('#contract_name').val(contractName);

        $('#apply_type_id').val(applyTypeId);

        $('#apply_type_id option').each(function(){
          if ($(this).val() != applyTypeId) {
            $(this).remove();
          }
        })
      }  
    })

    $('#identity_id').change(function(){
      const id = $(this).val();
      
      $('#organization_id option').remove();
      $('#organization_id').append(`<option value="">請選擇</option>`);

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

            $('#organization_id').append(options);
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

    $('#organization_id').change(function(){
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

            $('#user_id').append(options);
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
            $('#system_no').attr('readonly', true);
            $('#system_no').val(rs.data.system_no);
          },
          error: function(rs) {

            $('#system_no').attr('readonly', false);

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
      const saleTypeId = $('#sales_type_id').val();
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
        $('#regulationArea .main-title').each(function(){
          $(this).html(`<i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文${number}`);
          number++;
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
        if ($(this).prop('checked')) {
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