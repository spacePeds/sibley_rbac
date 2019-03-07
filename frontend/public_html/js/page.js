$(function(){
    console.log('page custom JS');

    $('#btnModalAsset').on('click',function() {
        $('#genericModal').find('.modal-dialog').addClass('modal-lg');
        $('#genericModal').modal('show');
    });

    $('#btnModalCategory').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $('#headerImageToggle').on('click',function() {
        $('#headerImageContainer').toggle();
    });
    
    $('#headerImageFormTrigger').on('click',function() {
        console.log('header image form triggered');
        var pageId = $(this).data('id');
        var pageTitle = $(this).data('title');
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load('/header-image/create', function() {
                $('#genericModal').find('.modal-title').html('Header Images: ' + pageTitle);
                $('#genericModal').find('#headerimage-image_idx').val('page_'+pageId);
                $('#genericModal').find('.modal-dialog').addClass('modal-lg');
                $('#genericModal').find('.modal-footer').hide();

                $('#headerImgSubmit').on('click',function() {
                    console.log('clicked submit');
                    $('#genericModal').find('.im_progress').show();
                    var url="/header-image/create";
                    ajaxFormSubmit(url,'#header_image_form',function(data){
                        if(data.status=='success'){
                            $('.im_progress').fadeOut();
                            var imgSrc = $('#img_preview').attr('src');
                            var imgId = (data.recordId) ? data.recordId : '';
                            console.log('img:',imgSrc);
                            $('.uploadedImages').append(
                                '<div class="card" data-id="'+imgId+'">' +
                                '<img class="card-img-top img-thumbnail" style="max-height:100px;object-fit:cover;" src="'+imgSrc+'">' +
                                '<div class="card-body p-0 text-center">' +
                                '<a href="#" class="small deleteHeaderImage" data-id="'+imgId+'">Delete</a>' +
                                '</div>');
                            $(".img_preview").hide();
                            $('#genericModal').modal('hide');
                        } else {
                            alert("Something went wrong." + data.message);
                            $(".img_preview").hide();
                            $('#genericModal').modal('hide');
                        }
                    });
                });
            });           
    });

    $('.deleteHeaderImage').on('click',function(e) {
        e.preventDefault();
        var imageId = $(this).data('id');
        var $card = $('.uploadedImages').find('.card[data-id="'+imageId+'"]');

        //console.log($card);
        $(this).append('<i class="fas fa-spinner fa-spin"></i>');
        
        if (imageId != '') {
            if(confirm('Are you sure?')) {
                $.ajax({
                    url: "/header-image/delete",
                    type: 'POST',
                    data: {'id': imageId},
                    datatype: 'json',
                    success: function(data) {
                        //console.log(data);
                        $card.fadeOut(500, function() { $(this).remove(); });
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText);
                    },
                });
            }
        }
        /**/
    });

    function ajaxFormSubmit(url, form, callback) {
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
    }

    //handle header image image preview
    $(document).on('change','#Fileinput',function(){
        var imgpreview = DisplayImagePreview(this);
        $(".img_preview").show();
    });

    function DisplayImagePreview(input){
        console.log('image preview:',input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log('onload:',e);    
                $('#img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    //reset
    $('#genericModal').on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass('modal-lg');
    });
});