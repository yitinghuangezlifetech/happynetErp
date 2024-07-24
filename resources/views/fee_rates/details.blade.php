@extends('layouts.main')
@section('css')
    <link href="/admins/plugins/bootstrap-toggle/css/bootstrap-toggle.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">查詢條件</h3>
            </div>
            <form id="searchForm" method="GET" action="{{route($menu->slug.'.details', $id)}}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label for="keyword">關鍵字</label>
                            <input type="text" class="form-control" name="keyword" id="keyword" value="{{$filters['keyword']}}">
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox"
                                        class="checkAll"></th>
                                <th class="bg-gradient-secondary" style="text-align: center">操作</th>
                                <th class="bg-gradient-secondary" style="text-align: center">英文國名</th>
                                <th class="bg-gradient-secondary" style="text-align: center">國家名稱</th>
                                <th class="bg-gradient-secondary" style="text-align: center">國碼</th>
                                <th class="bg-gradient-secondary" style="text-align: center">區碼</th>
                                <th class="bg-gradient-secondary" style="text-align: center">區域名稱/資費名稱</th>
                                <th class="bg-gradient-secondary" style="text-align: center">第1段單位(秒)</th>
                                <th class="bg-gradient-secondary" style="text-align: center">第1段價格</th>
                                <th class="bg-gradient-secondary" style="text-align: center">第2段單位(秒)</th>
                                <th class="bg-gradient-secondary" style="text-align: center">第2段價格</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs ?? [] as $data)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}">
                                    </td>
                                    <td style="text-align: center">
                                        @can('update_' . $menu->slug, app($menu->model))
                                            <button type="button" class="btn bg-gradient-secondary btn-sm"
                                                onclick="location.href='{{ route('fee_rates.detail_content', ['id'=>$id, 'detialId'=>$data->id]) }}'"><i
                                                    class="fas fa-edit"></i></button>
                                        @endcan
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">{{ $data->eng_country_name }}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->country_name}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->country_code}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->area_code}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->area_name}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->unit}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->price}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->unit_2}}</td>
                                    <td class="text-center" style="vertical-align: middle">{{$data->price_2}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $logs->links('pagination.adminLTE') }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
