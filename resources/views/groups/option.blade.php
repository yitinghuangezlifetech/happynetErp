<option value="{{$child->id}}" @if(!empty($value)) @if($value == $child->id){{'selected'}}@endif @endif>{{$child->name}}</option>
@foreach($child->getChilds??[] as $group)
    <option value="{{$group->id}}" @if(!empty($value)) @if($value == $group->id){{'selected'}}@endif @endif>{{$group->name}}</option>
    @foreach($group->getChilds??[] as $child)
        @if(!empty($value))
            @include('groups.option', ['child'=>$child, 'value'=>$value])
        @else
            @include('groups.option', ['child'=>$child, 'value'=>null])
        @endif
    @endforeach
@endforeach