<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$data->apply_no}}-申請書</title>
</head>
<style>
body {
  font-family: "ARIALUNI"; /*我們自訂的字型名稱*/
  font-size: 10px;
}
.page-break {
    page-break-after: always;
}
table {
    width: 100%;
}
table, th, td {
  border: 1px solid;
  word-wrap: break-word;
  word-break: break-all;
}
th {
    background-color: #DCDCDC;
}
td {
    padding: 10px;
}
</style>
<body>
<table cellspacing="0" cellpadding="0">
  <tr>
    <td style="text-align: center; background-color:#000000;"><span style="color:#ffffff">合約編號</span></td>
    <td style="text-align: center">{{$data->contract_no}}</td>
    <td style="text-align: center; background-color:#000000"><span style="color:#ffffff">用戶帳號</span></td>
    <td style="text-align: center">{{$data->user->account}}</td>
    <td style="text-align: center"><span style="font-size: 14px">{{$data->contract_name}}</span></td>
    <td style="text-align: center">專案編號({{optional($data->project)->project_no}})</td>
  </tr>
</table>
<br>
<table cellspacing="0" cellpadding="0">
  <tr>
    <td style="text-align: center;background-color: #77797d"><span style="color:#ffffff">申請日期</span></td>
    <td style="text-align: center" colspan="3">{{$data->apply_day}}</td>
    <td style="text-align: center;background-color: #77797d"><span style="color:#ffffff">申請類別</span></td>
    <td style="text-align: center" colspan="3">{{$data->applyType->type_name}}</td>
  </tr>
  <tr>
    <td style="text-align: center;background-color: #77797d"><span style="color:#ffffff">結算區間</span></td>
    <td style="text-align: center">{{$data->closePeriod->name}}</td>
    <td style="text-align: center;background-color: #77797d"><span style="color:#ffffff">付費方式</span></td>
    <td style="text-align: center">後付</td>
    <td style="text-align: center;background-color: #2ea7e8"><span style="color:#ffffff">月繳合計</span></td>
    <td style="text-align: center">2500</td>
    <td style="text-align: center;background-color: #a041d8"><span style="color:#ffffff">保證金合計</span></td>
    <td style="text-align: center">1000</td>
  </tr>
  <tr>
    <td style="text-align: center;background-color: #22aa22" colspan="4"><span style="color:#ffffff">新申裝或異動後資料</span></td>
    <td style="text-align: center;background-color: #000000" colspan="4"><span style="color:#ffffff">客戶異動前資料</span></td>
  </tr>
  <tr>
    <td style="text-align: center">用戶名稱</td>
    <td style="text-align: center" colspan="3">{{$data->user->name}}</td>
    <td style="text-align: center">用戶名稱</td>
    <td style="text-align: center" colspan="3"></td>
  </tr>
  <tr>
    <td style="text-align: center">統一編號</td>
    <td style="text-align: center">{{$data->organization->id_no}}</td>
    <td style="text-align: center">裝機聯絡人</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">統一編號</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">裝機聯絡人</td>
    <td style="text-align: center"></td>
  </tr>
  <tr>
    <td style="text-align: center">負責人</td>
    <td style="text-align: center">{{$data->organization->manager}}</td>
    <td style="text-align: center">裝機聯絡電話</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">負責人</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">裝機聯絡電話</td>
    <td style="text-align: center"></td>
  </tr>
  <tr>
    <td style="text-align: center">身份證字號</td>
    <td style="text-align: center">{{$data->organization->manager_id_no}}</td>
    <td style="text-align: center">帳務聯絡人</td>
    <td style="text-align: center">{{$data->organization->bill_contact}}</td>
    <td style="text-align: center">身份證字號</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">帳務聯絡人</td>
    <td style="text-align: center"></td>
  </tr>
  <tr>
    <td style="text-align: center">公司電話</td>
    <td style="text-align: center">{{$data->organization->tel}}</td>
    <td style="text-align: center">帳務聯絡電話</td>
    <td style="text-align: center">{{$data->organization->bill_contact_tel}}</td>
    <td style="text-align: center">公司電話</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">帳務聯絡電話</td>
    <td style="text-align: center"></td>
  </tr>
  <tr>
    <td style="text-align: center">公司傳真</td>
    <td style="text-align: center">{{$data->organization->fax}}</td>
    <td style="text-align: center">帳務人 E-mail</td>
    <td style="text-align: center">{{$data->organization->bill_contact_mail}}</td>
    <td style="text-align: center">公司傳真</td>
    <td style="text-align: center"></td>
    <td style="text-align: center">帳務人 E-mail</td>
    <td style="text-align: center"></td>
  </tr>
  <tr>
    <td style="text-align: center" colspan="2">公司地址 / 戶籍地址</td>
    <td style="text-align: center" colspan="2">{{$data->organization->zipcode}}{{$data->organization->county}}{{$data->organization->district}}{{$data->organization->address}}</td>
    <td style="text-align: center" colspan="2">公司地址 / 戶籍地址</td>
    <td style="text-align: center" colspan="2"></td>
  </tr>
  <tr>
    <td style="text-align: center" colspan="2">帳單地址</td>
    <td style="text-align: center" colspan="2">{{$data->organization->bill_zipcode}}{{$data->organization->bill_county}}{{$data->organization->bill_district}}{{$data->organization->bill_address}}</td>
    <td style="text-align: center" colspan="2">帳單地址</td>
    <td style="text-align: center" colspan="2"></td>
  </tr>
  <tr>
    <td style="text-align: center" colspan="2">裝機地址</td>
    <td style="text-align: center" colspan="2"></td>
    <td style="text-align: center" colspan="2">裝機地址</td>
    <td style="text-align: center" colspan="2"></td>
  </tr>
</table>
@if(!empty($data->contract_id))
  @foreach($data->contract->productTypeLogs??[] as $log)
    @if($log->productType->type_name == '語音服務')
    <table cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="text-align: center" colspan="7">{{$log->productType->type_name}}</th>
        </tr>
        <tr>
          <td style="text-align: center">服務名稱</td>
          <td style="text-align: center">撥打對象</td>
          <td style="text-align: center">通話費率</td>
          <td style="text-align: center">折讓(%)</td>
          <td style="text-align: center">折後費率</td>
          <td style="text-align: center">計費單位</td>
          <td style="text-align: center">服務帳號</td>
        </tr>
      </thead>
      <tbody>
        @foreach($log->productLogs??[] as $k=>$record)
          @if(isset($applyLogs[$log->product_type_id][$record->product_id]))
          <tr>
            <td style="vertical-align: middle;text-align: center" rowspan="{{$record->product->feeRate->logs->count()+1}}">
              {{$record->product->name}}
            </td>
          </tr>
            @foreach($record->product->feeRate->logs??[] as $k1=>$feeRateLog)
            <tr>
              <td style="text-align: center" style="vertical-align: middle">
                @if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]))
                {{$feeRateLog->target->type_name}}
                @endif
              </td>
              <td style="text-align: center" style="vertical-align: middle">
                {{$feeRateLog->call_rate}}
              </td>
              <td style="text-align: center" style="vertical-align: middle">
                @php
                  $value = '';
                  if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['discount'];
                  }
                @endphp
                {{$value}}
              </td>
              <td style="text-align: center" style="vertical-align: middle">
                @php
                  $value = '';
                  if(isset($applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id])) {
                    $value = $applyLogs[$log->product_type_id][$record->product_id][$feeRateLog->call_target_id]['amount'];
                  }
                @endphp
                {{$value}}
              </td>
              <td style="text-align: center" style="vertical-align: middle">
                {{$log->parameter}}
                @if($log->charge_unit == 1)
                秒鐘
                @else
                分鐘
                @endif
              </td>
              <td style="text-align: center;vertical-align: middle">
                @if($k==0 && $k1 == 0)
                <span id="userAccount">{{$data->user->account}}</span><br><br>
                <table cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="text-center" style="background-color: #d1cbba">顥示號碼</td>
                  </tr>
                  <tr>
                    <td class="text-center" id="telecom_number">{{$data->user->telecom_number}}</td>
                  </tr>
                </table>
                @endif
              </td>
            </tr>
            @endforeach
          @endif
        @endforeach
      </tbody>
    </table>
    @else
    <table cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="text-align: center" colspan="7">{{$log->productType->type_name}}</th>
        </tr>
        <tr>
          <td style="text-align: center">品名/型號/規格</td>
          <td style="text-align: center">數量</td>
          <td style="text-align: center">月租</td>
          <td style="text-align: center">折讓(%)</td>
          <td style="text-align: center">費用</td>
          <td style="text-align: center">保證金</td>
          <td style="text-align: center">備註</td>
        </tr>
      </thead>
      <tbody>
        @foreach($log->productLogs??[] as $k=>$record)
          @if(isset($applyLogs[$log->product_type_id][$record->product_id]))
          <tr>
            <td class="text-center">
              {{$record->product->name}}
            </td>
            <td class="text-center">
              @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['qty'])) {
                  $value = $applyLogs[$log->product_type_id][$record->product_id]['qty'];
                }
              @endphp
              {{$value}}
            </td>
            <td class="text-center">
              {{$record->product->rent_month}}
            </td>
            <td class="text-center">
              @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['discount'])) {
                  $value = $applyLogs[$log->product_type_id][$record->product_id]['discount'];
                }
              @endphp
              {{$value}}
            </td>
            <td class="text-center">
              @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['amount'])) {
                  $value = $applyLogs[$log->product_type_id][$record->product_id]['amount'];
                }
              @endphp
              {{$value}}
            </td>
            <td class="text-center">
              @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['security_deposit'])) {
                  $value = $applyLogs[$log->product_type_id][$record->product_id]['security_deposit'];
                }
              @endphp
              {{$value}}
            </td>
            <td class="text-center">
              @php
                $value = '';
                if(isset($applyLogs[$log->product_type_id][$record->product_id]['note'])) {
                  $value = $applyLogs[$log->product_type_id][$record->product_id]['note'];
                }
              @endphp
              {{$value}}
            </td>
          </tr>
          @endif
        @endforeach
      </tbody>
    </table>
    @endif
  @endforeach
@else

@endif
<table cellspacing="0" cellpadding="0">
  <tr>
    <td style="text-align: center; background-color:#22aa22"><span style="color:#ffffff">合約條款【攸關您的權益請詳細閱讀】</span></td>
  </tr>
  <tr>
    <td>
      @foreach($data->terms??[] as $log)
      {{$log->term->title}}：{{$log->term->describe}}<br>@if(!empty($log->term->content))<br>@endif
      {!! $log->term->content !!}
      @endforeach
    </td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0">
  <tr>
    <td style="text-align: center; background-color: #DCDCDC">新用戶簽章 (請蓋公司大小章)</td>
    <td style="text-align: center; background-color: #DCDCDC">原用戶簽章 (請蓋公司大小章)</td>
    <td style="text-align: center; background-color: #DCDCDC">經辦人員</td>
    <td style="text-align: center; background-color: #DCDCDC">收件中心</td>
  </tr>
  <tr>
    <td rowspan="6">
      <img src="{{$data->company_seal}}" width="60%">
      <img src="{{$data->company_stamp}}" width="30%">
    </td>
    <td rowspan="6"></td>
  </tr>
  <tr>
    <td style="text-align: center; background-color: #d1cbba">送件人</td>
    <td style="text-align: center; background-color: #d1cbba">收件人</td>
  </tr>
  <tr>
    <td style="text-align: center;">
    {{$data->sender->name}}<br>
    <img src="{{$data->sender_sign}}" width="100%">
    </td>
    <td style="text-align: center;">
    {{$data->recipient->name}}<br>
    <img src="{{$data->recipient_sign}}" width="100%">
    </td>
  </tr>
  <tr>
    <td style="text-align: center; background-color: #d1cbba">經銷代碼</td>
    <td style="text-align: center; background-color: #d1cbba">技術員</td>
  </tr>
  <tr>
    <td style="text-align: center;">
    {{$data->system_no}}
    </td>
    <td style="text-align: center;">
    {{optional($data->technician)->name}}<br>
    @if(!empty($data->technician_sign))
    <img src="{{$data->technician_sign}}" width="100%">
    @endif
    </td>
  </tr>
  <tr>
    <td style="text-align: center; background-color: #d1cbba">聯絡電話</td>
    <td style="text-align: center; background-color: #d1cbba">稽核人</td>
  </tr>
  <tr>
    <td style="text-align: center;" colspan="2">本人/公司已確認詳閱本專案服務內容,親簽本服務申請書,如有造假願付法律所有責任。</td>
    <td style="text-align: center;">{{$data->tel}}</td>
    <td style="text-align: center;">{{$data->audit->name}}</td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: none;">
  <tr>
    <td style="border: none;"><span style="color: #f80318; ">正本送交本公司請用戶自行影印留存</span></td>
    <td style="text-align: center; border: none;">收件中心傳真號碼:02-26621380</td>
    <td style="text-align: center; border: none;">客服電話:02-77143999</td>
    <td style="text-align: center; border: none;">申請書版本:{{date('Ymd')}}</td>
  </tr>
</table>
</body>
</html>