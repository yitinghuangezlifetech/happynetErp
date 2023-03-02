@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="/plugins/sortable/st/theme.css">  
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      @include('components.top_banner_bar', ['menu'=>$menu])
      <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox" class="checkAll"></th>
                @if($menu->menuBrowseDetails->count() > 0)
                  @foreach($menu->menuBrowseDetails as $detail)
                  <th class="bg-gradient-secondary" style="text-align: center">{{$detail->show_name}}</th>
                  @endforeach
                @endif
                <th class="bg-gradient-secondary" style="text-align: center">操作</th>
              </tr>
            </thead>
            <tbody id="items">
              @foreach($list??[] as $data)
              <tr>
                <td class="text-center">
                  <input type="hidden" name="sort[]" value="{{$data->id}}">
                  <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}">
                </td>
                @if($menu->menuBrowseDetails->count() > 0)
                  @foreach($menu->menuBrowseDetails as $detail)
                  <td class="text-center" style="vertical-align: middle">
                    @switch($detail->type)
                      @case('text')
                      @case('email')
                      @case('text_area')
                      @case('date_time')
                      @case('date')
                        @php
                          $com = '';
                          $value = '';

                          if ($detail->has_relationship == 1) {
                            $json = json_decode($detail->relationship, true);
                            if (is_array($json) && count($json) > 0) {
                              $options = app($json['model'])->where($json['references_field'], $data->{$detail->foreign_key})->get();
                              if ($options->count() > 0) {
                                foreach ($options as $option) {
                                  $value .= $com.$option->{$json['show_field']};
                                  $com = ',';
                                }
                              }
                            }
                          } else {
                            $value = $data->{$detail->field};
                          }
                        @endphp
                        {{ $value }}
                        @break 
                      @case('multiple_input')
                        @php
                          $str = '';
                          $com = '';

                          if ($detail->has_relationship == 1) {
                            $json = json_decode($detail->relationship, true);
                            if (is_array($json) && count($json) > 0) {
                              $options = app($json['model'])->where($json['references_field'], $data->{$detail->foreign_key})->get();

                              if ($options->count() > 0) {
                                foreach ($options as $option) {
                                  $str .= $com.$option->{$json['show_field']};
                                  $com = ',';
                                }
                              }
                            }
                          }
                        @endphp
                        {!! $str !!}
                        @break  
                      @case('radio')
                        @php
                          $value = '';
                          if (!empty($detail->options)) {
                            $options = json_decode($detail->options, true);

                            if(is_array($options) && count($options) > 0) {
                              foreach ($options as $key => $option) {
                                if ($data->{$detail->field} == $option['value']) {
                                  $value = $option['text'];
                                }
                              }
                            }
                          } else if ($detail->has_relationship == 1) {
                            if (!empty($detail->relationship)) {
                              $decodeData = json_decode($detail->relationship, true);
                              if (count($decodeData) > 0) {
                                  $options = app($decodeData['model'])->get();

                                  foreach ($options as $option) {
                                    if ($data->{$detail->field} == $option->id) {
                                      $value = $option->{$decodeData['show_field']};
                                    }
                                  }
                              }
                            }
                          }
                        @endphp
                        {!! $value !!}
                        @break
                      @case('image')
                          @if(!empty($data->{$detail->field}))
                          <img src="{{ $data->{$detail->field} }}" width="150px" height="150px">
                          @endif
                        @break
                      @case('select')
                        @php
                          $name = '';
                          if($detail->has_relationship == 1) {
                            $decodeData = json_decode($detail->relationship, true);
                            if (count($decodeData) > 0) {
                              $relationData = app($decodeData['model'])->find($data->{$detail->field});
                              if ($relationData) {
                                $name = $relationData->{$decodeData['show_field']};
                              }
                            }
                          } else {
                            if (!empty($detail->options)) {
                              $decodeData = json_decode($detail->options, true);
                              if (count($decodeData) > 0) {
                                foreach ($decodeData as $option) {
                                  if ($option['value'] == $data->{$detail->field}) {
                                    $name = $option['text'];
                                  }
                                }
                              }
                            }
                          }
                        @endphp
                        {{ $name }}
                      @break
                    @endswitch
                  </td>
                  @endforeach
                @endif
                <td style="text-align: center">
                  @can('update_'.$menu->slug, app($menu->model))
                    <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i>編輯</button>
                  @endcan
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        {{$list->links('pagination.adminLTE')}}
      </div>
    </div>
    <!-- /.card -->
  </div>
</div>
<form id="delete_form" method="POST" style="display: none;">
  
</form>
<!-- /.row -->
@if($menu->search_component == 1)
  @include('components.search_modal', ['menu'=>$menu, 'filters'=>$filters])
@endif
@endsection

@section('js')
<script src="/plugins/sortable/Sortable.js"></script>
<script src="/plugins/sortable/st/prettify/prettify.js"></script>
<script src="/plugins/sortable/st/prettify/run_prettify.js"></script>
<script type="text/javascript">

var items = document.getElementById('items');
new Sortable(items, {
    animation: 150,
    ghostClass: 'blue-background-class',
    onEnd: function (/**Event*/evt) {
      var url = '{{route('sortables.sort', 'main_attributes')}}';

      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url,
        cache: false,
        async: false,
        type:'POST',
        data: {
          "sort":$("input[name='sort[]']").serializeArray()
        }
      })
    },
});

$(document).ready(function(){
    $(".clearSearch").click(function(){
      $("#exampleModal input").val('');
      $("#exampleModal select").val('');
    })

    $(".checkAll").on('click', function(){
      if ( $(this).prop('checked') ) {
        $(".rowItem").prop('checked', true);
      } else {
        $(".rowItem").prop('checked', false);
      }
    })

    $(".deleteBtn").click(function(){
      if ( $('.rowItem:checked').length > 0) {
        $('#delete_form')[0].action = '{{ route($menu->slug.'.multipleDestroy') }}';
        Swal.fire({
            type: 'warning',
            title: '訊息提示',
            text: "是否要刪除資料？",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '是的, 刪除資料!',
            cancelButtonText: '取消',
        }).then((result) => {
            if (result.value) {
              if ($('.rowItem').length > 0) {
                $('#delete_form').html('');
                $('#delete_form').append('@csrf');
                $('.rowItem:checked').each(function(){
                  $('#delete_form').append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                });
                $('#delete_form').submit();
              }
            }
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: '訊息提示',
          text: '請選擇要刪除的資料'
        })
      }
    })  
});
</script> 
@endsection