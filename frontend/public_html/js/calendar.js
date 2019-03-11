var Cal = new function() {
    
    this.dayClick = function(date, jsEvent, view, resource, path, id) {
        var self = this;
        //do nothing
    };

    this.eventDrop = function(event, delta, revertFunc, jsEvent, ui, view, path) {
        var self = this;
        //do noting
    };

    this.calIcs = ics();

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
            $('#genericModal').modal('show').find('.modalContent').html(data);
            var subject = $('#genericModal').find('.event-view').data('subject');
            $('#genericModal').find('.modal-title').html('<i class="far fa-calendar-alt"></i> ' + subject);

            var desc = (typeof icsDescription !== undefined) ? icsDescription : '';
            var location = (typeof icsLocation !== undefined) ? icsLocation : '';
            var startDt = (typeof icsStartDt !== undefined) ? icsStartDt : '';
            var endDt = (typeof icsEndDt !== undefined) ? icsEndDt : '';
            //"{rrule: 'RRULE:FREQ=WEEKLY;INTERVAL=4'}";
            var rrRule = (typeof icsRRule !== undefined) ? icsRRule : '';  
            self.calIcs = ics();
            self.calIcs.addEvent(subject, desc, location, startDt, endDt, rrRule);
            //self.calIcs.addEvent('Demo Event', 'This is thirty minut event', 'Nome, AK', '8/7/2013 5:30 pm', '8/9/2013 6:00 pm');
            console.log(self.calIcs);

            $('.icsLink').on('click', function(e) {
                console.log('clicked ics', subject);
                e.preventDefault();
                self.calIcs.download(subject);
            });

        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
};
$(function(){
    console.log('calendar standard JS');

});