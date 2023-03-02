<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@if($audit->store){{$audit->store->name}}@endif 稽核紀錄表</title>
</head>
<style>
    body {
        font-family: "ARIALUNI"; /*我們自訂的字型名稱*/
        font-size: 9.5px;
    }
    .page-break {
        page-break-after: always;
    }
    table {
        width: 100%;
    }
    th {
        background-color: #DCDCDC;
    }
    td {
        padding: 10px;
    }
    .customer tr td{
        border: 1px solid #ddd;
        text-align: center;
        width: 100%;
        padding-top: 0px;
        padding-bottom: 0px;
        padding-left: 0px;
        padding-right: 0px;
    }
    td.noBorder {
        border-left-style:hidden;
    }
</style>
<body>
@php
    $auditDate = explode('-', $audit->audit_day);
    $taiwanYear = $auditDate[0] - 1911;
@endphp

<table cellspacing="0" cellpadding="0" class="confirmForm">
    <tr>
        <td style="text-align: center" colspan="3">
            臺北市 {{$taiwanYear}} 年度餐飲衛生管理分級計畫委託專業服務輔導及評核紀錄表
        </td>
    </tr>
    <tr>
        <td style="text-align: left">
        本表適用小型餐飲業: 
        @if($audit->audit_status==1)
        ■ 輔導 □ 評核
        @elseif ($audit->audit_status==2)
        □ 輔導 ■ 評核
        @else
        □ 輔導 □ 評核
        @endif
        </td>
        <td></td>
        <td style="text-align: right">日期: {{$taiwanYear}}年{{sprintf('%02d', $auditDate[1])}}月{{sprintf('%02d', $auditDate[2])}}日</td>
    </tr>
</table>
<table class="customer" style="border: 1px solid #ddd; border-collapse: collapse;">
    <tr>
        <td>商號<br>名稱</td>
        <td colspan="4">@if($audit->store){{$audit->store->name}}@endif</td>
        <td>地點</td>
        <td>
            @if($audit->store)
                @php
                    $len = mb_strlen($audit->store->address, 'UTF-8');
                    $line = ceil($len/10);
                @endphp
                @for($i=0; $i<$line; $i++)
                    @php
                        $position = $i * 10;
                    @endphp
                   {{mb_substr($audit->store->address, $position, 10, 'UTF-8')}}<br>
                @endfor
            @endif
        </td>
    </tr>
    <tr>
        <td>負責<br>或聯<br>絡人</td>
        <td>@if($audit->store){{$audit->store->manager}}@endif</td>
        <td>
            @if($audit->store)
                @if($audit->store->sex == 'm')
                ■ 男    □ 女
                @else
                □ 男    ■ 女
                @endif
            @endif
        </td>
        <td style="text-align: center">電話</td>
        <td style="text-align: left">@if($audit->store){{$audit->store->phone}}@endif</td>
        <td style="text-align: center">食品業者<br>登錄字號</td>
        <td style="text-align: left">@if($audit->store){{$audit->store->food_no}}@endif</td>
    </tr>
    <tr>
        <td rowspan="2">自主<br>管理<br>輔導</td>
        <td colspan="6" style="align-content: left">
            前：
            @if($audit->audit_status==1 &&$audit->counseling)
                @if($audit->counseling->has_manager_staff == 1)
                ■ 設管理衛生人員，
                @else
                □ 設管理衛生人員，
                @endif
                @if($audit->counseling->has_self_check == 1)
                ■ 自主檢查紀錄表，
                @else
                □ 自主檢查紀錄表，
                @endif
                @if($audit->counseling->certificate == 1)
                ■ 持證率　{{$audit->counseling->certificate_rate}}
                @else
                □ 持證率
                @endif
            @else
            □ 設管理衛生人員，  □ 自主檢查紀錄表，  □ 持證率
            @endif
        </td>
    </tr>
    <tr>    
        <td colspan="6" style="align-content: left">
            後：
            @if($audit->audit_status==2 &&$audit->counseling)
                @if($audit->counseling->has_manager_staff == 1)
                ■ 設管理衛生人員，
                @else
                □ 設管理衛生人員，
                @endif
                @if($audit->counseling->has_self_check == 1)
                ■ 自主檢查紀錄表，
                @else
                □ 自主檢查紀錄表，
                @endif
                @if($audit->counseling->certificate == 1)
                ■ 持證率　{{$audit->counseling->certificate_rate}}
                @else
                □ 持證率
                @endif
            @else
            □ 設管理衛生人員，  □ 自主檢查紀錄表，  □ 持證率
            @endif
        </td>
    </tr>
</table>
<table class="customer" style="border: 1px solid #ddd; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="align-content: center; width:60%;"  colspan="2">檢查項目</td>
        <td style="align-content: center; width:10%;">次要<br>缺失</td>
        <td style="align-content: center; width:10%;">主要<br>缺失</td>
        <td style="align-content: center; width:20%;">GHP<br>條文</td>
    </tr>
    @php
        $mainTotal = 0;
        $subTotal = 0;
        $storeTypName = optional($audit->store)->storeType->name;
        switch ($storeTypName) {
            case '小型': $type = 2;break;
            case '大型': $type = 1;break;
            default: $type = null;break;
        }
    @endphp
    @foreach($mainAttributes??[] as $mainAttribute)
        @if($mainAttribute->sort < 4)
            @foreach($mainAttribute->getSubAttributesByType($type)??[] as $k=>$subAttribute)
                @php
                $mainFail = 0;
                $subFail = 0;
                $str = $subAttribute->name.'：';
                @endphp
                @if($subAttribute->getRegulationByStoreType($type)->count() > 0)
                <tr>
                    @if($k==0)
                    <td rowspan="{{$mainAttribute->getSubAttributesByType($type)->count()}}" style="vertical-align: middle; word-wrap:break-word; width:10%;">{{$mainAttribute->name}}</td>
                    @endif
                    <td style="word-wrap:break-word; text-align:left; width:60%;">
                    @foreach($subAttribute->getRegulationByStoreType($type) as $regulation)
                        @php
                            if($regulation->is_import == 1) {
                                $str .= '【*';
                            }
                            if($regulation->is_main == 1) {
                                $str .= '*';
                            }
                            $log = $regulation->getAuditRecord($audit->id, $audit->store_id, $regulation->id);
                            if ($log) {
                                if($log->status == 1 || $log->status == 3) {
                                    $str .= '□'.$regulation->no.'.'.$regulation->clause.'．';
                                } else {
                                    if ($log->failType) {
                                        if (optional($log->failType)->name == '主要缺失' || optional($log->failType)->name == '主要缺失(重大)') {
                                            $mainFail ++;
                                            $mainTotal ++;
                                        }
                                        if (optional($log->failType)->name == '次要缺失') {
                                            $subFail ++;
                                            $subTotal ++;
                                        }
                                    }
                                    $str .= '■'.$regulation->no.'.'.$regulation->clause.'．';
                                }

                                if ($regulation->items->count() > 0) {
                                    $com = '';
                                    foreach ($regulation->items as $item) {
                                        $row = $auditRecordItem
                                            ->where('audit_record_id', $log->id)
                                            ->where('regulation_item_id', $item->id)
                                            ->first();
                                        if ($row) {
                                            $str .= str_replace('kg', '', $com.$item->name).':'.$row->value.'kg';
                                            $com = ',';
                                        }
                                    }
                                }

                                if($regulation->is_import == 1) {
                                    $str .= '】';
                                }
                            }
                        @endphp
                    @endforeach
                    {!! $str !!}
                    </td>
                    <td style="width:10%;">{{$subFail}}</td>
                    <td style="width:10%;">{{$mainFail}}</td>
                    <td style="word-wrap:break-word; text-align:left; width:10%;">{{$subAttribute->law_source}}</td>
                </tr>
                @endif
            @endforeach
        @endif
    @endforeach
</table>
<div class="page-break"></div>
<table class="customer" style="border: 1px solid #ddd; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="align-content: center; width:60%;"  colspan="2">檢查項目</td>
        <td style="align-content: center; width:10%;">次要<br>缺失</td>
        <td style="align-content: center; width:10%;">主要<br>缺失</td>
        <td style="align-content: center; width:20%;">GHP<br>條文</td>
    </tr>
    @php
        $storeTypName = optional($audit->store)->storeType->name;
        switch ($storeTypName) {
            case '小型': $type = 2;break;
            case '大型': $type = 1;break;
            default: $type = null;break;
        }
    @endphp
    @foreach($mainAttributes??[] as $mainAttribute)
        @if($mainAttribute->sort > 3)
            @foreach($mainAttribute->getSubAttributesByType($type)??[] as $k=>$subAttribute)
                @php
                $mainFail = 0;
                $subFail = 0;
                $str = $subAttribute->name.'：';
                @endphp
                @if($subAttribute->getRegulationByStoreType($type)->count() > 0)
                <tr>
                    @if($k==0)
                    <td rowspan="{{$mainAttribute->getSubAttributesByType($type)->count()}}" style="vertical-align: middle; word-wrap:break-word; width:10%;">{{$mainAttribute->name}}</td>
                    @endif
                    <td style="word-wrap:break-word; text-align:left; width:60%;">
                    @foreach($subAttribute->getRegulationByStoreType($type) as $regulation)
                        @php
                            if($regulation->is_import == 1) {
                                $str .= '【*';
                            }
                            if($regulation->is_main == 1) {
                                $str .= '*';
                            }
                            $log = $regulation->getAuditRecord($audit->id, $audit->store_id, $regulation->id);
                            if ($log) {
                                if($log->status == 1 || $log->status == 3) {
                                    $str .= '□'.$regulation->no.'.'.$regulation->clause.'．';
                                } else {
                                    if ($log->failType) {
                                        if (optional($log->failType)->name == '主要缺失') {
                                            $mainFail ++;
                                            $mainTotal ++;
                                        }
                                        if (optional($log->failType)->name == '次要缺失') {
                                            $subFail ++;
                                            $subTotal ++;
                                        }
                                    }
                                    $str .= '■'.$regulation->no.'.'.$regulation->clause.'．';
                                }

                                if ($regulation->items->count() > 0) {
                                    $com = '';
                                    foreach ($regulation->items as $item) {
                                        $row = $auditRecordItem
                                            ->where('audit_record_id', $log->id)
                                            ->where('regulation_item_id', $item->id)
                                            ->first();
                                        if ($row) {
                                            $str .= str_replace('kg', '', $com.$item->name).':'.$row->value.'kg';
                                            $com = ',';
                                        }
                                    }
                                }
                                if($regulation->is_import == 1) {
                                    $str .= '】';
                                }
                            }
                        @endphp
                    @endforeach
                    {!! $str !!}
                    </td>
                    <td style="width:10%;">{{$subFail}}</td>
                    <td style="width:10%;">{{$mainFail}}</td>
                    <td style="word-wrap:break-word; text-align:left; width:10%;">{{$subAttribute->law_source}}</td>
                </tr>
                @endif
            @endforeach
        @endif
    @endforeach
</table>    
<table class="customer" style="border: 1px solid #ddd; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="text-align: center">輔導查核紀要（含缺失及建議改善事項）</td>
    </tr>
    <tr>
        <td style="text-align: left; word-wrap:break-word; width:100%;">
            @php
                $filters = [
                    'audit_route_id'=>$audit->id
                ];
                $logs = $fail->getDataByFilters($filters);
            @endphp
            @if($logs->count() > 0)
            <ul>
                @foreach ($logs as $log)
                <li>[{{$log->auditRecord->regulation->no}}]{{$log->note}}</li>    
                @endforeach
                @if($audit->getNotApplicables->count() > 0)
                    @php
                        $str = '不適用：';
                        foreach ($audit->getNotApplicables as $item) {
                            if ($item->regulation) {
                                $str .= '['.$item->regulation->no.']';
                            }
                        }
                    @endphp
                    <li>{!! $str !!}</li>
                @endif
            </ul>
            @endif
        </td>
    </tr>
    <tr>
        <td style="text-align: left; word-wrap:break-word; width:100%;">
        本次輔導(評核)結果：主要缺失&nbsp;{{$mainTotal}}&nbsp;項，次要缺失&nbsp;{{$subTotal}}&nbsp;項，（3個次要缺失等同於1個主要缺失）。<br>
        @php 
            $mailFail = $mainTotal;
            $subFail = $subTotal;
            if ($subFail >=3) {
                $plus = floor($subFail / 3);

                $mailFail += $plus;
            }
        @endphp
        評核等級：
        <br>
        @if($mailFail < 2)
        ■ 優（未達２個主要缺失)
        @else
        □ 優（未達２個主要缺失)
        @endif
        <br> 
        @if($mailFail >= 2 && $mailFail < 4)
        ■ 良（２個主要缺失以上，未達４個主要缺失）
        @else
        □ 良（２個主要缺失以上，未達４個主要缺失）
        @endif
        @if($mailFail >= 4)
        <br>■ 不通過（４個主要缺失以上）。
        @else
        <br>□ 不通過（４個主要缺失以上）。
        @endif
        <br>最終評核結果以機關審查後核定為主。
        </td>
    </tr>
    <tr>
        <td>
            <table class="customer" style="border: 1px solid #ddd; border-collapse: collapse;">
                <tr>
                    <td style="text-align: left">
                        委託單位
                        <br><br><br><br>
                        輔導委員簽名：<br><br>
                        @if(!empty($audit->main_user_signe))
                        <img src="{{$audit->main_user_signe}}" width="60px" height="60px">
                        @endif
                        @if(!empty($audit->sub_user_signe))
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="{{$audit->sub_user_signe}}" width="60px" height="60px" style="margin-right: 10px">
                        @endif
                    </td>
                    <td style="text-align: left">
                        機關單位
                        <br><br><br><br>
                        會同人員簽名：
                        @if(!empty($audit->gov_signe))
                        <img src="{{$audit->gov_signe}}" width="60px" height="60px">
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align: left">
        業者簽名：<br>
        @if(!empty($audit->store_signe))
        <img src="{{$audit->store_signe}}" width="60px" height="60px"><br>
        @endif
        <span style="font-size: 10px">本店接受輔導查核時，並無發生金錢財務短少及其它任何損害情事</span>
        </td>
    </tr>
    <tr>
        <td style="text-align: left; font-size: 8px; word-wrap:break-word; width:100%;">
        1。輔導紀錄表依據行政院衛生福利部頒訂之「食品良好衛生規範準則則（GHP）」規範之餐飲業「從業人員、作業場所、設施衛生管理及其品保制度」及「餐飲衛
        生管理分級評核制度辦理注意事項」等相關規定項目訂定，同時 提報臺北市政府衛生局核可後實施。<br>
        2。不符合規定請在檢查項目欄位處以「■」呈現，同時自我裁量判定不符合項目是「主要缺失或次要缺失(條文前加*符號有不符合即判定為主要缺失)」，並
        陳述缺失狀況於輔導查核記要處。條文加【】符號(如不得有逾有效日期之食品)項目，不符合即判定未達良級(不合格)。
        3。輔導列有「不得有逾有效日期之食品」缺失者，其評核通過結果將降級，僅授予良級或不合格。
        4。本計畫結束，輔導及評核紀錄表送臺北市政府衛生局存查，個人資料依「個人資料保護法」規定辦理。
        </td>
    </tr>
</table>
</body>
</html>