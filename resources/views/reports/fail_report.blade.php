@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">查詢條件</h3>
            </div>
            <form id="searchForm" method="GET" action="{{route('reports.index')}}">
                @csrf
                <input type="hidden" name="data" value="{{$data}}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="start_day">稽核日期-起</label>
                                <input type="date" class="form-control" id="start_day" name="start_day" value="{{$filters['start_day']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="end_day">稽核日期-迄</label>
                                <input type="date" class="form-control" id="end_day" name="end_day" value="{{$filters['end_day']}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">輔導委員姓名</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$filters['name']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="store">商家名稱</label>
                                <input type="text" class="form-control" id="store" name="store" value="{{$filters['store']}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm clearSearch">清除</button>
                    <button type="button" class="btn bg-gradient-secondary btn-sm search">查詢</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">稽核日期</th>
                            <th class="text-center">輔導委員一</th>
                            <th class="text-center">輔導委員二</th>
                            <th class="text-center">商家</th>
                            <th class="text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list??[] as $info)
                        <tr>
                            <td class="text-center">{{$info->audit_day}}</td>
                            <td class="text-center">{{optional($info->mainUser)->name}}</td>
                            <td class="text-center">{{optional($info->subUser)->name}}</td>
                            <td class="text-center">{{optional($info->store)->name}}</td>
                            <td class="text-center">
                                <button type="button" data-id="{{$info->id}}" class="btn btn-primary btn-sm export" style="margin-left: 5px;"><i class="fas fa-cloud-download-alt"></i>&nbsp;缺失報告下載</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{$list->links('pagination.adminLTE')}}
            </div>
        </div>
    </div>
</div>
<form id="hiddenForm" method="GET" action="{{route('reports.download')}}" target="_blank">
    @csrf
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="data" value="{{$data}}">
</form>
@endsection

@section('js')
<script>
$(".clearSearch").click(function(){
    $("#searchForm input[type='text']").val('');
    $("#searchForm input[type='date']").val('');
    $("#searchForm select").val('');
    $("#searchForm input[type='radio']").each(function(){
        $(this).attr('checked', false);
    });
    $("#searchForm input[type='checkbox']").each(function(){
        $(this).attr('checked', false);
    });

    $('#searchForm').attr('action', '{{route('reports.index')}}');
    $('#searchForm').attr('target', '_self');
})    
$('.export').click(function(){
    let id = $(this).data('id');
    $('#id').val(id);
    $('#hiddenForm').submit();
})
$('.search').click(function(){
    $('#searchForm').attr('action', '{{route('reports.index')}}');
    $('#searchForm').attr('target', '_self');
    $('#searchForm').submit();
}) 
</script>
@endsection