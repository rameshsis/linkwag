<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
add_action('wp_ajax_ajax', 'grv_callbacks');

function img_src($img){
    return PURL . '/img/' . $img;
}

function grv_get_post_types(){
    
    $types = get_post_types();
    unset($types['attachment']);
    unset($types['revision']);
    unset($types['nav_menu_item']);
    
    return $types;
}

function grv_type_posts($type){
    global $wpdb; 
    $result = $wpdb->get_results( "select * from $wpdb->posts where post_type='$type' AND post_status='publish'" );
    return $result;
}

function wag_post($post_id,$state){
    $key = 'linkwag'.$post_id;
    update_post_meta($post_id, $key,$state);
    if($state)
        grv_save_post($post_id);
		
    else
        grv_remove_post($post_id);
    
}

function post_is_wagged($post_id){
    $key = 'linkwag'.$post_id;
	/* if(get_option('icon_post') == '1'){
	add_filter( 'the_content', 'like_content' ); */
		/* global $post;
		 $post = &get_post($post_id);
		the_content(); 
		$original = get_post_field('post_content', $post_id);
		$original = $post->post_content;
		$content = $original;
		$content .= "<div class=\"ikaz\">";
		$content .= '<img src="' . plugins_url( 'img/logo.jpg' , dirname(__FILE__) ) . '" >' ;
		$content .= "</div>"; */
		//}
	if( get_post_meta($post_id, $key,true) == ''){
		 update_post_meta($post_id, $key,1);
		 return 1;
	}
	
    return get_post_meta($post_id, $key,true);
}

function grv_remove_post($id){
    $postData = array('action'=>'del','id'=>$id,'domain'=>$_SERVER['SERVER_ADDR']);
   $post = curl_init();
	curl_setopt($post, CURLOPT_URL,API);
    curl_setopt($post, CURLOPT_POST, 1);
    curl_setopt($post, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    //$result = curl_exec($post);
    curl_close($post);
    $result; exit;
}

function grv_save_post($id){
    $wpPost = get_post($id);
	$list = array();
    $list = get_plugins_list(  $format, $show_inactive, $cache, $nofollow, $target );
    if($wpPost->post_status != 'publish') return;
    if(get_option('icon_post') == '1'){
		$s = '<img src="' . plugins_url( 'img/logo.jpg' , dirname(__FILE__) ) . '" > ';
	}
    else {
		$s ='';
	}
	if($wpPost->post_status=='publish'){
	$status=1;
	
	}
	elseif($wpPost->post_status=='draft'){
	$status=2;
	}
	elseif($wpPost->post_status=='trash'){
	$status=3;
	}
	
	
    $postData = array(
        'action'    => 'save',
        'id'        => urlencode($id),
        'title'     => urlencode($wpPost->post_title),
        'content'   => urlencode($wpPost->post_content),
		'posttype'   => urlencode($wpPost->post_type),
        'excerpt'   => urlencode($wpPost->post_excerpt),
        'slug'      => urlencode($wpPost->post_name),
        'link'      => get_permalink($id),
        'thumb'     => wp_get_attachment_url( get_post_thumbnail_id($id),'full'),
        'created'   => $wpPost->post_date,
        'category'  => serialize(grv_get_post_categories($id)),
        'tags'      => serialize(grv_get_post_tags($id)),
        'comments'  => serialize(grv_get_post_comments($id)),
        'status'    => $status,
        'domain'    => $_SERVER['SERVER_NAME'],
		'plugins_list' => $list,
		'linkwag_email' => get_option('linkwag_email'),
		'comment_count' =>count_comments($id),
		'look_number' =>'0',
		'rght' =>'0',
		'lft' =>'0',
		'quacks'=>'0',
		'sort_order' =>'0',
		'parent_id'=>'0'  
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
      // echo $result; exit;
		return $result;
	   
	


}

function grv_get_post_categories($post_id){
    $post_categories = wp_get_post_categories( $post_id );
    $cats = array();

    foreach($post_categories as $c){
            $cat = get_category( $c );
            $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug,'description'=>$cat->description);
    }
    return $cats;
}

function grv_get_post_tags($id){
    $tags = wp_get_post_tags($id);
    $tag = array();
    foreach($tags as $item){
        $tag[]= array( 'name' => $item->name, 'slug' => $item->slug );
                
    }
    return $tag;
}

function grv_get_post_comments($id){
    
    $comments  = get_comments(array('post_id' => $id,'status' => 'approve'));
    $data = array();
    foreach($comments as $comment){
        $data  = array('author'=>$comment->comment_author,'content'=>$comment->comment_content);
    }
    return $data;
}

function count_comments($id)
{
	$number = wp_count_comments($id); 
	 $total_comment = $number->approved; 
	return $total_comment;
}

function grv_callbacks(){ 
    
    extract($_POST);
    switch($fb){
        case 'wag_post': 
            wag_post($id,$state);
            break;
        default:
            break;
    }
}
function get_plugins_list( $format = '', $show_inactive = false, $cache = 1, $nofollow = false, $target = '' ) {
define( 'APL_DEFAULT_PLUGIN_FORMAT', '<li>#LinkedTitle# by #LinkedAuthor#.</li>' );
	if ( '' == $format ) { $format = APL_DEFAULT_PLUGIN_FORMAT; }



	if ( $nofollow ) { $nofollow = ' rel="nofollow"'; } else { $nofollow = ''; }
	if ( '' != $target ) { $target = ' target="' . $target . '"'; } else { $target = ''; }



	$plugins = apl_get_plugin_data( $cache );



	$output = '';

	foreach( $plugins as $plugin_file => $plugin_data ) {

		if ( $show_inactive || is_plugin_active( $plugin_file ) )  {
			$output .= format_plugin_list( $plugin_data, $format, $nofollow, $target );
		}
	}


	return "\n<!-- Plugins List v" . plugins_list_version . " -->\n" . $output . "\n<!-- End of YouTube Embed code -->\n";

}

function apl_get_plugin_data( $cache ) {


	if ( !$cache ) { $cache = 'no'; }

	$plugins = false;
	$cache_key = 'plugins_list';
	if ( is_numeric( $cache ) ) { $plugins = get_transient( $cache_key ); }


	if ( !$plugins ) {
		$plugins = get_plugins();
		if ( ( '' != $plugins ) && ( is_numeric( $cache ) ) ) { set_transient( $cache_key, $plugins, 3600 * $cache ); }
	}

	return $plugins;
}


function format_plugin_list( $plugin_data, $format, $nofollow, $target ) {


	$plugins_allowedtags1 = array( 'a' => array( 'href' => array(), 'title' => array() ), 'abbr' => array( 'title' => array() ), 'acronym' => array( 'title' => array() ), 'code' => array(), 'em' => array(), 'strong' => array() );

	$plugins_allowedtags2 = array( 'abbr' => array( 'title' => array() ), 'acronym' => array( 'title' => array() ), 'code' => array(), 'em' => array(), 'strong' => array() );


	$plugin_data[ 'Title' ] = wp_kses( $plugin_data[ 'Title' ], $plugins_allowedtags1 );
	$plugin_data[ 'PluginURI' ] = wp_kses( $plugin_data[ 'PluginURI' ], $plugins_allowedtags1 );
	$plugin_data[ 'AuthorURI' ] = wp_kses( $plugin_data[ 'AuthorURI' ], $plugins_allowedtags1 );
	$plugin_data[ 'Version' ] = wp_kses( $plugin_data[ 'Version' ], $plugins_allowedtags1 );
	$plugin_data[ 'Author' ] = wp_kses( $plugin_data[ 'Author' ], $plugins_allowedtags1 );


	$format = replace_tags( $plugin_data, $format, $nofollow, $target );

	return $format;
}


function replace_tags( $plugin_data, $format, $nofollow, $target ) {

	$format = str_replace( '#Title#', $plugin_data[ 'Title' ], $format );
	$format = str_replace( '#PluginURI#', $plugin_data[ 'PluginURI' ], $format );
	$format = str_replace( '#AuthorURI#', $plugin_data[ 'AuthorURI' ], $format );
	$format = str_replace( '#Version#', $plugin_data[ 'Version' ], $format );
	$format = str_replace( '#Description#', $plugin_data[ 'Description' ], $format );
	$format = str_replace( '#Author#', $plugin_data[ 'Author' ], $format );

	$format = str_replace( '#LinkedTitle#', "<a href='" . $plugin_data[ 'PluginURI' ] . "' title='" . $plugin_data[ 'Title' ] . "'" . $nofollow . $target . ">" . $plugin_data[ 'Title' ] . "</a>", $format );
	$format = str_replace( '#LinkedAuthor#', "<a href='" . $plugin_data[ 'AuthorURI' ] . "' title='" . $plugin_data[ 'Author' ] . "'" . $nofollow . $target . ">" . $plugin_data[ 'Author' ] . "</a>", $format );

	return $format;
}
?>