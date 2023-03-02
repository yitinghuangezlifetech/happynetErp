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
                                <label for="name">商家名稱</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$filters['name']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="store_type_id">商家類型</label>
                                <select class="form-control" name="store_type_id" id="store_type_id">
                                    <option value="">請選擇</option>
                                    @foreach($storeTypes as $type)
                                    <option value="{{$type->id}}" @if($filters['store_type_id']==$type->id){{'selected'}}@endif>{{$type->name}}</option>
                                    @endforeach
                                </select>
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
                            <th class="text-center">商店名稱</th>
                            <th class="text-center">表單類型</th>
                            <th class="text-center">商號地址</th>
                            <th class="text-center">聯絡人或負責人</th>
                            <th class="text-center">性別</th>
                            <th class="text-center">連絡電話</th>
                            <th class="text-center">業別</th>
                            <th class="text-center">食品業者登錄字號</th>
                            <th class="text-center">年度</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list??[] as $store)
                        <tr>
                            <td class="text-center">{{$store->name}}</td>
                            <td class="text-center">{{optional($store->storeType)->name}}</td>
                            <td class="text-center">{{$store->address}}</td>
                            <td class="text-center">{{$store->manager}}</td>
                            <td class="text-center">{{($store->sex=='m')?'男':'女'}}</td>
                            <td class="text-center">{{$store->phone}}</td>
                            <td class="text-center">{{optional($store->storeIndustry)->name}}</td>
                            <td class="text-center">{{$store->food_no}}</td>
                            <td class="text-center">{{$store->year}}</td>
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