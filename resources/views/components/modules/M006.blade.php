<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>右圖左文 - {{$row}}</span>
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
                <label for="builder_{{$module->id}}_{{$row}}_left_text">
                左文
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][left_text]" id="builder_{{$module->id}}_{{$row}}_left_text" value="">
             </div>
          </div>
          <script>imageInit();</script>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_right_img">
                右圖
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][right_img]" id="builder_{{$module->id}}_{{$row}}_right_img">
             </div>
          </div>
       </div>
    </div>
 </div>