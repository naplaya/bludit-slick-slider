var $jq = jQuery.noConflict();
$jq(document).ready(function()
{
    $jq('.slick-slider').slick(
    {
        dots: true,
        arrows:true,
        accessibility:true,
        autoplay:false,
        autoplaySpeed:5500,
        onHoverStop:true
    });

    $(".slick-background").css("background-color", $("#slide_bg_0").val());

    // On before slide change
    $jq('.slick-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){

        $(".slick-background").css({
            backgroundColor:$("#slide_bg_"+nextSlide).val(),
            WebkitTransition : 'background-color 0.3s linear',
            MozTransition    : 'background-color 0.3s linear',
            MsTransition     : 'background-color 0.3s linear',
            OTransition      : 'background-color 0.3s linear',
            transition       : 'background-color 0.3s linear'
        });
    });
});