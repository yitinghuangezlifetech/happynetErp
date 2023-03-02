$('body').on('click', '.addImgBtn', function(){
    let rows = parseInt($('.imgAreas').length)+1;
    let field = $(this).data('field');
    let id = $(this).data('id');
    let model = $(this).data('model');
    let img = $(this).data('img'); 
    let html = `
    <div class="col-md-3 imgAreas" id="imgRows_${rows}">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">圖片${rows}</h3>
                <div class="card-tools">
                    <button 
                        type="button" 
                        class="btn btn-tool removeMultipleImg" 
                        data-rows="${rows}" 
                        data-id="${id}"
                        data-model="${model}"
                        data-field="${field}"
                        data-card-widget="remove"
                    >
                    <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <input 
                    type="file" 
                    class="form-control dropify" 
                    name="${field}" 
                    data-id="${id}"
                    data-model="${model}"
                    data-field="${field}"
                    data-default-file="${img}"
                    data-show-remove="false"
                    required
                />
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    `;

    $('#imgContent').append(html);
    imageInit();
});

$('body').on('click', '.removeMultipleImg', function(){
    let rows = $(this).data('rows');
    let id = $(this).data('id');
    let model = $(this).data('model');
    let field = $(this).data('field');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'POST',
        url: '/api/image/deleteImg',
        cache: false,
        async: false,
        data: {
            id: id,
            model: model,
            field: field
        },
        success: function() {
            window.location.reload();
        }
    })
})