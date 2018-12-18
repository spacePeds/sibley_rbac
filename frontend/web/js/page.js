$(function(){
    console.log('page custom JS');

    $('#btnModalAsset').on('click',function() {
        $('#genericModal').find('.modal-dialog').addClass('modal-lg');
        $('#genericModal').modal('show');
    });
    $('#btnModalCategory').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
    
    //reset
    $('#genericModal').on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass('modal-lg');
    });
});