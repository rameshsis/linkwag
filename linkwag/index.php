<?php
/*
Plugin Name: LinkWag
Plugin URI: http://www.smartinfosystem.com
Description: Wag your blog pages and posts to Linwag
Author: Ankur Gupta
Version: 1.0
Author URI: http://www.smartinfosystem.com
*/

define('_ns','Linkwag');


define('LINKWAG_ROOT',dirname(__FILE__));
define('DS',DIRECTORY_SEPARATOR);
define('US','/');
require_once(LINKWAG_ROOT.DS.'config.php');$this_file = __FILE__;$update_check = "http://www.wptutions.com/ankur/newlinkwag/wp-content/plugins/linkwag/myplugin.txt";require_once(LINKWAG_ROOT.DS.'updates.php');
register_deactivation_hook( __FILE__, 'myplugin_deactivate' );
function myplugin_deactivate(){	
update_option('save_changes',0);
 $postData = array(
        'action'    => 'delete_post',     
        'domain'    =>URL
    );
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL,API);
        curl_setopt($post, CURLOPT_POST, true);
        curl_setopt($post, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($post, CURLOPT_HEADER, 1);  
        $result = curl_exec($post);
		//print_r($result);
		
        curl_close($post);
       //echo $result; 
	   //exit;
		return $result;

}

function myplugin_activate() {			update_option('wag_prev_link',1);            update_option('wag_new_post_auto',1);    	update_option('wag_new_page_auto',1);   	update_option('icon_post',1);      	update_option('icon_page',1);  	}register_activation_hook( __FILE__, 'myplugin_activate' );register_uninstall_hook( __FILE__, 'myplugin_uninstal' );function myplugin_uninstal(){$df = get_theme_root();$my_file = $df."/linkwag.txt";unlink($my_file);update_option('linkwag_email','');update_option('linkwag_unique','');update_option('linkwag_uni','');update_option('y_login','');}
$setting = new settings();
add_action( 'wp_ajax_fb_flash','fbpost');
add_action( 'wp_ajax_nopriv_fb_flash','fbpost');
function fbpost(){
    $id=1011;
    $post = get_post($id);
    die($post->post_content);
}
if(get_option('icon_post') == '1' || get_option('icon_page') == '1'){
add_action('init','add_logo');
}
function add_logo(){

add_filter('the_content','like_content');

}

function like_content($content){
global $post;
$original = '';
if(get_option('icon_post')==1){

		 if ($post->post_type == 'post') {
			
			$content .= $original;			
			$content .= '<div class=logo_img>';				
			$content .= '<img src="' . plugins_url( 'linkwag/img/logo.jpg' , dirname(__FILE__) ) . '" >' ;					
			$content .= '</div>';	
			}
			}
			if(get_option('icon_page')==1){
			 if ($post->post_type == 'page') {
			
			$content .= $original;			
			$content .= '<div class=logo_img>';				
			$content .= '<img src="' . plugins_url( 'linkwag/img/logo.jpg' , dirname(__FILE__) ) . '" >' ;					
			$content .= '</div>';	
			}
			}
			
			return $content;
			}?>