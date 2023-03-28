@extends('layouts.main')
@section('css')
<link href="/admins/plugins/bootstrap-toggle/css/bootstrap-toggle.css" rel="stylesheet">
@endsection

@section('content')
@if($menu->search_component == 1)
<div class="row">
  <div class="col-md-12">
      <div class="card card-secondary">
          <div class="card-header">
              <h3 class="card-title">查詢條件</h3>
          </div>
          <form id="searchForm" method="GET" action="{{route($menu->slug.'.index')}}">
              @csrf
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="group_id">關鍵字</label>
                        <input type="text" class="form-control" name="keyword" id="keyword" value="{{$filters['keyword']}}">
                      </div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn bg-gradient-secondary btn-sm clearSearch">清除</button>
                  <button type="submit" class="btn bg-gradient-secondary btn-sm">查詢</button>
              </div>
          </form>
      </div>
  </div>
</div>
@endif
<div class="row">
  <div class="col-md-12">
    <div class="card">
      @include('components.top_banner_bar', ['menu'=>$menu])
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox" class="checkAll"></th>
              <th class="bg-gradient-secondary" style="text-align: center">操作</th>
              <th class="bg-gradient-secondary" style="text-align: center">帳號</th>
              <th class="bg-gradient-secondary" style="text-align: center">電信號碼</th>
              <th class="bg-gradient-secondary" style="text-align: center">使用者名稱</th>
              <th class="bg-gradient-secondary" style="text-align: center">建立人員</th>
              <th class="bg-gradient-secondary" style="text-align: center">建立日期</th>
            </tr>
          </thead>
          <tbody>
            @foreach($list??[] as $data)
            <tr>
              <td class="text-center" style="vertical-align: middle">
                <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}">
              </td>
              <td style="text-align: center; vertical-align: middle">
                @can('update_'.$menu->slug, app($menu->model))
                  <button type="button" class="btn bg-gradient-secondary btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i></button>
                @endcan
                <a class="btn bg-gradient-secondary btn-sm" href='{{route('proxy_accounts.proxyLogin', $data->user_id)}}' target="_blank"><i class="fas fa-sign-in-alt"></i></a>
              </td>
              @foreach($list??[] as $data)
              <td style="text-align: center; vertical-align: middle">{{$data->user->account}}</td>
              <td style="text-align: center; vertical-align: middle">{{$data->user->telecom_number}}</td>
              <td style="text-align: center; vertical-align: middle">{{$data->user->name}}</td>
              <td style="text-align: center; vertical-align: middle">{{$data->createUser->name}}</td>
              <td style="text-align: center; vertical-align: middle">{{$data->created_at}}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        {{$list->links('pagination.adminLTE')}}
      </div>
    </div>
    <!-- /.card -->
  </div>
</div>
<form id="delete_form" method="POST" style="display: none;">
  
</form>
<!-- /.row -->
@endsection

@section('js')
<script src="/admins/plugins/bootstrap-toggle/js/bootstrap-toggle.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
         
      $(".clearSearch").click(function(){
        $("#searchForm input").val('');
        $("#searchForm select").val('');
        $("#searchForm textarea").val('');
      })

      $(".checkAll").on('click', function(){
        if ( $(this).prop('checked') ) {
          $(".rowItem").prop('checked', true);
        } else {
          $(".rowItem").prop('checked', false);
        }
      })

      $(".deleteBtn").click(function(){
        if ( $('.rowItem:checked').length > 0) {
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
                if ($('.rowItem').length > 0) {
                  $('#delete_form').html('');
                  $('#delete_form').append('@csrf');
                  $('.rowItem:checked').each(function(){
                    $('#delete_form').append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                  });
                  $('#delete_form').submit();
                }
              }
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: '訊息提示',
            text: '請選擇要刪除的資料'
          })
        }
      })
  });
</script> 
@endsection