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

var SubPageForm = new function() {
    
    this.currentType = '';

    this.init = function() {
        var self = this;
        console.log('initing sub-page form');
        //hide type dependent fields
        $('.field-subpage-body').hide();
        $('.field-subpage-path').hide();
        $('#subPage_links').hide();

        //does sub-page have a current selection for type
        self.currentType = $('#subpage-type').val();
        self.triggerType();

        //listen for sub-page type selection changes
        $('#subpage-type').on('change', function() {
            self.currentType = $(this).val();
            self.triggerType();

        });

        //listen for attachment and show appropriate preview
        $(document).on('change','#Fileinput',function(){
            var imgpreview = self._displayImagePreview(this);
            $(".img_preview").show();
            var url="/sub-page/ajax-upload";
            self.ajaxFormSubmit(url,'#Ajaxform',function(data){
                //var data=JSON.parse(output);
                if(data.status=='success'){
                    $('.im_progress').fadeOut();
                    var doc = $('#img_preview').attr('src');
                    console.log('doc:',doc);
                    $('.All_images').append('<div class="border border-success rounded"><img class="img-thumbnail" width="100" src="'+ doc +'">'+data.label+'</div>');
                    $(".img_preview").hide();
                }else{
                    alert("Something went wrong.Please try again.");
                    $('#uploadMessages').html('<div class="alert alert-danger">' + data.message + '</div>');
                    $(".img_preview").hide();
                }
                $('#subpage-ajax_file_label').val('');
            })  
        });

        //set appropiate form elements based on current selection
        self.linkTypeTest( $('#link-type') );
        $('#link-type').on('change', function() {
            self.linkTypeTest($(this));
        });

        //handle pdf UI
        $('#link-pdffile').on('change',function(){
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
            $('#link-name').val(fileName);
        });
    };

    this._displayImagePreview = function(input) {
        console.log(input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log('onload:',e);
                if (e.target.result.indexOf('application/pdf') >= 0) {
                    $('#img_preview').attr('src', '/img/pdf-placeholder.png');
                } else {
                    $('#img_preview').attr('src', e.target.result);
                }
                
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    this.ajaxFormSubmit = function(url, form, callback) {
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                // do some loading options
            },
            success: function(data) {
                console.log('callback:',data);
                callback(data);
            },
            complete: function() {
                // success alerts
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            },            
        });
    };

    this.triggerType = function() {
        var self = this;
        var subType = self.currentType;
        if(subType == 'section') {
            $('.field-subpage-body').slideDown();
            $('.field-subpage-path').slideUp();
            $('#subPage_links').slideDown();
        } else {
            $('.field-subpage-body').slideUp();
            $('.field-subpage-path').slideDown();
            $('#subPage_links').slideUp();

            if(subType == 'ilink') {
                $('#url_exHelp').hide();
                $('#url_inHelp').show();
            } else {
                $('#url_exHelp').show();
                $('#url_inHelp').hide();
            }
        }
    };

    this.linkTypeTest = function(jqElem) {
        if(jqElem.val() == 'file') {
            $('#fileLinkGroup').slideDown();
            $('#link-name').slideUp();
            $('#link-pdffile').change();
            $('label[for="link-name"]').text('Choose a file');
        } else {
            $('#fileLinkGroup').slideUp();
            $('#link-name').slideDown();
            $('label[for="link-name"]').text('Link');
        }
        $('#url_exHelp').hide();
        $('#url_inHelp').hide();
        if(jqElem.val() == 'xlink') {
            $('#url_exHelp').show();
        }
        if(jqElem.val() == 'ilink') {
            $('#url_inHelp').show();
        }
    };

/*
      
    };
    
    this.triggerType = function(subType) {
        
    };
    
    */
};

$(function(){
    //init proper class based on view
    if ($('#sub-page-form').length > 0) {
        SubPageForm.init();
    } 
    console.log('initilizing subPage JS');
    SubPage.init();

    
});