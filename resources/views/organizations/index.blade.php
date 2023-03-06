@extends('layouts.main')
@section('css')
<link href="/admins/plugins/bootstrap-toggle/css/bootstrap-toggle.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      @include('components.top_banner_bar', ['menu'=>$menu])
      <div class="card-body table-responsive p-0">
        <table class="table table-bordered text-nowrap">
          <thead>
            <tr>
              <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox" class="checkAll"></th>
              <th class="bg-gradient-secondary" style="text-align: center">系統編號</th>
              <th class="bg-gradient-secondary" style="text-align: center">群組</th>
              <th class="bg-gradient-secondary" style="text-align: center">身份</th>
              <th class="bg-gradient-secondary" style="text-align: center">帳號</th>
              <th class="bg-gradient-secondary" style="text-align: center">電信帳號</th>
              <th class="bg-gradient-secondary" style="text-align: center">名稱</th>
              <th class="bg-gradient-secondary" style="text-align: center">成本費率名稱</th>
              <th class="bg-gradient-secondary" style="text-align: center">統一編號</th>
              <th class="bg-gradient-secondary" style="text-align: center">系統商(數)</th>
              <th class="bg-gradient-secondary" style="text-align: center">經銷商(數)</th>
              <th class="bg-gradient-secondary" style="text-align: center">客戶(數)</th>
              <th class="bg-gradient-secondary" style="text-align: center">狀態</th>
              <th class="bg-gradient-secondary" style="text-align: center">建立人員</th>
              <th class="bg-gradient-secondary" style="text-align: center">建立日期</th>
              <th class="bg-gradient-secondary" style="text-align: center">修改人員</th>
              <th class="bg-gradient-secondary" style="text-align: center">修改日期</th>
            </tr>
          </thead>
          <tbody>
            @foreach($list??[] as $data)
            <tr>
              <td class="text-center"><input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}"></td>
              <td class="text-center" style="vertical-align: middle">{{$data->system_no}}</td>
              <td class="text-center" style="vertical-align: middle">{{optional($data->group)->name}}</td>
              <td class="text-center" style="vertical-align: middle">{{optional($data->identity)->name}}</td>
              <td class="text-center" style="vertical-align: middle">{{$data->mobile}}</td>
              <td class="text-center" style="vertical-align: middle">{{$data->zipcode.$data->county.$data->district.$data->address}}</td>
              <td class="text-center" style="vertical-align: middle">
                <input type="checkbox" class="switchBtn status" checkeddata-toggle="toggle" data-id="{{$data->id}}" data-field="status" data-model="{{$menu->model}}" @if($data->status == 1){{'checked'}}@endif>
              </td>
              <td style="text-align: center">
                @can('update_'.$menu->slug, app($menu->model))
                  <button type="button" class="btn bg-gradient-secondary btn-sm" onclick="location.href='{{route('admin.'.$menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i></button>
                @endcan
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        {{$list->links('admins.pagination.adminLTE')}}
      </div>
    </div>
    <!-- /.card -->
  </div>
</div>
<form id="delete_form" method="POST" style="display: none;">
  
</form>
<!-- /.row -->
@if($menu->search_component == 1)
  @include('admins.components.search_modal', ['menu'=>$menu, 'filters'=>$filters])
@endif
@endsection

@section('js')
<script src="/admins/plugins/bootstrap-toggle/js/bootstrap-toggle.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $(".clearSearch").click(function(){
        $("#exampleModal input").val('');
        $("#exampleModal select").val('');
      })

      $('.search').click(function(){
        $('#searchForm')[0].method = 'get';
        $('#searchForm')[0].target = '_self';
        $('#searchForm')[0].action = '{{ route('admin.'.$menu->slug.'.index') }}';
        $('#searchForm').submit();
      })

      $('.switchBtn').bootstrapToggle({
        on: '上架',
        off: '下架',
        onstyle: 'success',
        offstyle: 'secondary'
      }).on('change', function(){
        let status = 2;
        let model = $(this).data('model');
        let field = $(this).data('field');
        let id = $(this).data('id');

        if ($(this).prop('checked')) {
          status = 1;
        }

        $.ajax({
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              method: 'post',
              url: '{{ route('admin.status.changeStatus') }}',
              data: {
                id: id,
                model: model,
                status: status,
                field: field,
                slug: '{{$slug}}'
              }
        })
      });

      $(".checkAll").on('click', function(){
        if ( $(this).prop('checked') ) {
          $(".rowItem").prop('checked', true);
        } else {
          $(".rowItem").prop('checked', false);
        }
      })

      $('.exportBtn').click(function(){
        $('#searchForm')[0].method = 'post';
        $('#searchForm')[0].target = '_blank';
        $('#searchForm')[0].action = '{{ route('admin.'.$menu->slug.'.exportExcel') }}';
        $('#searchForm').submit();
      })

      $(".deleteBtn").click(function(){

        $('#delete_form')[0].method = 'get';
        $('#delete_form')[0].target = '_self';
        $('#delete_form')[0].action = '{{ route('admin.'.$menu->slug.'.multipleDestroy') }}';

        if ( $('.rowItem:checked').length > 0) {
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