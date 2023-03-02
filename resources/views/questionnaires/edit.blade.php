@extends('layouts.main')

@section('content')
<form id="form" enctype="multipart/form-data" method="POST" action="{{route($menu->slug.'.update', $id)}}">
  @csrf
  @method('put')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">編輯{{$menu->name}}資料</h3>
  </div>
  <div class="card-body">
      @if($menu->menuCreateDetails->count() > 0)
          @foreach($menu->menuCreateDetails as $detail)
            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
          @endforeach
      @endif
  </div>
</div>
<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">設定問卷題目</h3>
    <div style="float: right;">
      <button type="button" class="btn bg-gradient-success btn-sm addSubject"><i class="fas fa-plus-circle"></i> 增加題目</button>
    </div>
  </div>
  <div class="card-body" id="subjectContent">
    @if($data->topics->count() > 0)
      @foreach($data->topics as $k=>$topic)
      <div class="card card-warning" id="car_{{$topic->id}}" style="margin-top: 10px;">
        <input type="hidden" name="topics[{{$topic->id}}][sort]" id="sort_{{$topic->id}}" value="{{$topic->sort}}">
        <input type="hidden" name="topics[{{$topic->id}}][id]" value="{{$topic->id}}">
        <div class="card-header">
          <h3 class="card-title">題目{{$k+1}}</h3>
          <div style="float: right;">
            <button type="button" class="btn bg-gradient-danger btn-sm removeSubject" data-row="{{$topic->id}}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="topic_type_{{$topic->id}}">題目屬性</label>
            <select class="custom-select form-control-border topicType" name="topics[{{$topic->id}}][topic_type]" id="topic_type_{{$topic->id}}" data-row="{{$topic->id}}" required>
              <option value="">請選擇題目屬性</option>
              @foreach($types??[] as $type)
              <option value="{{$type->val}}" @if($type->val == $topic->topic_type){{'selected'}}@endif>{{$type->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <label for="subject_{{$topic->id}}}">題目</label>
            <input type="text" class="form-control" name="topics[{{$topic->id}}][subject]" id="subject_{{$topic->id}}" value="{{$topic->subject}}" placeholder="請輸入題目" required>
          </div>
          <div class="form-group row">
            <label>選項設定</label>
          </div>
          <div class="form-group row content_{{$topic->id}}">
          @if($topic->options->count() > 0)
            @foreach ($topic->options as $option)
            <div class="col-sm-12" id="item_{{$option->id}}">
              <div class="input-group">
                <input type="text" class="form-control" name="topics[{{$topic->id}}][items][{{$option->id}}][name]" value="{{$option->name}}" placeholder="請輸入選項名稱" required>
                <div class="input-group-append">
                  <span class="input-group-text">
                      <button type="button" class="btn btn-xs multipleMinus" data-row="{{$topic->id}}" data-item="{{$option->id}}"><i class="fas fa-minus"></i></button>
                  </span>
                  <span class="input-group-text">
                      <button type="button" class="btn btn-xs multiplePlus" data-row="{{$topic->id}}"><i class="fas fa-plus"></i></button>
                  </span>
                </div>
              </div>
            </div>
            @endforeach
          @endif
          </div>
        </div>
      </div>  
      @endforeach
    @endif
  </div>
</div>
<div class="card">
  <div class="card-footer text-center">
    <button type="button" class="btn bg-gradient-primary save">儲存</button>
    <button type="button" class="btn bg-gradient-secondary preview">預覽</button>
  </div>
</div>
</form>
@endsection

@section('js')
<script src="/admins/plugins/sortable/Sortable.js"></script>
<script src="/admins/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
var items = document.getElementById('subjectContent');
new Sortable(subjectContent, {
    animation: 150,
    ghostClass: 'blue-background-class',
    onEnd: function (/**Event*/evt) {
      var sort = 0;
      var number = 1;
      $('#subjectContent .card-title').each(function(){
        $(this).html(`題目${number}`);
        number++;
      })
      $('#subjectContent input[id^="sort_"]').each(function(){
        $(this).val(sort);
        sort++;
      })
    },
});  

$('#form').validate({
  rules: {
    questionnaire_event_id:{
      required: true
    },
    name:{
      required: true
    },
  },
  messages: {
    questionnaire_event_id: {
      required: '<span style="color:red">請選擇問卷活動!</span>'
    },
    name:{
      required: '<span style="color:red">請填寫問卷名稱!</span>'
    },
  }
});

$('.preview').click(function(){
  $('#form').attr('action', '{{route($menu->slug.'.preview')}}');
  $('#form').attr('target', '_blank');
  $('input[name="_method"]').val('');
  $('#form').submit();
})

$('.save').click(function(){
  $('#form').attr('action', '{{route($menu->slug.'.update', $id)}}')
  $('#form').attr('target', '_self')
  $('input[name="_method"]').val('put');
  $('#form').submit();
})

$('body').on('click', '.addSubject', function(){
  var row = parseInt($('#subjectContent div[id^="car_"]').length);
  var sort = parseInt($('#subjectContent input[id^="sort_"]').length);
  var number = row + 1;
  var str = `
  <div class="card card-warning" id="car_${row}" style="margin-top: 10px;">
    <input type="hidden" name="topics[${row}][sort]" id="sort_${row}" value="${sort}">
    <div class="card-header">
      <h3 class="card-title">題目${number}</h3>
      <div style="float: right;">
        <button type="button" class="btn bg-gradient-danger btn-sm removeSubject" data-row="${row}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="topic_type_${row}">題目屬性</label>
        <select class="custom-select form-control-border topicType" name="topics[${row}][topic_type]" id="topic_type_${row}" data-row="${row}" required>
          <option value="">請選擇題目屬性</option>
          @foreach($types??[] as $type)
          <option value="{{$type->val}}">{{$type->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group row">
        <label for="subject_${row}">題目</label>
        <input type="text" class="form-control" name="topics[${row}][subject]" id="subject_${row}" placeholder="請輸入題目" required>
      </div>
      <div class="form-group row">
        <label>選項設定</label>
      </div>
      <div class="form-group row content_${row}"></div>
  </div>
  `;
  $('#subjectContent').append(str);
});

$('body').on('click', '.removeSubject', function(){
  var row = $(this).data('row');
  var number = 1;
  $('#car_'+row).remove();

  $('#subjectContent .card-title').each(function(){
    $(this).html(`題目${number}`);
    number++;
  })
})

$('body').on('change', '.topicType', function(){
  var type = $(this).val();
  var row = $(this).data('row');
  var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length);
  var str = '';

  $('.content_'+row).html('');

  switch (type) {
    case 'select':
    case 'checkbox':
    case 'radio':
      str = `
      <div class="col-sm-12" id="item_${itemCount}" style="margin-top: 10px;">
          <div class="input-group">
            <input type="text" class="form-control" name="topics[${row}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
            <div class="input-group-append">
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs multipleMinus" data-row="${row}" data-item="${itemCount}"><i class="fas fa-minus"></i></button>
              </span>
              <span class="input-group-text">
                  <button type="button" class="btn btn-xs multiplePlus" data-row="${row}"><i class="fas fa-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
      `;
      $('.content_'+row).append(str);
      break;
    default:
      $('.content_'+row).html('');
      break;
  }
});

$('body').on('click', '.multiplePlus', function(){
  var row = $(this).data('row');
  var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length);
  var str = `
    <div class="col-sm-12" id="item_${itemCount}" style="margin-top: 10px;">
      <div class="input-group">
        <input type="text" class="form-control" name="topics[${row}][items][${itemCount}][name]" placeholder="請輸入選項名稱" required>
        <div class="input-group-append">
          <span class="input-group-text">
              <button type="button" class="btn btn-xs multipleMinus" data-row="${row}" data-item="${itemCount}"><i class="fas fa-minus"></i></button>
          </span>
          <span class="input-group-text">
              <button type="button" class="btn btn-xs multiplePlus" data-row="${row}"><i class="fas fa-plus"></i></button>
          </span>
        </div>
      </div>
    </div>
  `;
  $('.content_'+row).append(str);
})

$('body').on('click', '.multipleMinus', function(){
  var row = $(this).data('row');
  var item = $(this).data('item');
  var itemCount = parseInt($('.content_'+row+' div[id^="item_"]').length) - 1;
  
  if (itemCount === 0) {
    Swal.fire({
        type: 'warning',
        title: '訊息提示',
        text: "至少保留一組選項設定",
        icon: 'warning',
    })
  } else {
    $('#item_'+item).remove();
  }
})
</script>
@endsection