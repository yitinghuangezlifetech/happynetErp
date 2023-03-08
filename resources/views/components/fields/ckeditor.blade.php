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
    <textarea 
    class="form-control ckeditor" 
    name="{{ $detail->field }}" 
    @if($type == 'edit')
    id="edit_{{ $detail->field }}"
    @else
    id="{{ $detail->field }}"
    @endif
    style="width: 100%" rows="10"
    {!! $str !!} 
    @if($detail->required == 1 && $type != 'search'){{'required'}}@endif
    >
    {{$value}}
    </textarea>
</div>