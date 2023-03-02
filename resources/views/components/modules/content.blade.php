@php
    $i = 1;
@endphp
@if($module->fields->count() > 0)
    @if($module->module_no == 'M010')
    <div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
        <div class="card-header border-0">
            <button type="button" class="btn bg-gradient-secondary builderAddImg" data-moduleid="{{$module->id}} data-id="buildContentCard_{{$row}}" data-rows="{{$row}}" style="float: left;">
                <span aria-hidden="true">+</span> 新增圖片
            </button>
            <div class="card-tools">
                <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="builderImageContent_{{$row}}"></div>
        </div>
    </div>
    @else
    <div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
        <div class="card-header border-0">
            <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i></span>
            <div class="card-tools">
                <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
        @foreach($module->fields??[] as $field)
            @if($loop->first)
            <div class="row">
            @endif
            @if($field->type == 'ckeditor')
                </div>
                <div class="row">
                <div class="col-sm-12">
                    @include('components.fields.'.$field->type, ['type'=>'create', 'detail'=>$field, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                </div>
                </div>
                <div class="row">
                @php $i++; @endphp
                <script>ckeditorInit();</script>
            @else
                @if($field->field == 'empty')
                    <div class="col-sm-6"></div>
                @else
                    <div class="col-sm-6">
                        @include('components.fields.'.$field->type, ['type'=>'create', 'detail'=>$field, 'value'=>''])
                    </div>
                    @if($field->type=='image')
                    <script>imageInit();</script>
                    @endif
                @endif
            @endif
            @if($i % 2 == 0)
            </div>
            <div class="row">
            @endif
            @if($loop->last)
            </div>  
            @endif
            @php $i++; @endphp
        @endforeach
        </div>
    </div>
    @endif
@endif