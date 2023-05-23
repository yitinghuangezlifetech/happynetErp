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
    <div id="{{$detail->field}}_area">
        <img src="{{$value}}" style="max-width:100%;height:auto;">
    </div>
    @else
    <div id="{{$detail->field}}_area"></div>
    @endif
    <div class="signBoard" id="{{$detail->field}}_board" style="background-color: #fcfcbf; border-style: dashed solid; max-width:100%;height:auto;"></div>
    <br>
    <button type="button" class="btn bg-gradient-warning btn-sm" onclick="resetSign('{{$detail->field}}_area', '{{$detail->field}}_board')">清除</button>
    <button type="button" class="btn bg-gradient-info btn-sm" onclick="convertToBase64('{{$detail->field}}_area', '{{$detail->field}}_board')">確認</button>
</div>