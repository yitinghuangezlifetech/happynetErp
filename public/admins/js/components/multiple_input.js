$('body').on('click', '.multipleMinus', function(){
    var field = $(this).data('field');
    var key = $(this).data('key');
    var rows = parseInt($('div[id^="'+field+'_"]').length);

    if (rows > 1) {
        $(`#${field}_${key}`).remove();
    } else {
        Swal.fire({
            type: 'warning',
            title: '需保留一組欄位',
            icon: 'warning',
        })
    }
});

$('body').on('click', '.multiplePlus', function(){
    var field = $(this).data('field');
    var rows = parseInt($('div[id^="'+field+'_"]').length) + 1;
    var component = `
    <div class="input-group mb-12" id="${field}_${rows}">
        <input type="text" class="form-control" name="${field}[]" value="">
        <div class="input-group-append">
            <span class="input-group-text">
                <button type="button" class="btn btn-xs multipleMinus" data-field="${field}" data-key="${rows}"><i class="fas fa-minus"></i></button>
            </span>
            <span class="input-group-text">
                <button type="button" class="btn btn-xs multiplePlus" data-field="${field}" data-key="${rows}"><i class="fas fa-plus"></i></button>
            </span>
        </div>
    </div>
    `;
    $('#'+field).append(component);
});