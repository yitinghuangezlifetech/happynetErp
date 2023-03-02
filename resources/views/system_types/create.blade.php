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
              if ($detail->use_component == 1) {
                if (!in_array($detail->component_name.'.js', $jsArr)) {
                  array_push($jsArr, $detail->component_name.'.js');
                }
              }
              $halfRows = '';
            @endphp
            @if($detail->super_admin_use == 1 && $user->super_admin == 1)
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
                @endphp
                @include('components.fields.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
              @else
                @if($detail->use_component == 1)
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    @include('components.'.$detail->component_name, ['detail'=>$detail])
                  </div>
                </div>
                <div class="row">
                @php $i++; @endphp
                @elseif($detail->type == 'multiple_input')
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>{{$detail->show_name}}</label>
                    <div id="{{ $detail->field }}">
                      @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                    </div>
                  </div>
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
                @elseif($detail->type == 'multiple_select')
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
              <td><input type="text" class="form-control" name="colums[{{$k}}][name]" value=""></td>
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
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
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
  </script>
@endsection