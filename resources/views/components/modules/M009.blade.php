<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>影片專區 - {{$row}}</span>
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
                <label for="builder_{{$module->id}}_{{$row}}_youtube_id">
                Youtube ID <span style="color:red; font-size:12px">(ex: dQ9P1UAph7E)</span>
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][youtube_id]" id="builder_{{$module->id}}_{{$row}}_youtube_id" value="">
             </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_video_img">
                影片圖片 
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][video_img]" id="builder_{{$module->id}}_{{$row}}_video_img" data-id="" data-model="" data-field="" data-show-remove="false">
             </div>
          </div>
          <script>imageInit();</script>
       </div>
       <div class="row">
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_title">
                標題 
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][title]" id="builder_{{$module->id}}_{{$row}}_title" value="">
             </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_info">
                描述 
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][info]" id="builder_{{$module->id}}_{{$row}}_info" value="">
             </div>
          </div>
       </div>
    </div>
 </div>