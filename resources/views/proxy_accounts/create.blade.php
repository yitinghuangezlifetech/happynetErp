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
  <input type="hidden" name="create_user_id" value="{{$user->id}}">
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">建立{{$menu->name}}資料</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label><span style="color:red">*</span> 選擇帳戶</label>
            <select class="form-control" name="user_id" required>
              @foreach($users??[] as $data)
                @if($data->organization)
                <option value="{{$data->id}}">{{$data->organization->name}}—{{$data->account}}({{$data->name}})</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if($menu->seo_enable == 1)
  @include('components.seo', ['data'=>null])
  @endif
  <div class="card">
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </div>
</form>
@endsection