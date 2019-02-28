<div class="alert alert-primary" role="alert">
    <?php echo $this->description();?>
</div>
<button name="addLink" class="btn btn-primary my-2" type="submit"><?php echo $L->get('Save all'); ?></button>

<!-- Slider -->
<div class="home-slider">
    <?php $index =0; ?>
    <?php foreach ($slides as $slide): ?>
        <div href="<?php echo $slide["link"];?>" class="slide">
           <img id="slide_img_<?php echo $index;?>" src="<?php echo $slide["img"];?>" class="<?php echo isset($slide["img_mode"])? $slide["img_mode"]:"";?>">
            <div class="text-box <?php echo $slide["box_pos"];?>">
                <input type="text" name="box_title_<?php echo $index;?>" class="title" value="<?php echo $slide["box_title"];?>"><br>

                <input type="text" value="<?php if(isset($slide["box_detail"])) echo $slide["box_detail"];?>"><br>

                <div><input type="text" class="button" value="<?php if(isset($slide["box_btn_title"]))echo $slide["box_btn_title"];?>"></div>    

            </div>

        </div>
        <div class="slide-settings">
            <div>
                <div>
                    <!-- Img input -->
                    <label for="i_img_<?php echo $index;?>">Bild</label>
                    <input id="i_img_<?php echo $index;?>" type="text">
                </div>

                <div>
                    <!-- img mode --->
                    <label for="i_img_mode_<?php echo $index;?>">Bild Anzeige Modus</label>
                    <select id="i_img_mode_<?php echo $index;?>" onchange="onChangeImgMode(<?php echo $index;?>)">
                        <option <?php if($slide['img_mode'] == "contain"): echo "selected";  ?> value="contain">Alles</option>
                        <option <?php else: echo "selected"; endif;?> value="cover">Füllend</option>
                    </select>
                </div>

                <div>
                    <!-- IMG position -->
                    <label for="i_img_pos_<?php echo $index;?>">Bild Ausrichtung</label>
                     <select id="i_img_pos_<?php echo $index;?>" onchange="onChangePositionImg(<?php echo $index;?>)">
                        <option <?php if($slide['img_pos'] == "left"){echo "selected";}?> value="left">Links</option>
                        <option <?php if($slide['img_pos'] == "top"){echo "selected";}?> value="top">Oben</option>
                        <option <?php if($slide['img_pos'] == "right"){echo "selected";}?> value="right">rechts</option>
                        <option <?php if($slide['img_pos'] == "bottom"){echo "selected";}?> value="bottom">unten</option>
                        <option <?php if($slide['img_pos'] == "middle"){echo "selected";}?> value="middle">mitte</option>
                    </select>
                </div>
            </div>
            <div>
                <div>
                    <!-- Background color -->
                    <label for="i_bg_color_<?php echo $index;?>">Hintergrund Farbe (bei transparenten Bildern sichtbar</label>
                    <input id="i_bg_color_<?php echo $index;?>" type="color" value="<?php echo $slide['background'];?>">
                </div>

                <div>
                    <!-- Textbox color -->
                    <label for="i_tb_color_<?php echo $index;?>">Info Box Farbe</label>
                    <input id="i_tb_color_<?php echo $index;?>" type="color" value="<?php echo $slide['box_color'];?>">
                </div>

                <div>
                    <!-- Buttoncolor -->
                    <label for="i_btn_color_<?php echo $index;?>">Info Box Button Farbe</label>
                    <input id="i_btn_color_<?php echo $index;?>" type="color" value="<?php echo $slide['box_btn_color'];?>">
                </div>
                <div>
                    <!-- TextBox position -->
                    <label for="i_tb_pos_<?php echo $index;?>">Info Box Ausrichtung</label>
                    <select id="i_tb_pos_<?php echo $index;?>" onchange="onChangePositionTextBox(<?php echo $index;?>)">
                        <option <?php if($slide['box_pos'] == "left top"){    echo "selected";}?> value="left top">Links oben</option>
                        <option <?php if($slide['box_pos'] == "left bottom"){ echo "selected";}?> value="left bottom<">Links unten</option>
                        <option <?php if($slide['box_pos'] == "right top"){   echo "selected";}?> value="right top">rechts oben</option>
                        <option <?php if($slide['box_pos'] == "right bottom"){ echo "selected";}?> value="right bottom">rechts unten</option>
                    </select>
                </div>
            </div>


        </div>
    <?php endforeach ?>
</div>