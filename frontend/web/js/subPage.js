var SubPage = new function() {
    
    this.init = function() {
        var self = this;

        $('a.doDelete').on('click',function(e){
            e.preventDefault();
            var url="/sub-page/ajax-delete";
            var id = $(this).data('id');
            console.log('delete clicked',url,id);
            $.ajax({
                url: url,
                type: 'POST',
                data: {'docId':id},
                datatype: 'json'
            }).done(function(data ) {
                if (data.status == 'success') {
                    console.log('fadeing out', $('div').find('[data-id="'+id+'"]').length);
                    $('div').find('[data-id="'+id+'"]').remove();
                } else {
                    $(this).closest('div').append(data.message);
                }
                console.log(data);
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                console.log(jqXHR, textStatus, errorThrown);
                alert(errorThrown);
            }).always(function( data, textStatus, errorThrown ) { 
                //console.log(data, textStatus, errorThrown);
            });
        }); 

    };
};
$(function(){
    console.log('initilizing subPage JS');
    SubPage.init();
});