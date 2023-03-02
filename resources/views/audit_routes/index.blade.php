@extends('layouts.main')

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
          <tbody>
            @foreach($list??[] as $data)
            <tr>
              <td class="text-center">
                <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}"></td>
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
                  @if($user->super_admin == 1 || $user->role->has_audit_route ==1)
                  <button type="button" class="btn bg-gradient-secondary btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i>編輯</button>
                  @elseif($data->status == 0 || $data->status==2)
                  <button type="button" class="btn bg-gradient-secondary btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i>編輯</button>
                  @endif
                @endcan
                @if($data->status == 4)
                <button type="button" class="btn bg-gradient-success btn-sm sendEmail" data-routeid="{{$data->id}}" data-email="{{$data->store->email}}"><i class="fa fa-envelope"></i>&nbsp;寄出稽核報告</button>
                <a href="{{route('audit_routes.downloadReport', $data->id)}}" class="btn bg-gradient-info btn-sm" target="_blank"><i class="fa fa-cloud-download"></i>稽核報告下載</a>
                @endif
                <button type="button" class="btn bg-gradient-warning btn-sm" onclick="location.href='{{route('audit_records.index', $data->id)}}'"><i class="fas fa-search"></i>稽核作業</button>
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
<form id="delete_form" method="POST" style="display: none;"></form>
<form id="emailForm" method="POST" style="display: none;"></form>
<!-- /.row -->
@if($menu->search_component == 1)
  @include('components.search_modal', ['menu'=>$menu, 'filters'=>$filters])
@endif
@endsection

@section('js')
<script type="text/javascript">
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

      $('.sendEmail').click(function(){
        var email = $(this).data('email');
        var id = $(this).data('routeid');

        Swal.fire({
          title: '請確認或輸入商家E-mail',
          input: 'text',
          inputAttributes: {
            required: true
          },
          inputValue: email,
          showCancelButton: true,
          confirmButtonText: '確認',
          cancelButtonText: '取消',
          showLoaderOnConfirm: true,
        }).then((result) => {
          if (result.isConfirmed) {
            $('#emailForm')[0].action = '{{ route($menu->slug.'.sendEmail') }}';
            $('#emailForm').html('');
            $('#emailForm').append('@csrf');
            $('#emailForm').append(`<input type="hidden" name="id" value="${id}">`);
            $('#emailForm').append(`<input type="hidden" name="email" value="${result.value}">`);
            $('#emailForm').submit();
          }
        })


/*
        if ( $('.rowItem:checked').length > 0) {
          $('#emailForm')[0].action = '{{ route($menu->slug.'.sendEmail') }}';
          Swal.fire({
              type: 'warning',
              title: '訊息提示',
              text: "是否要寄出稽核報告？",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: '是的, 寄出資料!',
              cancelButtonText: '取消',
          }).then((result) => {
              if (result.value) {
                if ($('.rowItem').length > 0) {
                  $('#emailForm').html('');
                  $('#emailForm').append('@csrf');
                  $('.rowItem:checked').each(function(){
                    $('#emailForm').append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                  });
                  $('#emailForm').submit();
                }
              }
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: '訊息提示',
            text: '請選擇要寄出稽核報告的資料'
          })
        }
        */
      });

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