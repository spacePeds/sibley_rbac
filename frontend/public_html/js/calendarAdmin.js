var Cal = new function() {
    
    this.dayClick = function(date, jsEvent, view, resource, path) {
        var self = this;
        
        var dt = date.format('MM/DD/YYYY');
        var createEvent = path + '/event/create';
        //console.log( 'I clicked on a day', dt, path );
        $.get(createEvent,{'date':dt})
            .done(function(data){
                $('#genericModalLabel').html('Create Event');
                $('#genericModal').modal('show')
                    .find('.modal-body')
                    .html(data);
            $(".selectpicker").selectpicker(
                {"BootstrapVersion":4}
            );
            $('#event-repeat_interval').on('change',function() {
                if ($(this).val() == 5) {
                    $('.repeatDaysContainer').show();
                } else {
                    $('.repeatDaysContainer').hide();
                }
            });
        });
    };

    this.calIcs = ics();

    this.eventClick = function(calEvent, jsEvent, view, path) {

        //console.log('Event: ',calEvent.id, "view:", view.name); 
        //console.log('Coordinates: ', jsEvent.pageX,jsEvent.pageY); 

        $.ajax({
            url: path + '/event/update',
            data: {'id':calEvent.id},
            method: "get",
        }).done(function(data) {
            //console.log(data);
            $('#genericModal').modal('show').find('.modal-body').html(data);
            $('#genericModal').find('.modal-title').html('Updating Event: ' + calEvent.id);
            $(".selectpicker").selectpicker(
                {"BootstrapVersion":4}
            );

            $('#event-repeat_interval').on('change',function() {
                if ($(this).val() == 5) {
                    $('.repeatDaysContainer').show();
                } else {
                    $('.repeatDaysContainer').hide();
                }
            });

        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            //console.log(jqXHR);
        });
    };

    this.eventDrop = function(event, delta, revertFunc, jsEvent, ui, view, path) {
        var self = this;
        //console.log(event);
        if (!confirm("Are you sure about this change?")) {
            revertFunc();
        } else {
            self.updateDates(event, revertFunc, path);
        }

    };

    this.eventResize = function(event, delta, revertFunc, path) {
        var self = this;
        //console.log(event, delta);
        alert(event.title + " end is now " + event.end.format());

        if (!confirm("is this okay?")) {
            revertFunc();
        } else {
            self.updateDates(event, revertFunc, path);
        }
    };
    this.updateDates = function(event, revertFunc, path) {
        var self = this;
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
            //console.log(data);
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            //console.log(jqXHR);
            revertFunc();
        });
    };
};
$(function(){
    //console.log('calendar admin JS');

    

    //modal default to large modal-lg
    $('#genericModal').find('.modal-dialog').addClass('modal-lg');
    //append title to header
    //$('#genericModal').find('.modal-header')
    //    .prepend('<h5 class="modal-title"></h5>');

    $('.fc-content').append('<span class="fc-preview"><i class="fas fa-search"></i></span>');
});