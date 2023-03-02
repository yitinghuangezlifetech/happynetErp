@extends('layouts.main')

@section('content')
<div class="card ard-secondary">
  <div class="card-header">
    <h3 class="card-title">編輯資料</h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form enctype="multipart/form-data" method="POST" action="{{route('users.updateProfile')}}">
    @csrf
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6 text-center">
          <div class="form-group">
            @if(!empty($data->avatar))
            <img src="{{$data->avatar}}" width="215px" height="215px">
            @else
            <img src="/admins/img/avatar4.png" width="215px" height="215px">
            @endif
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="avatar">圖片</label>
            <input type="file" class="form-control dropify" id="avatar" name="avatar" data-default-file="{{$data->avatar}}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
              <label for="email"><span style="color:red">*</span> Email/帳號</label>
              <input type="email" class="form-control" id="email" name="email" value="{{$data->email}}" required>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
              <label for="password">密碼</label>
              <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
              <label for="name"><span style="color:red">*</span> 使用者名稱</label>
              <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}" required>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer text-center">
      <button type="submit" class="btn bg-gradient-dark">儲存</button>
      <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
    </div>
  </form>
</div>
<!-- /.row -->
@endsection

@section('js')
<script src="/admins/js/components/image.js"></script>
@endsection