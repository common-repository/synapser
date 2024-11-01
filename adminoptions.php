<?php
/**
 * Generate the option page
*/
function synapser_option_page() {
	global $synapserOptions, $synapserClient;
	
	print('<div class="wrap"><H2>' . __('Synapser Options', 'synapser') . '</H2>') ;
	print('<form method="post" action="options.php">') ;
	settings_fields ( 'synapser_settings_group' );
	print('
		<H4>' . sprintf ( __('Synapser API Configuration (Please login to %s and go to Domains > your domain)', 'synapser'), '<a href="http://panel.synapser.net" target="_blank">panel.synapser.net</a>') . '</H4>
		<table>
		<tr><td><label class="description" for="synapser_settings[hash_key]">' . __('Hash Key:', 'synapser') . '</label></td>
		<td><input style="width:300px;" id="synapser_settings[hash_key]" name="synapser_settings[hash_key]" type="text" value="' . $synapserOptions ['hash_key'] . '"/></td></tr>
		<tr><td><label class="description" for="synapser_settings[domain_key]">' . __('Public Domain Key:', 'synapser') . '</label></td>
		<td><input style="width:300px;" id="synapser_settings[domain_key]" name="synapser_settings[domain_key]" type="text" value="' . $synapserOptions ['domain_key'] . '"/></td></tr>
		<tr><td><label class="description" for="synapser_settings[title_mode]">' . __('Title mode:', 'synapser') . '</label></td>
		<td>
			<select id="synapser_settings[title_mode]" name="synapser_settings[title_mode]">
				<option value="keep" ' . ($synapserOptions ['title_mode'] == 'keep' ? 'selected' : '') . '>' . __('Keep Original', 'synapser') . '</option>
				<option value="append" ' . ($synapserOptions ['title_mode'] == 'append' ? 'selected' : '') . '>' . __('Append', 'synapser') . '</option>
				<option value="prepend" ' . ($synapserOptions ['title_mode'] == 'prepend' ? 'selected' : '') . '>' . __('Prepend', 'synapser') . '</option>
				<option value="replace" ' . ($synapserOptions ['title_mode'] == 'replace' ? 'selected' : '') . '>' . __('Replace', 'synapser') . '</option>
			</select>
		</td></tr>
		<tr><td><label class="description" for="synapser_settings[use_curl]">' . __('Use cURL:', 'synapser') . '</label></td>
		<td><p style="font-size:10px;"><input id="synapser_settings[use_curl]" name="synapser_settings[use_curl]" type="checkbox" value="true" ' . ($synapserOptions ['use_curl'] == true ? 'checked="checked"' : '') . '/> (<strong>' . __('Warning:', 'synapser') . '</strong> ' . __('please verify that the PHP CURL module are enabled on the server', 'synapser') . ')</p></td></tr>
		<tr><td><label class="description" for="synapser_settings[debug_enabled]">' . __('Enable Debug:', 'synapser') . '</label></td>
		<td><p style="font-size:10px;"><input id="synapser_settings[debug_enabled]" name="synapser_settings[debug_enabled]" type="checkbox" value="true" ' . ($synapserOptions ['debug_enabled'] == true ? 'checked="checked"' : '') . '/> (<strong>' . __('Warning:', 'synapser') . '</strong> ' . __('please activate only for debug and for problem solving. This option must be always deactivated on a production environment', 'synapser') . ')</p></td></tr>
		<tr><td colspan="2" align="right"><input type="submit" class="button-primary" value="' . __('Save Options', 'synapser') . '"/></td></tr></table>
	');
	print('</form>');
	print('</div>');
	
	$testAllowUrlFopenEnabled='<span style="color:red;">' . __('Disabled, please set allow_url_fopen=On on PHP.ini', 'synapser') . '</span>';
	if( ini_get('allow_url_fopen') ) {
		$testAllowUrlFopenEnabled='<span style="color:green;">' . __('Enabled, your server can connect to web', 'synapser') . '</span>';
	}

	$synapserTestPageMeta = $synapserClient->getPageMeta("/");
	$testSynapserData='<span style="color:red;">' . __('Disabled, your site can\'t connect to ws.synapser.net', 'synapser') . '</span>';
	if( $synapserTestPageMeta != null){
		$testSynapserData='<span style="color:green;">' . __('Enabled, your site is connected to ws.synapser.net', 'synapser') . '</span>';
	}

	$testCurlEnabled='<span style="color:red;">' . __('Disabled, please enable cURL on PHP.ini or not flag the Use cURL option', 'synapser') . '</span>';
	if( function_exists('curl_version') ) {
		$curlVersion = curl_version();
		$testCurlEnabled='<span style="color:green;">' . sprintf ( __('Enabled, cURL version: %s', 'synapser'), $curlVersion['version'] ) . '</span>';
	}
	
	
	print('<div class="wrap"><H2>' . __('Synapser API Diagnostics', 'synapser') . '</H2>');
	print('<H4>' . __('Look if your site can connect to Synapser API Server:', 'synapser') . '</H4>');
	print('
	<table>
	<tr>
		<td><label class="description">' . __('Allow URL fopen:', 'synapser') . '</label></td>
		<td>'.$testAllowUrlFopenEnabled.'</td>
	</tr>
	<tr>
		<td><label class="description">' . __('Synapser Connection:', 'synapser') . '</label></td>
		<td>'.$testSynapserData.'</td>
	</tr>
	<tr>
		<td><label class="description">' . __('cURL:', 'synapser') . '</label></td>
		<td>'.$testCurlEnabled.'</td>
	</tr>
	</table>
	');
	print('</div>');
}


/**
 * Add menu item
 */
function synapser_menu() {
	add_options_page ( 'Synapser: Options', 'Synapser', 'manage_options', 'synapser-options', 'synapser_option_page' );
}
add_action ( 'admin_menu', 'synapser_menu' );


/**
 * Add help to the plugin option page
 */
function synapser_plugin_help($contextual_help, $screen_id, $screen) {
	get_current_screen()->add_help_tab ( 
		array (
			'id' => 'overview',
			'title' => __( 'Overview', 'synapser' ),
			'content' => '<p><strong>' . esc_html__( 'What\'s Synapser?', 'synapser' ) . '</strong></p>' . 
			'<p>' . esc_html__( 'Synapser, the most effective Search Engine Dynamic Optmization "SEDO" application on the market to place your web site at the top of the list, however it may be coded, in all search enigines by harvesting your visitors\' most relevant keywords automatically and weaving them directly, in the right place, into each and every page in real time, at each visit!', 'synapser' ) . '</p>' .
			'<p>' . esc_html__( 'Synapser analyses the behaviour of the most important search engines and the Synapser algorithm adapts your site to take most advantage of them.', 'synapser' ) . '</p>' .
			'<p>' . esc_html__( 'When a visitor is sent to your web site, Synapser harvests the keywords which your visitor used to reach that page and then serves them up woven into that page to all subsequent visitors, (within user manageable title, meta-tag, description etc.) including web crawler searches, so improving ranking.', 'synapser' ) . '</p>' . 
			'<p>' . sprintf ( __( 'More informations on %s.', 'synapser' ), '<a href="http://www.synapser.net/" target="_blank">Synapser.net</a>' ) . '</p>' 
		) 
	);
	
	get_current_screen()->add_help_tab ( 
		array (
			'id' => 'setup-signup',
			'title' => __( 'New to Synapser', 'synapser' ),
			'content' => '<p><strong>' . esc_html__( 'Synapser Setup', 'synapser' ) . '</strong></p>' . 
			'<p>' . esc_html__( 'You need to enter an Hash key and Public Domain Key to activate the Synapser SEDO service on your site.', 'synapser' ) . '</p>' . 
			'<p>' . sprintf ( __( 'Signup for an account on %s to setup the service.', 'synapser' ), '<a href="http://panel.synapser.net/" target="_blank">Synapser.net</a>' ) . '</p>' 
		)
	);
	
	get_current_screen()->add_help_tab(
		array(
			'id'		=> 'setup-manual',
			'title'		=> __( 'Enter the Keys', 'synapser'),
			'content'	=>
			'<p><strong>' . esc_html__( 'Synapser Setup', 'synapser') . '</strong></p>' .
			'<p>' . esc_html__( 'If you already have the API keys', 'synapser') . '</p>' .
			'<ol>' .
			'<li>' . esc_html__( 'Copy and paste the data into the text fields.' , 'synapser') . '</li>' .
			'<li>' . esc_html__( 'Click the "Save Options" button.', 'synapser') . '</li>' .
			'</ol>',
		)
	);
		
	get_current_screen ()->set_help_sidebar ( 
		"<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/5VxTmKB9IxnVZF7j3rEqTQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>" . 
		'<p><strong>' . __( 'For more information:', 'synapser' ) . '</strong></p>' . 
		'<p><a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support" data-uv-forum-id="159378">' . __('Contact Support', 'synapser') . '</a></p>' . 
		'<p><a href="https://synapser.uservoice.com/knowledgebase" target="_blank">' . __('Knowledge Base', 'synapser') . '</a></p>' . 
		'<p><a href="https://synapser.uservoice.com/forums/159378-general" target="_blank">' . __('Support Forum', 'synapser') . '</a></p>' 
	);
}
add_filter ( 'contextual_help', 'synapser_plugin_help', 10, 3 );


/**
 * Add menu option group
 */
function synapser_register_settings() {
	register_setting ( 'synapser_settings_group', 'synapser_settings' );
}
add_action ( 'admin_init', 'synapser_register_settings' );
