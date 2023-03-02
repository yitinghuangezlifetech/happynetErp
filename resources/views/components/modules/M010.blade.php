<div class="card col-12 buildContentCard" id="buildContentCard_{{$row}}">
   <div class="card-header border-0">
      <span style="float:left"><i class="fas fa-arrows-alt" style="cursor:pointer"></i>圖片Slider - {{$row}}</span><br>
      <button type="button" class="btn bg-gradient-secondary builderAddImg" data-moduleid="{{$module->id}}" data-id="buildContentCard_{{$row}}" data-rows="{{$row}}" style="float: left;">
         <span aria-hidden="true">+</span> 新增圖片
      </button>
      <div class="card-tools">
         <button type="button" class="close contentRemove" data-id="buildContentCard_{{$row}}">
         <span aria-hidden="true">×</span>
         </button>
      </div>
   </div>
   <div class="card-body p-0">
      <div id="builderImageContent_{{$row}}"></div>
   </div>
</div>