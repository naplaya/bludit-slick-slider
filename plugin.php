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
        $html = "";
        
        
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
           
            // remove all tags from pagecontent
            $page_content = strip_tags($page->content());

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
    
    public function pageEnd(){
        
        // Get the page content into a variable.
        $page_content = ob_get_clean();

        // Display the changed page content.
        return $this->stripSlideSettings($page_content);; 
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
        
        $content = trim(strip_tags($this->stripSlideSettings($page->contentBreak())));
        
        
        
        $asTitle = $this->getValue('useAsTitle');
      
        
        
        $textColor = strlen($p["text-color"])>0? ('style="color:'.$p["text-color"].'"'):'';
        
        //Textbox
        $textbox = "";
        if( ($WHERE_AM_I == 'home' && !isset($p['no-detail']) && !empty(strip_tags($content))) || ($WHERE_AM_I == 'page' && $asTitle == 2) )
        {
            
              

$textbox = <<<EOF
<p class="detail" {$textColor}>
    <a href="{$page->permaLink()}">{$content}</a>
</p>
EOF;
            
        file_put_contents(PATH_PLUGINS."textbox.html", $textbox);
        }//end textbox


        if((isset($p["btn-primary-text"])|| $page->readMore()) && $WHERE_AM_I == 'home')
        {

            $actions = '<div class="actions">';
                if(isset($p["btn-primary-text"]))  $actions .= '<a href="'.$p["btn-primary-link"].'" class="button">'.$p["btn-primary-text"].'</a>';
                if(isset($p["btn-secondary-text"]))$actions .= '<a href="'.$p["btn-secondary-link"].'" class="button">'.$p["btn-secondary-text"].'</a>';
                else if($page->readMore())              $actions .= '<a class="button" href="'.$page->permalink().'" role="button"> '.$L->get('Read more').'</a>';
            $actions .= '</div>';
        }
        
        file_put_contents(PATH_PLUGINS."ac.html", $actions);

	$boxmat = "z-depth-1";
	if(isset($p["box-color"]) && isset($p["background-color"]) && $p["box-color"] == $p["background-color"]) $boxmat = "";

        if($WHERE_AM_I == 'home') $openLink = 'onclick="openLink(\''.$page->permalink().'\')"';
        
        
      
//slide
$html .= <<<EOF
<div  class="slide" style="background-color:{$p["background-color"]}">
    <input id="slide_bg_{$index}" type="hidden" value="{$p["background-color"]}">
    <img src="{$page->coverImage()}" 
        class="{$p["img-pos"]} {$p["img-mode"]}"
    >
    <a style="background-color:yellow"></a>
    <div class="textbox {$boxmat} {$p['box-pos']}" style="background-color:{$p["box-color"]}">
        <h1 class="title" {$textColor}>
            <a href="{$page->permaLink()}">{$page->title()}</a>
        </h1>

        {$textbox}

        {$actions}
    </div>
</div>
EOF;
//slide end   
        
        file_put_contents(PATH_PLUGINS."html.html", $html);
        
        return $html;
    }
    
    public function closeRootElement(){
        return '</div></div>';
    }
    
    public function stripSlideSettings($page_content)
    {
        $ts = "[SLIDE]";
        $te = "[SLIDE-END]";
        
        $str_start = strpos($page_content, $ts);
        if($str_start !== false)
        {
            $str_len = strpos($page_content, $te) - $str_start+strlen($te);
            return substr_replace($page_content, '', $str_start, $str_len);
        }
        
       return $page_content;
    }
    
    public function getSlideSettings($page_content){
        $p = array();
        $p['box-pos'] = "top right";

 $ts = "[SLIDE]";
        $te = "[SLIDE-END]";

        
        $str_start = stripos($page_content, $ts);
        $str_len = stripos($page_content, $te) - $str_start;
        $slide_props = trim(substr($page_content, $str_start + strlen($ts),$str_len-strlen("[SLIDE]")));
        $slide_props = str_replace(array("\r\n", "\n", "\r"), "", $slide_props);

        foreach(explode(",", $slide_props)as $prop)
        {
            if(strpos($prop, ":") !== false)
            {
                $prop = explode(":", $prop, 2);
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
