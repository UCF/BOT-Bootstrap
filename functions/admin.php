<?php

if (is_login()){
	add_action('login_head', 'login_scripts', 0);
}

function shortcode_interface_html(){
	global $shortcode_tags;
	$shortcodes = $shortcode_tags;
	$ignore     = array(
		"wp_caption" => null,
		"caption"    => null,
		"gallery"    => null,
		"embed"      => null,
	);
	$shortcodes = array_diff_key($shortcodes, $ignore);
	ksort($shortcodes);
	?>
	<input type="hidden" name="shortcode-form" id="shortcode-form" value="<?=THEME_URL."/includes/shortcode-form.php"?>" />
	<input type="hidden" name="shortcode-text" id="shortcode-text" value="<?=THEME_URL."/includes/shortcode-text.php"?>" />
	<input type="text" name="shortcode-search" id="shortcode-search" placeholder="Find shortcodes..."/>
	<button type="button">Search</button>
		
	<ul id="shortcode-results" class="empty">
	</ul>
		
	<p>Or select:</p>
	<select name="shortcode-select" id="shortcode-select">
		<option value="">--Choose Shortcode--</option>
		<?php foreach($shortcodes as $name=>$callback):?>
		<option class="shortcode" value="<?=$name?>"><?=$name?></option>
		<?php endforeach;?>
	</select>
	
	<p>For more information about available shortcodes, please see the <a href="<?=get_admin_url()?>admin.php?page=theme-help#shortcodes">help documentation for shortcodes</a>.</p>
	<?php
}


function shortcode_interface(){
	add_meta_box('shortcodes-metabox', __('Shortcodes'), 'shortcode_interface_html', 'page', 'side', 'core');
	add_meta_box('shortcodes-metabox', __('Shortcodes'), 'shortcode_interface_html', 'post', 'side', 'core');
	foreach(Config::$custom_post_types as $type){
		$instance = new $type;
		if ($instance->options('use_editor')){
			add_meta_box('shortcodes-metabox', __('Shortcodes'), 'shortcode_interface_html', $instance->options('name'), 'side', 'core');
		}
	}
}
add_action('add_meta_boxes', 'shortcode_interface');


/**
 * Prints out additional login scripts, called by the login_head action
 *
 * @return void
 * @author Jared Lang
 **/
function login_scripts(){
	ob_start();?>
	<link rel="stylesheet" href="<?=THEME_CSS_URL?>/admin.css" type="text/css" media="screen" charset="utf-8" />
	<?php 
	$out = ob_get_clean();
	print $out;
}


/**
 * Called on admin init, initialize admin theme here.
 *
 * @return void
 * @author Jared Lang
 **/
function init_theme_options(){
	register_setting(THEME_OPTIONS_GROUP, THEME_OPTIONS_NAME, 'theme_options_sanitize');
}

/**
 * Remove paragraph tag from excerpts
 **/
remove_filter('the_excerpt', 'wpautop');
