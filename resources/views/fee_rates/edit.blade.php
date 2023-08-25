@extends('layouts.main')

@section('content')
    <form enctype="multipart/form-data" method="POST" action="{{ route($menu->slug . '.update', $data->id) }}">
        @csrf
        @method('put')
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">編輯{{ $menu->name }}資料</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool addAreaBtn"><i class="fas fa-plus"></i> 增加費率模組</button>
                </div>
            </div>
            <div class="card-body">
                @php
                    $jsArr = [];
                    $assignJsArr = [];
                    $fieldNameArr = [];
                    if ($menu->seo_enable == 1) {
                        array_push($jsArr, 'image.js');
                    }
                    $i = 1;
                @endphp
                @if ($menu->menuEditDetails->count() > 0)
                    @foreach ($menu->menuEditDetails as $detail)
                        @if ($loop->first)
                            <div class="row">
                        @endif
                        @php
                            $json = [];
                            
                            if ($detail->has_js == 1) {
                                if (!in_array($detail->type . '.js', $jsArr)) {
                                    array_push($jsArr, $detail->type . '.js');
                                }
                                if ($detail->type == 'ckeditor') {
                                    if (!in_array('edit_' . $detail->field, $fieldNameArr)) {
                                        array_push($fieldNameArr, $detail->field);
                                    }
                                }
                            }
                            if ($detail->use_component == 1) {
                                if (!in_array($detail->component_name . '.js', $jsArr)) {
                                    array_push($jsArr, $detail->component_name . '.js');
                                }
                            }
                            if (!empty($detail->assign_js)) {
                                array_push($assignJsArr, $detail->assign_js);
                            }
                            if (!empty($detail->applicable_system)) {
                                $json = json_decode($detail->applicable_system, true);
                            }
                            $halfRows = '';
                        @endphp
                        @if ($detail->super_admin_use == 1 && $user->role->super_admin == 1)
                            <div class="col-sm-6">
                                @if (count($json) > 0)
                                    @if (in_array($user->role->systemType->name, $json))
                                        @include('components.fields.' . $detail->type, [
                                            'type' => 'edit',
                                            'detail' => $detail,
                                            'value' => $data->{$detail->field},
                                        ])
                                    @endif
                                @else
                                    @include('components.fields.' . $detail->type, [
                                        'type' => 'edit',
                                        'detail' => $detail,
                                        'value' => $data->{$detail->field},
                                    ])
                                @endif
                            </div>
                        @else
                            @if ($detail->show_hidden_field == 1)
                                @php
                                    $value = null;
                                    
                                    if ($detail->field == 'user_id') {
                                        $value = $user->id;
                                    } elseif ($detail->field == 'system_type_id') {
                                        $value = $user->role->system_type_id;
                                    } else {
                                        $value = $data->{$detail->field};
                                    }
                                    $i++;
                                @endphp
                                @include('components.fields.hidden', [
                                    'type' => 'edit',
                                    'detail' => $detail,
                                    'value' => $value,
                                ])
                            @else
                                @if ($detail->use_component == 1)
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('components.' . $detail->component_name, [
                        'type' => 'edit',
                        'detail' => $detail,
                        'model' => $menu->model,
                        'data' => $data,
                    ])
                </div>
            </div>
            <div class="row">
                @php $i++; @endphp
            @elseif($detail->type == 'multiple_input')
                @php
                    $options = [];
                    
                    if ($detail->has_relationship == 1) {
                        $jsonArr = json_decode($detail->relationship, true);
                        if (is_array($jsonArr) && count($jsonArr) > 0) {
                            $options = app($jsonArr['model'])
                                ->where($jsonArr['references_field'], $data->{$detail->foreign_key})
                                ->get();
                        }
                    }
                @endphp
                @if (count($json) > 0)
                    @if (in_array($user->role->systemType->name, $json))
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="{{ $detail->field }}">{{ $detail->show_name }}</label>
                    <div id="{{ $detail->field }}">
                        @if ($options->count() > 0)
                            @foreach ($options ?? [] as $key => $option)
                                @include('components.fields.' . $detail->type, [
                                    'type' => 'edit',
                                    'detail' => $detail,
                                    'jsonData' => $json,
                                    'index' => $key,
                                    'value' => $option->{$json['show_field']},
                                ])
                            @endforeach
                        @else
                            @include('components.fields.' . $detail->type, [
                                'type' => 'edit',
                                'detail' => $detail,
                                'jsonData' => [],
                                'index' => 0,
                                'value' => null,
                            ])
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                @php $i++; @endphp
                @endif
            @else
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="{{ $detail->field }}">{{ $detail->show_name }}</label>
                    <div id="{{ $detail->field }}">
                        @if ($options->count() > 0)
                            @foreach ($options ?? [] as $key => $option)
                                @include('components.fields.' . $detail->type, [
                                    'type' => 'edit',
                                    'detail' => $detail,
                                    'jsonData' => $json,
                                    'index' => $key,
                                    'value' => $option->{$json['show_field']},
                                ])
                            @endforeach
                        @else
                            @include('components.fields.' . $detail->type, [
                                'type' => 'edit',
                                'detail' => $detail,
                                'jsonData' => [],
                                'index' => 0,
                                'value' => null,
                            ])
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                @php $i++; @endphp
                @endif
            @elseif($detail->type == 'multiple_select' || $detail->type == 'ckeditor' || $detail->type == 'multiple_img')
                @if (count($json) > 0)
                    @if (in_array($user->role->systemType->name, $json))
            </div><br>
            <div class="row">
                <div class="col-sm-12">
                    @include('components.fields.' . $detail->type, [
                        'type' => 'edit',
                        'detail' => $detail,
                        'model' => $menu->model,
                        'data' => $data,
                        'value' => $data->{$detail->field},
                    ])
                </div>
            </div>
            <div class="row">
                @php $i++; @endphp
                @endif
            @else
            </div><br>
            <div class="row">
                <div class="col-sm-12">
                    @include('components.fields.' . $detail->type, [
                        'type' => 'edit',
                        'detail' => $detail,
                        'model' => $menu->model,
                        'data' => $data,
                        'value' => $data->{$detail->field},
                    ])
                </div>
            </div>
            <div class="row">
                @php $i++; @endphp
                @endif
            @else
                @if ($detail->field == 'empty')
                    <div class="col-sm-6"></div>
                @else
                    @if (count($json) > 0)
                        @if (in_array($user->role->systemType->name, $json))
                            <div class="col-sm-6">
                                @include('components.fields.' . $detail->type, [
                                    'type' => 'edit',
                                    'detail' => $detail,
                                    'id' => $data->id,
                                    'model' => $menu->model,
                                    'value' => $data->{$detail->field},
                                ])
                            </div>
                        @endif
                    @else
                        <div class="col-sm-6">
                            @include('components.fields.' . $detail->type, [
                                'type' => 'edit',
                                'detail' => $detail,
                                'id' => $data->id,
                                'model' => $menu->model,
                                'value' => $data->{$detail->field},
                            ])
                        </div>
                    @endif
                @endif
                @endif
                @endif
                @endif
                @if ($i % 2 == 0)
            </div>
            <div class="row">
                @endif
                @php $i++; @endphp
                @endforeach
                @endif
            </div>
        </div>
        </div>
        <div id="rateArea">
            @foreach ($data->logs ?? [] as $k => $log)
                @php
                    $number = $k + 1;
                @endphp
                <div class="card card-secondary" id="card_{{ $k }}">
                    <div class="card-header">
                        <h3 class="card-title">通話費率-{{ $number }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool removeAreaBtn" data-rows="{{ $k }}"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="call_target_id_{{ $k }}"><span
                                            style="color:red">*</span>撥打對象</label>
                                    <select class="form-control callTarget"
                                        name="rates[{{ $k }}][call_target_id]"
                                        id="call_target_id_{{ $k }}" required>
                                        <option value="">請選擇</option>
                                        @foreach ($callTargets ?? [] as $target)
                                            <option value="{{ $target->id }}"
                                                @if ($log->call_target_id == $target->id) {{ 'selected' }} @endif>
                                                {{ $target->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="call_rate_{{ $k }}"><span style="color:red">*</span>通話費率</label>
                                    <input type="text" class="form-control callRate"
                                        name="rates[{{ $k }}][call_rate]" id="call_rate_{{ $k }}"
                                        value="{{ $log->call_rate }}" data-rows="{{ $k }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discount_{{ $k }}">折讓</label>
                                    <input type="text" class="form-control discout"
                                        name="rates[{{ $k }}][discount]" id="discount_{{ $k }}"
                                        value="{{ $log->discount }}" data-rows="{{ $k }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="discount_after_rate_{{ $k }}">折後費率</label>
                                    <input type="text" class="form-control"
                                        name="rates[{{ $k }}][discount_after_rate]"
                                        id="discount_after_rate_{{ $k }}"
                                        value="{{ $log->discount_after_rate }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="charge_unit_{{ $k }}"><span
                                            style="color:red">*</span>計費單位</label>
                                    <select class="form-control" name="rates[{{ $k }}][charge_unit]"
                                        id="charge_unit_{{ $k }}" required>
                                        <option value="">請選擇</option>
                                        <option value="1"
                                            @if ($log->charge_unit == 1) {{ 'selected' }} @endif>秒鐘</option>
                                        <option value="2"
                                            @if ($log->charge_unit == 2) {{ 'selected' }} @endif>分鐘</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="parameter_{{ $k }}"><span style="color:red">*</span>參數</label>
                                    <input type="text" class="form-control"
                                        name="rates[{{ $k }}][parameter]" id="parameter_{{ $k }}"
                                        value="{{ $log->parameter }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card" id="footerArea">
            <div class="card-footer text-center">
                <button type="submit" class="btn bg-gradient-dark">儲存</button>
                <button type="button" class="btn bg-gradient-secondary"
                    onclick="javascript:location.href='{{ route($slug . '.index') }}'">回上一頁</button>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        let targets = [];

        const init = () => {
            @foreach ($data->logs ?? [] as $log)
                targets.push('{{ $log->call_target_id }}');
            @endforeach
        }
        init();

        $('.addAreaBtn').click(function() {
            let rows = parseInt($('#rateArea .card').length);
            let len = 0;

            console.log(targets);

            do {
                rows++;
                len = parseInt($(`#card_${rows}`).length);
            } while (len > 0);

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
            @foreach ($callTargets ?? [] as $target)
                if (targets.indexOf('{{ $target->id }}') < 0) {
                    str += `<option value="{{ $target->id }}">{{ $target->type_name }}</option>`;
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
						<label for="discount_${rows}">折讓</label>
						<input type="text" class="form-control discout" name="rates[${rows}][discount]" id="discount_${rows}" data-rows="${rows}">
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

            $('#rateArea').prepend(str);

            resetCardName();
        });

        $('body').on('click', '.removeAreaBtn', function() {
            const rows = $(this).data('rows');
            $(`#card_${rows}`).remove();

            resetCardName();
        })

        const resetCardName = () => {
            let rows = 1;

            $('#rateArea .card').each(function() {
                $(this).find('.card-title').text(`通話費率-${rows}`);
                rows++;
            })
        }

        $('body').on('change', '.callTarget', function() {
            if ($(this).val() != '') {
                targets.push($(this).val());
            } else {
                targets = [];

                $('body .callTarget').each(function() {
                    targets.push($(this).val());
                })
            }
        })

        $('body').on('keyup', '.callRate', function() {
            const rows = $(this).data('rows');
            const rate = $(this).val();
            const discount = $(`#discount_${rows}`).val();
            let afterRate = 0;

            if (discount > 0) {
                afterRate = rate - (rate * (discount / 100));

                $(`#discount_after_rate_${rows}`).val(afterRate.toFixed(4));
            }
        })

        $('body').on('keyup', '.discout', function() {
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
