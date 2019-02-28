
        function onChangeColorBackground(_element)
        {
            
        }
        
        function onChangeColorTextBox(_element)
        {
            
        }
        
        function onChangeColorButton(_element)
        {
            
        }
        
        function onChangeImgMode(_index)
        {
            var element = $("#slide_img_"+_index);
            
            if($("#i_img_mode_"+_index).val() == "contain") element.addClass("contain");
            else element.removeClass("contain");
        }
        
        function onChangePositionTextBox(_index)
        {
            var element = $("slide_text_box_"+_index);
            var select = $("i_tb_pos_"+_index).val();
            
            
            if(select.includes("top"))
            {
                element.addClass("top");
                element.removeClass("bottom");
            }
            else
            {
                element.addClass("bottom");
                element.removeClass("top");
            }
            
            if(select.includes("left"))
            {
                element.addClass("left");
                element.removeClass("right");
            }
            else
            {
                element.addClass("right");
                element.removeClass("left");
            }
        }
        
        function onChangePositionImg(_index)
        {
            var pos = $("#i_img_pos_"+_index).val();
            var lr = "50%";
            var tb = "50%";
            
            if(!pos.includes("mitte"))
            {
                if(pos.includes("top"))tb = "0%";
                else if(pos.includes("bottom")) tb = "100%";
                
                if(pos.includes("left")) lr = "0%";
                else if(pos.includes("right")) lr = "100%";
                    
            }
            
            $('#slide_img_'+_index).css('object-position', lr + " " + tb);
           
        }
    