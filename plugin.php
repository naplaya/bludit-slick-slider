<?php

class pluginSlides extends Plugin {

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
}
