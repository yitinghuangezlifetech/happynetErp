@extends('layouts.main')
@section('css')
<style>
.form-control-1 {
    display: block;
    width: 20%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control-2 {
    display: block;
    width: 20%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control-3 {
    display: block;
    width: 10%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control-4 {
    width: 30%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control-5 {
    width: 69%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control-6 {
    width: 40%;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
</style>
@endsection

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $data->id)}}">
  @csrf
  @method('put')
  <div class="card card-secondary">
    <div class="card-header">
      <h3 class="card-title">編輯{{$menu->name}}資料</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="system_no"><span style="color:red">*</span>系統編號</label>
            <input type="text" class="form-control" name="system_no" value="{{$data->system_no}}" id="system_no" placeholder="請輸入系統編號" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="organization_type_id">所屬類型</label>
            <select class="form-control" name="organization_type_id" id="organization_type_id">
              <option value="">請選擇</option>
              @foreach($organizationTypes??[] as $organizationType)
              <option value="{{$organizationType->id}}" @if($data->organization_type_id == $organizationType->id){{'selected'}}@endif>{{$organizationType->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="identity_id"><span style="color:red">*</span>身份別</label>
            <select class="form-control" name="identity_id" id="identity_id" required>
              <option value="">請選擇</option>
              @foreach($identities??[] as $identity)
              <option value="{{$identity->id}}" @if($data->identity_id == $identity->id){{'selected'}}@endif>{{$identity->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="organization_type_id">所屬組織</label>
            <select class="form-control" name="parent_id" id="parent_id">
              <option value="">請選擇</option>
              @foreach($organizations??[] as $organization)
              <option value="{{$organization->id}}" @if($data->organization_type_id == $organization->id){{'selected'}}@endif>{{$organization->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="group_id"><span style="color:red">*</span>所屬群組</label>
            <select class="form-control" name="group_id" id="group_id" required>
              <option value="">請選擇</option>
              @foreach($groups??[] as $group)
              <option value="{{$group->id}}" @if($data->group_id == $group->id){{'selected'}}@endif>{{$group->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="fee_rate_id">使用費率</label>
            <select class="form-control" name="fee_rate_id" id="fee_rate_id">
              <option value="">請選擇</option>
              @foreach($feeRates??[] as $rate)
              <option value="{{$rate->id}}" @if($data->fee_rate_id == $rate->id){{'selected'}}@endif>{{$rate->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name"><span style="color:red">*</span>用戶名稱</label>
            <input type="text" class="form-control" name="name" id="name" value="{{$data->name}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="id_no"><span style="color:red">*</span>統編/身份證號</label>
            <input type="text" class="form-control" name="id_no" id="id_no" value="{{$data->id_no}}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="manager"><span style="color:red">*</span>負責人</label>
            <input type="text" class="form-control" name="manager" id="manager" value="{{$data->manager}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="manager_id_no"><span style="color:red">*</span>負責人身份字號</label>
            <input type="text" class="form-control" name="manager_id_no" id="manager_id_no" value="{{$data->manager_id_no}}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="mobile"><span style="color:red">*</span>行動電話(ex:<span style="color:red;size:12px">0988123456</span>)</label>
            <input type="number" class="form-control" name="mobile" id="mobile" value="{{$data->mobile}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>市話</label><br>
            <input type="tel" class="form-control-6" name="tel" value="{{$data->tel}}" id="tel" placeholder="市話號碼" pattern="^\d+$">#
            <input type="tel" class="form-control-4" name="tel_1" value="{{$data->tel_1}}" id="tel_1" placeholder="分機號碼" pattern="^\d+$">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group clearfix">
            <label for="fax">傳真</label><br>
            <input type="text" class="form-control" name="fax" value="{{$data->fax}}" id="fax" placeholder="請輸入傳真號碼">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="line">line</label>
            <input type="text" class="form-control" name="line" value="{{$data->line}}" id="line" placeholder="請輸入line">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group clearfix">
            <label for="email"><span style="color:red">*</span>Email</label><br>
            <input type="email" class="form-control" name="email" value="{{$data->email}}" id="email" placeholder="請輸入Email" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="official_website">官網</label>
            <input type="text" class="form-control" name="official_website" value="{{$data->official_website}}" id="official_website" placeholder="請輸入官網網址">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group clearfix">
            <label for="expiry_day"><span style="color:red">*</span>合約到期日</label><br>
            <input type="date" class="form-control" name="expiry_day" value="{{$data->expiry_day}}" id="expiry_day" placeholder="請選擇日期" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_day"><span style="color:red">*</span>每月結算日</label>
            <input type="text" class="form-control" name="bill_day" value="{{$data->bill_day}}" id="bill_day" placeholder="請輸入數字" pattern="^\d+$" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group clearfix">
            <label for="attach_number_limit"><span style="color:red">*</span>附掛號碼總數</label><br>
            <input type="text" class="form-control" name="attach_number_limit" value="{{$data->attach_number_limit}}" id="attach_number_limit" placeholder="請輪入附掛號碼限制數" pattern="^\d+$" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="phone_call_limit"><span style="color:red">*</span>最大通話總數</label>
            <input type="text" class="form-control" name="phone_call_limit" value="{{$data->phone_call_limit}}" id="phone_call_limit" placeholder="請輸入數字" pattern="^\d+$" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label><span style="color:red">*</span>統編/戶籍地址</label>
            <!-- https://github.com/essoduke/jQuery-TWzipcode -->
          </div>
        </div>
      </div>
      <div class="row twzipcode" style="margin-left: 1px">  
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="county" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="district" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="zipcode" data-value=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">  
        <div class="col-md-12">
          <div class="form-group clearfix">
            <div class="d-inline">
              <input type="text" class="form-control" name="address" value="{{$data->address}}" id="address" placeholder="請輸入地址" required>
            </div>
          </div>
        </div>  
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label><span style="color:red">*</span>帳單地址</label>
            <!-- https://github.com/essoduke/jQuery-TWzipcode -->
          </div>
        </div>
      </div>
      <div class="row twzipcode-1" style="margin-left: 1px">  
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="bill_county" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="bill_district" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="bill_zipcode" data-value=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group clearfix">
            <div class="d-inline">
              <input type="text" class="form-control" name="bill_address" value="{{$data->bill_address}}" id="bill_address" placeholder="請輸入地址" required>
            </div>
          </div>
        </div>  
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label><span style="color:red">*</span>營業地址</label>
            <!-- https://github.com/essoduke/jQuery-TWzipcode -->
          </div>
        </div>
      </div>
      <div class="row twzipcode-2" style="margin-left: 1px">  
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="business_county" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="business_district" data-value=""></div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group clearfix">
            <div class="d-inline">
              <div data-role="business_zipcode" data-value=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group clearfix">
            <div class="d-inline">
              <input type="text" class="form-control" name="business_address" value="{{$data->business_address}}" id="business_address" placeholder="請輸入地址" required>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">帳戶聯絡人資訊</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_contact"><span style="color:red">*</span>帳務聯絡人</label>
            <input type="text" class="form-control" name="bill_contact" id="bill_contact" value="{{$data->bill_contact}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_contact_line">帳務聯絡人-line</label>
            <input type="text" class="form-control" name="bill_contact_line" id="bill_contact_line" value="{{$data->bill_contact_line}}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_contact_mobile"><span style="color:red">*</span>帳務聯絡人-手機(ex:<span style="color:red;size:12px">0988123456</span>)</label>
            <input type="number" class="form-control" name="bill_contact_mobile" id="bill_contact_mobile" value="{{$data->bill_contact_mobile}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>帳務聯絡人-市話</label><br>
            <input type="tel" class="form-control-6" name="bill_contact_tel" value="{{$data->bill_contact_tel}}" id="bill_contact_tel" placeholder="市話號碼" pattern="^\d+$">#
            <input type="tel" class="form-control-4" name="bill_contact_tel_1" value="{{$data->bill_contact_tel_1}}" id="bill_contact_tel_1" placeholder="分機號碼" pattern="^\d+$">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_contact_mail"><span style="color:red">*</span>帳務聯絡人信箱1</label>
            <input type="email" class="form-control" name="bill_contact_mail" id="bill_contact_mail" value="{{$data->bill_contact_mail}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="bill_contact_mail_1">帳務聯絡人信箱2</label>
            <input type="email" class="form-control" name="bill_contact_mail_1" id="bill_contact_mail_1" value="{{$data->bill_contact_mail_1}}">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">裝機聯絡人資訊</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="setup_contact"><span style="color:red">*</span>裝機聯絡人</label>
            <input type="text" class="form-control" name="setup_contact" id="setup_contact" value="{{$data->setup_contact}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="setup_contact_line">裝機聯絡人-line</label>
            <input type="text" class="form-control" name="setup_contact_line" id="setup_contact_line" value="{{$data->setup_contact_line}}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="setup_contact_mobile"><span style="color:red">*</span>裝機聯絡人-手機(ex:<span style="color:red;size:12px">0988123456</span>)</label>
            <input type="number" class="form-control" name="setup_contact_mobile" id="setup_contact_mobile" value="{{$data->setup_contact_mobile}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>裝機聯絡人-市話</label><br>
            <input type="tel" class="form-control-6" name="setup_contact_tel" value="{{$data->setup_contact_tel}}" id="setup_contact_tel" placeholder="市話號碼" pattern="^\d+$">#
            <input type="tel" class="form-control-4" name="setup_contact_tel_1" value="{{$data->setup_contact_tel_1}}" id="setup_contact_tel_1" placeholder="分機號碼" pattern="^\d+$">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="setup_contact_mail"><span style="color:red">*</span>裝機聯絡人信箱1</label>
            <input type="email" class="form-control" name="setup_contact_mail" id="setup_contact_mail" value="{{$data->setup_contact_mail}}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="setup_contact_mail_1">裝機聯絡人信箱2</label>
            <input type="email" class="form-control" name="setup_contact_mail_1" id="setup_contact_mail_1" value="{{$data->setup_contact_mail_1}}">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="card-footer text-center">
        <button type="submit" class="btn bg-gradient-dark">儲存</button>
        <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
      </div>
    </div>
  </div>
</form>
@endsection

@section('js')
<script src="/admins/js/components/image.js"></script>
<script src="/admins/plugins/twzipcode/twzipcode.js"></script>
<script>
$('.twzipcode').twzipcode({
  'css':['form-control-1','form-control-2','form-control-3'],
  'countySel': '{{$data->county}}',
  'districtSel': '{{$data->district}}',
  'zipcodeSel': '{{$data->zipcode}}'
});
$('.twzipcode-1').twzipcode({
  'css':['form-control-1','form-control-2','form-control-3'],
  'countyName': 'bill_county',
  'districtName': 'bill_district',
  'zipcodeName': 'bill_zipcode',
  'countySel': '{{$data->bill_county}}',
  'districtSel': '{{$data->bill_district}}',
  'zipcodeSel': '{{$data->bill_zipcode}}'
});
$('.twzipcode-2').twzipcode({
  'css':['form-control-1','form-control-2','form-control-3'],
  'countyName': 'business_county',
  'districtName': 'business_district',
  'zipcodeName': 'business_zipcode',
  'countySel': '{{$data->business_county}}',
  'districtSel': '{{$data->business_district}}',
  'zipcodeSel': '{{$data->business_zipcode}}'
});
</script>  
@endsection