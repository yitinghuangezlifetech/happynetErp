<option value="{{$child->id}}" @if(!empty($value['value'])) @if($value['value'] == $child->id){{'selected'}}@endif @endif>{{$child->name}}</option>
@foreach($child->getChilds??[] as $group)
    <option value="{{$group->id}}" @if(!empty($value['value'])) @if($value['value'] == $group->id){{'selected'}}@endif @endif>{{$group->name}}</option>
    @foreach($group->getChilds??[] as $child)
        @include('groups.option', $child)
    @endforeach
@endforeach