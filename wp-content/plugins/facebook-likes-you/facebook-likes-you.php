<?php
/*
Plugin Name: Facebook Likes You!
Plugin URI: http://wolnaelekcja.pl/wp-facebook-likes-you
Description: Facebook Likes You! is simple plugin which makes it easy to add Facebook Like button and widgetable Like box. It's fully configurable, so you can decide where to append the button.
Version: 1.5.4
Author: Piotr Sochalewski
Author URI: http://wolnaelekcja.pl/
License: GPL3
*/

/*  Copyright 2010-2012 Piotr Sochalewski (piotr@wolnaelekcja.pl)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('FB_LIKE_INIT')) define('FB_LIKE_INIT', 1);
else return;

$fb_like_settings = array();

$fb_like_layouts        = array('standard', 'button_count', 'box_count');
$fb_like_verbs          = array('like', 'recommend');
$fb_like_colorschemes   = array('light', 'dark');
$fb_like_font           = array('', 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana');

$defaultappid = 165570960176857;

function fb_register_like_settings() {
	register_setting('fb_like', 'fb_like_width');
	register_setting('fb_like', 'fb_like_layout');
	register_setting('fb_like', 'fb_like_showfaces');
	register_setting('fb_like', 'fb_like_verb');
	register_setting('fb_like', 'fb_like_colorscheme');
	register_setting('fb_like', 'fb_like_font');
	register_setting('fb_like', 'fb_like_valid');
	register_setting('fb_like', 'fb_like_xfbml');
	register_setting('fb_like', 'fb_like_google1');
	register_setting('fb_like', 'fb_like_opengraph');
	register_setting('fb_like', 'fb_like_defaultpic');
	register_setting('fb_like', 'fb_like_appid');
	register_setting('fb_like', 'fb_like_send');
	register_setting('fb_like', 'fb_like_show_at_top');
	register_setting('fb_like', 'fb_like_show_at_bottom');
	register_setting('fb_like', 'fb_like_show_on_page');
	register_setting('fb_like', 'fb_like_show_on_post');
	register_setting('fb_like', 'fb_like_show_on_home');
	register_setting('fb_like', 'fb_like_show_on_search');
	register_setting('fb_like', 'fb_like_show_on_archive');
	register_setting('fb_like', 'fb_like_margin_top');
	register_setting('fb_like', 'fb_like_margin_bottom');
	register_setting('fb_like', 'fb_like_margin_left');
	register_setting('fb_like', 'fb_like_margin_right');
	register_setting('fb_like', 'fb_like_excl_post');
	register_setting('fb_like', 'fb_like_excl_cat');
	register_setting('fb_like', 'fb_like_css_style');
}

function fb_like_init()
{
    global $fb_like_settings;

	if ( is_admin() )
		add_action( 'admin_init', 'fb_register_like_settings' );

	add_filter('the_content', 'fb_like_button');
	add_filter('admin_menu', 'fb_like_admin_menu');
	add_filter('widget_text', 'do_shortcode');
	add_action('widgets_init', create_function('', 'return register_widget("fb_like_widget");'));
	
	add_option('fb_like_width', '450');
	add_option('fb_like_layout', 'standard');
	add_option('fb_like_showfaces', 'true');
	add_option('fb_like_verb', 'like');
	add_option('fb_like_font', '');
	add_option('fb_like_colorscheme', 'light');
	add_option('fb_like_valid', 'false');
	add_option('fb_like_opengraph', 'true');
	add_option('fb_like_defaultpic', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'images/facebook.png');
	add_option('fb_like_xfbml', 'true');
	add_option('fb_like_google1', 'false');
	add_option('fb_like_appid', '');
	add_option('fb_like_send', 'false');
	add_option('fb_like_show_at_top', 'false');
	add_option('fb_like_show_at_bottom', 'true');
	add_option('fb_like_show_on_page', 'false');
	add_option('fb_like_show_on_post', 'true');
	add_option('fb_like_show_on_home', 'false');
	add_option('fb_like_show_on_search', 'false');
	add_option('fb_like_show_on_archive', 'false');
	add_option('fb_like_margin_top', '0');
	add_option('fb_like_margin_bottom', '0');
	add_option('fb_like_margin_left', '0');
	add_option('fb_like_margin_right', '0');
	add_option('fb_like_excl_post', '');	
	add_option('fb_like_excl_cat', '');	
	add_option('fb_like_css_style', '');

	$fb_like_settings['width'] = get_option('fb_like_width');
	$fb_like_settings['layout'] = get_option('fb_like_layout');
	$fb_like_settings['showfaces'] = get_option('fb_like_showfaces') === 'true';
	$fb_like_settings['verb'] = get_option('fb_like_verb');
	$fb_like_settings['font'] = get_option('fb_like_font');
	$fb_like_settings['colorscheme'] = get_option('fb_like_colorscheme');
	$fb_like_settings['valid'] = get_option('fb_like_valid') === 'true';
	$fb_like_settings['opengraph'] = get_option('fb_like_opengraph') === 'true';
	$fb_like_settings['defaultpic'] = get_option('fb_like_defaultpic');
	$fb_like_settings['xfbml'] = get_option('fb_like_xfbml') === 'true';
	$fb_like_settings['google1'] = get_option('fb_like_google1') === 'true';
	$fb_like_settings['appid'] = get_option('fb_like_appid');
	$fb_like_settings['send'] = get_option('fb_like_send') === 'true';
	$fb_like_settings['showattop'] = get_option('fb_like_show_at_top') === 'true';
	$fb_like_settings['showatbottom'] = get_option('fb_like_show_at_bottom') === 'true';
	$fb_like_settings['showonpage'] = get_option('fb_like_show_on_page') === 'true';
	$fb_like_settings['showonpost'] = get_option('fb_like_show_on_post') === 'true';
	$fb_like_settings['showonhome'] = get_option('fb_like_show_on_home') === 'true';
	$fb_like_settings['showonsearch'] = get_option('fb_like_show_on_search') === 'true';
	$fb_like_settings['showonarchive'] = get_option('fb_like_show_on_archive') === 'true';
	$fb_like_settings['margin_top'] = get_option('fb_like_margin_top');
	$fb_like_settings['margin_bottom'] = get_option('fb_like_margin_bottom');
	$fb_like_settings['margin_left'] = get_option('fb_like_margin_left');
	$fb_like_settings['margin_right'] = get_option('fb_like_margin_right');
	$fb_like_settings['excl_post'] = get_option('fb_like_excl_post');
	$fb_like_settings['excl_cat'] = get_option('fb_like_excl_cat');
	$fb_like_settings['css_style'] = get_option('fb_like_css_style');
	
	$locale = defined(WPLANG) ? WPLANG : 'en_US';

	// 'wp_footer' is there instead of 'wp_head' because it makes better validation
	add_action('wp_footer', 'fb_like_js_sdk');

	// Google +1 JavaScripted placed in 'wp_head'. That's OK.
	add_action('wp_head', 'fb_like_google1_js');

	// Open Graph
	add_action('wp_head', 'fb_like_open_graph');

	// Shortcode [fb-like-button] linked to generate_button()
	add_shortcode('fb-like-button', 'generate_button');

    load_plugin_textdomain( 'fb_like_trans_domain', '', plugin_basename( dirname( __FILE__ ) . '/languages') );
}

function fb_like_pluginPath($file) {
	// $file without first '/'
	return WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__) , "" , plugin_basename(__FILE__) ) . $file;
}

function fb_like_return_appid() {
	global $fb_like_settings, $defaultappid;
	
	$appid = trim($fb_like_settings['appid']);

	if (!$appid)
		$appid = $defaultappid;
		
	return $appid;	
}

/* Load Facebook SDK if needed */
function fb_like_js_sdk() {
	global $fb_like_settings;
	
	if($fb_like_settings['xfbml']=='true') {
	global $locale;
	
	if($fb_like_settings['valid']=='true')
		echo '	<script type="text/javascript" src="' . fb_like_pluginPath('js/fbObjectValidationV4.js') . '"></script>
';
	
	echo '	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/' . $locale . '/all.js#xfbml=1&appId=' . fb_like_return_appid() . '";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, \'script\', \'facebook-jssdk\'));</script>
	';
	}
}

function fb_like_google1_js() {
	global $fb_like_settings;
	
	if($fb_like_settings['google1']=='true') {
		global $locale;

	echo <<<END
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		{lang: '$locale'}
	</script>

END;
	}
}

// URL Validation (for incomplete/relative address in Custom Field)
function fb_like_isValidURL($url) {
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	return eregi($urlregex, $url);
}

function fb_like_catch_image() {
	global $fb_like_settings, $post, $posts;
	ob_start();
	ob_end_clean();
	
	// Default picture if is NOT post or page
	if (!is_single() && !is_page())
		return $fb_like_settings['defaultpic'];

	// Post thumbnail supported by theme
	if (current_theme_supports('post-thumbnails'))
		if (has_post_thumbnail($post->ID)) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
			return esc_attr($img[0]);
		}
	
	// Custom Fields
	$cf_thumb_values = get_post_custom_values('thumb');
	if (!empty($cf_thumb_values))
		$img = $cf_thumb_values[0];
	$cf_thumbnail_values = get_post_custom_values('thumbnail');
	if (!empty($cf_thumbnail_values))
		$img = $cf_thumbnail_values[0];

	if (!empty($img))
		if (fb_like_isValidURL($img))
			return $img;
		else {
			$upload_dir = wp_upload_dir();
			$img = $upload_dir['baseurl'] . '/' . $img;
			if (fb_like_isValidURL($img))
				return $img;
		}

	// First picture in the post
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$img = $matches[1][0];
	
	//Default picture
	if (empty($img))
		$img = $fb_like_settings['defaultpic'];
		
	return $img;
}

function fb_like_open_graph() {
	global $fb_like_settings, $defaultappid;
	
	if($fb_like_settings['opengraph']=='true') {
		?>
		<?php if (!is_single() && !is_page()) 
			echo '<meta property="og:title" content="' . get_bloginfo('name') . '"/>
		<meta property="og:type" content="blog"/>
		<meta property="og:url" content="' . get_bloginfo("home") . '"/>
';
			else echo '<meta property="og:title" content="' . wp_title('', false) . ' &laquo; ' . get_bloginfo('name') .'"/>
		<meta property="og:type" content="article"/>
		<meta property="og:url" content="' . get_permalink() . '"/>
'; ?>
		<meta property="og:image" content="<?php echo fb_like_catch_image() ?>"/>
		<meta property="og:site_name" content="<?php bloginfo('name') ?>"/>
		<?php if (!empty($fb_like_settings['appid']) && fb_like_return_appid()!=$defaultappid)
	    echo '<meta property="fb:app_id" content="' . fb_like_return_appid() . '"/>';
	}
}

/* Show the button */
function fb_like_button($content) {
    global $fb_like_settings;

    if (is_feed()) return $content;

    if(is_single() && !$fb_like_settings['showonpost'])
		return $content;

    if(is_page() && !$fb_like_settings['showonpage'])
		return $content;

    if(is_front_page() && !$fb_like_settings['showonhome'])
		return $content;

    if(is_search() && !$fb_like_settings['showonsearch'])
		return $content;

    if(is_archive() && !$fb_like_settings['showonarchive'])
		return $content;
	
	/* Exclude posts and pages */
	if(trim($fb_like_settings['excl_post'])!='') {
		$excl_post_array = explode(",", $fb_like_settings['excl_post']);
		for ( $i = 0; $i < count($excl_post_array); $i++ ) {
			$excl_post_array[$i] = trim($excl_post_array[$i]);
			if(is_single($excl_post_array[$i])==true or is_page($excl_post_array[$i])==true)
				return $content;
		}	
	}
	
	/* Exclude categories */
	if(trim($fb_like_settings['excl_cat'])!='') {	
		$excl_cat_array = explode(",", $fb_like_settings['excl_cat']);	
		for ( $i = 0; $i < count($excl_cat_array); $i++ ) {
			$excl_cat_array[$i] = trim($excl_cat_array[$i]);
			if(in_category($excl_cat_array[$i])==true)
				return $content;
		}
	}
 
    /* Show the button where user wants to */
    if($fb_like_settings['showattop']=='true')
		$content = generate_button() . $content;

    if($fb_like_settings['showatbottom']=='true')
	    $content .= generate_button();
	    
	return $content;
}

function fb_like_count_margin() {
	global $fb_like_settings;

	return $fb_like_settings['margin_top'] . 'px '
		. $fb_like_settings['margin_right'] . 'px ' 
		. $fb_like_settings['margin_bottom'] . 'px '
		. $fb_like_settings['margin_left'] . 'px';
}

/* Generate code for Google +1 Button. Nothing more. */
function fb_like_generate_google1() {
	global $fb_like_settings;
	
	if($fb_like_settings['google1']=='true') {
		switch($fb_like_settings['layout']) {
			case "standard":
				return '<div style="float: right; margin: ' . fb_like_count_margin() . '"><g:plusone href="' . get_permalink() . '"></g:plusone></div>';
				break;					
			case "button_count":
				return '<div style="float: right; margin: ' . fb_like_count_margin() . '"><g:plusone size="medium" href="' . get_permalink() . '"></g:plusone></div>';
				break;
			case "box_count":
				return '<div style="float: right; margin: ' . fb_like_count_margin() . '"><g:plusone size="tall" href="' . get_permalink() . '"></g:plusone></div>';
				break;
		}
	}
}

/* Return button's body (to fb_like_button() and shortcode [fb-like-button]) */
function generate_button() {
	global $fb_like_settings;
	
	$margin = fb_like_count_margin();

	if($fb_like_settings['xfbml']) {
		/* XFBML VERSION */
		global $locale;
		
		$fblike = '<div class="fb-like" data-href="' . get_permalink() . '" data-send="' . (($fb_like_settings['send']=='true')?'true':'false') . '" data-layout="' . $fb_like_settings['layout'] . '" data-width="' . $fb_like_settings['width'] . '" show_faces="'. (($fb_like_settings['showfaces']=='true')?'true':'false') . '" data-action="' . $fb_like_settings['verb'] . '"' . (($fb_like_settings['colorscheme']=='dark')?' data-colorscheme="dark" ':'') . ' data-font="' . $fb_like_settings['font'] . '" style="margin: ' . $margin . ';' . (($fb_like_settings['css_style']!='') ? ' ' . $fb_like_settings['css_style'] : '') . '"></div>';
		
		if ($fb_like_settings['valid'])
			return '
	<span class="fbreplace">
	<!-- FBML ' . $fblike . ' FBML -->
	</span>
	' . fb_like_generate_google1() . '
'; else return  $fblike . fb_like_generate_google1();
		/* END OF XFBML VERSION */
	}
	else {
		/* STANDARD (NON-XFBML) VERSION */	
		switch($fb_like_settings['layout']) {
			case "standard":
				$height = (($fb_like_settings['showfaces']=='true')?80:35);
				break;
			case "button_count":
				$height = 21;
				break;
			case "box_count":
				$height = 65;
				break;
		}

		return '<iframe class="fblikes" src="http://www.facebook.com/plugins/like.php?href=' . get_permalink() . '&amp;send=false&amp;layout=' . $fb_like_settings['layout'] . '&amp;width=' . $fb_like_settings['width'] . '&amp;show_faces=' . (($fb_like_settings['showfaces']=='true')?'true':'false') . '&amp;action=' . $fb_like_settings['verb'] . '&amp;colorscheme=' . $fb_like_settings['colorscheme'] . '&amp;' . (($fb_like_settings['font']!='') ? 'font='. $fb_like_settings['font'] : 'font') . '&amp;height=' . $height . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $fb_like_settings['width'] . 'px; height:' . $height . 'px; margin: ' . $margin . ';' . (($fb_like_settings['css_style']!='') ? ' ' . $fb_like_settings['css_style'] : '') . '" allowTransparency="true"></iframe>' . fb_like_generate_google1();
		/* END OF STANDARD (NON-XFBML) VERSION */
	}
}

/* Widget Facebook Like Box */
class fb_like_widget extends WP_Widget {
    /** constructor */
    function fb_like_widget() {
        parent::WP_Widget(false, $name = 'Facebook Like Box');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
    	global $fb_like_settings;
    	
        extract( $args );
        $title     = apply_filters('fly-fb-likebox', $instance['fb_likebox_title']);
        $url       = apply_filters('fly-fb-likebox', $instance['fb_likebox_url']);
        $width     = apply_filters('fly-fb-likebox', $instance['fb_likebox_width']);
        $showfaces = $instance['fb_likebox_showfaces'];
        $stream    = $instance['fb_likebox_stream'];
        $header    = $instance['fb_likebox_header'];
        
        echo $before_widget;
         
        if ( $title )
        	echo $before_title . $title . $after_title;
                  
        if($fb_like_settings['xfbml']) {
        	/* XFBML VERSION OF LIKE BOX */
			$fblikebox = '<div class="fb-like-box" data-href="' . $url . '" data-width="' . $width . '"' . ($fb_like_settings['colorscheme']=="dark" ? ' data-colorscheme="dark"' : '') . ' data-show-faces="' . ($showfaces ? 'true' : 'false') . '" data-stream="' . ($stream ? 'true' : 'false') . '" data-header="' . ($header ? 'true' : 'false') . '"></div>';
         	
         	if ($fb_like_settings['valid'])
			echo '
	<span class="fbreplace">
	<!-- FBML ' . $fblikebox . ' FBML -->
	</span>
'; else
			echo $fblikebox;
       	}
        else {
            /* IFRAME VERSION OF LIKE BOX */
            
            // Count height in so lame way. :X
            if ( $stream && $header && $showfaces )
            	$height = 590;
            elseif ( $stream && $header )
            	$height = 420;
            elseif ( $stream && $showfaces )
            	$height = 558;	
            elseif ( $header && $showfaces )
            	$height = 290; 	
            elseif ( $stream )
            	$height = 395;
            elseif ( $showfaces )
            	$height = 258;
            else $height = 62;	
            
			echo '<iframe src="http://www.facebook.com/plugins/likebox.php?href=' . $url . '&amp;width=' . $width . '&amp;colorscheme=' . $fb_like_settings['colorscheme'] . '&amp;show_faces=' . ($showfaces ? 'true' : 'false') . '&amp;border_color&amp;stream=' . ($stream ? 'true' : 'false') . '&amp;header=' . ($header ? 'true' : 'false') . '&amp;height=' . $height . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $width . 'px; height:' . $height . 'px;" allowTransparency="true"></iframe>';
        }
         	
       		echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['fb_likebox_title']     = strip_tags($new_instance['fb_likebox_title']);
		$instance['fb_likebox_url']       = strip_tags($new_instance['fb_likebox_url']);
		$instance['fb_likebox_width']     = strip_tags($new_instance['fb_likebox_width']);
		$instance['fb_likebox_showfaces'] = (isset($new_instance['fb_likebox_showfaces']) ? 1 : 0);
		$instance['fb_likebox_stream']    = (isset($new_instance['fb_likebox_stream']) ? 1 : 0);
		$instance['fb_likebox_header']    = (isset($new_instance['fb_likebox_header']) ? 1 : 0);
	
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	
    
    	global $fb_like_settings;
    	
    	/* Some default widget settings */
		$defaults = array( 'fb_likebox_width' => 292,
		                   'fb_likebox_height' => 427,
		                   'fb_likebox_stream' => 1,
		                   'fb_likebox_header' => 1 );
						   
		$instance = wp_parse_args( (array) $instance, $defaults );
    			
        $title     = esc_attr($instance['fb_likebox_title']);
        $url       = esc_attr($instance['fb_likebox_url']);
        $width     = esc_attr($instance['fb_likebox_width']);
        $showfaces = isset($instance['fb_likebox_showfaces']) ? 1 : 0;
        $stream    = isset($instance['fb_likebox_stream']) ? 1 : 0;
        $header    = isset($instance['fb_likebox_header']) ? 1 : 0;
        
        ?>
            <p><label for="<?php echo $this->get_field_id('fb_likebox_title'); ?>"><?php _e("Title:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_title'); ?>" name="<?php echo $this->get_field_name('fb_likebox_title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

            <p><label for="<?php echo $this->get_field_id('fb_likebox_url'); ?>"><?php _e("Facebook Page URL:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_url'); ?>" name="<?php echo $this->get_field_name('fb_likebox_url'); ?>" type="text" value="<?php echo $url; ?>" /></label><br /><small><?php _e("The URL of the FB Page for this Like box.", 'fb_like_trans_domain' ); ?></small></p>

            <p><label for="<?php echo $this->get_field_id('fb_likebox_width'); ?>"><?php _e("Width:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_width'); ?>" name="<?php echo $this->get_field_name('fb_likebox_width'); ?>" type="number" value="<?php echo $width; ?>" /></label><br /><small><?php _e("The width of the widget in pixels.", 'fb_like_trans_domain' ); ?></small></p>

            <p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_showfaces'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_showfaces' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_showfaces' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_showfaces' ); ?>">&nbsp;<?php _e("Show Faces", 'fb_like_trans_domain' ); ?></label></p>

            <p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_stream'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_stream' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_stream' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_stream' ); ?>">&nbsp;<?php _e("Stream", 'fb_like_trans_domain' ); ?></label><br /><small><?php _e("Show the profile stream for the public profile.", 'fb_like_trans_domain' ); ?></small></p>

			<p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_header'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_header' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_header' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_header' ); ?>">&nbsp;<?php _e("Header", 'fb_like_trans_domain' ); ?></label><br /><small><?php _e("Show the 'Find us on Facebook' bar at top.<br /><small>Only when either stream or connections are present.</small>", 'fb_like_trans_domain' ); ?></small></p>

        <?php 
    }
}

/* Admin menu page linked to fb_plugin_options() */
function fb_like_admin_menu() {
    add_options_page('Facebook Likes You! Options', 'Facebook Likes You!', 8, __FILE__, 'fb_plugin_options');
}

function fb_plugin_options() {
    global $fb_like_layouts;
    global $fb_like_verbs;
    global $fb_like_font;
    global $fb_like_colorschemes;
    global $fb_like_aligns;
?>

    <div class="wrap">
    <h2>Facebook Likes You! <small>by <a href="http://wolnaelelekcja.pl/" target="_blank">Piotr Sochalewski</a></small></h2>

    <form method="post" action="options.php">

    <?php settings_fields('fb_like'); ?>
	
    <table class="form-table">
    	<?php if (@fopen("http://wolnaelekcja.pl/facebook-likes-you.html", "r")) {
    		echo "<tr valign=\"top\"><th scope=\"row\" colspan=\"2\"><h3>";
    		_e("Important info for users", 'fb_like_trans_domain' );
    		echo "</h3></th></tr><tr valign=\"top\"><th scope=\"row\" colspan=\"2\"><iframe src=\"http://wolnaelekcja.pl/facebook-likes-you.html\" width=\"100%\" height=\"75\"><p>Your browser does not support iframes.</p></iframe></th></tr>"; }
    	?>
    		
        <tr valign="top">
            <th scope="row"><h3><?php _e("Appearance", 'fb_like_trans_domain' ); ?></h3>
			</th>
		</tr>
        <tr valign="top">
            <th scope="row"><?php _e("Width:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="4" type="text" name="fb_like_width" style="text-align:right" value="<?php echo get_option('fb_like_width'); ?>" />&nbsp;<small>px</small></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Layout:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_layout">
                <?php
                    $curmenutype = get_option('fb_like_layout');
                    foreach ($fb_like_layouts as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
				</select><br />
				<input id="faces" type="checkbox" name="fb_like_showfaces" value="true" <?php echo (get_option('fb_like_showfaces') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="faces"><?php _e("Show Faces", 'fb_like_trans_domain' ); ?></label>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Verb to display:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_verb">
                <?php
                    $curmenutype = get_option('fb_like_verb');
                    foreach ($fb_like_verbs as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Font:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_font">
                <?php
                    $curmenutype = get_option('fb_like_font');
                    foreach ($fb_like_font as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Color Scheme:", 'fb_like_trans_domain' ); ?></th>
			<td>
                <select name="fb_like_colorscheme">
                <?php
                    $curmenutype = get_option('fb_like_colorscheme');
                    foreach ($fb_like_colorschemes as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Fix for W3C Valid XFBML:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_valid" value="true" <?php echo (get_option('fb_like_valid') == 'true' ? 'checked' : ''); ?>/></td>
		</tr>
		<tr valign="top">
            <th scope="row"><?php _e("Send Button:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_send" value="true" <?php echo (get_option('fb_like_send') == 'true' ? 'checked' : ''); ?>/> <small><?php _e("It allows your users to easily send your content to their friends. <strong>Requires XFBML</strong>.", 'fb_like_trans_domain' ); ?></small></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Google +1 Button:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_google1" value="true" <?php echo (get_option('fb_like_google1') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Use <span title='XFBML version is more versatile, but requires use of the JavaScript SDK' style='border-bottom: 1px dotted #CCC; cursor: help; '>XFBML</span>:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_xfbml" value="true" <?php echo (get_option('fb_like_xfbml') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Use Open Graph:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_opengraph" value="true" <?php echo (get_option('fb_like_opengraph') == 'true' ? 'checked' : ''); ?>/> <small><?php _e("Mainly fixes an issue with wrong thumbnails. Turn off if you have another Open Graph plugin.", 'fb_like_trans_domain' ); ?><br /><?php _e("Absolute path to default picture (used if not found any):", 'fb_like_trans_domain' ); ?>&nbsp;<input size="40" type="text" id="fb_like_defaultpic" name="fb_like_defaultpic" style="font-size: 11px; height: 18px;" value="<?php echo get_option('fb_like_defaultpic'); ?>" />&nbsp;<a onclick="document.getElementById('fb_like_defaultpic').value='<?php echo fb_like_pluginPath('images/facebook.png') ?>'">[ <?php _e("Back to default", 'fb_like_trans_domain' ); ?> ]</a></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Your app ID:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="21" type="text" name="fb_like_appid" value="<?php echo get_option('fb_like_appid'); ?>" /> <br /><small><?php _e("If you have no app ID, you can leave this empty to use default Facebook app ID <code>165570960176857</code>,<br />but remember that you can get your own an app ID by <a href='https://www.facebook.com/developers/createapp.php' target='_blank'>registering your application</a>.", 'fb_like_trans_domain' ); ?></small></td>
        </tr>
        <tr valign="top">
            <th scope="row"><h3><?php _e("Position", 'fb_like_trans_domain' ); ?></h3></th>
		</tr>
		<tr valign="top">
			<th scope="row" colspan="2" style="line-height: 100%;"><small><?php _e("Remember that you can place it manually by <code>[fb-like-button]</code>. There is PHP for that, but you can't use it in WordPress post editor - <code>&lt;?php echo do_shortcode('[fb-like-button]'); ?&gt;</code>.", 'fb_like_trans_domain' ); ?></small></th>
		</tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show at:", 'fb_like_trans_domain' ); ?></th>
            <td>
            	<input id="top" type="checkbox" name="fb_like_show_at_top" value="true" <?php echo (get_option('fb_like_show_at_top') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="top"><?php _e("Top", 'fb_like_trans_domain' ); ?></label><br />
            	<input id="bottom" type="checkbox" name="fb_like_show_at_bottom" value="true" <?php echo (get_option('fb_like_show_at_bottom') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="bottom"><?php _e("Bottom", 'fb_like_trans_domain' ); ?></label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on:", 'fb_like_trans_domain' ); ?></th>
            <td>
            	<input id="page" type="checkbox" name="fb_like_show_on_page" value="true" <?php echo (get_option('fb_like_show_on_page') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="page"><?php _e("Page", 'fb_like_trans_domain' ); ?></label><br />
            	<input id="post" type="checkbox" name="fb_like_show_on_post" value="true" <?php echo (get_option('fb_like_show_on_post') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="post"><?php _e("Post", 'fb_like_trans_domain' ); ?></label><br />
            	<input id="home" type="checkbox" name="fb_like_show_on_home" value="true" <?php echo (get_option('fb_like_show_on_home') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="home"><?php _e("Home", 'fb_like_trans_domain' ); ?></label><br />
            	<input id="search" type="checkbox" name="fb_like_show_on_search" value="true" <?php echo (get_option('fb_like_show_on_search') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="search"><?php _e("Search", 'fb_like_trans_domain' ); ?></label><br />
            	<input id="archive" type="checkbox" name="fb_like_show_on_archive" value="true" <?php echo (get_option('fb_like_show_on_archive') == 'true' ? 'checked' : ''); ?>/>&nbsp;<label for="archive"><?php _e("Archive", 'fb_like_trans_domain' ); ?></label>
            </td>
        </tr>
        <tr valign="top">
        	<th scope="row"><?php _e("Margins:", 'fb_like_trans_domain' ); ?></th>
        	<td>
        		<div style="width: 470px; border: 1px solid #E3E3E3; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; text-align: center; padding: 5px; ">
				<span><input size="2" type="text" name="fb_like_margin_top" style="text-align:right" value="<?php echo get_option('fb_like_margin_top'); ?>" />&nbsp;<small>px</small></span>
					<br />
					<span style="float: left; margin-top: 3px;"><input size="2" type="text" name="fb_like_margin_left" style="text-align:right" value="<?php echo get_option('fb_like_margin_left'); ?>" />&nbsp;<small>px</small></span>
					<img src="<?php echo fb_like_pluginPath('images/button.jpg'); ?>" style="margin-top: 10px; margin-bottom: 7px;">
					<span style="float: right; margin-top: 3px;"><input size="2" type="text" name="fb_like_margin_right" style="text-align:right" value="<?php echo get_option('fb_like_margin_right'); ?>" />&nbsp;<small>px</small></span>
					<br />
					<span><input size="2" type="text" name="fb_like_margin_bottom" style="text-align:right" value="<?php echo get_option('fb_like_margin_bottom'); ?>" />&nbsp;<small>px</small></span>
				</div>
			</td>
        </tr>
        
		<tr valign="top">
            <th scope="row"><?php _e("Exclude posts and pages:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="50" type="text" name="fb_like_excl_post" value="<?php echo get_option('fb_like_excl_post'); ?>" /> <br /><small><?php _e("You can type for each post/page ID, title, or slug seperated with commas.<br />E.g. <code>17, Irish Stew, beef-stew</code>.", 'fb_like_trans_domain' ) ?></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Exclude categories:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="50" type="text" name="fb_like_excl_cat" value="<?php echo get_option('fb_like_excl_cat'); ?>" /> <br /><small><?php _e("You can type for each category ID, name, or slug seperated with commas.<br />E.g. <code>9, Stinky Cheeses, blue-cheese</code>.", 'fb_like_trans_domain' ) ?></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Additional CSS style:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="80" type="text" name="fb_like_css_style" value="<?php echo get_option('fb_like_css_style'); ?>" /> <br /><small><?php _e("Added properties will be placed between <code>style=\"</code> and <code>\"</code>. If you want refer to Like button in e.g. <strong>style.css</strong>,<br />try to use <code>iframe.fblikes</code> or if you use XFBML <code>.fb_edge_widget_with_comment</code>.", 'fb_like_trans_domain' ) ?><small></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><h3><?php _e("Help and Support", 'fb_like_trans_domain' ); ?></h3></th>
		</tr>

		<tr valign="top">
            <th scope="row" colspan="2"><small><?php _e("<strong>Free tip:</strong> You can get easily <a href='http://www.facebook.com/insights/' target='_blank'>insights for your website</a> supplied by Facebook.", 'fb_like_trans_domain' ); ?></small></th>
        </tr>
        <tr valign="top">
            <th scope="row" colspan="2"><a href="http://wolnaelekcja.pl/wp-facebook-likes-you" target="_blank"><?php _e("Read the plugin homepage and its comments", 'fb_like_trans_domain' ); ?></a></th>
        </tr>
    </table>
    
	<p class="submit">
		<a class="button-primary" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sochalewski%40gmail%2ecom&lc=PL&item_name=Facebook%20Likes%20You%21&item_number=fly%2ddonate&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank"><?php _e("Donate to this plugin", 'fb_like_trans_domain' ); ?></a>
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	
</form>
</div>
<?php
}

fb_like_init();
?>