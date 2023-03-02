@php
    $str = '';
    $com = '';
    $select = [];

    if (!empty($detail->attributes)) {
        $json = json_decode($detail->attributes, true);

        if (is_array($json) && count($json) > 0) {
            foreach ($json as $item) {
                foreach ($item as $attribute=>$val) {
                    $str .= $com.$attribute.'="'.$val.'"';
                    $com = ' ';
                }
            }
        }
    }
@endphp

<div class="form-group">
    <label/>
    @if($detail->required == 1)<span style="color:red">*</span>@endif 
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif {!! $detail->note !!}
    </label>
    @if(!empty($detail->options))
        @php
            $options = json_decode($detail->options, true);
        @endphp
        @foreach($options??[] as $key=>$option)
        <div class="custom-control custom-checkbox">
            <input
                class="custom-control-input" 
                type="checkbox" 
                id="checkbox_{{$key}}_{{$detail->field}}"
                name="{{$detail->field}}[]" 
                data-field="{{$detail->field}}"                                                                                                  }}" 
                value="{{$option['value']}}" @if($option['value'] == $value){{'cehcked'}}@endif>
            <label for="checkbox_{{$key}}_{{$detail->field}}" class="custom-control-label">{{$option['text']}}</label>
        </div>
        @endforeach
    @else
        @if($detail->has_relationship == 1)
            @php
                $decodeData = json_decode($detail->relationship, true);
            @endphp
            @if(count($decodeData) > 0)
                @php
                    $options = app($decodeData['model'])->get();

                    if(!empty($data) && $data->{$detail->relationship_method}->count() > 0) {
                        foreach ($data->{$detail->relationship_method} as $val) {
                            $select[$val->{$detail->relationship_foreignkey}] = 1;
                        }
                    }
                @endphp
                @foreach($options??[] as $key=>$option)
                    @php
                        $checked = '';
                        if (isset($select[$option->id])) {
                            $checked = 'checked';
                        } else if(!empty($value)) {
                            if ($option->id == $value) {
                                $checked = 'checked';
                            }
                        }
                    @endphp
                <div class="custom-control custom-checkbox">
                    <input
                        class="custom-control-input" 
                        type="checkbox" 
                        id="checkbox_{{$key}}_{{$detail->field}}"
                        name="{{$detail->field}}[]" 
                        data-field="{{$detail->field}}" 
                        {!! $str !!}
                        value="{{$option->id}}" {{$checked}}>
                    <label for="checkbox_{{$key}}_{{$detail->field}}" class="custom-control-label">{{$option->name}}</label>
                </div>
                @endforeach
            @endif
        @endif
    @endif
</div>