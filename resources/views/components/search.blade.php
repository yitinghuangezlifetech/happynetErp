<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">查詢條件</h3>
            </div>
            <form id="searchForm" method="GET" action="{{route($menu->slug.'.index')}}">
                @csrf
                <div class="card-body">
                    @php
                        $i = 1;
                    @endphp
                    <div class="row">
                    @if($menu->menuSearchDetails->count() > 0)
                        @foreach($menu->menuSearchDetails as $detail)
                            @php
                                $json = [];
                                if (!empty($detail->applicable_system)) {
                                    $json = json_decode($detail->applicable_system, true);
                                }
                            @endphp
                            @if($detail->super_admin_use == 1)
                                @if($user->role->super_admin == 1 || $user->role->has_audit_route == 1)
                                <div class="col-sm-6">
                                    @if(count($json) > 0)
                                        @if (in_array($user->role->systemType->name, $json))
                                            @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                        @endif
                                    @else
                                        @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                    @endif
                                </div>
                                @endif
                            @else
                                @switch($detail->type)
                                    @case('date')
                                    @case('date_time')
                                        <div class="col-sm-6">
                                            @if(count($json) > 0)
                                                @if (in_array($user->role->systemType->name, $json))
                                                    @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters])
                                                @endif
                                            @else
                                                @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters])
                                            @endif
                                        </div>
                                        @break;
                                    @case('multiple_select')
                                        <div class="col-sm-6">
                                            @if(count($json) > 0)
                                                @if (in_array($user->role->systemType->name, $json))
                                                    @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                                @endif
                                            @else
                                                @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                            @endif
                                        </div>
                                        @break;
                                    @case('empty')
                                        <div class="col-sm-6"></div>
                                        @break;
                                    @case('component')
                                        @break
                                    @default
                                        <div class="col-sm-6">
                                            @if(count($json) > 0)
                                                @if (in_array($user->role->systemType->name, $json))
                                                    @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                                @endif
                                            @else
                                                @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                                            @endif
                                        </div>
                                        @break;
                                @endswitch
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
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm clearSearch">清除</button>
                    <button type="submit" class="btn bg-gradient-secondary btn-sm">查詢</button>
                </div>
            </form>
        </div>
    </div>
</div>