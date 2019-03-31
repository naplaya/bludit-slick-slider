<?php


global $WHERE_AM_I, $page;


if($WHERE_AM_I == 'page' && $this->getValue('useAsTitle') != 0 && $page->type() == 'sticky' && $page->coverImage())
{
    if($this->getValue('useAsTitle') == 2) $removeTitle =      "$('".$this->getValue('titleSelector')."').remove();";
    else $removeTitle = "$('.slide .textbox').remove();";
        
    if($this->getValue('useAsTitle') >= 1) $removeCoverImage = "$('".$this->getValue('coverImageSelector')."').remove();";
}

$script = <<<EOF
<script>

   
    var \$jq = jQuery.noConflict();
    \$jq(document).ready(function()
    {
         {$removeTitle}
        {$removeCoverImage}

    
        \$jq('.slick-slider').slick(
        {
            dots: true,
            arrows:true,
            accessibility:true,
            autoplay:{$this->getValue('autoplay')},
            autoplaySpeed:{$this->getValue('autoplaySpeed')},
            onHoverStop:true
        });

        $(".slick-background").css("background-color", $("#slide_bg_0").val());

        // On before slide change
        \$jq('.slick-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){

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
</script>
EOF;
?>