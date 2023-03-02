<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>圖文區塊 - {{$row}}</span>
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
                <label for="builder_{{$module->id}}_{{$row}}_top_left_text]">
                左上文字描述 
                </label>
                <textarea class="form-control" name="builders[{{$module->id}}][{{$row}}][top_left_text]" id="builder_{{$module->id}}_{{$row}}_top_left_text" style="width: 100%" rows="10"></textarea>
             </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_top_right_img">
                右上圖片 
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][top_right_img]" id="builder_{{$module->id}}_{{$row}}_top_right_img">
             </div>
          </div>
       </div>
       <div class="row">
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_left_img">
                左圖 
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][left_img]" id="builder_{{$module->id}}_{{$row}}_left_img">
             </div>
          </div>
          <script>imageInit();</script>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_right_text">
                右文 
                </label>
                <textarea class="form-control" name="builders[{{$module->id}}][{{$row}}][right_text]" id="builder_{{$module->id}}_{{$row}}_right_text" style="width: 100%" rows="10"></textarea>
             </div>
          </div>
       </div>
    </div>
 </div>