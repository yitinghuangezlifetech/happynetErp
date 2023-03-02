<div class="form-group">
    <label>
        @if($detail->required == 1)<span style="color:red">*</span>@endif
        @if(isset($columns[$detail->field]))
        {{$columns[$detail->field]}}
        @else
        {{$detail->show_name}}
        @endif
        <button 
            type="button" 
            class="btn bg-gradient-secondary btn-sm addImgBtn"
            @if($value)
            data-id="{{$id}}"
            data-field="{{ $detail->field }}" 
            data-model="{{$model}}"
            data-img="{{$value}}"
            @else
            data-id=""
            data-field="{{ $detail->field }}" 
            data-model=""
            data-img=""
            @endif
        >
            新增圖片
        </button>
    </label>
    <div class="row" id="imgContent">
        @if (isset($data) && $data->{$detail->relationship_method}->count() > 0)
            @php
                $json = json_decode($detail->relationship, true);
            @endphp
            @foreach($data->{$detail->relationship_method}??[] as $k=>$info)
                @if (is_array($json) && count($json) > 0)
                <div class="col-md-3 imgAreas" id="imgRows_{{$data->id}}">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">圖片{{$loop->iteration}}</h3>
                            <div class="card-tools">
                                <button 
                                    type="button" 
                                    class="btn btn-tool removeMultipleImg" 
                                    data-rows="{{$info->id}}" 
                                    data-id="{{$info->id}}"
                                    data-model="{{$json['model']}}"
                                    data-field="{{$json['show_field']}}"
                                    data-card-widget="remove"
                                >
                                <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input 
                                type="file" 
                                class="form-control dropify" 
                                name="{{$detail->field}}" 
                                data-id="{{$info->id}}"
                                data-model="{{$json['model']}}"
                                data-field="{{$json['show_field']}}"
                                data-default-file="{{$info->img}}"
                                data-show-remove="false"
                            />
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                @endif
            @endforeach
        @endif
    </div>
</div>