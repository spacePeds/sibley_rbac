$(function(){
    //console.log('page custom JS');

    $('#btnModalAsset').on('click',function() {
        $('#genericModal').find('.modal-dialog').addClass('modal-lg');
        $('#genericModal').modal('show');
    });

    $('#btnModalCategory').on('click',function() {
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    //setup listener for prepend image toggle
    $('#headerImageToggle').on('click',function() {
        $('#headerImageContainer').toggle();
    });
    //look for previously uploaded prepended images and click cechkbox if found
    if($('.uploadedImages').find('img').length > 0) {
        $('#headerImageToggle').click();
    }
    
    
    $('#headerImageFormTrigger').on('click',function() {
        //console.log('header image form triggered');
        var pageId = $(this).data('id');
        var pageTitle = $(this).data('title');
        $('#genericModal').modal('show')
            .find('#modalContent')
            .load('/header-image/create', function() {
                $('#genericModal').find('.modal-title').html('Header Images: ' + pageTitle);
                $('#genericModal').find('#headerimage-image_idx').val('page_'+pageId);
                $('#genericModal').find('.modal-dialog').addClass('modal-lg');
                $('#genericModal').find('.modal-footer').hide();
                $('.uploadMessage').html('');

                $('#headerimage-display').on('change', function() {
                    if ($(this).val() == 'parallax') {
                        $('#headerimage-position').val('center');
                    }
                });

                $('#headerImgSubmit').on('click',function() {
                    //console.log('clicked submit');
                    $('#genericModal').find('.im_progress').show();
                    var url="/header-image/create";
                    ajaxFormSubmit(url,'#header_image_form',function(data){
                        if(data.status=='success'){
                            $('.im_progress').fadeOut();
                            var imgSrc = $('#img_preview').attr('src');
                            var imgId = (data.recordId) ? data.recordId : '';
                            //console.log('img:',imgSrc);
                            $('.uploadedImages').append(
                                '<div class="card" data-id="'+imgId+'">' +
                                '<img class="card-img-top img-thumbnail" style="max-height:100px;object-fit:cover;" src="'+imgSrc+'">' +
                                '<div class="card-body p-0 text-center">' +
                                '<a href="#" class="small deleteHeaderImage" data-id="'+imgId+'">Delete</a>' +
                                '</div>');
                            $(".img_preview").hide();
                            $('#genericModal').modal('hide');
                        } else {
                            var errors = [];
                            for (var elem in data.errors) {
                                errors.push(data.errors[elem]);
                            }
                            var errMsg = '<div class="alert alert-danger alert-dismissible fade show p-1 small" role="alert">'
                                + errors.join('<br>')
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button>'
                                + '</div>';
                            $('.uploadMessage').html(errMsg);
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

    //facebook
    if ($('#pagewithcategories-fb_token').val() != '') {
        $('#fbToggle').prop('checked',true);
        $('#fbOptions').show();
    }
    $('#fbToggle').on('click', function() {
        if ($('#fbOptions').is(':hidden')) {
            $('#fbOptions').slideDown();
        } else {
            $('#fbOptions').slideUp();
        }
    });

    //cancel
    $('#cancelButn').on('click', function() {
        window.history.back();
    });

    //categories
    if ($('#pagewithcategories-category_ids').val() != '') {
        var selectedCategories = $('#pagewithcategories-category_ids').val();
        getCategoryDetails(selectedCategories);
    }
    $('#pagewithcategories-category_ids').on('change',function() {
        var selectedCategories = $(this).val();
        //console.log('category selection changed');
        getCategoryDetails(selectedCategories);
    });
    
    function getCategoryDetails(categories) {
        //console.log('cats:',categories);
        $.ajax({
            url: '/page/ajax-organization-details',
            type: 'POST',
            data: {'categories': categories},
            datatype: 'json',

        }).done(function(data) {
            //console.log(data);
            if (data.status =='error') {
                $('#categoryDetails').html(data.message);
            } else {
                $('#categoryDetails').html(data);
            }
        }).fail(function( jqXHR, textStatus, errorThrown ) {
            console.log(jqXHR, textStatus, errorThrown);
            alert(errorThrown);
        })
    }

    //reset
    $('#genericModal').on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass('modal-lg');
    });
});