$(function(){
    //console.log('page custom JS');

    $('#btnModalCategory').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
        $('#genericModalLabel').html('Create Category');
    });

    $('.btnModalCategoryEdit').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
            $('#genericModalLabel').html('Edit Category');
    });
    
    //reset
    $('#genericModal').on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass('modal-lg');
    });

    //cancel
    $('#cancelButn').on('click', function() {
        window.history.back();
    });
});