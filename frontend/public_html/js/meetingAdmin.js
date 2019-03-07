var MeetingAdmin = new function() {

    this.init = function() {
        var self = this;

        console.log('initillzing adminf js');
        
        $('#createAgenda').on('click', function() {
            $.ajax({
                url: Meeting.basePath + '/agenda/create',
                data: {},
                method: "get",
            }).done(function(data) {
                //console.log(data);
                $('#formModal').modal('show').find('#modalContent').html(data);
                $('#formModal').find('.modal-title').html('Create Meeting Agenda');
            }).fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
                console.log(jqXHR);
            });
        });
    };

    /**
     * Retrieve form to edit specified agenda
     * @param integer id
     */
    this.editAgenda = function(id) {
        var self = this;
        $.ajax({
            url: Meeting.basePath + '/agenda/update',
            data: {'id':id},
            method: "get",
        }).done(function(data) {
            //console.log(data);
            $('#formModal').modal('show').find('#modalContent').html(data);
            $('#formModal').find('.modal-title').html('Editing Agenda');
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
    /**
     * Retrieves form necessary to create minutes for a specified meeting
     * @param integer agendaId
     * @param string agendaDate
     */
    this.createMinutes = function(agendaId, agendaDate) {
        var self = this;
        $.ajax({
            url: Meeting.basePath + '/agenda-minutes/create',
            data: {'agendaId':agendaId},
            method: "get",
        }).done(function(data) {
            //console.log(data);
            $('#formModal').modal('show').find('#modalContent').html(data);
            $('#formModal').find('.modal-title').html('Create Minutes for "<i>' + agendaDate + '</i>" meeting.');
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
    
    /**
     * Retrieves form necessary to update minutes
     * @param integer id
     * @param string agendaDate
     */
    this.editMinutes = function(id, agendaDate) {
        var self = this;
        $.ajax({
            url: Meeting.basePath + '/agenda-minutes/update',
            data: {'id':id},
            method: "get",
        }).done(function(data) {
            //console.log(data);
            $('#formModal').modal('show').find('#modalContent').html(data);
            $('#formModal').find('.modal-title').html('Updating Minutes for "<i>' + agendaDate + '</i>" meeting.');
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
    
};