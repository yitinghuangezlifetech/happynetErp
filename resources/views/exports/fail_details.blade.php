@php
$tempDay = explode('-', $route->audit_day);
$year = $tempDay[0] - 1911;
@endphp
<table style="width: 100%;">
    <thead>
        <tr>
            <th style="text-align: center;">
                {{$year}}年度臺北市餐飲衛生管理分級<br>
                輔導評核{{$year}}年度餐飲業者 缺失報告
            </th>
        </tr>
    </thead>
</table>
<table style="width: 100%; margin-bottom: 1rem; color: #212529;background-color: transparent;border-collapse: collapse;text-indent: initial;border-spacing: 2px;">
    <tbody>
        <tr>
            <td rowspan="3" style="vertical-align: middle; text-align: left; border: 1px solid #dee2e6;">店名：{{optional($route->store)->name}}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #dee2e6;">輔導日期</td>
            <td style="border: 1px solid #dee2e6;">@if($route->audit_status==1){{$route->audit_day}}@endif</td>
        </tr>
        <tr>
            <td style="border: 1px solid #dee2e6;">評核日期</td>
            <td style="border: 1px solid #dee2e6;">@if($route->audit_status==2){{$route->audit_day}}@endif </td>
        </tr>
        <tr>
            <td style="text-align: center; border: 1px solid #dee2e6;" colspan="3">
                @if($route->storeImgs->count() > 0)
                    @foreach ( $route->storeImgs as $img )
                     <img src="{{$img->img}}" {{app('BladeService')->imageResize($img->img)}}><br>   
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td style="text-align: center; border: 1px solid #dee2e6;" colspan="3">市招</td>
        </tr>
        <tr>
            <td style="text-align: center; border: 1px solid #dee2e6;" colspan="3">
                @if($route->audit_status == 1)
                輔導照片
                @elseif($route->audit_status == 2)
                評核照片
                @endif
            </td>
        </tr>
        @if ($route->auditRecordFails->count() > 0)
            @foreach($route->auditRecordFails as $fail)
                @if($loop->first)
                    <tr>
                @endif
                    <td style="text-align: center; border: 1px solid #dee2e6;" @if($loop->iteration % 2 == 0) colspan="2" @endif>
                        <img src="{{$fail->img}}" {{app('BladeService')->imageResize($img->img)}}><br>
                        {{$fail->note}}
                    </td>
                @if($loop->last)
                    </tr>
                @endif
                @if($loop->iteration % 2 == 0)
                    </tr>
                    <tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>