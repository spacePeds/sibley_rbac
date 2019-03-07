var Staff = new function() {
    
    this.init = function() {
        var self = this;
        $('.date').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true
        });
        
        $('#staff-elected').on('change', function() {
            console.log($(this).val(), $('#electedElements'));
            if (parseInt($(this).val()) === 0) {
                $('#electedElements').slideUp();
            } else {
                $('#electedElements').slideDown();
            }
        });
        if (parseInt($('#staff-elected').val()) === 0) {
            $('#electedElements').slideUp();
        } else {
            $('#electedElements').slideDown();
        }
        
        $('#staffCarousel').carousel({
            'ride': false,
            'wrap': true,
            'interval': false
        });

        $('#staffCarousel img').on('click', function(e) {
            e.preventDefault();
            var imgId = $(this).data('id');
            var imgSrc = $(this).attr('src');
            console.log('clicked img:',imgId);
            $('#staff-image_asset').val(imgId);
            $('#staffImgContainer').html('<img class="img-thumbnail img-fluid" src="'+imgSrc+'">');
            $('#imgAssetContainer').slideUp();
        });

        $('#imgAssetToggle').on('click', function() {
            $('#imgAssetContainer').toggle();
        });

        //load image if is set in loaded record
        var imgId = $('#staff-image_asset').val();
        $('#staffCarousel img').each(function() {
            if ($(this).data('id') === imgId) {
                var imgSrc = $(this).attr('src');
                $('#staffImgContainer').html('<img class="img-thumbnail img-fluid" src="'+imgSrc+'">');
            }
        });
    };

    
};