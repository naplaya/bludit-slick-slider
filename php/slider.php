 <!-- Slider -->
<div class="home-slider">
    <?php $index =0;?>
    <?php foreach ($page as $slide): ?>

        <?php 
             if($slide->type()!='sticky'){break;}

                $ts = "[SLIDE]";
                $te = "[SLIDE-END]";

                // Get the page content into a variable.
                $page_content = strip_tags($slide->contentBreak());
                $prefs = array();
                $d['box-pos'] = "top right";

                if(stripos($page_content, $ts) !== false && stripos($page_content, "[SLIDE-END]") !== false)
                {
                    $str_start = stripos($page_content, $ts);
                    $str_len = stripos($page_content, $te) - $str_start;
                    $slide_props = trim(substr($page_content, $props_start + strlen("[SLIDE]"),$str_len-strlen("[SLIDE]")));
                    $slide_props = str_replace(array("\r\n", "\n", "\r"), "", $slide_props);

                    foreach(explode(",", $slide_props)as $prop)
                    {
                        if(stripos($prop, ":") !== false)
                        {
                            $prop = explode(":", $prop);
                            $prefs[$prop[0]] = $prop[1];
                        }else 
                        { $prefs[$prop[0]] = true; }
                    }


                    $page_content = substr_replace($page_content, "", $str_start, $str_len+strlen($te));


                    echo "start:$str_start";    
                    echo "str_len:$str_len";
                    echo "l:".strlen($slide->contentBreak());
                   echo "page:".$page_content;
                }





        ?>


        <div href="<?php echo $slide->permalink();?>" class="slide">





            <input id="slide_bg_<?php echo $index;?>" type="hidden" value="<?php echo $prefs["background-color"];?>">
            <img id="slide_img_<?php echo $index;?>" 
                src="<?php echo $slide->coverImage(); ?>" 
                class="<?php echo isset($prefs["img-mode"])? $prefs["img-mode"]:"";?>"
            >
            <div class="text-box <?php echo isset($prefs["box-pos"])? $prefs['box-pos']:$d['box-pos'];?>" 
                 <?php if(isset($prefs['box-color'])) echo 'style="background-color:'.$prefs["box-color"].'"';?>
                >
                <p class="title" <?php if(isset($prefs['text-color'])) echo 'style="color:'.$prefs["text-color"].'"';?>>
                    <?php echo $slide->title();?>
                </p>

                <?php if(!isset($prefs['no-detail'])):?>
                    <p class="detail" <?php if(isset($prefs['text-color'])) echo 'style="color:'.$prefs["text-color"].'"';?>>
                        <?php echo strip_tags($page_content); ?>
                        <!-- Shows "read more" button if necessary -->
                        <?php if ($slide->readMore()): ?>
                            <div class="text-right pt-3">
                                <a class="btn btn-primary btn-sm" href="<?php echo $slide->permalink(); ?>" role="button"><?php echo $L->get('Read more'); ?></a>
                            </div>
                        <?php endif ?>
                    </p>
                <?php endif; ?>

                <?php if(isset($prefs["box-btn-primary-title"])):?>
                    <div class="actions">
                        <?php if(isset($prefs["box-btn-primary-title"])):?><a href="<?php echo $prefs["box-btn-primary-link"];?>" class="button"><?php echo $prefs["box-btn-primary-title"];?></a> <?php endif ?>
                        <?php if(isset($prefs["box-btn-secondary-title"])):?><a href="<?php echo $prefs["box-btn-secondary-link"];?>" class="button"><?php echo $prefs["box-btn-secondary-title"];?></a> <?php endif ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php $index++;?>
    <?php endforeach; ?>
</div>

<script>
    var $jq = jQuery.noConflict();
    $jq(document).ready(function()
    {
        $jq('.home-slider').slick(
        {
            dots: true,
            arrows:true,
            accessibility:true,
            autoplay:false,
            autoplaySpeed:5500,
            onHoverStop:true
        });

        $(".outer-header").css("background-color", $("#slide_bg_0").val());

        // On before slide change
        $jq('.home-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            console.log(nextSlide + " " + $("#slide_bg_"+nextSlide).val());

            $(".outer-header").css({
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
