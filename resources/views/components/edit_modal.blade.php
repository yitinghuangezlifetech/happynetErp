<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="editForm" enctype="multipart/form-data" method="POST" action="{{ route('admin.'.$menu->slug.'.update', $id) }}">
        @csrf
        @method('put')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">編輯{{$menu->name}}資料</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @if($menu->menuEditDetails->count() > 0)
                @foreach($menu->menuEditDetails as $detail)
                  @php
                    if ($detail->has_js == 1)  {
                      if (!in_array($detail->type.'.js', $jsArr)) {
                        array_push($jsArr, $detail->type.'.js');
                      }
                    }
                  @endphp
                  @if($detail->super_admin_use == 1 && $user->super_admin == 1)
                    @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>null])
                  @else  
                    @if($detail->show_hidden_field == 1)
                      @php
                        $value = null;

                        if ($detail->field == 'user_id') {
                          $value = $user->id;
                        } else {
                          $value = $data->{$detail->field};
                        }
                      @endphp
                      @include('components.fields.hidden', ['type'=>'edit', 'detail'=>$detail, 'value'=>$value])
                    @else  
                      @if($detail->type == 'multiple_input')
                        @php
                          $options = [];
                          
                          if ($detail->has_relationship == 1) {
                              $json = json_decode($detail->relationship, true);
                              if (is_array($json) && count($json) > 0) {
                                  $options = app($json['model'])->where($json['references_field'], $data->{$detail->foreign_key})->get();
                              }
                          }
                        @endphp
                        @if(!empty($options) && $options->count() > 0)
                          <label for="{{ $detail->field }}">{{$detail->show_name}}</label>
                          <div id="{{ $detail->field }}">
                          @foreach($options as $option)
                            @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'jsonData'=>$json, 'index'=>$key, 'value'=>$option->{$json['show_field']}])
                          @endforeach
                          </div>
                        @endif    
                      @else
                        @include('components.fields.'.$detail->type, ['type'=>'edit', 'detail'=>$detail, 'value'=>$data->{$detail->field}])
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