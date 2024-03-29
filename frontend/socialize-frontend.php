<?php
class SocializeFrontEnd {
	
	function SocializeFrontEnd() {
		if(is_admin()){

		} else {
			add_filter('the_content', array(&$this, 'insert_socialize'));
			add_filter('the_excerpt', array(&$this, 'insert_socialize'));
			add_action('wp_print_styles', array(&$this, 'socialize_style'));
			add_action('wp_print_scripts', array(&$this, 'socialize_scripts'));
		}
	}
	
	function get_button($serviceID){
		global $socializeWPservices;
		// return button coresponding to $serviceID
		switch($serviceID){
			case 1:
			case 11:
				return $socializeWPservices->createSocializeTwitter();
				break;
			case 2:
			case 12:
				return $socializeWPservices->createSocializeFacebook();
				break;
			case 3:
			case 13:
				return $socializeWPservices->createSocializeDigg();
				break;
			case 4:
			case 14:
				return $socializeWPservices->createSocializeSphinn();
				break;
			case 5:
			case 15:
				return $socializeWPservices->createSocializeReddit();
				break;
			case 6:
			case 16:
				return $socializeWPservices->createSocializeDzone();
				break;
			case 7:
			case 17:
				return $socializeWPservices->createSocializeStumble();
				break;
			case 8:
			case 18:
				return $socializeWPservices->createSocializeDelicous();
				break;
			case 9:
			case 19:
				return $socializeWPservices->createSocializeBuzz();
				break;
			case 10:
			case 20:
				return $socializeWPservices->createSocializeYahoo();
				break;
			case 22:
			case 23:
				return $socializeWPservices->createSocializeLinkedIn();
				break;
                        case 24:
			case 25:
				return $socializeWPservices->createSocializePlusOne();
				break;
		}
	}
	
	
	//display specifc button
	function display_button($serviceID, $before_button = "", $after_button = "", $socialize_settings = array(), $socializemeta = array()){
		global $post;

                // Get out fast
                if((!empty($socializemeta)) && !in_array($serviceID, $socializemeta)){
                    return false;
                }
		
		// Does this post have buttons
		if(empty($socializemeta)){
			if(get_post_custom_keys($post->ID) && in_array('socialize', get_post_custom_keys($post->ID))){
				$socializemeta = explode( ',', get_post_meta($post->ID,'socialize',true));
			} else {
				// Retrieve settings if they were not passed
				if (empty($socialize_settings)){
					$socialize_settings = array();
					$socialize_settings = get_option('socialize_settings10');	
				}
				$socializemeta = explode( ',', $socialize_settings['sharemeta']);
			}
		}
		
		// Return button
		if(in_array($serviceID, $socializemeta)){
			return $before_button . $this->get_button($serviceID) . $after_button;
		} else {
			return false;
		}
	}
	
	//wrapper for inline content
	function inline_wrapper($socialize_settings = null){
		global $post;
		
		if(!isset($socialize_settings)){
			$socialize_settings=array();
			$socialize_settings = get_option('socialize_settings10');
		}
		
		$buttonDisplay = "";
		if($socialize_settings['socialize_position'] == 'vertical'){
			$float_class = ' socialize-in-button-'.$socialize_settings['socialize_float'];
		} else {
			$float_class = ' socialize-in-button-vertical';
		}
		
		$before_button = '<div class="socialize-in-button'.$float_class.'">';
		$after_button = '</div>';
		
		if(get_post_custom_keys($post->ID) && in_array('socialize', get_post_custom_keys($post->ID))){
                        $socializemeta = explode( ',', get_post_meta($post->ID,'socialize',true));
                } else {
                        // Retrieve settings if they were not passed
                        if (empty($socialize_settings)){
                                $socialize_settings = array();
                                $socialize_settings = get_option('socialize_settings10');
                        }
                        $socializemeta = explode( ',', $socialize_settings['sharemeta']);
                }
		
		$inline_buttons_array = array(1,2,3,4,5,6,7,8,9,10,22,24);
		$r_socializemeta = array_reverse($socializemeta);
		foreach ($r_socializemeta as $socialize_button){
			if(in_array($socialize_button,$inline_buttons_array)){
				array_splice($inline_buttons_array ,array_search($socialize_button, $inline_buttons_array),1);
				array_unshift($inline_buttons_array,$socialize_button);	
			}
		}
		
		
		foreach ($inline_buttons_array as $serviceID) {
			$buttonDisplay .= $this->display_button($serviceID, $before_button, $after_button, $socialize_settings, $socializemeta);
		}
		
		if ($buttonDisplay != ""){
			return '<div class="socialize-in-content" style="float:'.$socialize_settings['socialize_float'].';">' . $buttonDisplay. '</div>';
		} else{
			return "";
		}
	}
	
	//wrapper for inline content
	function action_wrapper($socialize_settings = null){
		global $post, $socializeWPservices;
		
		$buttonDisplay = "";
		$socialize_text = "";
		$before_button = '<div class="socialize-button">';
		$after_button = '</div>';
		$alert_display = '';
		
		if(!isset($socialize_settings)){
			$socialize_settings=array();
			$socialize_settings = get_option('socialize_settings10');
		}
		
				
		if((is_single() && isset($socialize_settings['socialize_alert_box']) && $socialize_settings['socialize_alert_box'] == 'on') ||  (is_page() && isset($socialize_settings['socialize_alert_box_pages']) && $socialize_settings['socialize_alert_box_pages'] == 'on')){
			if(get_post_custom_keys($post->ID) && in_array('socialize_text',get_post_custom_keys($post->ID)) && get_post_meta($post->ID,'socialize_text',true) != ""){
				$socialize_text = get_post_meta($post->ID,'socialize_text',true);
			}  else {
				$socialize_text = $socialize_settings['socialize_text'];
			}

			if(get_post_custom_keys($post->ID) && in_array('socialize', get_post_custom_keys($post->ID))){
                                $socializemeta = explode( ',', get_post_meta($post->ID,'socialize',true));
                        } else {
                                // Retrieve settings if they were not passed
                                if (empty($socialize_settings)){
                                        $socialize_settings = array();
                                        $socialize_settings = get_option('socialize_settings10');
                                }
                                $socializemeta = explode( ',', $socialize_settings['sharemeta']);
                        }

			if(!in_array(21,$socializemeta)){
				$alert_buttons_array = array(11,12,13,14,15,16,17,18,19,20,23,25);
				$r_socializemeta = array_reverse($socializemeta);
				foreach ($r_socializemeta as $socialize_button){
					if(in_array($socialize_button,$alert_buttons_array)){
						array_splice($alert_buttons_array ,array_search($socialize_button, $alert_buttons_array),1);
						array_unshift($alert_buttons_array,$socialize_button);
					}
				}
				
				
				
				foreach ($alert_buttons_array as $serviceID) {
					$buttonDisplay .= $this->display_button($serviceID, $before_button, $after_button, $socialize_settings, $socializemeta);
				}
				
				$alert_display = $socialize_settings['socialize_action_template'];
				
				preg_match_all('%\%\%([a-zA-Z0-9_ ]+)\%\%%', $alert_display, $m);
				
				foreach($m[1] as $i) {
					$strReplace = "";

					switch(strtolower(trim($i))){
						case "buttons":
							$strReplace = $buttonDisplay;
							break;
						case "content":
							$strReplace = $socialize_text;
							break;
						case "facebook_like_standard":
							$strReplace = $socializeWPservices->createSocializeFacebook('official-like', array('fb_layout' => 'standard', 'fb_showfaces' => 'true', 'fb_width' => '450', 'fb_verb' => 'like', 'fb_font' => 'arial', 'fb_color' => 'light'));
							break;
						case "facebook_compact":
							$strReplace = $socializeWPservices->createSocializeFacebook('official-like', array('fb_layout' => 'button_count', 'fb_showfaces' => 'false', 'fb_width' => '90', 'fb_verb' => 'like', 'fb_font' => 'arial', 'fb_color' => 'light'));
							break;
						case "tweetmeme_compact":
							$strReplace = $socializeWPservices->createSocializeTwitter('tweetmeme', array('socialize_tweetmeme_style' => 'compact'));
							break;
						case "twitter_compact":
							$strReplace = $socializeWPservices->createSocializeTwitter('official', array('socialize_twitter_count' => 'horizontal'));
							break;
					}

					$alert_display = str_replace("%%" . $i . "%%", trim($strReplace), $alert_display);
				}
				
				$alert_display = '<div class="socialize-containter" style="background-color:'.$socialize_settings['socialize_alert_bg'].'; border: '.$socialize_settings['socialize_alert_border_size'].' '.$socialize_settings['socialize_alert_border_style'].' '.$socialize_settings['socialize_alert_border_color'].';">' . $alert_display . '</div>';
			}
		}
		return $alert_display;
	}
	
	// Add css to header
	function socialize_style() {
		$socialize_settings=array();
		$socialize_settings=get_option('socialize_settings10');
		if(isset($socialize_settings['socialize_css']) && $socialize_settings['socialize_css'] != "on"){
			wp_enqueue_style('socialize', SOCIALIZE_URL . 'frontend/css/socialize.css');	
		}
	}
	
	// Add javascript to header
	function socialize_scripts() {
		//wp_enqueue_script('topsy_button', 'http://cdn.topsy.com/topsy.js');
	}

	// Add buttons to page
	function insert_socialize($content) {
            if(in_the_loop()){
		$socialize_settings=array();
		$socialize_settings=get_option('socialize_settings10');
	
		if((is_front_page() || is_home()) && isset($socialize_settings['socialize_display_front']) && $socialize_settings['socialize_display_front']){
			// Display on front page
			$content = $this->inline_wrapper($socialize_settings) . $content . $this->action_wrapper($socialize_settings);
		} else if(is_archive() && isset($socialize_settings['socialize_display_archives']) && $socialize_settings['socialize_display_archives'] == 'on'){
			// Display in archives
			$content = $this->inline_wrapper($socialize_settings) . $content;
		} else if(is_search() && isset($socialize_settings['socialize_display_search']) && $socialize_settings['socialize_display_search'] == 'on'){
			// Display in search
			$content = $this->inline_wrapper($socialize_settings) . $content;
		} else if(is_singular('page') && isset($socialize_settings['socialize_display_pages']) && $socialize_settings['socialize_display_pages'] == 'on'){
			// Display on pages
			$content = $this->inline_wrapper($socialize_settings) . $content . $this->action_wrapper($socialize_settings);
		} else if(is_singular('post') && isset($socialize_settings['socialize_display_posts']) && $socialize_settings['socialize_display_posts'] == 'on'){
			// Display on single pages
			$content = $this->inline_wrapper($socialize_settings) . $content . $this->action_wrapper($socialize_settings);
		} else if(!empty($socialize_settings['socialize_display_custom']) && is_singular($socialize_settings['socialize_display_custom'])){
			// Display on single pages
			$content = $this->inline_wrapper($socialize_settings) . $content . $this->action_wrapper($socialize_settings);
		} else if(is_feed() && isset($socialize_settings['socialize_display_feed']) && $socialize_settings['socialize_display_feed'] == 'on'){
			// Display in feeds
			$content = $this->inline_wrapper($socialize_settings) . $content;
		} else {
			// default display (add inline buttons without action box
			//$content = $this->inline_wrapper() . $content;
		}
		
            }
            return $content;
	}


}
?>