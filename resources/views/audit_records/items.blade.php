@extends('layouts.main')

@section('content')
<form method="post" action="{{route('audit_records.recordItems')}}">
    @csrf
    <input type="hidden" name="record_id" value="{{$record->id}}">
    <div class="col-md-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
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
                        <tbody>
                            @foreach($originItems as $key=>$item)
                            <tr>
                                <td style="text-align: right">
                                    <input type="hidden" name="items[{{$key}}][regulation_item_id]" value="{{$item->id}}">
                                    {{$item->name}}：
                                </td>
                                <td><input type="text" class="form-control" name="items[{{$key}}][value]" value="@if(isset($items[$item->id])){{$items[$item->id]}}@endif" placeholder="需輸入單位" required></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-12">
        <div class="text-center">
            <button type="submit" class="btn btn-primary">完成</button>
            <button type="button" class="btn btn-secondary" onclick="location.href='{{route('audit_records.regulations', ['routeId'=>$record->audit_route_id, 'mainAttributeId'=>$record->main_attribute_id])}}'">回上一頁</button>
        </div>
    </div>
</form>
@endsection