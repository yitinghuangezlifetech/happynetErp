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
<div id="{{$detail->field}}">
    <div class="input-group mb-12" id="{{$detail->field}}_{{$index}}">
        <input type="text" class="form-control" name="{{$detail->field}}[]" id="" value="{{ $value }}">
        <div class="input-group-append">
            <span class="input-group-text">
                <button type="button" class="btn btn-xs multipleMinus" data-field="{{$detail->field}}" data-key="{{$index}}"><i class="fas fa-minus"></i></button>
            </span>
            <span class="input-group-text">
                <button type="button" class="btn btn-xs multiplePlus" data-field="{{$detail->field}}" data-key="{{$index}}"><i class="fas fa-plus"></i></button>
            </span>
        </div>
    </div>
</div>