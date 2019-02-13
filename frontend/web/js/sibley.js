
$(function(){
    
    var $scCity = $('.SlickCarousel-city');
    var $scRec = $('.SlickCarousel-rec');
    var $scChamber = $('.SlickCarousel-chamber');

    $scCity.on('init', function(event, slick){ 
        //console.log('city', event, slick);
        var newType = $(slick.$slides[0]).data('type');
        var slideMax = $(slick.$slides).length;
        $('#Container-city').removeClass().addClass('bg-'+newType);
        $('#Container-city').find('.badge').html('1 of ' + slideMax);
    });
    $scRec.on('init', function(event, slick){ 
        //console.log('rec', event, slick);
        var newType = $(slick.$slides[0]).data('type');
        var slideMax = $(slick.$slides).length;
        $('#Container-rec').removeClass().addClass('bg-'+newType);
        $('#Container-rec').find('.badge').html('1 of ' + slideMax);
    });
    $scChamber.on('init', function(event, slick){ 
        //console.log('chamber',event, slick);
        var slideMax = $(slick.$slides).length;
        var newType = $(slick.$slides[0]).data('type');
        $('#Container-chamber').removeClass().addClass('bg-'+newType);
        $('#Container-chamber').find('.badge').html('1 of ' + slideMax);
    });


    /*Slider rules for City*/
    $('.SlickCarousel-city').slick({
        rtl:false, // If RTL Make it true & .slick-slide{float:right;}
        //autoplay:true, 
        //autoplaySpeed:5000, //  Slide Delay
        speed:800, // Transition Speed
        slidesToShow:1, // Number Of Carousel
        slidesToScroll:1, // Slide To Move 
        pauseOnHover:false,
        appendArrows:$("#Container-city .Arrows .arrow"), // Class For Arrows Buttons
        prevArrow:'<span class="Slick-Prev"><i class="fas fa-caret-left"></i></span>',
        nextArrow:'<span class="Slick-Next"><i class="fas fa-caret-right"></i></span>',
        easing:"linear",
        asNavFor: '.slider-for-city',
    });
    $('.slider-for-city').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        adaptiveHeight: true,
        asNavFor: '.SlickCarousel-city'
    }); 
    $('.SlickCarousel-city').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        console.log(event, slick, currentSlide,nextSlide);
        var newType = $(slick.$slides[nextSlide]).data('type');
        var slideMax = $(slick.$slides).length;
        $('#Container-city').removeClass().addClass('bg-'+newType);
        $('#Container-city').find('.badge').html((nextSlide+1) + ' of ' + slideMax);
    });

    /*Slider rules for Rec -------------------------------------------*/
    
    
    $('.SlickCarousel-rec').slick({
        rtl:false, // If RTL Make it true & .slick-slide{float:right;}
        //autoplay:true, 
        //autoplaySpeed:5000, //  Slide Delay
        speed:800, // Transition Speed
        slidesToShow:1, // Number Of Carousel
        slidesToScroll:1, // Slide To Move 
        pauseOnHover:false,
        appendArrows:$("#Container-rec .Arrows .arrow"), // Class For Arrows Buttons
        prevArrow:'<span class="Slick-Prev"><i class="fas fa-caret-left"></i></span>',
        nextArrow:'<span class="Slick-Next"><i class="fas fa-caret-right"></i></span>',
        easing:"linear",
        asNavFor: '.slider-for-rec',
    });
    $('.slider-for-rec').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        adaptiveHeight: true,
        asNavFor: '.SlickCarousel-rec'
    });
    $('.SlickCarousel-rec').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        //console.log(event, slick, currentSlide,nextSlide);
        var newType = $(slick.$slides[nextSlide]).data('type');
        var slideMax = $(slick.$slides).length;
        $('#Container-rec').removeClass().addClass('bg-'+newType);
        $('#Container-rec').find('.badge').html((nextSlide+1) + ' of ' + slideMax);
    });

    /*Slider rules for Chamber ------------------------------------------*/
    
    
    $('.SlickCarousel-chamber').slick({
        rtl:false, // If RTL Make it true & .slick-slide{float:right;}
        //autoplay:true, 
        //autoplaySpeed:5000, //  Slide Delay
        speed:800, // Transition Speed
        slidesToShow:1, // Number Of Carousel
        slidesToScroll:1, // Slide To Move 
        pauseOnHover:false,
        appendArrows:$("#Container-chamber .Arrows .arrow"), // Class For Arrows Buttons
        prevArrow:'<span class="Slick-Prev"><i class="fas fa-caret-left"></i></span>',
        nextArrow:'<span class="Slick-Next"><i class="fas fa-caret-right"></i></span>',
        easing:"linear",
        asNavFor: '.slider-for-chamber',
    });
    $('.slider-for-chamber').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        adaptiveHeight: true,
        asNavFor: '.SlickCarousel-chamber'
    });
    $('.SlickCarousel-chamber').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        //console.log(event, slick, currentSlide,nextSlide);
        var newType = $(slick.$slides[nextSlide]).data('type');
        var slideMax = $(slick.$slides).length;
        $('#Container-chamber').removeClass().addClass('bg-'+newType);
        $('#Container-chamber').find('.badge').html((nextSlide+1) + ' of ' + slideMax);
    });
});