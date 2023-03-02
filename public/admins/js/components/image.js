let imageInit = () => {

    let drEvent = $('.dropify').dropify({
        messages: {
            'default': '請上傳圖片'
        }
    });

    drEvent.on('dropify.afterClear', function(event, element){
        let id = $(this).data('id');
        let model = $(this).data('model');
        let field = $(this).data('field');
    
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            method: 'POST',
            url: '/api/image/remove',
            cache: false,
            async: false,
            data: {
                id: id,
                model: model,
                field: field
            }
        })
    });
}

imageInit();