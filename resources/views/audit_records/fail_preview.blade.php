@extends('layouts.main')

@section('content')
<form method="post" enctype="multipart/form-data" action="{{route('audit_records.recordFails')}}">
    @csrf
    <div class="col-md-12"><h5>複檢缺失</h5></div>
    <div class="col-md-12">
        @if($route->failRecords->count() > 0)
            @foreach($route->failRecords as $record)
            @php
                $color = '';
                if ($record->regulation->is_main == 1) {
                    $color = '#faf3e8';
                }
                if ($record->regulation->is_import == 1) {
                    $color = '#f7dfdf';
                }
            @endphp
            <div class="card card-outline card-warning">
                <div class="card-header" style="background-color: {{$color}}">
                    <h3 class="card-title">
                        {{$record->regulation->clause}}
                    </h3>
                </div>
                <div class="card-body" style="display: block;">
                    {{$record->subAttribute->law_source}}
                </div>
                <div class="card-footer" style="display: block;">
                    <div class="col-md-12">
                        <table class="table">
                            <tbody id="fialContent">
                                @if($record->fails->count() > 0)
                                    <tr>
                                        <td>缺失判定：
                                            {{optional($record->failType)->name}}
                                        </td>
                                    </tr>
                                    @foreach($record->fails as $key=>$fail)
                                    <tr>
                                        <td style="vertical-align: middle; text-align:center">
                                            <img src="{{$fail->img}}" style="max-width:100%;height:auto;">
                                        </td>
                                    </tr>
                                    <tr>   
                                        <td>所見事實：
                                            @if($record->regulation->facts->count() > 0)
                                                @foreach($record->regulation->facts as $fact)
                                                    @if($fact->id==$fail->regulation_fact_id)
                                                    {{$fact->name}}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            註記：<br><br>
                                            {!! $fail->note !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    
            @endforeach
        @else
            無缺失項目
        @endif
    </div>
    <div class="col-md-12">
        <div class="text-center">
            <button type="button" class="btn bg-gradient-primary" onclick="location.href='{{route('audit_records.completed', $routeId)}}'">完成預覽,確定上傳紀錄</button>
            <button type="button" class="btn bg-gradient-danger" id="cancel">取消上傳</button>
        </div>
    </div>
</form>
@endsection

@section('js')<script>
$('#cancel').click(function(){
    Swal.fire({
        title: '確認取消上傳?',
        icon: "warning",
        confirmButtonText:'確認',
        allowOutsideClick: false
    }).then((result)=>{
        if(result.value){
            location.href='{{route('audit_routes.index')}}'
        }
    })
})
</script>
@endsection