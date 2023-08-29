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
                <form id="searchForm" method="GET" action="{{ route($menu->slug . '.index') }}">
                    @csrf
                    <div class="card-body">

                        <div class="row">

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
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-secondary btn-sm exportBtn"
                        style="float: left; margin-left: 5px;"><i class="fa fa-download"></i>&nbsp;資料匯出</button>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox"
                                        class="checkAll"></th>
                                <th class="bg-gradient-secondary" style="text-align: center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" style="vertical-align: middle">
                                    <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}">
                                </td>
                                <td style="text-align: center; vertical-align: middle">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $list->links('pagination.adminLTE') }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('js')
    <script src="/admins/plugins/bootstrap-toggle/js/bootstrap-toggle.js"></script>
    <script type="text/javascript"></script>
@endsection
