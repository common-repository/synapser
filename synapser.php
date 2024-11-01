<?php
/*
Plugin Name: Synapser
Plugin URI: http://www.synapser.net
Description: Synapser plugin enable Synapser.net Search Engine Dynamic Optimization "SEDO" functionalities on your Wordpress site.
Version: 1.4.2
Author: Synapser Team
Author URI: http://www.synapser.net
License: GPL2
	Copyright 2014-2017 Synapser.net  (email : info@synapser.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//skip ajax calls
$is_ajax = false;
if (defined('DOING_AJAX') && DOING_AJAX) { 
	$is_ajax = true;
}

if ( !$is_ajax ) {

	/**
	 * Initialize global vars
	*/

	//get synapser admin options settings
	$synapserOptions = get_option('synapser_settings');

	//instantiate the API Client
	if( $synapserOptions['use_curl'] == true ){
		include ('SynapserV1APICURL.php'); //Synapser API V1
		$synapserClient = new SynapserV1APICURL($synapserOptions['hash_key'], $synapserOptions['domain_key'], $synapserOptions['debug_enabled']);
	}
	else{
		include ('SynapserV1API.php'); //Synapser API V1
		$synapserClient = new SynapserV1API($synapserOptions['hash_key'], $synapserOptions['domain_key'], $synapserOptions['debug_enabled']);
	}

	//disable synapser for backend and login
	if ( !is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ){
		//get current page metadata
		$synapserPageMeta = $synapserClient->getCurrentPageMeta();
	}

	
	/**
	 * Include plugin files and lib
	*/
	include ('adminoptions.php'); //Admin Options
	include ('plugin.php'); //Plugin script

}