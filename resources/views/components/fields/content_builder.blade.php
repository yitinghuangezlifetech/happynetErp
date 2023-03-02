@php
if($detail->has_relationship == 1) {
    $decodeData = json_decode($detail->relationship, true);
    if (count($decodeData) > 0) {
        $modules = app($decodeData['model'])->orderBy('sort', 'ASC')->get();
    }
}
    
@endphp
<label>
@if($detail->required == 1 && $type != 'search')<span style="color:red">*</span>@endif 
@if(isset($columns[$detail->field]))
{{$columns[$detail->field]}}
@else
{{$detail->show_name}}
@endif {!! $detail->note !!}
</label>
<div class="row text-center" id="mode_module"></div>
<div class="row text-center">
    @foreach($modules??[] as $module)
    <div class="module_icon col-lg-3 col-md-3" data-id="{{$module->id}}" data-url="{{route('admin.content_builder.getComponents')}}" style="cursor: pointer">
        <div class="card border mt-2 mb-2 p-2 pb-1">
            <div class="mt-3 pw_header">
                <img src="{{$module->module_icon}}" alt="{{$module->module_name}}" {{app('BladeService')->imageResize($module->module_icon)}}>
                <h6>{{$module->module_name}}</h6>
            </div>
        </div>
    </div>
    @endforeach
</div>