$(function(){
    console.log('page custom JS');

    $('#btnModalCategory').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $('.btnModalCategoryEdit').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
    
    //reset
    $('#genericModal').on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass('modal-lg');
    });
});