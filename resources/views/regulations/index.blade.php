@extends('layouts.main')
@section('css')
<link href="/admins/plugins/bootstrap-toggle/css/bootstrap-toggle.css" rel="stylesheet">
@endsection

@section('content')
@if($menu->search_component == 1)
  @include('components.search', ['menu'=>$menu, 'filters'=>$filters])
@endif
<div class="row">
  <div class="col-md-12">
    <div class="card">
      @include('components.top_banner_bar', ['menu'=>$menu])
      <div class="card-body table-responsive p-0">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox" class="checkAll"></th>
              <th class="bg-gradient-secondary" style="text-align: center">操作</th>
              @if($menu->menuBrowseDetails->count() > 0)
                @foreach($menu->menuBrowseDetails as $detail)
                <th class="bg-gradient-secondary" style="text-align: center">
                  @if(isset($columns[$detail->field]))
                    {{$columns[$detail->field]}}
                  @else
                    {{$detail->show_name}}
                  @endif
                </th>
                @endforeach
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach($list??[] as $data)
            <tr>
              <td class="text-center" style="vertical-align: middle"><input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}"></td>
              <td style="text-align: center; vertical-align: middle">
                @can('update_'.$menu->slug, app($menu->model))
                  <button type="button" class="btn bg-gradient-dark btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i></button>
                @endcan
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
                    @case('ckeditor')
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
                      @if($detail->use_edit_link == 1)
                        <a href="{{route($menu->slug.'.edit', $data->id)}}">{!! $value !!}</a>
                      @else
                        {!! $value !!}
                      @endif
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
                    @case('multiple_select')
                      @php
                        $str = '';
                        $com = '';

                        if ($detail->has_relationship == 1) {

                          if ($data->{$detail->relationship_method}->count() > 0) {
                            $json = json_decode($detail->relationship, true);

                            foreach ($data->{$detail->relationship_method} as $log) {
                              
                              if (is_array($json) && count($json) > 0) {
                                $option = app($json['model'])->where($json['references_field'], $log->{$detail->relationship_foreignkey})->first();
                                if ($option) {
                                  $str .= $com.$option->{$json['show_field']};
                                  $com = ',';
                                }
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
                        $on = '';
                        $off = '';
                        if (!empty($detail->options)) {
                          $options = json_decode($detail->options, true);

                          if(is_array($options) && count($options) > 0) {
                            foreach ($options as $key => $option) {
                              if ($option['value'] == 1) {
                                $on = 'data-on='.$option['text'];
                              } else {
                                $off = 'data-off='.$option['text'];
                              }
                            }
                            foreach ($options as $key => $option) {
                              if ($data->{$detail->field} == $option['value']) {
                                if ($data->{$detail->field} == 1) {
                                  $value = '
                                    <input type="checkbox" class="switchBtn '.$detail->field.'" checkeddata-toggle="toggle" '.$on.' '.$off.' data-id="'.$data->id.'" data-field="'.$detail->field.'" data-model="'.$menu->model.'" checked>
                                  ';
                                } else {
                                  $value = '
                                    <input type="checkbox" class="switchBtn '.$detail->field.'" checkeddata-toggle="toggle" '.$on.' '.$off.' data-id="'.$data->id.'" data-field="'.$detail->field.'" data-model="'.$menu->model.'">
                                  ';
                                }
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
                        <img src="{{ $data->{$detail->field} }}" {{app('BladeService')->imageResize($data->{$detail->field})}}>
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
                      {!! $name !!}
                      @break
                    @case('radio')
                      @php
                        $value = '';
                        $on = '';
                        $off = '';
                        if ($detail->field == 'home_page_status') {
                          $startDay = strtotime($data->start_day);
                          $endDay = strtotime($data->end_day);
                          $now = strtotime(now());

                          if ($data->status ==  1 && $now >= $startDay && $now <= $endDay) {
                            $value = '<span style="color: green">顥示</span>';
                          } else {
                            $value = '<span style="color: black">未顥示</span>';
                          }
                        } else if (!empty($detail->options)) {
                          $options = json_decode($detail->options, true);

                          if(is_array($options) && count($options) > 0) {
                            foreach ($options as $key => $option) {
                              if ($option['value'] == 1) {
                                $on = 'data-on='.$option['text'];
                              } else {
                                $off = 'data-off='.$option['text'];
                              }
                            }
                            foreach ($options as $key => $option) {
                              if ($data->{$detail->field} == $option['value']) {
                                if ($data->{$detail->field} == 1) {
                                  $value = '
                                    <input type="checkbox" class="switchBtn '.$detail->field.'" checkeddata-toggle="toggle" '.$on.' '.$off.' data-id="'.$data->id.'" data-field="'.$detail->field.'" data-model="'.$menu->model.'" checked>
                                  ';
                                } else {
                                  $value = '
                                    <input type="checkbox" class="switchBtn '.$detail->field.'" checkeddata-toggle="toggle" '.$on.' '.$off.' data-id="'.$data->id.'" data-field="'.$detail->field.'" data-model="'.$menu->model.'">
                                  ';
                                }
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
                  @endswitch
                </td>
                @endforeach
              @endif
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
<div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form method="POSt" action="{{route($menu->import_data_route)}}" enctype="multipart/form-data">
            @csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{$menu->menu_name}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="regulation_version_id">版本</label>
								<select class="form-control" name="regulation_version_id" id="regulation_version_id">
                  <option value="">請選擇</option>
                  @foreach($versions??[] as $version)
                  <option value="{{$version->id}}">{{$version->name}}</option>
                  @endforeach
                </select>
							</div>
							<div class="form-group">
								<label for="file">Excel檔案上傳</label>
								<input type="file" class="form-control" name="file" value="" id="file" placeholder="請上傳檔案" accept=".xlsx,.xls">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn bg-gradient-secondary btn-sm" data-dismiss="modal">關閉</button>
					<button type="submit" class="btn bg-gradient-secondary btn-sm">上傳檔案</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- /.row -->
@endsection

@section('js')
<script src="/admins/plugins/bootstrap-toggle/js/bootstrap-toggle.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $('.switchBtn').bootstrapToggle({
        onstyle: 'success',
        offstyle: 'secondary'
      }).on('change', function(){
        let status = 2;
        let model = $(this).data('model');
        let field = $(this).data('field');
        let id = $(this).data('id');

        if ($(this).prop('checked')) {
          status = 1;
        }

        $.ajax({
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              method: 'post',
              url: '{{ route('status.changeStatus') }}',
              data: {
                id: id,
                model: model,
                status: status,
                field: field,
                slug: '{{$slug}}'
              }
        })
      });
    
      $(".clearSearch").click(function(){
        $("#searchForm input").val('');
        $("#searchForm select").val('');
        $("#searchForm").submit();
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
              cancelButtonText: '取消',
              confirmButtonText: '是的, 刪除資料!',
              reverseButtons: true
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

      if ($('.importBtn').length > 0) {
        $('.importBtn').click(function(){
          $('#importForm').modal('show');
        });
      }
  });
</script> 
@endsection