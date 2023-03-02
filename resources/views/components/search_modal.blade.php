<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="searchForm" method="GET" action="{{route($menu->slug.'.index')}}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">查詢條件</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="rows"> 顯示筆數</label>
            <select class="form-control" name="rows">
              <option value="">請選擇</option>
              @for($i=10;$i<=200;$i+=20)
              <option value="{{$i}}" @if($filters['rows']==$i){{'selected'}}@endif>{{$i}}</option>
              @endfor
            </select>
          </div>
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
                  @if(count($json) > 0)
                      @if (in_array($user->role->systemType->name, $json))
                          @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                      @endif
                  @else
                      @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                  @endif
                @endif
              @else
                @if($detail->type == 'date' || $detail->type == 'date_time')
                  @if(count($json) > 0)
                    @if (in_array($user->role->systemType->name, $json))
                      @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters])
                    @endif
                  @else
                    @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters])
                  @endif
                @else
                  @if(count($json) > 0)
                    @if (in_array($user->role->systemType->name, $json))
                      @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                    @endif
                  @else
                    @include('components.fields.'.$detail->type, ['type'=>'search', 'detail'=>$detail, 'value'=>$filters[$detail->field]])
                  @endif
                @endif
              @endif
            @endforeach
          @endif  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gradient-secondary btn-sm" data-dismiss="modal">關閉</button>
          <button type="button" class="btn bg-gradient-secondary btn-sm clearSearch">清除</button>
          <button type="submit" class="btn bg-gradient-secondary btn-sm">查詢</button>
        </div>
      </div>
    </form>
  </div>
</div>