var Cal = new function() {
    
    this.dayClick = function(date, jsEvent, view, resource, path, id) {
        var self = this;
        //do nothing
    };

    this.eventDrop = function(event, delta, revertFunc, jsEvent, ui, view, path) {
        var self = this;
        //do noting
    };

    this.eventClick = function(calEvent, jsEvent, view, path) {
        var self = this;
        console.log('Event: ',calEvent.id, "view:", view.name); 
        console.log('Coordinates: ', jsEvent.pageX,jsEvent.pageY); 

        $.ajax({
            url: path + '/event/view-modal',
            data: {'id':calEvent.id},
            method: "get",
        }).done(function(data) {
            //console.log(data);
            $('#genericModal').modal('show').find('#modalContent').html(data);
            $('#genericModal').find('.modal-title').html('View Event');
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
};
$(function(){
    console.log('calendar standard JS');

});