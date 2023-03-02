@extends('layouts.main')


@section('content')
<form method="post" enctype="multipart/form-data" action="{{route('audit_records.recordFails')}}" onsubmit="return checkFailItems()">
    @csrf
    <input type="hidden" name="record_id" value="{{$record->id}}">
    @if($record->regulation->items->count() > 0)
    <div class="col-md-12">
        <div class="card card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    @php
                        $color = '';
                        if ($record->regulation->is_main == 1) {
                            $color = '#faf3e8';
                        }
                        if ($record->regulation->is_import == 1) {
                            $color = '#f7dfdf';
                        }
                    @endphp
                    <span style="background-color: {{$color}}">{{$record->regulation->clause}}</span>
                </h3>
            </div>
            <div class="card-body" style="display: block;">
                {{$record->subAttribute->law_source}}
            </div>
            <div class="card-footer" style="display: block;">
                <div class="col-md-12">
                    <table class="table">
                        <tbody>
                            @foreach($record->regulation->items as $key=>$item)
                            <tr>
                                <td style="text-align: right">
                                    <input type="hidden" name="items[{{$key}}][regulation_item_id]" value="{{$item->id}}">
                                    {{$item->name}}：
                                </td>
                                <td><input type="text" class="form-control" name="items[{{$key}}][value]" value="@if(isset($items[$item->id])){{$items[$item->id]}}@endif" placeholder="需輸入單位" @if($route->status == 4 && $user->role->has_audit_route!=1){{'disabled'}}@else{{'required'}}@endif></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- /.card-body -->
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="card card-outline card-warning">
            @if($record->regulation->items->count() == 0)
            @php
                $color = '';
                if ($record->regulation->is_main == 1) {
                    $color = '#faf3e8';
                }
                if ($record->regulation->is_import == 1) {
                    $color = '#f7dfdf';
                }
            @endphp
            <div class="card-header" style="background-color: {{$color}}">
                <h3 class="card-title">
                    {{$record->regulation->clause}}
                </h3>
            </div>
            <div class="card-body" style="display: block;">
                {{$record->subAttribute->law_source}}
            </div>
            @else
            <div class="card-header">
                <h3 class="card-title">
                    缺失紀錄
                </h3>
            </div>
            @endif
            <div class="card-footer" style="display: block;">
                <div class="col-md-12">
                    <table class="table">
                        <tbody id="fialContent">
                            @if($record->fails->count() > 0)
                                @foreach($record->fails as $key=>$fail)
                                <tr class="row_{{$fail->id}}">
                                    <td style="vertical-align: middle; text-align:center">
                                        <img src="{{$fail->img}}" style="max-width:100%;height:auto;"><br>
                                        @if($route->status != 4 || $user->role->has_audit_route == 1)
                                        <input type="file" class="form-control" name="fails[{{$fail->id}}][img]">
                                        @endif
                                        <input type="hidden" class="form-control" name="fails[{{$fail->id}}][id]" value="{{$fail->id}}">
                                    </td>
                                </tr>
                                <tr class="row_{{$fail->id}}">   
                                    <td>
                                        <select name="fails[{{$fail->id}}][regulation_fact_id]" class="form-control" data-number="{{$record->regulation->no}}" class="item" @if($route->status == 4 && $user->role->has_audit_route!=1){{'disabled'}}@endif>
                                            <option value="">所見事實</option>
                                            @if($record->regulation->facts->count() > 0)
                                                @foreach($record->regulation->facts as $fact)
                                                <option value="{{$fact->id}}" @if($fail->regulation_fact_id==$fact->id){{'selected'}}@endif>{{$fact->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr class="row_{{$fail->id}}">
                                    <td><textarea class="form-control note" name="fails[{{$fail->id}}][note]" id="note_{{$fail->id}}" data-number="{{$record->regulation->no}}" placeholder="註記" @if($route->status == 4 && $user->role->has_audit_route!=1){{'disabled'}}@endif>{{$fail->note}}</textarea></td>
                                </tr>
                                @if(($record->status == 2 && $route->status != 4) || $user->role->has_audit_route == 1 )
                                <tr class="row_{{$fail->id}}">
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRecord('{{$fail->id}}')"><i class="fas fa-trash-alt"></i>刪除</button>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            @elseif($route->status != 4 || $user->role->has_audit_route == 1)
                            <tr class="row_0">   
                                <td>
                                    <select name="fails[0][regulation_fact_id]" id="fact_0" class="form-control facts" data-number="{{$record->regulation->no}}" data-factid="0" class="item">
                                        <option value="">所見事實</option>
                                        @if($record->regulation->facts->count() > 0)
                                            @foreach($record->regulation->facts as $fact)
                                            <option value="{{$fact->id}}">{{$fact->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            @if($route->status != 4 || $user->role->has_audit_route == 1)
                            <tr class="row_0">
                                <td style="vertical-align: middle; text-align:center">
                                    <input type="file" class="form-control" name="fails[0][img]" data-min-width="200" required>
                                </td>
                            </tr>
                            @endif
                            <tr class="row_0">
                                <td><textarea name="fails[0][note]" class="form-control note" id="note_0" data-number="{{$record->regulation->no}}" placeholder="註記"></textarea></td>
                            </tr>
                            <tr class="row_0">
                                <td class="text-center" colspan="2">
                                    <button type="button" class="btn btn-danger btn-sm deleteTemplate" data-row="0"><i class="fas fa-trash-alt"></i>刪除</button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if(($record->status == 2 && $route->status != 4)  || $user->role->has_audit_route == 1)
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary btn-lg" id="plusFail">
                        <i class="fa fa-plus"></i><br>
                        新增新的一筆缺失
                    </button>
                </div>
                @endif
            </div>
        <!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-12">
        <div class="text-center">
            @if(($record->status == 2 && $route->status != 4) || $user->role->has_audit_route == 1)
            <button type="submit" class="btn btn-primary">完成</button>
            @else
            <button type="button" class="btn btn-secondary" onclick="location.href='{{route('audit_records.regulations', ['routeId'=>$record->audit_route_id, 'mainAttributeId'=>$record->main_attribute_id])}}'">回上一頁</button>
            @endif
        </div>
    </div>
</form>
@endsection

@section('js')
<script>

var checkFailItems = function(){
    var pass = true;
    var rows = parseInt($('#fialContent tr').length);

    

    if (rows === 0) {
        pass = false;

        Swal.fire({
            type: 'warning',
            title: '訊息提示',
            text: "請至少新增一筆缺失紀錄",
            icon: 'warning',
        })
    }

    $('textarea[id^="note_"]').each(function(){
        if ($(this).val()=='') {
            pass = false;

            Swal.fire({
                type: 'warning',
                title: '訊息提示',
                text: "缺失註記請勿空白",
                icon: 'warning',
            })
        }
    })

    return pass;
}

$('.dropify').dropify({
    messages: {
        'default': '請上傳照片'
    }
});
$('#plusFail').click(function(){
    var row = parseInt($('.note').length);
    var template = `
    <tr class="row_${row}">   
        <td>
            <select name="fails[${row}][regulation_fact_id]" class="form-control facts" data-number="{{$record->regulation->no}}" id="fact_${row}" data-factid="${row}" class="item">
                <option value="">所見事實</option>
                @if($record->regulation->facts->count() > 0)
                    @foreach($record->regulation->facts as $fact)
                    <option value="{{$fact->id}}">{{$fact->name}}</option>
                    @endforeach
                @endif
            </select>
        </td>
    </tr>
    <tr class="row_${row}">
        <td style="vertical-align: middle; text-align:center">
            <input type="file" class="form-control" name="fails[${row}][img]" required>
        </td>
    </tr>
    <tr class="row_${row}">
        <td colspan="2"><textarea name="fails[${row}][note]" class="form-control note" id="note_${row}" data-number="{{$record->regulation->no}}" placeholder="註記" required></textarea></td>
    </tr>
    <tr class="row_${row}">
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm deleteTemplate" data-row="${row}"><i class="fas fa-trash-alt"></i>刪除</button>
        </td>
    </tr>
    `;

    $('#fialContent').append(template);
    $('.dropify').dropify({
        messages: {
            'default': '請上傳照片'
        }
    });
});

$('body').on('focus', '.note', function(){
    var number = $(this).data('number');
    var str = $(this).val();
    const regex = new RegExp(`${number}.`);

    if (str == '') {
        $(this).val(`${number}. `);
    } else {
        if (!regex.test(`${str}`)) {
           $(this).val(`${number}. ${str}`);
        }
    }
})

$('body').on('click', '.deleteTemplate', function(){
    var row = parseInt($(this).data('row'));
    $('.row_'+row).remove();
})

$('body').on('change', '.facts', function(){
    var factId = $(this).data('factid');
    var number = $(this).data('number');
    var content = $('#note_'+factId).val();
    var str = '';

    if (content != '') {

        str += content+'\n';
        str += number+'. '+$('#fact_'+factId+' option:selected').text();
        $('#note_'+factId).val(str);

    } else {
        $('#note_'+factId).val(number+'. '+$('#fact_'+factId+' option:selected').text());
    }
})


var removeRecord = function(id) {

    Swal.fire({
        type: 'warning',
        title: '訊息提示',
        text: "是否要刪除缺失資料？",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '是的, 刪除資料!',
        cancelButtonText: '取消',
    }).then((result) => {
        if (result.value) {
            $('.row_'+id).remove();

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                url: '{{ route('audit_records.removeFailRecord') }}',
                data: {
                    "id": id,
                }
            })
        }
    })
}
</script>
@endsection