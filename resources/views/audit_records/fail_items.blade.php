@extends('layouts.main')
@section('css')
<style>
.dropify-wrapper .dropify-preview{
  padding:0 !important;
}
.dropify-wrapper .dropify-preview .dropify-render img{
  width:100%;
  height:auto;
  -webkit-transform:none;
  transform:none;
  top:0;
}
.dropify-wrapper{
  border:0;
  background-color:#f7f8f9;
  padding:0!important;
}
</style>
@endsection

@section('content')
<form method="post" enctype="multipart/form-data" action="{{route('audit_records.recordFailItems', $routeId)}}" onsubmit="return checkFailItems()">
    @csrf
    @if($route->pendingChecks->count() > 0)
        @foreach($route->pendingChecks??[] as $record)
            @php
                $originRecord = $record->getOriginRecord($record->audit_route_id, $record->regulation_id);
            @endphp
        <div class="col-md-12" id="{{$record->id}}">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        {{$record->regulation->clause}}
                    </h3>
                </div>
                <div class="card-body" style="display: block;">
                    {{$record->regulation->subAttribute->law_source}}
                </div>
                <div class="card-footer" style="display: block;">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-left">
                                        <label>複檢結果:</label>
                                        <select class="form-control" id="audit_result_{{$record->id}}" data-id="{{$record->id}}" data-clause="{{$record->regulation->clause}}" data-items="{{$record->pendingCheckLogs->count()}}" disabled>
                                            <option value=""></option>
                                            <option value="1" @if($record->status==1){{'selected'}}@endif>複檢通過</option>
                                            <option value="2" @if($record->status==2){{'selected'}}@endif>複檢不通過</option>
                                        </select>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">缺失紀錄</th>
                                    <th class="text-center">複檢</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($record->pendingCheckLogs->count() > 0)
                                    @foreach($record->pendingCheckLogs??[] as $k=>$log)
                                        <td>
                                            <div class="row col-sm-6">
                                                <img src="{{$log->img}}" class="img-fluid mb-6">
                                            </div>
                                            <label>所見事實：</label><br>{{optional($log->regulationFact)->name}}<br>
                                            <p></p>
                                            <label>備註：</label><br>{{$log->note}}
                                        </td>
                                        <td style="vertical-align: middle; text-align:center">
                                            <label style="float: left">項目評核：</label>
                                            <select class="form-control checkResult" id="auditItem_{{$record->id}}_{{$log->id}}" data-id="{{$log->id}}" data-recordid="{{$record->id}}" data-originrecordid="{{optional($originRecord)->id}}">
                                                <option value="">請選擇</option>
                                                <option value="1" @if($log->status==1){{'selected'}}@endif>合格</option>
                                                <option value="2" @if($log->status==2){{'selected'}}@endif>不合格</option>
                                            </select>
                                            <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                id="addSuccessRecord_{{$log->id}}" 
                                                data-recordid="{{$record->id}}"  
                                                @if($log->status==0 || $log->status==2 )style="display: none"@endif
                                                onclick="location.href='{{route('pending_checks.success', ['checkId'=>$record->id, 'logId'=>$log->id])}}'"
                                            >
                                                <i class="fa fa-plus"></i>
                                                新增合格紀錄
                                            </button>
                                            <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                id="addFailRecord_{{$log->id}}" 
                                                data-recordid="{{$record->id}}"  
                                                @if($log->status==0 || $log->status==1 )style="display: none"@endif
                                                onclick="location.href='{{route('pending_checks.fail', ['checkId'=>$record->id, 'logId'=>$log->id])}}'"
                                            >
                                                <i class="fa fa-plus"></i>
                                                新增不合格紀錄
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- /.card-body -->
            </div>
        </div>
        @endforeach
        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-primary">完成</button>
                <button type="button" class="btn btn-secondary" onclick="location.href='{{route('audit_records.index', $routeId)}}'">回上一頁</button>
            </div>
        </div>
    @else
    尚無待複檢項目
    @endif
</form>
@endsection

@section('js')
<script>

$('select[id^="auditItem_"]').change(function(){
    let status = $(this).val();
    let logId = $(this).data('id');
    let checkId = $(this).data('recordid');
    let items = parseInt($('#audit_result_'+checkId).data('items'));
    let ok = 0;
    let fails = 0;
    let rows = 0;

    $('select[id^="auditItem_'+checkId+'_"]').each(function(){
        if ($(this).val() == 1) {
            ok ++;
        } 

        if ($(this).val() == 2) {
            fails ++;
        }
    })


    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        async: false,
        cache: false,
        url: '{{ route('status.changePendingCheckLogStatus') }}',
        data: {
            id: logId,
            status: status
        }
    })

    if (status == 1) {
        $('#addSuccessRecord_'+logId).show();
        $('#addFailRecord_'+logId).hide();
    } else {
        $('#addSuccessRecord_'+logId).hide();
        $('#addFailRecord_'+logId).show();
    }

    rows = ok + fails;

    if (rows == items) {
        if (fails > 0) {
            $('#audit_result_'+checkId).val(2);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                url: '{{ route('status.changePendingCheckStatus') }}',
                data: {
                    id: checkId,
                    status: 2
                }
            })
        } else if (ok == items) {
            $('#audit_result_'+checkId).val(1);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                url: '{{ route('status.changePendingCheckStatus') }}',
                data: {
                    id: checkId,
                    status: 1
                }
            })
        }
    } else if (rows == 0) {
        $('#audit_result_'+checkId).val('');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('status.changePendingCheckStatus') }}',
            data: {
                id: checkId,
                status: 0
            }
        })
    }
})

let checkFailItems = () => {
    let pass = true;
    let positionId;

    $('select[id^="audit_result_"]').each(function(){
        let id = $(this).data('id');

        if ($(this).val() == '') {
            pass = false;
            Swal.fire({
                icon: 'error',
                title: '訊息提示',
                text: $(this).data('clause')+' - 未複檢完成, 請確實各項複檢完成後再送出資料'
            }).then( (result) => {
                if (result.isConfirmed) {
                    $('html, body').animate({
                        scrollTop: $("#"+id).offset().top
                    }, 2000);
                }

                return false;
            })
        } else {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                async: false,
                cache: false,
                url: '{{ route('pending_checks.checkRecord') }}',
                data: {
                    id: id,
                },
                error: function(rs) {
                    pass = false;
                    positionId = id;
                    Swal.fire({
                        icon: 'error',
                        title: '訊息提示',
                        text: rs.responseJSON.message
                    }).then( (result) => {
                        if (result.isConfirmed) {
                            $('html, body').animate({
                                scrollTop: $("#"+id).offset().top
                            }, 2000);
                        }

                        return false;
                    })
                }
            })
        }
    })

    return pass; 
}

$('.checkResult').change(function(){
    let result = parseInt($(this).val());
    let recordId = $(this).data('originrecordid');
    let url = '{{route('audit_records.failPage', ['routeId'=>$routeId, 'recordId'=>'__recordId'])}}'.replace('__recordId', recordId);

    if (result == 2) {
        location.href = url;
    }
})
</script>
@endsection