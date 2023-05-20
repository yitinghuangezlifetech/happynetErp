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
    @endif
    {!! $detail->note !!}
    </label>
    @if(!empty($value))
    <input 
        type="file" 
        class="form-control dropify" 
        name="{{ $detail->field }}" 
        data-id="{{$id}}"
        data-model="{{$model}}"
        data-field="{{$detail->field}}"
        data-default-file="{{$value}}"
        data-show-remove="true"
        /><br>
    @endif
    <div class="signBoard" style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;"></div>
</div>