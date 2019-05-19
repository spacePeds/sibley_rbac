var Meeting = new function() {

    this.basePath = '';
    /**
     * Load the basepath
     */
    this.getBasePath = function(path) {
        var self = this;
        self.basePath = path;
        //console.log('basepath is:',self.basePath);
        self.init();
    }
    this.init = function() {
        var self = this;
        //console.log('initilizing meeting menu');

        //load meeting menu for current year
        self.generateMeetingMenu(new Date().getFullYear());

        //check if a meeting agenda is provided
        //console.log('default meeting:',defaultMeeting);
        if (parseInt(defaultMeeting) > 0) {
            self._retrieveAgenda(defaultMeeting);
        }

        //menu navigation
        $('#agenda-yeartoggle').on('change',function() {
            var yr = $(this).val();
            self.generateMeetingMenu(yr);
        });

        $('#nextYear').on('click',function() {
            self.toggleYear('next');
        });
        $('#previousYear').on('click',function() {
            self.toggleYear('prev');
        });

        //search
        $('#button-search').on('click',function() {
            var searchCriteria = $('#meetingSearchForm').find('.searchTerm').val();
            //console.log(searchCriteria);
            if (searchCriteria !== undefined) {
                $('#meetingSearchForm').submit();
            } else {
                $('#searchHelpBlock').text('Please enter a search term or phrase');
            }        
        });
    };
    /**
     * Find previous and next month
     * @param string direction
     */
    this.toggleYear = function(direction) {
        var self = this;
        var yrList = [];
        var selectedYr = $('#agenda-yeartoggle').val();
        var nextYr = parseInt(selectedYr) + 1;
        var prevYr = parseInt(selectedYr) - 1;
        $("#agenda-yeartoggle > option").each(function() {
            yrList.push(parseInt(this.value));
        });
        yrList.sort();

        //console.log(direction,selectedYr, nextYr, prevYr, yrList);

        if (direction === 'next') {
            //console.log('next index',nextYr, yrList.indexOf(nextYr));
            if (yrList.indexOf(nextYr) !== -1) {
                $('#agenda-yeartoggle').val(nextYr);
                self.generateMeetingMenu(nextYr);
            } else {
                $('#agenda-yeartoggle').val(yrList[0]);
                self.generateMeetingMenu(yrList[0]);
            }
        }
        
        if (direction === 'prev') {
            //console.log('previous index',prevYr, yrList.indexOf(prevYr));
            if (yrList.indexOf(prevYr) !== -1) {
                
                $('#agenda-yeartoggle').val(prevYr);
                self.generateMeetingMenu(prevYr);
            } else {
                var lastIdx = yrList.length -1;
                $('#agenda-yeartoggle').val(yrList[lastIdx]);
                self.generateMeetingMenu(yrList[lastIdx]);
            }
        }

    };
    /**
     * Regenerate menu with appropiate meetings
     */
    this.generateMeetingMenu = function(yr) {
        var self = this;
        var ajaxPath = self.basePath + '/agenda/generate-menu';
        //console.log('generateMeetingMenu',ajaxPath);

        $.get(ajaxPath,{'yr':yr})
            .done(function(data){
                $('#meetingNavigation').html(data);

            $('#meetingNavigation').on('click','a',function(e) {
                e.preventDefault;
                var agendaId = $(this).data('id');
                self._retrieveAgenda(agendaId);
            });
        });
    };
    /**
     * Retrieve an agenda from the server
     * @param integer agendaId
     */
    this._retrieveAgenda = function(agendaId) {
        var self = this;
        //console.log('clicked agenda',agendaId);
        if (agendaId === undefined) {
            return;
        }
        var ajaxPath = self.basePath + '/agenda/get-agenda';
        $.get(ajaxPath,{'id':agendaId})
            .done(function(data){
                $('#meetingContainer').html(data);

            //setup admin options if applicable
            if ($('#editAgenda').length > 0) {
                var id = $('#editAgenda').data('id');
                $('#editAgenda').on('click', function() {
                    MeetingAdmin.editAgenda(id);
                });
                
            }
            if ($('#createMinutes').length > 0) {
                var agendaId = $('#createMinutes').data('agenda');
                var agendaDt = $('#createMinutes').data('date');
                $('#createMinutes').on('click', function() {
                    MeetingAdmin.createMinutes(agendaId, agendaDt);
                });
                
            }
            
            if ($('#editMinutes').length > 0) {
                var id = $('#editMinutes').data('id');
                var agendaDt = $('#editMinutes').data('date');
                $('#editMinutes').on('click', function() {
                    MeetingAdmin.editMinutes(id, agendaDt);
                });    
            }

            if ($('#minutesButn').length > 0) {
                $('#minutesButn').on('click', function() {
                    $('#minutes-tab').tab('show');
                });
                
            }
        
        });
        
    };

};