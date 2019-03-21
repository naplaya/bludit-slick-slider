<?php

class pluginSlickSlider extends Plugin {

	public function init()
	{
		// JSON database
		$jsondb = json_encode(array(
			'Bludit'=>'https://www.bludit.com',
			'Bludit PRO'=>'https://pro.bludit.com'
		));

		// Fields and default values for the database of this plugin
		$this->dbFields = array(
			'label'=>'Links',
			'jsondb'=>$jsondb
		);

		// Disable default Save and Cancel button
		$this->formButtons = true;
	}
    
    public function registerHooks()
    {
        return array("slider");
    }

	// Method called when a POST request is sent
	public function post()
	{
      
        
        
		// Get current jsondb value from database
		// All data stored in the database is html encoded
		$jsondb = $this->db['jsondb'];
		$jsondb = Sanitize::htmlDecode($jsondb);

		// Convert JSON to Array
		$links = json_decode($jsondb, true);

		// Check if the user click on the button delete or add
		if( isset($_POST['deleteLink']) ) {
			// Values from $_POST
			$name = $_POST['deleteLink'];

			// Delete the link from the array
			unset($links[$name]);
		}
		elseif( isset($_POST['addLink']) ) {
			// Values from $_POST
			$name = $_POST['linkName'];
			$url = $_POST['linkURL'];

			// Check empty string
			if( empty($name) ) { return false; }

			// Add the link
			$links[$name] = $url;
		}
        
        $this->db["box_title_0"] = Sanitize::html($_POST["box_title_0"]);

		// Encode html to store the values on the database
		$this->db['label'] = Sanitize::html($_POST['label']);
		$this->db['jsondb'] = Sanitize::html(json_encode($links));

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
		$slides = json_decode($jsondb, true);
        
        echo "DB CONTENT<br>";
        
        print_r($slides);
        
        echo "<br>DB CONTENT ENDE";

		$html .= !empty($slides) ? '<h4 class="mt-3">'.$L->get('Links').'</h4>' : '';

            
        
        $html .= '<div>';
		$html .= '<label>'.$L->get('Name').'</label>';
		$html .= '<input name="linkName" type="text" value="" placeholder="Bludit">';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('Url').'</label>';
		$html .= '<input name="linkURL" type="text" class="form-control" value="" placeholder="https://www.bludit.com/">';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<button name="addLink" class="btn btn-primary my-2" type="submit">'.$L->get('Add').'</button>';
		$html .= '</div>';
        
        
        
        $slides = array
            (
                array
                (
                    "background" =>"#FF0000",
                    "link"=>"test",
                    "img"=>"https://thumbs.dreamstime.com/z/badminton-139106.jpg",
                    "img_mode"=>"contain",
                    "img_pos"=>"top",

                    "box_color"=>"#00FF00",
                    "box_title"=>"Willkommen",
                    "box_detail" => "bei uns",
                    "box_pos"=>"top right",
                    "box_btn_title" => "More",
                    "box_btn_color" =>"#FFFFFF"

                    /*"conditions" => array
                    (
                        "os" => array("win", "mac", "linux"),
                        "date" => array("from" => "2019-01-21", "to" => "2019-01-30"),
                        "mobile" => true
                    ) */  
                ),
            array
                (
                    "background" =>"#FF0000",
                    "link"=>"test",
                    "img"=>"https://thumbs.dreamstime.com/z/badminton-139106.jpg",
                    "img_mode"=>"contain",
                    "img_pos"=>"top",

                    "box_color"=>"#00FF00",
                    "box_title"=>"Willkommen",
                    "box_detail" => "bei uns",
                    "box_pos"=>"top right",
                    "box_btn_title" => "More",
                    "box_btn_color" =>"#FFFFFF"

                    /*"conditions" => array
                    (
                        "os" => array("win", "mac", "linux"),
                        "date" => array("from" => "2019-01-21", "to" => "2019-01-30"),
                        "mobile" => true
                    ) */  
                )
            );
      
		

        //catch php content, prevent client seeing following
        ob_start();
        include(__DIR__."/test.php");
        
        $html .= ob_get_contents();
        ob_end_clean();
       
        
        $html .= $this->includeCSS('style.css');
		$html .= $this->includeJS('script.js');
		return $html;
	}

	// Method called on the sidebar of the website
	public function siteSidebar()
	{
		global $L;

		// HTML for sidebar
		$html  = '<div class="plugin plugin-pages">';
		if ($this->getValue('label')) {
			$html .= '<h2 class="plugin-label">'.$this->getValue('label').'</h2>';
		}
		$html .= '<div class="plugin-content">';
		$html .= '<ul>';

		// Get the JSON DB, getValue() with the option unsanitized HTML code
		$jsondb = $this->getValue('jsondb', false);
		$links = json_decode($jsondb);

		// By default the database of categories are alphanumeric sorted
		foreach( $links as $name=>$url ) {
			$html .= '<li>';
			$html .= '<a href="'.$url.'">';
			$html .= $name;
			$html .= '</a>';
			$html .= '</li>';
		}

		$html .= '</ul>';
 		$html .= '</div>';
 		$html .= '</div>';

		return $html;
	}
    

    
    
    public function siteHead()
    {
        global $L, $content;
        
        // Get the JSON DB, getValue() with the option unsanitized HTML code
		$jsondb = $this->getValue('jsondb', $unsanitized=false);
        
        //include external Files
        $html .= $this->includeJS('jquery-3.3.1.min.js');
        $html .= $this->includeJS('jquery-migrate-1.2.1.min.js');
        $html .= $this->includeJS('slick.min.js');
        $html .= $this->includeJS('slider.js');
        
        $html .= $this->includeCSS('slick.min.css');
        $html .= $this->includeCSS('slick-theme.css');
        $html .= $this->includeCSS('slick.css');
        
        
  

        //Slider html (root-elemnt)
        $html .= '<div class="slick-background"><div class="slick-slider">';
        $index =0;
        foreach ($content as $slide)
        {   
            //progress Slide Settings
            if($slide->type()!='sticky'){break;}

            $ts = "[SLIDE]";
            $te = "[SLIDE-END]";

            // Get the page content into a variable.
            $page_content = strip_tags($slide->contentBreak());
            $p = array();
            $p['box-pos'] = "top right";

            if(stripos($page_content, $ts) !== false && stripos($page_content, "[SLIDE-END]") !== false)
            {
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

                $page_content = substr_replace($page_content, "", $str_start, $str_len+strlen($te));
            }
            //Progress end
            
            
            //Textbox
            $textbox = "";
            if(!isset($p['no-detail']) && !empty(strip_tags($page_content)))
            {
                $readmore = "";
                if ($slide->readMore())
                {

$readmore = <<<EOF
<div class="text-right pt-3">
    <a class="btn btn-primary btn-sm" href="{$slide->permalink()}" role="button">
        {$L->get('Read more')}
    </a>
</div>
EOF;
                }//end readmore

$textbox = <<<EOF
<p class="detail" style="color:'{$p["text-color"]}">
    {strip_tags($page_content)}
    <!-- Shows "read more" button if necessary -->
    {$readmore}
</p>
EOF;
            }//end textbox
            
            
            if(isset($p["box-btn-primary-title"]))
            {
                
                $actions = '<div class="actions">';
                    if(isset($p["box-btn-primary-title"]))  $actions .= '<a href="'.$p["box-btn-primary-link"].'" class="button">'.$p["box-btn-primary-title"].'</a>';
                    if(isset($p["box-btn-secondary-title"]))$actions .= '<a href="'.$p["box-btn-secondary-link"].'" class="button">'.$p["box-btn-secondary-title"].'</a>';
                $actions .= '</div>';
            }
            
//slide
$html .= <<<EOF
<a href="{$slide->permalink()}" class="slide" style="background-color:{$p["background-color"]}">
    <input id="slide_bg_{$index}" type="hidden" value="{$p["background-color"]}">
    <img id="slide_img_{$index}" 
        src="{$slide->coverImage()}" 
        class="{$p["img-pos"]} {$p["img-mode"]}"
    >
    <div class="text-box {$p['box-pos']}" style="background-color:{$p["box-color"]}">
        <p class="title" style="color:{$p["text-color"]}" >
            {$slide->title()}
        </p>

        {$textBox}

        {$actions}
    </div>
</a>
EOF;
//slide end            

            $index++;
        }//end foreach slide
        
        
        
        $html .= '</div></div>';
                
        return $html;
    }
    
    
    
    //Method called on custom Hook "slider"
    public function slider()
    {
        

    }
}
