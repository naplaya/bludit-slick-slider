<?php

class pluginSlickSlider extends Plugin {

    
    //start and end tags
    public $ts = "[SLIDE]";
    public $te = "[SLIDE-END]";
    
	public function init()
	{
		

		// Fields and default values for the database of this plugin
		$this->dbFields = array(
			'autoplay'=>'true',
			'autoplaySpeed'=>5500,
            'useAsTitle'=>false,
            'sliderPagesWithChilds'=>true,    
		);

		// Disable default Save and Cancel button
		$this->formButtons = true;
	}
    
	// Method called when a POST request is sent
	public function post()
	{
      
		// Encode html to store the values on the database
		$this->db['autoplay'] = Sanitize::html($_POST['autoplay']);
		$this->db['autoplaySpeed'] = Sanitize::html($_POST['autoplaySpeed']);
        $this->db['useOnParents'] = Sanitize::html($_POST['useOnParents']);
        
        $this->db['useAsTitle'] = Sanitize::html($_POST['useAsTitle']);
        $this->db['titleSelector'] = Sanitize::html($_POST['titleSelector']);
        $this->db['coverImageSelector'] = Sanitize::html($_POST['coverImageSelector']);

		// Save the database
		return $this->save();
	}

	// Method called on plugin settings on the admin area
	public function form()
	{
        //initialise Language
		global $L;
        
		// Get the JSON DB, getValue() with the option unsanitized HTML code
		$jsondb = $this->getValue('jsondb', $unsanitized=false);
        


		$html .= '<h4 class="mt-3">'.$L->get('Settings').'</h4>';

            
        
        $html .= '<div>';
		$html .= '<label>'.$L->get('autplay').'</label>';
		$html .= '<input name="autoplay" type="text" value="'.$this->getValue('autoplay').'" placeholder="true">';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('autoplay speed').'</label>';
		$html .= '<input name="autoplaySpeed" type="text" class="form-control" value="'.$this->getValue('autoplaySpeed').'" placeholder="https://www.bludit.com/">';
		$html .= '</div>';
        
        //$html .= '<div>';
		//$html .= '<label>'.$L->get('useOnParents').'</label>';
		//$html .= '<input name="useOnParents" type="text" class="form-control" value="'.$this->getValue('useOnParents').'" placeholder="https://www.bludit.com/">';
		//$html .= '</div>';
        
        
        $html .= '<div>';
		$html .= '<label>'.$L->get('useAsTitle').'</label>';
		$html .= '<select name="useAsTitle" type="text" class="form-control" value="'.$this->getValue('useAsTitle').'">';
            $html .= '<option value="0">'.$L->get("Don't use as title").'</option>';
            $html .= '<option value="1">'.$L->get("Use only image").'</option>';
            $html .= '<option value="2">'.$L->get("Use both (image and title)").'</option>';
		$html .= '</select>';
		$html .= '</div>';
        
        $html .= '<div>';
		$html .= '<label>'.$L->get('Title selector').'</label>';
		$html .= '<input name="titleSelector" type="text" class="form-control" value="'.$this->getValue('titleSelector').'" placeholder=".container .title">';
		$html .= '</div>';
        
        $html .= '<div>';
		$html .= '<label>'.$L->get('Cover image selector').'</label>';
		$html .= '<input name="coverImageSelector" type="text" class="form-control" value="'.$this->getValue('coverImageSelector').'" placeholder=".container .page-cover-image">';
		$html .= '</div>';

       
		return $html;
	}
    
    public function siteHead()
    {
        global $L, $content, $WHERE_AM_I, $page;
        
        
        
        $asTitle = $this->getValue('useAsTitle');
        $forParents = $this->getValue('useOnParents');
       
        
        if($WHERE_AM_I == 'home')
        {
            $html .= $this->includeFilesAndRootElementStart();
            
            $i = 0;
            foreach ($content as $slide)
            {   
                //fixed pages are at the top at database. if page is sticky then loop done
                if($slide->type()!='sticky'){break;}

                // remove all tags from pagecontent
                $page_content = strip_tags($slide->contentBreak());

                //parse individual slide settings from page_content
                $sp = $this->getSlideSettings($page_content);

                //get html code of the slide represented by a page
                $html .= $this->createSlideElement($L, $slide, $sp, $i);    

                $i++;
            }
            
            $html .= $this->closeRootElement();
        }
        else if($WHERE_AM_I == 'page' && $asTitle != 0 && $page->type() == 'sticky' && $page->coverImage())
        {
            $html .= $this->includeFilesAndRootElementStart();
            echo "hallo";
 
            // remove all tags from pagecontent
            $page_content = strip_tags($page->contentBreak());

            //parse individual slide settings from page_content
            $sp = $this->getSlideSettings($page_content);

            //get html code of the slide represented by a page
            $html .= $this->createSlideElement($L, $page, $sp, 0);                

            $html .= $this->closeRootElement();
        }
        
        else
        {
            
        }
        
       
                
        return $html;
    }
    
    public function includeFilesAndRootElementStart(){
         //include external Files
        $html .= $this->includeJS('jquery-3.3.1.min.js');
        $html .= $this->includeJS('jquery-migrate-1.2.1.min.js');
        $html .= $this->includeJS('slick.min.js');
        
        include("js/slider.php");
        $html .= $script;
        
        $html .= $this->includeCSS('slick.min.css');
        $html .= $this->includeCSS('slick-theme.css');
        $html .= $this->includeCSS('slick.css');
        
        
  

        //Slider html (root-elemnt)
        $html .= '<div class="slick-background z-depth-1"><div class="slick-slider">';
        
        return $html;
    }
    
    public function createSlideElement($L, $page, $p, $index){
        global $WHERE_AM_I;
        
        $asTitle = $this->getValue('useAsTitle');
        echo $WHERE_AM_I;
        //Textbox
        $textbox = "";
        if($WHERE_AM_I == 'home' && !isset($p['no-detail']) && !empty(strip_tags($p['content'])))
        {
            $readmore = "";
            if ($page->readMore())
            {

$readmore = <<<EOF
<div class="text-right pt-3">
    <a class="btn btn-primary btn-sm" href="{$page->permalink()}" role="button">
        {$L->get('Read more')}
    </a>
</div>
EOF;
            }//end readmore
                
                
$detail = strip_tags($page_content);
                
$textbox = <<<EOF
<p class="detail" style="color:'{$p["text-color"]}">
    {$page_content}
    <!-- Shows "read more" button if necessary -->
    {$readmore}
</p>
EOF;
        }//end textbox


        if(isset($p["box-btn-primary-title"]) && $WHERE_AM_I == 'home')
        {

            $actions = '<div class="actions">';
                if(isset($p["box-btn-primary-title"]))  $actions .= '<a href="'.$p["box-btn-primary-link"].'" class="button">'.$p["box-btn-primary-title"].'</a>';
                if(isset($p["box-btn-secondary-title"]))$actions .= '<a href="'.$p["box-btn-secondary-link"].'" class="button">'.$p["box-btn-secondary-title"].'</a>';
            $actions .= '</div>';
        }

        $container = $WHERE_AM_I == 'home'? 'a':'div';

//slide
$html .= <<<EOF
<{$container} href="{$page->permalink()}" class="slide" style="background-color:{$p["background-color"]}">
    <input id="slide_bg_{$index}" type="hidden" value="{$p["background-color"]}">
    <img src="{$page->coverImage()}" 
        class="{$p["img-pos"]} {$p["img-mode"]}"
    >
    <div class="text-box z-depth-1 {$p['box-pos']}" style="background-color:{$p["box-color"]}">
        <h1 class="title" style="color:{$p["text-color"]}" >
            {$page->title()}
        </h1>

        {$textBox}

        {$actions}
    </div>
</{$container}>
EOF;
//slide end   
        
        return $html;
    }
    
    public function closeRootElement(){
        return '</div></div>';
    }
    
    public function stripSlideSettings($page_content)
    {
         $str_start = stripos($page_content, $ts);
        $str_len = stripos($page_content, $te) - $str_start;
    }
    
    public function getSlideSettings($page_content){
        $p = array();
        $p['box-pos'] = "top right";
        
        $str_start = stripos($page_content, $ts);
        $str_len = stripos($page_content, $te) - $str_start;
        $slide_props = trim(substr($page_content, $props_start + strlen("[SLIDE]"),$str_len-strlen("[SLIDE]")));
        $slide_props = str_replace(array("\r\n", "\n", "\r"), "", $slide_props);

        foreach(explode(",", $slide_props)as $prop)
        {
            if(strpos($prop, ":") !== false)
            {
                $prop = explode(":", $prop);
                $p[$prop[0]] = $prop[1];
            }else 
            { $p[$prop] = true; }
        }

        //set backgroundcolor to transparent
        if(isset($p['no-box-color'])) $p['box-color'] = 'transparent';
        else if(isset($p['box-color-background'])) $p['box-color'] = 'transparent';

        //if box color is set and starts with hex-color indicator and is with it 9 long or transparent setting is set
        else if(isset($p['box-color']) && substr($p['box-color'], 0, 1)=== "#" && (strlen($p['box-color'])==9 || isset($p['box-color-t'])))
        {
            $val = $p['box-color'];

            //convert Hex to dec from a 6 or 8 char long hex-color
            $r = base_convert(substr($val, 1, 2), 16, 10);
            $g = base_convert(substr($val, 3, 2), 16, 10);
            $b = base_convert(substr($val, 5, 2), 16, 10);
            $a = str_replace(',', '.', base_convert(substr($val, 7), 16, 10)/255);

            if(isset($p['box-color-t'])) $a = $p['box-color-t'];

            $p['box-color'] = 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
        }
    
        return $p;
    }//end getSlideSettings
}
