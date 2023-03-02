<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
    <div class="card-header border-0">
       <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>四欄圖文 - {{$row}}</span>
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
                <label for="builder_{{$module->id}}_{{$row}}_info">
                描述文字
                </label>
                <input type="text" class="form-control" name="builders[{{$module->id}}][{{$row}}][info]" id="builder_{{$module->id}}_{{$row}}_info" value="">
             </div>
          </div>
          <div class="col-sm-6"></div>
       </div>
       <div class="row">
          <div class="col-sm-6">
             <div class="form-group">
                <label for="builder_{{$module->id}}_{{$row}}_img_1">
                圖一
                </label>
                <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][img_1]" id="builder_{{$module->id}}_{{$row}}_img_1">
             </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
               <label for="builder_{{$module->id}}_{{$row}}_img_2">
               圖二
               </label>
               <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][img_2]" id="builder_{{$module->id}}_{{$row}}_img_2">
            </div>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label for="builder_{{$module->id}}_{{$row}}_img_3">
               圖三
               </label>
               <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][img_3]" id="builder_{{$module->id}}_{{$row}}_img_3">
            </div>
         </div>
         <div class="col-sm-6">
           <div class="form-group">
              <label for="builder_{{$module->id}}_{{$row}}_img_4">
              圖四
              </label>
              <input type="file" class="form-control dropify" name="builders[{{$module->id}}][{{$row}}][img_4]" id="builder_{{$module->id}}_{{$row}}_img_4">
           </div>
        </div>
      </div>
    </div>
 </div>
 <script>imageInit();</script>