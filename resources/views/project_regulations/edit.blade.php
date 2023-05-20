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
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $data->id)}}">
  @csrf
  @method('put')
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
      <h3 class="card-title">專案活動綁定</h3>
    </div>
    <div class="card-body table-responsive p-0" id="prjectContent"></div>
  </div>
  <div class="card" id="footerArea">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
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
<script>
  $(document).ready(function(){
    @if($menu->menuCreateDetails->count() > 0)
        @foreach($menu->menuCreateDetails as $detail)
          @switch($detail->type)
            @case('multiple_select')
              $('#edit_{{$detail->field_id}}').multiSelect();
              @break
          @endswitch
        @endforeach
    @endif
    
    $(".deleteBtn").click(function(){
      $('#delete_form')[0].action = '{{ route($menu->slug.'.multipleDestroy') }}';
        Swal.fire({
            type: 'warning',
            title: '訊息提示',
            text: "是否要刪除資料？",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '是的, 刪除資料!',
            cancelButtonText: '取消',
        }).then((result) => {
            if (result.value) {
              $('#delete_form').html('');
              $('#delete_form').append('@csrf');
              $('#delete_form').append(`<input type="hidden" name="ids[]" value="{{$data->id}}">`);
              $('#delete_form').submit();
            }
        })
    })

    $('#edit_sales_type_id').change(function(){
      const id = $(this).val();

      $('#prjectContent').html('');

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          method: 'post',
          url: '{{ route('project_regulations.getProjects') }}',
          data: {
            sales_type_id: id
          },
          success: function(rs) {
            $('#prjectContent').html(rs.data);
          }
      })
    })

    $('#edit_product_type_id').change(function(){
      const saleTypeId = $('#sales_type_id').val();
      const id = $(this).val();

      $('#prjectContent').html('');

      if (saleTypeId == '') {
        Swal.fire({
          icon: 'error',
          title: '訊息提示',
          text: '請選擇要銷售模式'
        })
      } else {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('project_regulations.getProjects') }}',
            data: {
              sales_type_id: saleTypeId,
              product_type_id: id,
            },
            success: function(rs) {
              $('#prjectContent').html(rs.data);
            }
        })
      }
    })

    const init = () => {
      const saleTypeId = $('#edit_sales_type_id').val();
      const productTypeId = $('#edit_product_type_id').val();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          method: 'post',
          url: '{{ route('project_regulations.getProjects') }}',
          data: {
            project_regulation_id: '{{$data->id}}', 
            sales_type_id: saleTypeId,
            product_type_id: productTypeId,
          },
          success: function(rs) {
            $('#prjectContent').html(rs.data);
          }
      })
    }

    init();
  })
</script>
@endsection