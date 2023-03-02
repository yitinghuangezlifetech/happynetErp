<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>標題與描述 - {{$row}}</span>
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
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_title">標題</label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][title]" id="builder_{{$module->id}}_{{$row}}_title" value="">
             </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_info">描述</label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][info]" id="builder_{{$module->id}}_{{$row}}_info" value="">
             </div>
          </div>
       </div>
    </div>
 </div>