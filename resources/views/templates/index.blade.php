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
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="bg-gradient-secondary" style="text-align: center"><input type="checkbox" class="checkAll"></th>
              @if($menu->menuBrowseDetails->count() > 0)
                @foreach($menu->menuBrowseDetails as $detail)
                  @php
                    $json = [];

                    if (!empty($detail->applicable_system)) {
                      $json = json_decode($detail->applicable_system, true);
                    }
                  @endphp
                  @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
                    @if(count($json) > 0)
                      @if (in_array($user->role->systemType->name, $json))
                      <th class="bg-gradient-secondary" style="text-align: center">{{$detail->show_name}}</th>
                      @endif
                    @else
                      <th class="bg-gradient-secondary" style="text-align: center">{{$detail->show_name}}</th>
                    @endif
                  @else
                    @if($detail->show_hidden_field != 1)
                      @if(count($json) > 0)
                        @if (in_array($user->role->systemType->name, $json))
                        <th class="bg-gradient-secondary" style="text-align: center">{{$detail->show_name}}</th>
                        @endif
                      @else
                        <th class="bg-gradient-secondary" style="text-align: center">{{$detail->show_name}}</th>
                      @endif
                    @endif
                  @endif
                @endforeach
              @endif
              <th class="bg-gradient-secondary" style="text-align: center">編輯</th>
            </tr>
          </thead>
          <tbody>
            @foreach($list??[] as $data)
            <tr>
              <td class="text-center" style="vertical-align: middle">
                <input type="checkbox" class="rowItem" name="items[]" value="{{ $data->id }}">
              </td>
              @if($menu->menuBrowseDetails->count() > 0)
                @foreach($menu->menuBrowseDetails as $detail)
                  @php
                    $json = [];

                    if (!empty($detail->applicable_system)) {
                      $json = json_decode($detail->applicable_system, true);
                    }
                  @endphp
                  @if($detail->super_admin_use == 1 && $user->role->super_admin == 1)
                    @if(count($json) > 0)
                      @if (in_array($user->role->systemType->name, $json))
                      <td class="text-center" style="vertical-align: middle">
                        @switch($detail->type)
                          @case('text')
                          @case('email')
                          @case('text_area')
                          @case('date_time')
                          @case('number')
                          @case('date')
                          @case('ckeditor')
                            @php
                              $com = '';
                              $value = '';

                              if ($detail->has_relationship == 1) {
                                $jsonArr = json_decode($detail->relationship, true);
                                if (is_array($jsonArr) && count($jsonArr) > 0) {
                                  $options = app($jsonArr['model'])->where($jsonArr['references_field'], $data->{$detail->foreign_key})->get();
                                  if ($options->count() > 0) {
                                    foreach ($options as $option) {
                                      $value .= $com.$option->{$jsonArr['show_field']};
                                      $com = ',';
                                    }
                                  }
                                }
                              } else {
                                $value = $data->{$detail->field};
                              }
                            @endphp
                            {!! $value !!}
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
                              <img src="{{ $data->{$detail->field} }}"  width="100px" height="100px">
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
                        @endswitch
                      </td>
                      @endif
                    @else
                      <td class="text-center" style="vertical-align: middle">
                        @switch($detail->type)
                          @case('text')
                          @case('email')
                          @case('text_area')
                          @case('date_time')
                          @case('number')
                          @case('date')
                          @case('ckeditor')
                            @php
                              $com = '';
                              $value = '';

                              if ($detail->has_relationship == 1) {
                                $jsonArr = json_decode($detail->relationship, true);
                                if (is_array($jsonArr) && count($jsonArr) > 0) {
                                  $options = app($jsonArr['model'])->where($jsonArr['references_field'], $data->{$detail->foreign_key})->get();
                                  if ($options->count() > 0) {
                                    foreach ($options as $option) {
                                      $value .= $com.$option->{$jsonArr['show_field']};
                                      $com = ',';
                                    }
                                  }
                                }
                              } else {
                                $value = $data->{$detail->field};
                              }
                            @endphp
                            {!! $value !!}
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
                              <img src="{{ $data->{$detail->field} }}"  width="100px" height="100px">
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
                        @endswitch
                      </td>
                    @endif
                  @else
                    @if($detail->show_hidden_field != 1)
                      @if(count($json) > 0)
                        @if (in_array($user->role->systemType->name, $json))
                        <td class="text-center" style="vertical-align: middle">
                          @switch($detail->type)
                            @case('text')
                            @case('time')
                            @case('email')
                            @case('text_area')
                            @case('date_time')
                            @case('number')
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
                              {!! $value !!}
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
                                <img src="{{ $data->{$detail->field} }}" width="100px" height="100px">
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
                          @endswitch
                        </td>
                        @endif
                      @else
                        <td class="text-center" style="vertical-align: middle">
                          @switch($detail->type)
                            @case('text')
                            @case('time')
                            @case('email')
                            @case('text_area')
                            @case('date_time')
                            @case('number')
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
                              {!! $value !!}
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
                                <img src="{{ $data->{$detail->field} }}" width="100px" height="100px">
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
                          @endswitch
                        </td>
                      @endif
                    @endif
                  @endif
                @endforeach
              @endif
              <td style="text-align: center; vertical-align: middle">
                @can('update_'.$menu->slug, app($menu->model))
                  <button type="button" class="btn bg-gradient-secondary btn-sm" onclick="location.href='{{route($menu->slug.'.edit', $data->id)}}'"><i class="fas fa-edit"></i></button>
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
@if($menu->import_data == 1)
  @include('components.import_modal', ['menu'=>$menu])
@endif
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
        $("#searchForm textarea").val('');
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

      if ($('.importBtn').length > 0) {
        $('.importBtn').click(function(){
          $('#importForm').modal('show');
        });
      }
  });
</script> 
@endsection