@extends('layouts.main')

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.store')}}">
  @csrf
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">建立{{$menu->name}}資料</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool addAreaBtn"><i class="fas fa-plus"></i> 增加費率模組</button>
    </div>
  </div>
  <div class="card-body">
    @php
    $jsArr = [];
    @endphp
    @if($menu->menuCreateDetails->count() > 0)
        @foreach($menu->menuCreateDetails as $detail)
          @php
            if ($detail->has_js == 1)  {
              if (!in_array($detail->type.'.js', $jsArr)) {
                array_push($jsArr, $detail->type.'.js');
              }
            }
          @endphp
          @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
            @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
          @else  
            @if($detail->show_hidden_field == 1)
              @php
                $value = null;

                if ($detail->field == 'user_id') {
                  $value = $user->id;
                }
              @endphp
              @include('components.fields.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
            @else
              @if($detail->type == 'multiple_input')
              <div class="form-group">
              <label>{{$detail->show_name}}</label>
                <div id="{{ $detail->field }}">
                  @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                </div>
              </div>
              @else
                @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
              @endif  
            @endif
          @endif  
        @endforeach
    @endif
  </div>
</div>
<div id="rateArea">
  <div class="card card-secondary" id="card_1">
    <div class="card-header">
      <h3 class="card-title">通話費率-1</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool removeAreaBtn" data-rows="1" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="call_target_id_1"><span style="color:red">*</span>撥打對象</label>
            <select class="form-control callTarget" name="rates[1][call_target_id]" id="call_target_id_1" required>
              <option value="">請選擇</option>
              @foreach($callTargets??[] as $target)
              <option value="{{$target->id}}">{{$target->type_name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-6"></div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="call_rate_1"><span style="color:red">*</span>通話費率</label>
            <input type="text" class="form-control callRate" name="rates[1][call_rate]" id="call_rate_1" data-rows="1" required>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="discount_1"><span style="color:red">*</span>折讓</label>
            <input type="text" class="form-control discout" name="rates[1][discount]" id="discount_1" data-rows="1" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="discount_after_rate_1">折後費率</label>
            <input type="text" class="form-control" name="rates[1][discount_after_rate]" id="discount_after_rate_1" readonly>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="charge_unit_1"><span style="color:red">*</span>計費單位</label>
            <select class="form-control" name="rates[1][charge_unit]" id="charge_unit_1" required>
              <option value="">請選擇</option>
              <option value="1">秒鐘</option>
              <option value="2">分鐘</option>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="parameter_1"><span style="color:red">*</span>參數</label>
            <input type="text" class="form-control" name="rates[1][parameter]" id="parameter_1" required>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="card" id="footerArea">
  <div class="card-footer text-center">
    <button type="submit" class="btn bg-gradient-dark">儲存</button>
    <button type="button" class="btn bg-gradient-secondary" onclick="javascript:location.href='{{route($slug.'.index')}}'">回上一頁</button>
  </div>
</div>
</form>
@endsection

@section('js')
<script>
let targets = [];

$('.addAreaBtn').click(function(){
  let rows = parseInt($('#rateArea .card').length);
  let len = 0;

  do {
    rows++;
    len = parseInt($(`#card_${rows}`).length);
  } while( len > 0);

  let str = `
  <div class="card card-secondary" id="card_${rows}">
    <div class="card-header">
      <h3 class="card-title">通話費率-${rows}</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool removeAreaBtn" data-rows="${rows}" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="call_target_id_${rows}"><span style="color:red">*</span>撥打對象</label>
            <select class="form-control callTarget" name="rates[${rows}][call_target_id]" id="call_target_id_${rows}" required>
              <option value="">請選擇</option>`;
              @foreach($callTargets??[] as $target)
              if (targets.indexOf('{{$target->id}}') < 0) {
                str += `<option value="{{$target->id}}">{{$target->type_name}}</option>`;
              }
              @endforeach
    str += `          
            </select>
          </div>
        </div>
        <div class="col-sm-6"></div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="call_rate_${rows}"><span style="color:red">*</span>通話費率</label>
            <input type="text" class="form-control callRate" name="rates[${rows}][call_rate]" id="call_rate_${rows}" data-rows="${rows}" required>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="discount_${rows}"><span style="color:red">*</span>折讓</label>
            <input type="text" class="form-control discout" name="rates[${rows}][discount]" id="discount_${rows}" data-rows="${rows}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="discount_after_rate_${rows}">折後費率</label>
            <input type="text" class="form-control" name="rates[${rows}][discount_after_rate]" id="discount_after_rate_${rows}" readonly>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="charge_unit_${rows}"><span style="color:red">*</span>計費單位</label>
            <select class="form-control" name="rates[${rows}][charge_unit]" id="charge_unit_${rows}" required>
              <option value="">請選擇</option>
              <option value="1">秒鐘</option>
              <option value="2">分鐘</option>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="parameter_${rows}"><span style="color:red">*</span>參數</label>
            <input type="text" class="form-control" name="rates[${rows}][parameter]" id="parameter_${rows}" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  `;
  
  $('#rateArea').append(str);

  resetCardName();
});

$('body').on('click', '.removeAreaBtn', function(){
  const rows = $(this).data('rows');
  $(`#card_${rows}`).remove();

  resetCardName();
})

const resetCardName = () => {
  let rows = 1;

  $('#rateArea .card').each(function(){
    $(this).find('.card-title').text(`通話費率-${rows}`);
    rows++;
  })
}

$('body').on('change', '.callTarget', function(){
  if ($(this).val() != '') {
    targets.push($(this).val());
  } else {
    targets = [];

    $('body .callTarget').each(function(){
      targets.push($(this).val());
    })
  }
})

$('body').on('keyup', '.callRate', function(){
  const rows = $(this).data('rows');
  const rate = $(this).val();
  const discount = $(`#discount_${rows}`).val();
  let afterRate = 0;

  if (discount > 0) {
    afterRate = rate - (rate * (discount / 100));

    $(`#discount_after_rate_${rows}`).val(afterRate.toFixed(4));
  }
})

$('body').on('keyup', '.discout', function(){
  const rows = $(this).data('rows');
  const rate = $(`#call_rate_${rows}`).val();
  const discount = $(this).val();
  let afterRate = 0;

  if (discount > 0) {
    afterRate = rate - (rate * (discount / 100));

    $(`#discount_after_rate_${rows}`).val(afterRate.toFixed(4));
  }
})
</script>
@endsection