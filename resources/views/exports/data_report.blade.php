<table>
    <thead>
        <tr>
            <th style="text-align: center; background-color:gray">缺失開主時間</th>
            <th style="text-align: center; background-color:gray">複檢時間</th>
            <th style="text-align: center; background-color:gray">商家名稱</th>
            <th style="text-align: center; background-color:gray">屬性</th>
            <th style="text-align: center; background-color:gray">次屬性</th>
            <th style="text-align: center; background-color:gray">條文</th>
            <th style="text-align: center; background-color:gray">條文狀態</th>
            <th style="text-align: center; background-color:gray">缺失照片</th>
            <th style="text-align: center; background-color:gray">所見事實</th>
            <th style="text-align: center; background-color:gray">委員名稱</th>
            <th style="text-align: center; background-color:gray">數值</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list??[] as $info)
        <tr>
            <td style="text-align: center;">{{$info->created_at}}</td>
            <td style="text-align: center;">
                @if(optional($info->auditRoute)->audit_status == 2)
                {{$info->created_at}}
                @endif
            </td>
            <td style="text-align: center;">{{optional($info->auditRoute)->store->name}}</td>
            <td style="text-align: center;">{{optional($info->mainAttribute)->name}}</td>
            <td style="text-align: center;">{{optional($info->subAttribute)->name}}</td>
            <td style="text-align: center;">{{optional($info->regulation)->clause}}</td>
            <td style="text-align: center;">
                @switch($info->status)
                    @case(1)
                        合格
                        @break
                    @case(2)
                        不合格
                        @break
                    @case(3)
                        不適用
                        @break
                    @default
                        未檢查
                        @break
                @endswitch
            </td>
            <td style="text-align: center;">
                @foreach ($info->fails??[] as $fail)
                    {{$fail->img}}   
                @endforeach
            </td>
            <td style="text-align: center;">
                @foreach ($info->fails??[] as $fail)
                    {{optional($fail->regulationFact)->name}}
                @endforeach
            </td>
            <td style="text-align: center;">{{optional($info->auditRoute)->mainUser->name}}</td>
            <td style="text-align: center;">
                @foreach ($info->items??[] as $item)
                    {{$item->regulationItem->name}}:{{$item->value}}<br>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>