@extends('admins.layouts.main')
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
</style>
@endsection

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route('admin.'.$menu->slug.'.store')}}">
  @csrf
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">建立{{$menu->name}}資料</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name"><span style="color:red">*</span>民宿名稱</label>
            <input type="text" class="form-control" name="name" value="" id="name" placeholder="請輸入民宿名稱">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="homestay_no"><span style="color:red">*</span>民宿合法編號</label>
            <input type="text" class="form-control" name="homestay_no" value="" id="homestay_no" placeholder="請輸入民宿合法編號">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group clearfix">
            <label>民宿電話號碼</label><br>
            <input type="tel" class="form-control-4" name="homestay_tel_1" value="" id="homestay_tel_1" maxlength="5" placeholder="請輸入區碼" pattern="^\d+$">
            <input type="tel" class="form-control-5" name="homestay_tel_2" value="" id="homestay_tel_2" placeholder="請輸入號碼" pattern="^\d+$">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="homestay_mobile"><span style="color:red">*</span>民宿手機號碼<span style="color: red">(ex:0988123456)</span></label>
            <input type="text" class="form-control" name="homestay_mobile" value="" id="homestay_mobile" pattern="^\d+$" placeholder="請輸入民宿手機號碼">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="homestay_email"><span style="color:red">*</span>民宿email</label>
            <input type="email" class="form-control" name="homestay_email" value="" id="homestay_email" placeholder="請輸入民宿email">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="manager">民宿聯絡窗口</label>
            <input type="text" class="form-control" name="manager" value="" id="manager" placeholder="請輸入民宿聯絡窗口">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="mobile"><span style="color:red">*</span>聯絡窗口手機號碼<span style="color: red">(ex:0988123456)</span></label>
            <input type="text" class="form-control" name="mobile" value="" id="mobile" pattern="^\d+$" placeholder="請輸入聯絡窗口手機號碼" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email"><span style="color:red">*</span>聯絡窗口email</label>
            <input type="email" class="form-control" name="email" value="" id="email" placeholder="請輸入聯絡窗口email" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="email"><span style="color:red">*</span>子網域</label>
            <input type="text" class="form-control" name="sub_domain" value="" id="sub_domain" placeholder="請輸入子網域" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>啟用稅金</label>
            <div class="form-check">
              <input type="radio" class="form-check-input" name="use_tax" id="use_tax_1" value="1">
              <label for="use_tax_1" class="form-check-label">是</label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" name="use_tax" id="use_tax_2" value="2" checked>
              <label for="use_tax_2" class="form-check-label">否</label>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>啟用訂金</label>
            <div class="form-check">
              <input type="radio" class="form-check-input" name="use_deposit" id="use_deposit_1" value="1">
              <label for="use_deposit_1" class="form-check-label">是</label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" name="use_deposit" id="use_deposit_2" value="2" checked>
              <label for="use_deposit_2" class="form-check-label">否</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label><span style="color:red">*</span>民宿地址</label>
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
              <input type="text" class="form-control" name="address" value="" id="address" placeholder="請輸入地址" required>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>
  <div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">系統設定</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="logo"><span style="color:red">*</span>民宿Logo</label>
            <input type="file" class="form-control dropify" name="system[logo]" value="" id="logo" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="web_icon"><span style="color:red">*</span>Web Icon</label>
            <input type="file" class="form-control dropify" name="system[web_icon]" value="" id="web_icon" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="layout_id"><span style="color:red">*</span>選擇樣版</label>
            <select class="form-control" name="system[layout_id]" id="system[layout_id]" placeholder="請選擇樣版" required>
              <option value="">請選擇</option>
              @foreach($layouts??[] as $layout)
              <option value="{{$layout->id}}">{{$layout->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="web_title"><span style="color:red">*</span>網站標題</label>
            <input type="text" class="form-control" name="system[web_title]" value="" id="web_title">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="main_style_color"><span style="color:red">*</span>網站主要色標	</label>
            <input type="color" class="form-control" name="system[main_style_color]" value="" id="main_style_color" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('admins.components.seo', ['data'=>null])
  <div class="card">
    <div class="card-body">
      <div class="card-footer text-center">
        <button type="submit" class="btn bg-gradient-dark">儲存</button>
        <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route('admin.'.$slug.'.index')}}'">回上一頁</button>
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
  'css':['form-control-1','form-control-2','form-control-3']
});
</script>  
@endsection