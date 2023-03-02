<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>左圖右文 - {{$row}}</span>
       <div class="card-tools">
          <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
          <span aria-hidden="true">×</span>
          </button>
       </div>
    </div>
    <div class="card-body p-0">
       <div class="row">
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_img">
                左圖 
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][img]" id="builder_{{$module->id}}_{{$row}}_img">
             </div>
          </div>
          <script>imageInit();</script>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_right_title">
                右文 
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][right_title]" id="builder_{{$module->id}}_{{$row}}_right_title" value="">
             </div>
          </div>
       </div>
    </div>
 </div>