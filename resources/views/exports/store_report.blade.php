<table>
    <thead>
        <tr>
            <th style="text-align: center; background-color:gray">序號</th>
            <th style="text-align: center; background-color:gray">狀態</th>
            <th style="text-align: center; background-color:gray">評核日期</th>
            <th style="text-align: center; background-color:gray">評核委員<br>老師</th>
            <th style="text-align: center; background-color:gray">評核委員</th>
            <th style="text-align: center; background-color:gray">評核結果</th>
            <th style="text-align: center; background-color:gray">主要缺失</th>
            <th style="text-align: center; background-color:gray">次要缺失</th>
            <th style="text-align: center; background-color:gray">主要缺失條文及註記</th>
            <th style="text-align: center; background-color:gray">次要缺失條文及註記</th>
            <th style="text-align: center; background-color:gray">商家類型</th>
            <th style="text-align: center; background-color:gray">餐飲業別</th>
            <th style="text-align: center; background-color:gray">證書編號</th>
            <th style="text-align: center; background-color:gray">業者名稱(店名)</th>
            <th style="text-align: center; background-color:gray">食品業者登錄字號</th>
            <th style="text-align: center; background-color:gray">負責人或聯絡人</th>
            <th style="text-align: center; background-color:gray">電話</th>
            <th style="text-align: center; background-color:gray">地址</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list??[] as $data)
        <tr>
            <td style="text-align: center">{{$loop->iteration}}</td>
            <td style="text-align: center">
                @switch($data->audit_status)
                    @case(1)
                        輔導
                        @break
                    @case(2)
                        評核
                        @break
                    @case(3)
                        追評
                        @break
                    @default
                        未開始
                        @break
                @endswitch
            </td>
            <td style="text-align: center">{{$data->audit_day}}</td>
            <td style="text-align: center">{{optional($data->mainUser)->name}}</td>
            <td style="text-align: center">{{optional($data->subUser)->name}}</td>
            <td style="text-align: center">
            @php
                
                $subFails = $data->getSubFails->count();
                $subFailRows = floor($subFails / 3);
                $mainFails = $data->getMainFails->count() + $subFailRows;
            @endphp
            @if($mainFails < 2)
                優
            @elseif($mainFails >= 2 && $mainFails < 4 )
                良
            @else
                不通過
            @endif
            </td>
            <td style="text-align: center">{{$mainFails}}</td>
            <td style="text-align: center">{{$subFails}}</td>
            <td style="text-align: center">
                @foreach($data->getMainFails??[] as $record)
                    @if($record->fails->count() > 0)
                        @foreach ( $record->fails??[] as $fail)
                            {{$fail->note}}<br>
                        @endforeach
                    @endif
                @endforeach
            </td>
            <td style="text-align: center">
                @foreach($data->getSubFails??[] as $record)
                    @if($record->fails->count() > 0)
                        @foreach ( $record->fails??[] as $fail)
                            {{$fail->note}}<br>
                        @endforeach
                    @endif
                @endforeach
            </td>
            <td style="text-align: center">{{optional($data->store)->storeType->name}}</td>
            <td style="text-align: center">{{optional($data->store)->storeIndustry->name}}</td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{{optional($data->store)->name}}</td>
            <td style="text-align: center">{{optional($data->store)->food_no}}</td>
            <td style="text-align: center">{{optional($data->store)->manager}}</td>
            <td style="text-align: center">{{optional($data->store)->phone}}</td>
            <td style="text-align: center">{{optional($data->store)->address}}</td>
        </tr>
        @endforeach
    </tbody>
</table>