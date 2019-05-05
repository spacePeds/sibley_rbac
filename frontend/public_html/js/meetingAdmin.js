var MeetingAdmin = new function() {

    this.init = function() {
        var self = this;
        
        $('#createAgenda').on('click', function() {
            $.ajax({
                url: Meeting.basePath + '/agenda/create',
                data: {},
                method: "get",
            }).done(function(data) {
                //console.log(data);
                $('#formModal').modal('show').find('#modalContent').html(data);
                $('#formModal').find('.modal-title').html('Create Meeting Agenda');
                $('.date').datepicker({
                    format: 'mm/dd/yyyy',
                    todayHighlight: true
                });
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
            $('.date').datepicker({
                format: 'mm/dd/yyyy',
                todayHighlight: true
            });
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

            //handle pdf delete
            $('a.doDelete').on('click',function(e){
                e.preventDefault();
                if (confirm("Are you sure you wish to delete this PDF?")) {
                    var url="/sub-page/ajax-delete";
                    var id = $(this).data('id');
                    //console.log('delete clicked',url,id);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {'docId':id},
                        datatype: 'json'
                    }).done(function(data ) {
                        if (data.status == 'success') {
                            //console.log('fadeing out', $('div').find('[data-id="'+id+'"]').length);
                            $('div').find('[data-id="'+id+'"]').remove();
                        } else {
                            $(this).closest('div').append(data.message);
                        }
                        //console.log(data);
                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                        //console.log(jqXHR, textStatus, errorThrown);
                        alert(errorThrown);
                    }).always(function( data, textStatus, errorThrown ) { 
                        //console.log(data, textStatus, errorThrown);
                    });
                }
                
                
                
            }); 

        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
    };
    
};