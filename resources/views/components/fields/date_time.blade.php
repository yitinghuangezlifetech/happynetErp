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
@endphp

@if($type != 'search')
<div class="form-group">
    <label 
        @if($type == 'edit')
        for="edit_{{ $detail->field }}"
        @else
        for="{{ $detail->field }}"
        @endif
    />
    @if($detail->required == 1 && $type != 'search')<span style="color:red">*</span>@endif 
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif {!! $detail->note !!}
    </label>
    <input 
        type="datetime-local" 
        class="form-control" 
        name="{{ $detail->field }}" 
        @if($type == 'edit')
        id="edit_{{ $detail->field }}"
        @else
        id="{{ $detail->field }}"
        @endif
        value="{{$value}}" 
        {!! $str !!} 
        @if($detail->required == 1 && $type != 'search'){{'required'}}@endif
        />
</div>
@else
<div class="form-group">
    <label for="start_day" />
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif-起
    </label>
    <input 
        type="datetime-local" 
        class="form-control" 
        name="start_day" 
        id="start_day"
        value="{{$value['start_day']}}" 
        {!! $str !!} 
        />
</div>
<div class="form-group">
    <label for="end_day" />
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif-迄
    </label>
    <input 
        type="datetime-local" 
        class="form-control" 
        name="end_day" 
        id="end_day"
        value="{{$value['end_day']}}" 
        {!! $str !!} 
        />
</div>
@endif