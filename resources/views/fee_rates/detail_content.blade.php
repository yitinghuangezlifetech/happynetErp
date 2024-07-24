@extends('layouts.main')

@section('content')
    <form enctype="multipart/form-data" method="POST" action="{{ route('fee_rates.detailUpdate', ['id'=>$id, 'detialId'=>$data->id]) }}">
        @csrf
        @method('put')
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">編輯費率資料</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>涵蓋區域</label>
                            @php
                                $str = '國際';
                                if($data->type=='0') {
                                    $str = '國內';
                                }
                            @endphp
                            <input type="text" class="form-control" value="{{$str}}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="eng_country_name"><span style="color:red">*</span>英文國名</label>
                            <input type="text" class="form-control" name="eng_country_name" id="eng_country_name" value="{{$data->eng_country_name}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><span style="color:red">*</span>國家名稱</label>
                            <input type="text" class="form-control" name="country_name" value="{{$data->country_name}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="country_code"><span style="color:red">*</span>國碼</label>
                            <input type="text" class="form-control" name="country_code" id="country_code" value="{{$data->country_code}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><span style="color:red">*</span>前置碼</label>
                            <input type="text" class="form-control" name="area_code" value="{{$data->area_code}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="area_name"><span style="color:red">*</span>區域名稱</label>
                            <input type="text" class="form-control" name="area_name" id="area_name" value="{{$data->area_name}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><span style="color:red">*</span>第1段單位(秒)</label>
                            <input type="text" class="form-control" name="unit" value="{{$data->unit}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="price"><span style="color:red">*</span>第1段價格</label>
                            <input type="text" class="form-control" name="price" id="price" value="{{$data->price}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><span style="color:red">*</span>第2段單位(秒)</label>
                            <input type="text" class="form-control" name="unit_2" value="{{$data->unit_2}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="price_2"><span style="color:red">*</span>第2段價格</label>
                            <input type="text" class="form-control" name="price_2" id="price_2" value="{{$data->price_2}}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="footerArea">
            <div class="card-footer text-center">
                <button type="submit" class="btn bg-gradient-dark">儲存</button>
                <button type="button" class="btn bg-gradient-secondary"
                    onclick="javascript:location.href='{{ route('fee_rates.details', $id) }}'">回上一頁</button>
            </div>
        </div>
    </form>
@endsection
