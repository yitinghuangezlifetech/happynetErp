<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>編輯器 - {{$row}}</span>
       <div class="card-tools">
         <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
         </button>
         <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
            <span aria-hidden="true">×</span>
         </button>
       </div>
    </div>
    <div class="card-body p-0">
       <div class="row">
          <div class="col-sm-12">
             <div class="form-group">
                <textarea class="form-control ckeditor" name="builders[{{$module->id}}][{{$row}}][content]" id="builder_{{$module->id}}_{{$row}}_content"></textarea>
             </div>
          </div>
       </div>
    </div>
 </div>