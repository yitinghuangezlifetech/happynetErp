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
<form id="form" enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}">
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
      <h3 class="card-title">內容設定</h3>
      <div style="float: right;">
        <button type="button" class="btn btn-block btn-outline-secondary btn-sm addSubject" style="color:white"><i class="fas fa-plus-circle"></i> 增加項目</button>
      </div>
    </div>
    <div class="card-body" id="subjectContent"></div>
  </div>
  <div class="card">
    <div class="card-footer text-center">
      <button type="button" class="btn bg-gradient-dark save">儲存</button>
      <button type="button" class="btn bg-gradient-secondary preview"><i class="fas fa-eye"></i>&nbsp;預覽</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </div>
  
</form>
@endsection

@section('js')
<script src="/admins/plugins/sortable/Sortable.js"></script>
<script src="/admins/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
  $(document).ready(function(){
    var items = document.getElementById('subjectContent');
    new Sortable(subjectContent, {
        animation: 150,
        ghostClass: 'blue-background-class',
        onEnd: function (/**Event*/evt) {
          var sort = 0;
          var number = 1;
          $('#subjectContent .main-title').each(function(){
            $(this).html(`項目${number}`);
            number++;
          })
          $('#subjectContent input[id^="sort_"]').each(function(){
            $(this).val(sort);
            sort++;
          })
        },
    });

    $('#form').validate({
      rules: {
        name:{
          required: true
        },
      },
      messages: {
        name:{
          required: '<span style="color:red">申請項目名稱!</span>'
        },
      }
    });

    $('.preview').click(function(){
      $('#form').attr('action', '{{route($menu->slug.'.preview')}}')
      $('#form').attr('target', '_blank')
      $('input[name="_method"]').val('post');
      $('#form').submit();
    })

    $('.save').click(function(){
      $('#form').attr('action', '{{route($menu->slug.'.store')}}')
      $('#form').attr('target', '_self')
      $('input[name="_method"]').val('post');
      $('#form').submit();
    })

    $('body').on('click', '.addSubject', function(){
      var row = parseInt($('#subjectContent div[id^="car_"]').length);
      var sort = parseInt($('#subjectContent input[id^="sort_"]').length);
      var number = row + 1;
      var str = `
      <div class="card card-secondary disabled" id="car_${row}" style="margin-top: 10px;">
        <input type="hidden" name="field[${row}][sort]" id="sort_${row}" value="${sort}">
        <div class="card-header">
          <h3 class="card-title main-title">項目${number}</h3>
          <div style="float: right;">
            <table>
              <tr>
                <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeSubject" style="color:white;" data-row="${row}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="topic_type_${row}">欄位屬性</label>
            <select class="custom-select form-control-border topicType" name="field[${row}][field_attribute_id]" id="field_attribute_${row}" data-row="${row}" required>
              <option value="">請選擇欄位屬性</option>
              @foreach($types??[] as $type)
              <option value="{{$type->val}}">{{$type->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <label for="subject_${row}">欄位名稱</label>
            <input type="text" class="form-control" name="field[${row}][title]" id="subject_${row}" placeholder="請輸入欄位名稱" required>
          </div>
          <div id="options_${row}" style="display:none">
            <div class="form-group row">
              <label>選項設定</label>
            </div>
            <div class="form-group row content_${row}"></div>
          </div>
      </div>
      `;
      $('#subjectContent').prepend(str);
    });

    $('body').on('click', '.addChildSubject', function(){
      var row = $(this).data('row');
      var item = $(this).data('item');
      var subRow = parseInt($(`#childSubject_${row}_${item} .card`).length);
      var number = subRow + 1;
      var str = `
      <div class="card card-warning disabled" id="sub_car_${row}_${item}_${subRow}" style="margin-top: 10px;">
        <div class="card-header">
          <h3 class="card-title">子項目${number}</h3>
          <div style="float: right;">
            <table>
              <tr>
                <td><button type="button" class="btn btn-block btn-outline-default btn-sm removeChildSubject" style="color:red;" data-row="${row}" data-item="${item}" data-subrow="${subRow}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="field_attribute_${row}_${item}_${subRow}">欄位屬性</label>
            <select class="custom-select form-control-border childTopicType" name=field[${row}][items][${item}][child][${subRow}][field_attribute_id]" id="field_attribute_${row}_${item}_${subRow}" data-row="${row}" data-item="${item}" data-subrow="${subRow}" required">
              <option value="">請選擇欄位屬性</option>
              @foreach($types??[] as $type)
              <option value="{{$type->val}}">{{$type->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <label for="subject_${row}_${item}_${subRow}">欄位名稱</label>
            <input type="text" class="form-control" name="field[${row}][items][${item}][child][${subRow}][title]" id="subject_${row}_${item}_${subRow}" placeholder="請輸入欄位名稱" required>
          </div>
          <div id="options_${row}_${item}_${subRow}" style="display:none">
            <div class="form-group row">
              <label>選項設定</label>
            </div>
            <div class="form-group row content_${row}_${item}_${subRow}"></div>
          </div>
      </div>
      `;

      $(`#childSubject_${row}_${item}`).append(str);
      $(`#childSubject_${row}_${item}`).show();
    });

    $('body').on('click', '.removeSubject', function(){
      var row = $(this).data('row');
      var number = 1;
      $('#car_'+row).remove();

      $('#subjectContent .card-title').each(function(){
        $(this).html(`項目${number}`);
        number++;
      })
    })
    $('body').on('click', '.removeChildSubject', function(){
      var row = $(this).data('row');
      var item = $(this).data('item');
      var subRow = $(this).data('subrow');
      var number = 1;
      $(`#sub_car_${row}_${item}_${subRow}`).remove();

      $(`#childSubject_${row} .card-title`).each(function(){
        $(this).html(`項目${number}`);
        number++;
      })
    })

    $('body').on('change', '.childTopicType', function(){
      var type = $(this).val();
      var row = $(this).data('row');
      var item = $(this).data('item');
      var subRow = $(this).data('subrow');
      var itemCount = parseInt($(`.content_${row}_${item}_${subRow} div[id^="child_item_"]`).length);
      var str = '';

      switch (type) {
        case 'select':
        case 'checkbox':
        case 'radio':
          str += `
          <div class="col-sm-12" id="child_item_${row}_${item}_${subRow}_${itemCount}" style="margin-top: 10px;">
              <div class="input-group">
                <input type="text" class="form-control" name="field[${row}][items][${item}][child][${subRow}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
                <div class="input-group-append">
                  <span class="input-group-text">
                      <button type="button" class="btn btn-xs childMultipleMinus" data-row="${row}" data-item="${item}" data-subrow="${subRow}" data-itemcount="${itemCount}"><i class="fas fa-minus"></i></button>
                  </span>
                  <span class="input-group-text">
                      <button type="button" class="btn btn-xs childMultiplePlus" data-row="${row}" data-item="${item}" data-subrow="${subRow}" data-itemcount="${itemCount}">><i class="fas fa-plus"></i></button>
                  </span>
                </div>
              </div>
            </div>
          `;
          $(`#options_${row}_${item}_${subRow}`).show();
          $(`.content_${row}_${item}_${subRow}`).append(str);
          break;
        default:
        $(`#options_${row}_${item}_${subRow}`).hide();
        $(`.content_${row}_${item}_${subRow}`).html('');
          break;
      }
    })

    $('body').on('change', '.topicType', function(){
      var type = $(this).val();
      var row = $(this).data('row');
      var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length);
      var str = '';

      switch (type) {
        case 'select':
        case 'checkbox':
        case 'radio':
          str += `
          <div class="col-sm-12" id="item_${row}_${itemCount}" style="margin-top: 10px;">
            <div class="input-group">
              <input type="text" class="form-control" name="field[${row}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
              <div class="input-group-append">
                <span class="input-group-text">
                    <button type="button" class="btn btn-xs multipleMinus" data-row="${row}" data-item="${itemCount}"><i class="fas fa-minus"></i></button>
                </span>
                <span class="input-group-text">
                    <button type="button" class="btn btn-xs multiplePlus" data-row="${row}"><i class="fas fa-plus"></i></button>
                </span>
                <span class="input-group-text">
                  <button type="button" class="btn btn-block btn-outline-secondary btn-sm addChildSubject" data-row="${row}" data-item="${itemCount}" style="color:black;"><i class="fas fa-plus-circle"></i> 增加子項目</button>
                </span>
              </div>
            </div>
            <div id="childSubject_${row}_${itemCount}" style="display:none"></div>
          </div>
          `;
          $(`#options_${row}`).show();
          $('.content_'+row).append(str);
          break;
        default:
          $(`#options_${row}`).hide();
          $('.content_'+row).html('');
          break;
      }
    });

    $('body').on('click', '.multiplePlus', function(){
      var row = $(this).data('row');
      var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length);
      var str = `
        <div class="col-sm-12" id="item_${row}_${itemCount}" style="margin-top: 10px;">
          <div class="input-group">
            <input type="text" class="form-control" name="field[${row}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
            <div class="input-group-append">
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs multipleMinus" data-row="${row}" data-item="${itemCount}"><i class="fas fa-minus"></i></button>
              </span>
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs multiplePlus" data-row="${row}"><i class="fas fa-plus"></i></button>
              </span>
              <span class="input-group-text">
                  <button type="button" class="btn btn-block btn-outline-secondary btn-sm addChildSubject" data-row="${row}" data-item="${itemCount}" style="color:black;"><i class="fas fa-plus-circle"></i> 增加子項目</button>
              </span>
            </div>
          </div>
          <div id="childSubject_${row}_${itemCount}" style="display:none"></div>
        </div>
      `;
      $('.content_'+row).append(str);
    })

    $('body').on('click', '.childMultiplePlus', function(){
      var row = $(this).data('row');
      var subRow = $(this).data('subrow');
      var itemCount = parseInt($('.content_'+row+'_'+subRow+' div[id^="child_item_"]').length);
      var str = `
        <div class="col-sm-12" id="child_item_${row}_${subRow}_${itemCount}" style="margin-top: 10px;">
          <div class="input-group">
            <input type="text" class="form-control" name="field[${row}][child][${subRow}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
            <div class="input-group-append">
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs childMultipleMinus" data-row="${row}" data-subrow="${subRow}" data-item="${itemCount}"><i class="fas fa-minus"></i></button>
              </span>
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs childMultiplePlus" data-row="${row}" data-subrow="${subRow}" data-item="${itemCount}"><i class="fas fa-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
      `;
      $(`.content_${row}_${subRow}`).append(str);
    })

    $('body').on('click', '.multipleMinus', function(){
      var row = $(this).data('row');
      var item = $(this).data('item');
      var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length) - 1;
      
      if (itemCount === 0) {
        Swal.fire({
            type: 'warning',
            title: '訊息提示',
            text: "至少保留一組選項設定",
            icon: 'warning',
        })
      } else {
        $('#item_'+item).remove();
      }
    })

    $('body').on('click', '.childMultipleMinus', function(){
      var row = $(this).data('row');
      var subRow = $(this).data('subrow');
      var item = $(this).data('item');
      var itemCount = parseInt($(`.content_${row}_${subRow} div[id^="child_item_"]`).length) - 1;
      
      if (itemCount === 0) {
        Swal.fire({
            type: 'warning',
            title: '訊息提示',
            text: "至少保留一組選項設定",
            icon: 'warning',
        })
      } else {
        $(`#child_item_${row}_${subRow}_${itemCount}`).remove();
      }
    })
  })
</script>
@endsection