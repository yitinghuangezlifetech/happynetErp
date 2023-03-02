@php
$jsonData = json_decode($detail->relationship, true);
$categories = app($jsonData['model'])
    ->whereNull('parent_id')
    ->orderBy('sort', 'ASC')
    ->get();

if (isset($data))
{

}
@endphp
<div class="form-group">
    <label class="col-sm-2 col-form-label"><span style="color:red">*</span>{{$detail->show_name}}</label>
    <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        @foreach($categories??[] as $k=>$category)
        <tr>
            <td>
                <input type="checkbox" class="category" id="parent_category_{{$category->id}}" data-id="{{$category->id}}" value="{{$category->id}}">{{$category->name}}
            </td>
        </tr>
        <tr class="tr_0">
            <td>
            @if($category->getChilds->count() > 0)
                @php
                    $lineCount = 1;
                @endphp
                <table class="table dt-responsive table-borderless">
                    @foreach($category->getChilds as $k1=>$child)
                        @php
                            $rows = $loop->count;
                            $line = ceil($rows / 8);
                        @endphp
                        @if($loop->iteration == 1)
                            <tr>
                        @endif
                                <td style="background-color: #ffffff; max-width:13%; width:13%">
                                    <input class="productInput categoryItem" type="checkbox" data-categoryid="{{$category->id}}" id="category_{{$category->id}}_{{$child->id}}" name="category[]" value="{{$child->id}}">
                                    <label>{{$child->name}}</label>
                                </td>
                        @if($loop->iteration % 8 == 0)
                            @php
                                $lineCount++;
                            @endphp
                            </tr><tr>
                        @endif
                        @if($loop->last)
                            @if($line == $lineCount)
                                @php
                                    $remain = ($line * 8) - $rows;
                                @endphp
                                @for($i=1;$i<=$remain;$i++)
                                <td style="background-color: #ffffff; max-width:13%; width:13%"></td>
                                @endfor
                            @endif
                            </tr>
                        @endif
                    @endforeach
                </table>
            @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>