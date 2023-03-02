<div class="form-group">
    <label for="show_{{$detail->field}}">
    @if(isset($columns[$detail->field]))
    {{$columns[$detail->field]}}
    @else
    {{$detail->show_name}}
    @endif
    </label>
    @if(!empty($value))
    <img src="{{$value}}" {{app('BladeService')->imageResize($value)}}>
    @endif
</div>