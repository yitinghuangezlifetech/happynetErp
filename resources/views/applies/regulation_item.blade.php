@foreach($data->terms??[] as $log)
    <div class="card card-secondary disabled" id="regulation_car_{{$loop->iteration}}" style="margin-top: 10px;">
      <div class="card-header">
        <h3 class="card-title main-title"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>&nbsp;&nbsp;條文{{$loop->iteration}}</h3>
        <div style="float: right;">
          <table>
            <tr>
              <td><button type="button" class="btn btn-block btn-outline-secondary btn-sm removeRegulation" style="color:white;" data-row="{{$loop->iteration}}"><i class="fas fa-trash-alt"></i>&nbsp;刪除</button></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group" id="regulationItem_{{$loop->iteration}}">
            {{$log->term->title}}：{{$log->term->describe}}
            <input type="hidden" name="regulations[{{$loop->iteration}}][sort]" id="sort_{{$log->sort}}" value="{{$log->sort}}">
            <input type="hidden" class="term_id" name="regulations[{{$loop->iteration}}][term_id]" value="{{$log->term_id}}">
            </div>
          </div>
        </div>
      </div>
    </div>
@endforeach