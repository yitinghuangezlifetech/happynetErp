@foreach($data->contract->productTypeLogs??[] as $log)
<div class="card card-secondary disabled" style="margin-top: 10px;">
    <div class="card-header">
    <h3 class="card-title main-title">{{$log->productType->type_name}}</h3>
    </div>
    <div class="card-body">
    <div class="form-group row productList_{{$loop->iteration}}">
        @if($log->productType->type_name == '語音服務')
        <table class="table table-hover table-bordered text-nowrap">
        <thead>
            <tr style="background-color: yellowgreen">
            <td class="text-center">服務名稱</td>
            <td class="text-center">撥打對象</td>
            <td class="text-center">通話費率</td>
            <td class="text-center">折讓(%)</td>
            <td class="text-center">折後費率</td>
            <td class="text-center">計費單位</td>
            <td class="text-center">服務帳號</td>
            </tr>
        </thead>
        <tbody>
            @foreach($log->productLogs??[] as $k=>$record)
            <tr>
                <td class="text-center" style="vertical-align: middle" rowspan="{{$record->product->feeRate->logs->count()+1}}">
                <input type="checkbox" name="products[{{$i}}][product_id]" value="{{$record->product_id}}" @if(isset($applyLogs[$log->product_type_id][$record->product_id])){{'checked'}}@endif>{{$record->product->name}}
                <input type="hidden" name="products[{{$i}}][contract_id]" value="{{$data->id}}">
                <input type="hidden" name="products[{{$i}}][product_type_id]" value="{{$log->product_type_id}}">
                </td>
            </tr>  
            @foreach($record->product->feeRate->logs??[] as $k1=>$feeRateLog)
            <tr>
                <td class="text-center" style="vertical-align: middle">
                <input type="checkbox" name="products[{{$i}}][feeRates][{{$k1}}][call_target_id]" value="{{$feeRateLog->call_target_id}}" @if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])){{'checked'}}@endif>{{$feeRateLog->target->type_name}}
                </td>
                <td class="text-center" style="vertical-align: middle">
                {{$feeRateLog->call_rate}}
                <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][call_rate]" id="call_rate_{{$feeRateLog->id}}" value="{{$feeRateLog->call_rate}}">
                </td>
                <td class="text-center" style="vertical-align: middle">
                @php
                    $value = '';
                    if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['discount'];
                    }
                @endphp
                <input type="number" class="form-control feeRateDiscount" name="products[{{$i}}][feeRates][{{$k1}}][discount]" data-logid="{{$feeRateLog->id}}" value="{{$value}}">
                </td>
                <td class="text-center" style="vertical-align: middle">
                @php
                    $value = '';
                    if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['amount'];
                    }
                @endphp
                <input type="number" class="form-control" name="products[{{$i}}][feeRates][{{$k1}}][amount]" id="feeRateAmount_{{$feeRateLog->id}}" data-logid="{{$feeRateLog->id}}" value="{{$value}}" readonly>
                </td>
                <td class="text-center" style="vertical-align: middle">
                {{$log->parameter}}
                @if($log->charge_unit == 1)
                秒鐘
                @else
                分鐘
                @endif
                <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][charge_unit]" value="{{$feeRateLog->charge_unit}}">
                <input type="hidden" name="products[{{$i}}][feeRates][{{$k1}}][parameter]" value="{{$feeRateLog->parameter}}">
                </td>
                <td class="text-center table-responsive" style="vertical-align: middle">
                @if($k==0 && $k1 == 0)
                <span id="userAccount"></span><br><br>
                <table class="table table-bordered text-nowrap">
                    <tr>
                    <td class="text-center" style="background-color: #d1cbba">顥示號碼</td>
                    </tr>
                    <tr>
                    <td class="text-center" id="telecom_number"></td>
                    </tr>
                </table>
                @endif
                </td>
            </tr>
            @endforeach
            @php $i++; @endphp
            @endforeach
        </tbody>
        </table>
        @else
        <table class="table table-hover text-nowrap">
        <thead>
            <tr style="background-color: yellowgreen">
            <td class="text-center">品名/型號/規格</td>
            <td class="text-center">數量</td>
            <td class="text-center">月租</td>
            <td class="text-center">折讓(%)</td>
            <td class="text-center">費用</td>
            <td class="text-center">保證金</td>
            <td class="text-center">備註</td>
            </tr>
        </thead>
        <tbody>
            @foreach($log->productLogs??[] as $k=>$record)
            <tr>
            <td class="text-center">
                {{$record->product->name}}
                <input type="hidden" name="products[{{$i}}][contract_id]" value="{{$data->id}}">
                <input type="hidden" name="products[{{$i}}][product_type_id]" value="{{$log->product_type_id}}">
                <input type="hidden" name="products[{{$i}}][product_id]" value="{{$record->product_id}}">
            </td>
            <td class="text-center">
                @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['qty'])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id]['qty'];
                }
                @endphp
                <input type="number" class="form-control qty" id="qty_{{$record->product_id}}" data-productid="{{$record->product_id}}" name="products[{{$i}}][qty]" value="{{$value}}">
            </td>
            <td class="text-center">
                {{$record->product->rent_month}}
                <input type="hidden" id="rent_month_{{$record->product_id}}" name="products[{{$i}}][rent_month]" value="{{$record->product->rent_month}}">
            </td>
            <td class="text-center">
                @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['discount'])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id]['discount'];
                }
                @endphp
                <input type="text" class="form-control discount" id="discount_{{$record->product_id}}" name="products[{{$i}}][discount]" value="{{$value}}" data-productid="{{$record->product_id}}">
            </td>
            <td class="text-center">
                @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['amount'])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id]['amount'];
                }
                @endphp
                <input type="text" class="form-control" id="amount_{{$record->product_id}}" name="products[{{$i}}][amount]" value="{{$value}}" readonly>
            </td>
            <td class="text-center">
                @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['security_deposit'])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id]['security_deposit'];
                }
                @endphp
                <input type="text" class="form-control" name="products[{{$i}}][security_deposit]" value="{{$value}}">
            </td>
            <td class="text-center">
                @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['note'])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id]['note'];
                }
                @endphp
                <input type="text" class="form-control" name="products[{{$i}}][note]" value="{{$value}}">
            </td>
            </tr>
            @php $i++; @endphp
            @endforeach
        </tbody>
        </table>
        @endif
    </div>
    </div>
</div>
@endforeach