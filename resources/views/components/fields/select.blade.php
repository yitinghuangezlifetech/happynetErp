@php
$items = '';
$str = '';
$com = '';
if($detail->has_relationship == 1) {
    $decodeData = json_decode($detail->relationship, true);
    if (count($decodeData) > 0) {
        if (isset($decodeData['type_code']))
        {
            $options = collect([]);
            $main = app($decodeData['model'])->where('type_code', $decodeData['type_code'])->first();

            if ($main)
            {
                $options = $main->getChilds;
            }
        }
        else
        {
            if (isset($decodeData['parent_id']))
            {
                if ($decodeData['parent_id']=='')
                {
                    $options = app($decodeData['model'])->whereNull('parent_id')->get();
                }
            }
            else
            {
                $options = app($decodeData['model'])->get();
            }
        }
        
        foreach ($options??[] as $option) {
            $checked = '';
            if ($option->id == $value) {
                $checked = 'selected';
            }

            $items .= '<option value="'.$option->id.'" '.$checked.'>'.$option->{$decodeData['show_field']}.'</option>';
        }
    }
} else {
    if (!empty($detail->options)) {
        $options = json_decode($detail->options, true);

        foreach ($options as $option) {
            $checked = '';
            if ($option['value'] == $value) {
                $checked = 'selected';
            }

            $items .= '<option value="'.$option['value'].'" '.$checked.'>'.$option['text'].'</option>';
        }
    }
}
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
    <select 
        class="form-control" 
        name="{{$detail->field}}" 
        @if($type == 'edit')
        id="edit_{{ $detail->field }}"
        @else
        id="{{ $detail->field }}"
        @endif
        {!! $str !!}
        @if($detail->required == 1 && $type != 'search'){{'required'}}@endif
    />
        <option value="">請選擇</option>
        {!! $items !!}
      </select>
</div>