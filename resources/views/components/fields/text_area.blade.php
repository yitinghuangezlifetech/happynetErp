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
@if($type!='search')
<div class="form-group">
    <label 
        @if($type == 'edit')
        for="edit_{{ $detail->field }}"
        @else
        for="{{ $detail->field }}"
        @endif
    />
    @if($detail->required == 1)<span style="color:red">*</span>@endif 
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif {!! $detail->note !!}
    </label>
    <textarea 
    class="form-control" 
    name="{{ $detail->field }}" 
    @if($type == 'edit')
    id="edit_{{ $detail->field }}"
    @else
    id="{{ $detail->field }}"
    @endif
    style="width: 100%" rows="10"
    {!! $str !!} 
    @if($detail->required == 1){{'required'}}@endif
    >{{$value}}</textarea>
</div>
@else
<div class="form-group">
    <label for="{{ $detail->field }}">
    {{$detail->show_name}}
    </label>
    <input 
    class="form-control" 
    name="{{ $detail->field }}" 
    id="{{ $detail->field }}"
    style="width: 100%" rows="10"
    value="{{$value}}"
    {!! $str !!} 
    />
</div>
@endif