var Cal = new function() {
    
    this.dayClick = function(date, jsEvent, view, resource, path) {
        var self = this;
        
        var dt = date.format('MM/DD/YYYY');
        var createEvent = path + '/event/create';
        console.log( 'I clicked on a day', dt, path );
        $.get(createEvent,{'date':dt})
            .done(function(data){
                $('#genericModal').find('.modal-title').html('Create Event');
                $('.modal').modal('show')
                    .find('#modalContent')
                    .html(data);
        });
    };

    this.eventClick = function(calEvent, jsEvent, view, path) {

        console.log('Event: ',calEvent.id, "view:", view.name); 
        console.log('Coordinates: ', jsEvent.pageX,jsEvent.pageY); 

        $.ajax({
            url: path + '/event/update',
            data: {'id':calEvent.id},
            method: "get",
        }).done(function(data) {
            console.log(data);
            $('#genericModal').modal('show').find('#modalContent').html(data);
            $('#genericModal').find('.modal-title').html('Updating Event: '+calEvent.id);
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };

    this.eventDrop = function(event, delta, revertFunc, jsEvent, ui, view, path) {
        console.log(event);
        if (!confirm("Are you sure about this change?")) {
            revertFunc();
        } else {
            var startDt = '';
            var endDt = '';
            if (event.start !== null) {
                startDt = event.start.format('YYYY-MM-DD');
            }
            if (event.end !== null) {
                endDt = event.end.format('YYYY-MM-DD');
            }
            $.ajax({
                url: path + "/event/update_ajax",
                data: {'id':event.id, 'startDate': startDt, 'endDate': endDt },
                method: "post",
                dataType: "json"
            }).done(function(data) {
                if (data.status !== 'success') {
                    alert('An error occured during time shift.');
                    revertFunc();
                }
                //$.parseJSON()
                console.log(data);
            }).fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
                console.log(jqXHR);
                revertFunc();
            });
        }

    }

};
$(function(){
    console.log('calendar admin JS');

    

});