const getSpecList = (self, level, categories, parentCategory, categoryId) => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/admin/products/getSpecList',
        type: 'POST',
        data: {
            level: level,
            categories: categories
        },
        success:function(response){
            if(response){
                let html = '';
                response.data.map(function(v){
                    html += `<div class="spec_item" id="spec_${categoryId}_${v.id}" style="vertical-align:middle;">`
                    html +=    `<input type="checkbox" class="inputSpec" data-categoryid="${categoryId}" name="spec[${v.id}][id]" value="${v.id}">`
                    html +=    `<input type="hidden" name="spec[${v.id}][sort]" value="${v.sort}">`
                    html +=    `<h3>${v.product_model} - ${v.name}</h3>`
                    html += '</div>'
                });

                $('.spec_list').append(html);

                if (self.prop('checked')) {
                    $('.spec_list').append(html);
                } else {
                    let pass = 2;
                    $(`input[id^="category_${parentCategory}_"]`).each(function(){
                        if ($(this).prop('checked')) {
                            pass = 1;
                        }
                    })
                    if (pass == 2) {
                        $(`#parent_category_${parentCategory}`).prop('checked', false);
                    }
                }
            }
        }
    })
}

$('body').on('click', '.category', function(){
    const id = $(this).val();
    const level = $('#main_product').val();

    if ($(this).prop('checked')) {
        $(`input[id^="category_${id}_"]`).each(function(){
            const self = $(this);
            const categoryId = $(this).val();
            if (categories.indexOf(categoryId) < 0) {
                categories.push(categoryId);
            }
            $(this).prop('checked', true);
            getSpecList(self, level, categories, id, categoryId);
        });
    } else {
        $(`input[id^="category_${id}_"]`).each(function(){
            const self = $(this);
            const categoryId = $(this).val();
            let index = categories.indexOf(categoryId);
            categories.splice(index, 1);

            $(this).prop('checked', false);
            getSpecList(self, level, categories, id, categoryId);
        });
    }
})

$('body').on('click', '.productInput', function(){
    const self = $(this);
    const categoryId = $(this).val();
    const level = $('#main_product').val();
    const parentCategory = $(this).data('categoryid');
    const category = $(`#parent_category_${parentCategory}`);
    
    $('.spec_list').html('');

    if (level == '') {
        $(this).prop('checked', false);

        Swal.fire({
            title: '請先選擇商品型態',
            icon: "error",
            allowOutsideClick: false
        })
    } else {

        if (!category.prop('checked')) {
            category.prop('checked', true);
        }

        if ($(this).prop('checked')) {
            if (categories.indexOf(categoryId) < 0) {
                categories.push(categoryId);
            }
        } else {
            let index = categories.indexOf(categoryId);
            categories.splice(index, 1);
        }

        getSpecList(self, level, categories, parentCategory, categoryId);
    }
})

$('.category').click(function(){
    const id = $(this).data('id');

    if ($(this).prop('checked')) {
        $(`input[id^="category_${id}_"`).each(function(){
            $(this).prop('checked', true);
            $(this).trigger('change');
        })
    } else {
        $(`input[id^="category_${id}_"`).each(function(){
            $(this).prop('checked', false);
            $(this).trigger('change');
        })
    }
})