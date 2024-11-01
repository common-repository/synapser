<?php

//add init action
add_action('init', 'synapser_load_plugin_textdomain');

//adds title tags to site - 20 priority to ensure last run
add_filter( 'wp_title', 'synapser_set_title', 10, 1 );

//adds meta description to <head>
add_action( 'wp_head', 'synapser_set_metas' );

//add tracking code on footer
add_action('wp_footer', 'synapser_add_tracking_code');

//removes meta description  from <head>
remove_action('wp_head', 'description');


/**
 * Initialize the plugin
 */
function synapser_load_plugin_textdomain() {
	// Localization
	load_plugin_textdomain( 'synapser', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


/**
 * Defines and sets the title tags based for the add_filter call
*/
function synapser_set_title($title){
	global $synapserPageMeta, $synapserOptions;
	
	if( $synapserOptions['title_mode'] == 'keep' ){
		return $title;
	}

	//test other options only if pageTitle not empty
	if (isset($synapserPageMeta->pageTitle) && trim($synapserPageMeta->pageTitle)!='') {
		$sy_title = trim($synapserPageMeta->pageTitle);
	
		if( $synapserOptions['title_mode'] == 'append' ){
			//append
			$title = $title . ' - ' . $sy_title;
		}
		else if( $synapserOptions['title_mode'] == 'prepend' ){
			//prepend
			$title = $sy_title . ' - ' . $title;
		}
		else{
			//replace
			$title = $sy_title;
		}
	}
	
	return $title;
}


/**
 * generates the meta description from the_content
*/
function synapser_set_metas() {
	global $synapserPageMeta;
	
	if (isset($synapserPageMeta->pageDescription) && trim($synapserPageMeta->pageDescription)!='') {
		print("<meta name='description' content='".$synapserPageMeta->pageDescription."' /> \n");
	}
	
	if (isset($synapserPageMeta->pageKeywords) && trim($synapserPageMeta->pageKeywords)!='') {
		print("<meta name='keywords' content='".$synapserPageMeta->pageKeywords."' /> \n");
	}
}


/**
 * Add Javascript block Tracking code on footer
*/
function synapser_add_tracking_code() {
	global $synapserClient;
	
	print($synapserClient->getTrackingCode());
}
