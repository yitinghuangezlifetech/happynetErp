@extends('layouts.main')

@section('content')
@foreach($list as $item)
<div class="col-md-12">
    <div class="card card-outline card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{$item['name']}}</h3>

        <div class="card-tools">
            @if($route->status != 4)<button type="button" class="btn btn-tool" onclick="checkAll('{{$item['id']}}')"><i class="fas fa-check"></i>全部合格</button>@endif
        </div>
      </div>
      <div class="card-footer" style="display: block;">
        <div class="col-md-12">
            @if(count($item['regulations']) > 0)
            <ul class="nav nav-pills flex-column">
                @foreach($item['regulations'] as $key=>$regulation)
                @php
                    $color = '';
                    if ($regulation['is_import']==1) {
                        $color = '#f7dfdf';
                    }
                    if ($regulation['is_main']==1) {
                        $color = '#faf3e8';
                    }
                @endphp
                <li class="nav-item" style="background-color: {{$color}}">
                    @if(count($regulation['original_items']) > 0)
                    <a href="{{route('audit_records.getItems', ['routeId'=>$routeId, 'recordId'=>$regulation['id']])}}">{{$regulation['name']}}</a>
                    @else
                    {{$regulation['name']}}
                    @endif
                    <span class="float-right badge">
                        
                        <button type="button" id="btn_{{$item['id']}}_{{$regulation['id']}}" class="btn bg-gradient-danger btn-sm" onclick="checkFailType('{{$item['id']}}_{{$regulation['id']}}', '{{$regulation['id']}}')" @if(count($regulation['fails']) > 0 || $regulation['status'] == 2) @else style="display:none" @endif>缺失紀錄</button>
                        <select class="item" id="item_{{$item['id']}}_{{$regulation['id']}}" data-important="{{$regulation['is_import']}}" data-main="{{$regulation['is_main']}}" data-itemid="{{$item['id']}}" data-recordid="{{$regulation['id']}}" data-status="{{$regulation['status']}}" data-hasitem="@if(count($regulation['original_items']) > 0){{'1'}}@else{{'2'}}@endif" data-fullitem="{{count($regulation['original_items'])}}" data-finishitem="{{count($regulation['items'])}}" data-regulationname="{{$regulation['name']}}" @if($route->status == 4){{'disabled'}}@endif>
                            <option value="0" @if($regulation['status']==0){{'selected'}}@endif>未檢查</option>
                            <option value="1" @if($regulation['status']==1){{'selected'}}@endif>合格</option>
                            <option value="2" @if($regulation['status']==2){{'selected'}}@endif>不合格</option>
                            <option value="3" @if($regulation['status']==3){{'selected'}}@endif>不適用</option>
                        </select>
                        <select id="fail_{{$item['id']}}_{{$regulation['id']}}" class="failType" data-hasitem="@if(count($regulation['original_items']) > 0){{'1'}}@else{{'2'}}@endif" data-regulationid="{{$regulation['id']}}" @if($regulation['status'] != 2 || $regulation['is_import']==1) style="display: none" @endif @if($route->status == 4){{'disabled'}}@endif>
                            <option value="">缺失判定</option>
                            @if(count($regulation['fail_types']) > 0)
                                @foreach($regulation['fail_types'] as $type)
                                <option value="{{$type['id']}}" @if($regulation['audit_fail_type_id']==$type['id']){{'selected'}}@endif>{{$type['name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
      </div>
    </div>
</div>
@endforeach
<div class="col-md-12">
    <div class="text-center">
        <button type="button" class="btn btn-secondary" onclick="location.href='{{route('audit_records.index', $routeId)}}'">回上一頁</button>
    </div>
</div>
@endsection

@section('js')
<script>
var checkFailType = function(id, recordId) {
    var failType = $('#fail_'+id).val();
    var status = parseInt($('#item_'+id).val());
    var url = '{{route('audit_records.failPage', ['routeId'=>$routeId, 'recordId'=>'__recordId'])}}'.replace('__recordId', recordId);
    var item = $('#item_'+id);
    var important = parseInt(item.data('important'));

    if (status == 2) {
        if (important == 2) {
            if (failType === '') {
                Swal.fire({
                    icon: 'error',
                    text: '請先選擇缺失判定'
                })
            } else {
                location.href = url;
            }
        } else {
            location.href = url;
        }
        
    } else {
        location.href = url;
    }
}

var checkAll = function(subId) {
    $('select[id^="item_'+subId+'_"]').each(function(){
        if (parseInt($(this).val()) === 0) {
            if (parseInt($(this).data('hasitem'))===1) {
                var fullItem = parseInt($(this).data('fullitem'));
                var finishItem = parseInt($(this).data('finishitem'));
                var regulationName = $(this).data('regulationname');

                if (finishItem != fullItem) {
                    Swal.fire({
                        icon: 'error',
                        text: `[${regulationName}]之條文尚有項目數值未填寫!!`
                    })
                } else {
                    $(this).val(1);
                    $(this).trigger('change');
                }
            } else {
                $(this).val(1);
                $(this).trigger('change');
            }
        }
    })
}

$('.failType').change(function(){
    var recordId = $(this).data('regulationid');
    var hasItem = $(this).data('hasitem');
    var typeId = $(this).val();
    var url = '{{route('audit_records.failPage', ['routeId'=>$routeId, 'recordId'=>'__recordId'])}}'.replace('__recordId', recordId);

    if (typeId != '') {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('audit_records.changeFailType') }}',
            data: {
                "routeId": '{{$routeId}}',
                "record_id": recordId,
                "audit_fail_type_id": typeId
            },
            success: function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        text: res.message,
                        confirmButtonText:'確認',
                        allowOutsideClick: false
                    }).then((result)=>{
                        if (result.isConfirmed) {
                            location.href = url;
                        }
                    })
                }
            },
            error: function(rs) {
                Swal.fire({
                    icon: 'error',
                    text: rs.responseJSON.message
                })
            }
        })
    } else {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'post',
            url: '{{ route('audit_records.changeFailType') }}',
            data: {
                "routeId": '{{$routeId}}',
                "record_id": recordId,
                "audit_fail_type_id": null
            },
            error: function(res) {
                Swal.fire({
                    icon: 'error',
                    text: res.data.message
                })
            }
        })
    }
})

$('.item').change(function(){
    var important = $(this).data('important');
    var main = $(this).data('main');
    var itemId = $(this).data('itemid');
    var recordId = $(this).data('recordid');
    var status = parseInt($(this).val());
    var hasItem = parseInt($(this).data('hasitem'));
    
    var fullItem = parseInt($(this).data('fullitem'));
    var finishItem = parseInt($(this).data('finishitem'));
    var regulationName = $(this).data('regulationname');
    var url = '{{route('audit_records.failPage', ['routeId'=>$routeId, 'recordId'=>'__recordId'])}}'.replace('__recordId', recordId);

    if (finishItem != fullItem) {
        $(this).val(0);
        Swal.fire({
            icon: 'error',
            text: `[${regulationName}]之條文尚有項目數值未填寫!!`
        })
    } else {
        if (status === 2) {
            $(this).attr('data-status', 2);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                url: '{{ route('audit_records.changeStatus') }}',
                data: {
                    "routeId": '{{$routeId}}',
                    "record_id": recordId,
                    "status": status
                },
                error: function(res) {
                    Swal.fire({
                        icon: 'error',
                        text: res.data.message
                    })
                }
            })
          
            if (important == 2 && main == 2) {
                $('#fail_'+itemId+'_'+recordId).show();
                $('#fail_'+itemId+'_'+recordId)[0].selectedIndex = 1;

                var typeId = $('#fail_'+itemId+'_'+recordId).val();

                $('#btn_'+itemId+'_'+recordId).show();
                
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    method: 'post',
                    url: '{{ route('audit_records.changeFailType') }}',
                    data: {
                        "routeId": '{{$routeId}}',
                        "record_id": recordId,
                        "audit_fail_type_id": typeId
                    }
                })
                
                Swal.fire({
                    icon: 'info',
                    text: '請選擇缺失項目後再點擊缺失紀錄按鈕進入缺失紀錄頁',
                    confirmButtonText:'確認',
                    allowOutsideClick: false
                })
            } else if(main == 1) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    method: 'post',
                    url: '{{ route('audit_records.changeFailType') }}',
                    data: {
                        "routeId": '{{$routeId}}',
                        "record_id": recordId,
                        "audit_fail_type_id": '{{$mainFailType->id}}'
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                text: res.message,
                                confirmButtonText:'確認',
                                allowOutsideClick: false
                            }).then((result)=>{
                                if (result.isConfirmed) {
                                    location.href = url;
                                }
                            })
                        }
                    },
                    error: function(res) {
                        Swal.fire({
                            icon: 'error',
                            text: res.data.message
                        })
                    }
                })
            } else {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    method: 'post',
                    url: '{{ route('audit_records.changeFailType') }}',
                    data: {
                        "routeId": '{{$routeId}}',
                        "record_id": recordId,
                        "audit_fail_type_id": '{{$importantFailType->id}}'
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                text: res.message,
                                confirmButtonText:'確認',
                                allowOutsideClick: false
                            }).then((result)=>{
                                if (result.isConfirmed) {
                                    location.href = url;
                                }
                            })
                        }
                    },
                    error: function(res) {
                        Swal.fire({
                            icon: 'error',
                            text: res.data.message
                        })
                    }
                })
            }
            
        } else {
            if (parseInt($(this).data('status')) == 2) {
                var name = '';
                switch (status) {
                    case 1: name='合格';break;
                    case 3: name='不適用';break;
                }

                Swal.fire({
                    title:  `確認要將不合格調整為${name}?`,
                    icon: "info",
                    confirmButtonText:'確認',
                    cancelButtonText: '取消',
                    showCancelButton: true,
                    allowOutsideClick: false
                }).then((result)=>{
                    if (result.isConfirmed) {
                        $(this).attr('data-status', status);
                        $('#fail_'+itemId+'_'+recordId).val('');
                        $('#fail_'+itemId+'_'+recordId).hide();
                        $('#fail_'+itemId+'_'+recordId).trigger('change');

                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            method: 'post',
                            url: '{{ route('audit_records.changeStatus') }}',
                            data: {
                                "routeId": '{{$routeId}}',
                                "record_id": recordId,
                                "status": status
                            },
                            success: function(){
                                if (hasItem === 1) {
                                    location.href = url;
                                }
                            },
                            error: function(res) {
                                Swal.fire({
                                    icon: 'error',
                                    text: res.data.message
                                })
                            }
                        })
                    } else {
                        $(this).val(2)
                    }
                })
            } else {
                $(this).attr('data-status', status);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    method: 'post',
                    url: '{{ route('audit_records.changeStatus') }}',
                    data: {
                        "routeId": '{{$routeId}}',
                        "record_id": recordId,
                        "status": status
                    },
                    error: function(res) {
                        Swal.fire({
                            icon: 'error',
                            text: res.data.message
                        })
                    }
                })
            }
        }
    }
});
</script>
@endsection