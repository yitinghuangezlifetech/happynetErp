$('body').on('click', '.module_icon', function(){
    let moduleId = $(this).data('id');
    let url = $(this).data('url');
    let row = parseInt($('.buildContentCard').length) + 1;

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        url: url,
        data: {
          id: moduleId,
          row: row
        },
        success: function(rs) {
          $('#mode_module').append(rs.data);
          initCkeditorById(`builder_${moduleId}_${row}_content`);
          imageInit();
        },
        error: function(rs) {

        }
  })
})

$('body').on('click', '.contentRemove', function(){
  let id = $(this).data('id');

  $('#'+id).remove();
})

$('body').on('click', '.builderAddImg', function(){
  let rows = parseInt($(this).data('rows'));
  let len = parseInt($('#builderImageContent_'+rows+' .dropify').length);
  let moduleId = $(this).data('moduleid');
  let str = '';

  str += `
  <div class="col-sm-6" style="display:inline" id="${moduleId}_${rows}_img_area_${len}">
    <div class="form-group">
        <label for="${moduleId}_${rows}_img_${len}">
        圖片
        </label>
        <input type="file" class="form-control dropify" name="builders[${moduleId}][${rows}][img][]" id="${moduleId}_${rows}_img_${len}" data-id="" data-model="" data-field="" data-show-remove="false"><br>
        <button type="button" class="btn bg-gradient-danger btn-sm" onclick="removeImg('${moduleId}_${rows}_img_area_${len}')">刪除</button>
    </div>
  </div>
  <hr>
  `;
  // if ( len != 0 && len % 2 == 0) {
  //   str += '</div><div class="row">';
  // }

  $('#builderImageContent_'+rows).append(str);
    $(`#${moduleId}_${rows}_img_${len}`).dropify({
      messages: {
          'default': '請上傳圖片'
      }
  });
})

let removeImg = (id) => {
  $('#'+id).remove();
}