@extends('layouts.main')
@section('css')
<style>
</style>
@endsection

@section('content')
<form method="post" enctype="multipart/form-data" action="{{route('audit_records.uploadRecord', $data->id)}}" onsubmit="return checkSign()">
    @csrf
    <div class="col-md-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">完成檢查</h3>
            </div>
            <div class="card-footer" style="display: block;">
                <div class="col-md-12">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <td class="text-center">註記</td>
                                <td class="text-center">
                                    @php
                                        $str = '';
                                        foreach($fails??[] as $k=>$fail) {
                                            if ($fail->auditRecord->status == 2) {
                                                $str .= $fail->note.'&#13;&#10;';
                                            }
                                        }
                                    @endphp
                                    <textarea class="form-control" name="note" style="height: 100px">{!! $str !!}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">輔導委員一<br>(必簽)</td>
                                <td class="text-center">
                                    <div id="main_user_signe_area">
                                        @if(!empty($data->main_user_signe))
                                        <img src="{{$data->main_user_signe}}" style="max-width:100%;height:auto;">
                                        @endif
                                    </div>
                                    <div style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;" id="main_user_signe_board"></div>
                                    <br>
                                    <button type="button" class="btn bg-gradient-warning btn-sm" onclick="resetSign('main_user_signe_area', 'main_user_signe_board')">清除</button>
                                    <button type="button" class="btn bg-gradient-info btn-sm" onclick="convertToBase64('main_user_signe_area', 'main_user_signe_board')">確認</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">輔導委員二<br>(必簽)</td>
                                <td class="text-center">
                                    <div id="sub_user_signe_area">
                                        @if(!empty($data->sub_user_signe))
                                        <img src="{{$data->sub_user_signe}}" style="max-width:100%;height:auto;">
                                        @endif
                                    </div>
                                    <div style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;" id="sub_user_signe_board"></div>
                                    <br>
                                    <button type="button" class="btn bg-gradient-warning btn-sm" onclick="resetSign('sub_user_signe_area', 'sub_user_signe_board')">清除</button>
                                    <button type="button" class="btn bg-gradient-info btn-sm" onclick="convertToBase64('sub_user_signe_area', 'sub_user_signe_board')">確認</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">業者<br>(必簽)</td>
                                <td class="text-center">
                                    <div id="store_signe_area">
                                        @if(!empty($data->store_signe))
                                        <img src="{{$data->store_signe}}" style="max-width:100%;height:auto;">
                                        @endif
                                    </div>
                                    <div style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;" id="store_signe_board"></div>
                                    <br>
                                    <button type="button" class="btn bg-gradient-warning btn-sm" onclick="resetSign('store_signe_area', 'store_signe_board')">清除</button>
                                    <button type="button" class="btn bg-gradient-info btn-sm" onclick="convertToBase64('store_signe_area', 'store_signe_board')">確認</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">衛生局人員<br>(非必簽)</td>
                                <td class="text-center">
                                    <div id="gov_signe_area">
                                        @if(!empty($data->gov_signe))
                                        <img src="{{$data->gov_signe}}" style="max-width:100%;height:auto;">
                                        @endif
                                    </div>
                                    <div style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;" id="gov_signe_board"></div>
                                    <br>
                                    <button type="button" class="btn bg-gradient-warning btn-sm" onclick="resetSign('gov_signe_area', 'gov_signe_board')">清除</button>
                                    <button type="button" class="btn bg-gradient-info btn-sm" onclick="convertToBase64('gov_signe_area', 'gov_signe_board')">確認</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-12">
        <div class="text-center">
            <button type="submit" class="btn btn-primary">完成簽名</button>
         </div>
    </div>
</form>
@endsection

@section('js')
<!--[if lt IE 9]>
<script type="text/javascript" src="/plugins/jSignature/flashcanvas.js"></script>
<![endif]-->
<script src="/plugins/jSignature/jSignature.min.js"></script>
<script>

$(document).ready(function(){
    $("#main_user_signe_board").jSignature({ 'width': '100%', 'height': 300});
    $("#sub_user_signe_board").jSignature({ 'width': '100%', 'height': 300 });
    $("#store_signe_board").jSignature({ 'width': '100%', 'height': 300 });
    $("#gov_signe_board").jSignature({ 'width': '100%', 'height': 300 });
})

$('.dropify').dropify({
    messages: {
        'default': '請上傳簽名檔'
    }
});

var convertToBase64 = function(areaName, idName) {
    var fieldName = idName.replace('_board', '');
    var $sigdiv = $('#'+idName);
    var datapair = $sigdiv.jSignature("getData", "svgbase64");
    var i = new Image();
    $('#'+areaName).html('');

    i.src = "data:" + datapair[0] + "," + datapair[1];
    $(i).appendTo($("#"+areaName));
    $('#'+areaName).append('<input type="hidden" name="'+fieldName+'" id="'+fieldName+'" value="'+datapair+'">');
    $("#"+idName).hide();
    $("#"+idName).jSignature('reset');
}

var resetSign = function(areaName, idName) {
    $('#'+areaName).html('');
    $("#"+idName).jSignature('reset');
    $("#"+idName).show();
}

var checkSign = function(){
    @if(empty($data->main_user_signe))
    if( $('#main_user_signe').length === 0) {
        Swal.fire({
            icon: 'error',
            title: '提示訊息',
            text: '請上傳輔導委員一簽名檔',
        })

        return false;
    }
    @endif
    @if(empty($data->sub_user_signe))
    if ($('#sub_user_signe').length === 0) {
        Swal.fire({
            icon: 'error',
            title: '提示訊息',
            text: '請上傳輔導委員二簽名檔',
        })

        return false;
    }
    @endif
    @if(empty($data->store_signe))
    if ($('#store_signe').length === 0) {
        Swal.fire({
            icon: 'error',
            title: '提示訊息',
            text: '請上傳業者簽名檔',
        })

        return false;
    }
    @endif
    return true;
}
</script>
@endsection