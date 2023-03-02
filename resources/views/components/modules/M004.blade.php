<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>滿版大圖 - {{$row}}</span>
       <div class="card-tools">
          <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
          <span aria-hidden="true">×</span>
          </button>
       </div>
    </div>
    <div class="card-body p-0">
       <div class="row">
          <div class="col-sm-12">
             <div class="form-group">
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][full_img]" id="builder_{{$module->id}}_{{$row}}_full_img">
             </div>
          </div>
       </div>
    </div>
 </div>