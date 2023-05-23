@foreach($logs??[] as $k=>$log)
<div class="card card-secondary disabled" id="car_{{$loop->iteration}}" style="margin-top: 10px;">
    <div class="card-header">
        <h3 class="card-title main-title">商品{{$loop->iteration}}</h3>
        <div style="float: right;">
        <table>
            <tr>
            <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeProduct" style="color:white;" data-row="{{$loop->iteration}}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
            </tr>
        </table>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
        <label for="product_type_id_{{$loop->iteration}}">商品類別</label>
        <select class="custom-select form-control-border productType" name="products[{{$loop->iteration}}][product_type_id]" id="product_type_id_{{$loop->iteration}}" data-row="{{$loop->iteration}}" required>
            <option value="">請選擇商品類別</option>
            @foreach($types??[] as $type)
            <option value="{{$type->id}}" @if($k==$type->id){{'selected'}}@endif>{{$type->type_name}}</option>
            @endforeach
        </select>
        </div>
        <div class="form-group row productList_{{$loop->iteration}}">
        <table class="table table-hover text-nowrap">
            <tbody>
            @php
                $lineCount = 1;
                $row = $loop->iteration;
                $products = $obj->getProductsByTypeId($k);
            @endphp
            @foreach($products??[] as $product)
                @php
                    $rows = $loop->count;
                    $line = ceil($rows / 5);
                @endphp
                @if($loop->first)
                <tr>
                @endif
                <td style="text-align: left;max-width:20%; width:20%">
                    <input type="checkbox" class="items" name="products[{{$row}}][items][]" value="{{$product->id}}" @if(isset($log[$product->id])){{'checked'}}@endif>
                    {{$product->name}}
                </td>
                @if($loop->iteration % 5 == 0)
                @php
                    $lineCount++;
                @endphp
                </tr>
                <tr>
                @endif  
                @if($loop->last)  
                @if($line == $lineCount)
                    @php
                        $remain = ($line * 5) - $rows;
                    @endphp
                    @for($i=1;$i<=$remain;$i++)
                    <td style="max-width:20%; width:20%"></td>
                    @endfor
                @endif
                @endif
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endforeach