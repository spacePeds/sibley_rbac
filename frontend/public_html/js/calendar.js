var Cal = new function() {
    
    this.dayClick = function(date, jsEvent, view, resource, path, id) {
        var self = this;
        //do nothing
    };

    this.eventDrop = function(event, delta, revertFunc, jsEvent, ui, view, path) {
        var self = this;
        //do noting
    };

    this.calIcs;

    this.eventClick = function(calEvent, jsEvent, view, path) {
        var self = this;
        console.log('Event: ',calEvent.id, "view:", view.name); 
        console.log('Coordinates: ', jsEvent.pageX,jsEvent.pageY); 

        $.ajax({
            url: path + '/event/get-ajax-event',
            data: {'id':calEvent.id},
            method: "get",
            dataType: "json",
        }).done(function(data) {
            console.log(data);
            if (data.status == 'success') {
                console.log('registered success');
                var event = data.payload.event;
                var ics = data.payload.ics;
                var evt = '<div class="text-right font-weight-light small">Posted by: ' + event.group + '</div>'
                        + '<p class="lead">'+event.start_dt+'</p>'
                        + '<div class="contanier">'
                        + '<div class="row">'
                        + '<div class="col-md-6">';
                if (ics.pdf) {
                    evt+= '<ul>' +
                          '<li><a href=""><i class="far fa-file-pdf"></i>&nbsp;</a></li>' +
                          '</ul>';
                }
                evt += '</div>' +
                       '<div class="col-md-6 text-right">' +
                       '<a class="icsLink" href="#"><i class="far fa-calendar-plus"></i> Add</a>' +
                       '</div>' +
                       '</div>' +
                       '</div>';

                $('#genericModal').modal('show').find('.modal-title').html('<i class="far fa-calendar-alt"></i> ' + event.subject);
                $('#modalContent').html(evt);

                //initilize ics
                self.calIcs = ics();
                self.calIcs.addEvent(event.subject, isc.description, ics.location, ics.startDt, ics.endDt, ics.rrule);
                $('.icsLink').on('click', function(e) {
                    console.log('clicked ics', event.subject);
                    e.preventDefault();
                    self.calIcs.download(event.subject);
                });

            } else {
                $('#genericModal').modal('show').find('.modal-title').html('Ohoh! Somethign went wrong.');
                $('#modalContent').html(data.message);
                $('.modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
            }
            

        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });    

        // $.ajax({
        //     url: path + '/event/view-modal',
        //     data: {'id':calEvent.id},
        //     method: "get",
        // }).done(function(data) {
        //     console.log(data);
        //     //$('#modalContent').html(data);

        //     //$('#genericModal').modal('show').find('.modal-title').html('<i class="far fa-calendar-alt"></i> ' + subject);
            
        //     //
        //     //var subject = $('#genericModal').find('.event-view').data('subject');
            

        //     var desc = (typeof icsDescription !== undefined) ? icsDescription : '';
        //     var location = (typeof icsLocation !== undefined) ? icsLocation : '';
        //     var startDt = (typeof icsStartDt !== undefined) ? icsStartDt : '';
        //     var endDt = (typeof icsEndDt !== undefined) ? icsEndDt : '';
        //     //"{rrule: 'RRULE:FREQ=WEEKLY;INTERVAL=4'}";
        //     var rrRule = (typeof icsRRule !== undefined) ? icsRRule : '';  
        //     self.calIcs = ics();
        //     self.calIcs.addEvent(subject, desc, location, startDt, endDt, rrRule);
        //     //self.calIcs.addEvent('Demo Event', 'This is thirty minut event', 'Nome, AK', '8/7/2013 5:30 pm', '8/9/2013 6:00 pm');
        //     console.log(self.calIcs);

        //     $('.icsLink').on('click', function(e) {
        //         console.log('clicked ics', subject);
        //         e.preventDefault();
        //         self.calIcs.download(subject);
        //     });

        // });
    };
};
$(function(){
    console.log('calendar standard JS');
    Cal.calIcs= ics();
    Cal.calIcs.addEvent('Demo Event', 'This is thirty minut event', 'Nome, AK', '8/7/2013 5:30 pm', '8/9/2013 6:00 pm');
    Cal.calIcs.download('I am a test');
});