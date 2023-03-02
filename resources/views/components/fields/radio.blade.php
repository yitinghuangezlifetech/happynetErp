@php
    $str = '';
    $com = '';
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

    $id = $detail->field;
    if ($type == 'edit') {
        $id = 'edit_'.$detail->field;
    }
@endphp

@if($type != 'search')
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
        <div class="form-check">
            <input
                class="form-check-input" 
                type="radio" 
                id="{{$id}}_{{$key}}" 
                name="{{$detail->field}}" 
                data-field="{{$detail->field}}"
                {!! $str !!}
                value="{{$option['value']}}" @if(empty($value)) @if($option['default']==1){{'checked'}}@endif @else  @if($option['value'] == $value){{'checked'}}@endif @endif>
            <label for="{{$id}}_{{$key}}" class="form-check-label">{{$option['text']}}</label>
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
                @endphp
                @foreach($options??[] as $key=>$option)
                <div class="form-check">
                    <input
                        class="form-check-input" 
                        type="radio" 
                        id="{{$id}}_{{$key}}" 
                        name="{{$detail->field}}" 
                        data-field="{{$detail->field}}" 
                        {!! $str !!}
                        value="{{$option['value']}}" @if($option['value'] == $value){{'cehcked'}}@endif>
                    <label for="{{$id}}_{{$key}}" class="form-check-label">{{$option['text']}}</label>
                </div>
                @endforeach
            @endif
        @endif
    @endif
</div>
@else
<div class="form-group">
    <label>
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif
    {!! $detail->note !!}
    </label>
    @if(!empty($detail->options))
        @php
            $options = json_decode($detail->options, true);
        @endphp
        <select 
            class="form-control" 
            name="{{$detail->field}}" 
            id="{{ $detail->field }}"
            {!! $str !!} 
        />
        <option value="">請選擇</option>
        @foreach($options??[] as $key=>$option)
        <option value="{{$option['value']}}" @if($option['value'] == $value){{'selected'}}@endif>{{$option['text']}}</option>
        @endforeach
        </select>
    @else
        @if($detail->has_relationship == 1)
            @php
                $decodeData = json_decode($detail->relationship, true);
            @endphp
            @if(count($decodeData) > 0)
                @php
                    $options = app($decodeData['model'])->get();
                @endphp
                <select 
                    class="form-control" 
                    name="{{$detail->field}}" 
                    id="{{ $detail->field }}"
                    {!! $str !!} 
                />
                <option value="">請選擇</option>
                @foreach($options??[] as $key=>$option)
                <option value="{{$option['value']}}" @if($option['value'] == $value){{'selected'}}@endif>{{$option['text']}}</option>
                @endforeach
                </select>
            @endif
        @endif
    @endif
</div>
@endif