<div class="form-group">
    <label/>
    @if($detail->required == 1)<span style="color:red">*</span>@endif 
    {{$detail->show_name}} {!! $detail->note !!}
    </label>
    @if($detail->has_relationship == 1)
    <div class="table-responsive p-0">
        @php
        $i = 1;
        $select = [];
        $jsonData = json_decode($detail->relationship, true);
        if (is_array($jsonData) && count($jsonData) > 0)
        {
            $dataList = app($jsonData['model'])->orderBy('created_at', 'DESC')->get();
        }

        if (isset($data))
        {
            foreach($data->{$detail->relationship_method}??[] as $type)
            {
                if (!in_array($type->id, $select))
                {
                    array_push($select, $type->id);
                }
            }
        }
        @endphp
        <table class="table text-nowrap table-striped">
            <tbody>
                @foreach($dataList??[] as $key=>$list)
                    @if($i==1)
                    <tr>
                    @endif

                    @if($i%6 == 1)
                    </tr>
                    <tr>
                    @endif
                    <td>
                        <div class="custom-control custom-checkbox systemTypeTd" id="td_{{$list->id}}">
                            @php
                                $name = $list->{$jsonData['show_field']};
                                $checked = '';

                                if (in_array($list->id, $select))
                                {
                                    $checked = 'checked';
                                }
                            @endphp
                            <input
                                class="custom-control-input systemType" 
                                type="checkbox" 
                                id="checkbox_{{$list->id}}_{{$detail->field}}"
                                name="{{$detail->field}}[]" 
                                data-field="{{$detail->field}}" 
                                value="{{$list->id}}" {{$checked}}>
                            <label for="checkbox_{{$list->id}}_{{$detail->field}}" class="custom-control-label">{{$name}}</label>
                        </div>
                    </td>
                    @if($loop->last)
                    </tr>
                    @endif
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>