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
            @endphp
            @if($detail->super_admin_use == 1 && $user->super_admin == 1)
            <div class="col-sm-6">
              @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
            </div>
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
                @switch($detail->type)
                  @case('multiple_input')
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
                    <div class="col-sm-6">
                      <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                      <div id="{{ $detail->field }}">
                      @foreach($options as $option)
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                      @endforeach
                      </div>
                    </div>  
                    @endif
                    @break
                  @case('multiple_select')
                    <div class="col-sm-6">
                      @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data])
                    </div>
                    @break;
                  @case('ckeditor')
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                      </div>
                    </div>
                    <div class="row">
                    @php $i++; @endphp
                    @break;  
                  @case('empty')
                    <div class="col-sm-6"></div>
                    @break;
                  @case('component')
                    @include('components.'.$detail->component_name, ['type'=>'edit', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>$data])
                    @break
                  @default
                    <div class="col-sm-6">
                      @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'id'=>$data->id, 'model'=>$menu->model, 'value'=>$data->{$detail->field}])
                    </div>
                    @break;
                @endswitch
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
      <h3 class="card-title">欄位名稱設定</h3>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <th>資料表欄位</th>
          <th>範例名稱</th>
          <th>對應名稱</th>
        </thead>
        <tbody>
          @php
            $avoid = ['id', 'created_at', 'updated_at'];
          @endphp
          @foreach($colums as $k=>$colum)
            @if(!in_array($colum, $avoid))
            <tr>
              <td>
                {{$colum['field']}}
                <input type="hidden" name="colums[{{$k}}][field]" value="{{$colum['field']}}">
              </td>
              <td>{{$colum['show_name']}}</td>
              <td><input type="text" class="form-control" name="colums[{{$k}}][name]" value="@if(isset($columVals[$colum['field']])){{$columVals[$colum['field']]}}@endif"></td>
            </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card" style="box-shadow: 0px 0px 0px">
    <div class="card-footer text-center">
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
      @if($menu->slug != 'apply_step_logs' && $menu->slug != 'home_page_product_logs')
      <button type="button" class="btn bg-gradient-secondary deleteBtn"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
      @endif
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
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
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
              $('#delete_form').html('');
              $('#delete_form').append('@csrf');
              $('#delete_form').append(`<input type="hidden" name="ids[]" value="{{$data->id}}">`);
              $('#delete_form').submit();
            }
        })
    })
  })
</script>
@endsection