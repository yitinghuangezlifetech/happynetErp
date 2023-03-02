@php
$items = '';
$str = '';
$com = '';
$select = [];

if($detail->has_relationship == 1) {
    $decodeData = json_decode($detail->relationship, true);
    if (count($decodeData) > 0) {
        if ($user->role->super_admin ==1 ) {
            $options = app($decodeData['model'])->get();
        } else {
            if ($decodeData['model'] == 'App\Models\HomeStay') {
                $options = app($decodeData['model'])->where('id', $user->home_stay_id)->get();
            } else {
                $options = app($decodeData['model'])->where('home_stay_id', $user->home_stay_id)->get();
            }
        }

        if(!empty($data) && $data->{$detail->relationship_method}->count() > 0) {
            foreach ($data->{$detail->relationship_method} as $val) {
                $select[$val->{$detail->relationship_foreignkey}] = 1;
            }
        }

        foreach ($options as $option) {
            $checked = '';
            if (isset($select[$option->id])) {
                $checked = 'selected';
            } else if(!empty($value)) {
                if ($option->id == $value) {
                    $checked = 'selected';
                }
            }

            $items .= '<option value="'.$option->id.'" '.$checked.'>'.$option->{$decodeData['show_field']}.'</option>';
        }
    }
    
} else {
    if (!empty($detail->options)) {
        $options = json_decode($detail->options, true);

        if(!empty($data) && $data->{$detail->relationship_method}->count() > 0) {
            foreach ($data->{$detail->relationship_method} as $val) {
                $select[$val->{$detail->relationship_foreignkey}] = 1;
            }
        }

        foreach ($options as $option) {
            $checked = '';
            if (isset($select[$option->id])) {
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
@if($type!='search')
<div class="form-group">
    <label 
        @if($type == 'edit')
        for="edit_{{ $detail->field_id }}"
        @else
        for="{{ $detail->field_id }}"
        @endif
    />
    @if($detail->required == 1)<span style="color:red">*</span>@endif 
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
        id="edit_{{ $detail->field_id }}"
        @else
        id="{{ $detail->field_id }}"
        @endif
        multiple='multiple'
        {!! $str !!}
        @if($detail->required == 1){{'required'}}@endif
    />
        <option value="">請選擇</option>
        {!! $items !!}
      </select>
</div>
@else
<div class="form-group">
    <label for="{{ $detail->field_id }}">
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif
    </label>
    <select 
        class="form-control" 
        name="{{$detail->field_id}}" 
        id="{{ $detail->field_id }}"
        {!! $str !!}
    />
        <option value="">請選擇</option>
        {!! $items !!}
      </select>
</div>
@endif