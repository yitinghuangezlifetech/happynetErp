<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="createForm" enctype="multipart/form-data" method="POST" action="{{route('admin.'.$menu->slug.'.store')}}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">建立{{$menu->name}}資料</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @php
              $jsArr = [];
              $fieldNameArr = [];
              if($menu->seo_enable == 1) {
                array_push($jsArr, 'image.js');
              }
            @endphp
            @if($menu->menuCreateDetails->count() > 0)
                @foreach($menu->menuCreateDetails as $detail)
                  @php
                    if ($detail->has_js == 1)  {
                      if (!in_array($detail->type.'.js', $jsArr)) {
                        array_push($jsArr, $detail->type.'.js');
                      }
                      if($detail->type == 'ckeditor') {
                        if (!in_array($detail->field, $fieldNameArr)) {
                          array_push($fieldNameArr, $detail->field);
                        }
                      }
                    }
                    $halfRows = '';
                  @endphp
                  @if($detail->super_admin_use == 1 && $user->super_admin == 1)
                    @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])  
                  @else  
                    @if($detail->show_hidden_field == 1)
                      @php
                        $value = null;

                        if ($detail->field == 'user_id') {
                          $value = $user->id;
                        }
                      @endphp
                      @include('components.fields.hidden', ['type'=>'create', 'detail'=>$detail, 'value'=>$value])
                    @else
                      @if($detail->type == 'multiple_input')
                      <div class="form-group">
                        <label>{{$detail->show_name}}</label>
                        <div id="{{ $detail->field }}">
                          @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'jsonData'=>null, 'index'=>0, 'value'=>null])
                        </div>
                      </div>
                      @elseif($detail->type == 'multiple_select')
                        @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'model'=>$menu->model, 'data'=>null])
                      @elseif($detail->type == 'ckeditor')
                      <div class="col-sm-12">
                        @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                      </div>
                      @else
                        @include('components.fields.'.$detail->type, ['type'=>'create', 'detail'=>$detail, 'value'=>''])
                      @endif  
                    @endif
                  @endif
                @endforeach
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">關閉</button>
            <button type="submit" class="btn btn-primary btn-sm">儲存</button>
          </div>
        </div>
      </form>
    </div>
</div>