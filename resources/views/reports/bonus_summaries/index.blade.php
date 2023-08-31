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
                <form id="searchForm" method="GET">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="storganization_idart">組織</label>
                                    <select class="form-control" name="organization_id" id="organization_id">
                                        <option value="">請選擇</option>
                                        @foreach ($organizations ?? [] as $organization)
                                            <option value="{{ $organization->id }}"
                                                @if ($filters['organization_id']) {{ 'selected' }} @endif>
                                                {{ $organization->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start">日期-起</label>
                                    <input type="date" class="form-control" name="start" id="start"
                                        placeholder="請選擇起始日期" value="{{ $filters['start'] }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start">日期-迄</label>
                                    <input type="date" class="form-control" name="end" id="end"
                                        placeholder="請選擇結束日期" value="{{ $filters['end'] }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary btn-sm clearSearch">清除</button>
                        <button type="button" class="btn bg-gradient-secondary btn-sm searchBtn">查詢</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-secondary btn-sm exportBtn"
                        style="float: left; margin-left: 5px;"><i class="fa fa-download"></i>&nbsp;資料匯出</button>
                    {{ $list->links('pagination.adminLTE') }}
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="bg-gradient-secondary" style="text-align: center">組織名稱</th>
                                <th class="bg-gradient-secondary" style="text-align: center">成本</th>
                                <th class="bg-gradient-secondary" style="text-align: center">應收金額</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list ?? [] as $data)
                                <tr>
                                    <td class="text-center">{{ $data->name }}</td>
                                    <td class="text-center">{{ number_format($data->fee, 2) }}</td>
                                    <td class="text-center">{{ number_format($data->charge_fee, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('js')
    <script src="/admins/plugins/bootstrap-toggle/js/bootstrap-toggle.js"></script>
    <script type="text/javascript">
        $(".clearSearch").click(function() {
            $("#searchForm input").val('');
            $("#searchForm select").val('');
            $("#searchForm textarea").val('');

            $('#searchForm').attr('action', '{{ route('reports.reportIndex', $reportName) }}');
            $('#searchForm').attr('target', '_self');
        })

        $('.searchBtn').click(function() {
            $('#searchForm').attr('action', '{{ route('reports.reportIndex', $reportName) }}');
            $('#searchForm').attr('target', '_self');
            $('#searchForm').submit();
        })

        $('.exportBtn').click(function() {
            $('#searchForm').attr('action', '{{ route('reports.download', $reportName) }}');
            $('#searchForm').attr('target', '_blank');
            $('#searchForm').submit();
        })
    </script>
@endsection
