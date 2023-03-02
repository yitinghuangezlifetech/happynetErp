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
            <div class="card-header">
                <button type="button" class="btn btn-primary btn-sm export" style="float: left; margin-left: 5px;"><i class="fas fa-cloud-download-alt"></i>&nbsp;資料下載</button>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">狀態</th>
                            <th class="text-center">評核日期</th>
                            <th class="text-center">評核委員<br>老師</th>
                            <th class="text-center">評核委員</th>
                            <th class="text-center">商家類型</th>
                            <th class="text-center">餐飲業別</th>
                            <th class="text-center">業者名稱</th>
                            <th class="text-center">負責人/聯絡人</th>
                            <th class="text-center">電話</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list??[] as $info)
                        <tr>
                            <td class="text-center">
                                @switch($info->audit_status)
                                    @case(1)
                                        輔導
                                        @break
                                    @case(2)
                                        評核
                                        @break
                                    @case(3)
                                        追評
                                        @break
                                    @default
                                        未開始
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">{{$info->audit_day}}</td>
                            <td class="text-center">{{optional($info->mainUser)->name}}</td>
                            <td class="text-center">{{optional($info->subUser)->name}}</td>
                            <td class="text-center">{{optional($info->store)->storeType->name}}</td>
                            <td class="text-center">{{optional($info->store)->storeIndustry->name}}</td>
                            <td class="text-center">{{optional($info->store)->name}}</td>
                            <td class="text-center">{{optional($info->store)->manager}}</td>
                            <td class="text-center">{{optional($info->store)->phone}}</td>
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
    $('#searchForm').attr('action', '{{route('reports.download')}}');
    $('#searchForm').attr('target', '_blank');
    $('#searchForm').submit();
})
$('.search').click(function(){
    $('#searchForm').attr('action', '{{route('reports.index')}}');
    $('#searchForm').attr('target', '_self');
    $('#searchForm').submit();
}) 
</script>
@endsection