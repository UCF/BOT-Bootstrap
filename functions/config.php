<?php

/**
 * Responsible for running code that needs to be executed as wordpress is
 * initializing.  Good place to register scripts, stylesheets, theme elements,
 * etc.
 *
 * @return void
 * @author Jared Lang
 **/
function __init__(){
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-header', array(
		'width'              => 1600,
		'height'             => 550,
		'flex-height'        => true,
		'flex-height'        => true,
		'uploads'            => true,
		'header-text'        => true,
		'default-text-color' => 'fff'
	) );

	add_image_size( 'header-mobile', 768, 550, array( 'left', 'top' ) );
	add_image_size( 'people-portrait', 300, 300, array( 'center', 'top' ) );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

	foreach(Config::$styles as $style){Config::add_css($style);}
	foreach(Config::$scripts as $script){Config::add_script($script);}

	global $timer;
	$timer = Timer::start();

	wp_deregister_script('l10n');
	set_defaults_for_options();
}
add_action('after_setup_theme', '__init__');



# Set theme constants
#define('DEBUG', True);                  # Always on
#define('DEBUG', False);                 # Always off
define( 'DEBUG', isset( $_GET['debug'] ) ); # Enable via get parameter
define( 'THEME_URL', get_stylesheet_directory_uri() );
define( 'THEME_ADMIN_URL', get_admin_url() );
define( 'THEME_DIR', get_stylesheet_directory() );
define( 'THEME_INCLUDES_DIR', THEME_DIR.'/includes' );
define( 'THEME_STATIC_URL', THEME_URL.'/static' );
define( 'THEME_IMG_URL', THEME_STATIC_URL.'/img' );
define( 'THEME_JS_URL', THEME_STATIC_URL.'/js' );
define( 'THEME_CSS_URL', THEME_STATIC_URL.'/css' );
define( 'THEME_OPTIONS_GROUP', 'settings' );
define( 'THEME_OPTIONS_NAME', 'theme' );
define( 'THEME_OPTIONS_PAGE_TITLE', 'Theme Options' );
define( 'THEME_CUSTOMIZER_PREFIX', 'bot_' );

$theme_options = get_option(THEME_OPTIONS_NAME);
define('GA_ACCOUNT', $theme_options['ga_account']);
define('CB_UID', $theme_options['cb_uid']);
define('CB_DOMAIN', $theme_options['cb_domain']);

ThemeConfig::$setting_defaults = array(
	'web_font_key' => '//cloud.typography.com/730568/675644/css/fonts.css'
);

function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'homepage',
		array(
			'title' => 'Homepage'
		)
	);

	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'web_fonts',
		array(
			'title' => 'Web Fonts'
		)
	);
}
add_action( 'customize_register', 'define_customizer_sections' );

function define_customizer_fields( $wp_customize ) {
	// Home Page Copy
	$wp_customize->add_setting(
		'header_copy'
	);
	$wp_customize->add_control(
		'header_copy',
		array(
			'type'        => 'textarea',
			'label'       => 'Header Copy',
			'description' => 'Copy displayed in the header on the homepage.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'homepage'
		)
	);
	// Home Page Button
	$wp_customize->add_setting(
		'header_button_copy'
	);
	$wp_customize->add_control(
		'header_button_copy',
		array(
			'type'        => 'text',
			'label'       => 'Header Button Copy',
			'description' => 'Copy displayed in the button on the homepage.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'homepage'
		)
	);
	$wp_customize->add_setting(
		'header_button_link'
	);
	$wp_customize->add_control(
		'header_button_link',
		array(
			'type'        => 'text',
			'label'       => 'Header Button Link',
			'description' => 'Link for the button on the homepage.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'homepage'
		)
	);

	# Typography
	$wp_customize->add_setting(
		'web_font_key'
	);

	$wp_customize->add_control(
		'web_font_key',
		array(
			'type'        => 'text',
			'label'       => 'Cloud.Typography CSS Key URL',
			'description' => 'The CSS Key provided by Cloud.Typography for this project.  <strong>Only include the value in the "href" portion of the link tag provided; e.g. "//cloud.typography.com/000000/000000/css/fonts.css".</strong><br><br>NOTE: Make sure the Cloud.Typography project has been configured to deliver fonts to this site\'s domain.<br>See the <a target="_blank" href="http://www.typography.com/cloud/user-guide/managing-domains">Cloud.Typography docs on managing domains</a> for more info.',
			'default'     => get_setting_default( 'web_font_key' ),
			'section'     => THEME_CUSTOMIZER_PREFIX . 'web_fonts'
		)
	);
}
add_action( 'customize_register', 'define_customizer_fields' );

# Header links
Config::$links = array(
	array('rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico',),
	array('rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo('rss_url'),),
);

# Header styles
Config::$styles = array(
	THEME_CSS_URL.'/style.min.css',
);

if ( get_theme_mod_or_default( 'web_font_key' ) ) {
	Config::$styles[] = array( 'name' => 'font-cloudtypography', 'src' => get_theme_mod_or_default( 'web_font_key' ) );
}

# Only include gravity forms styles if the plugin is active
include_once(ABSPATH.'wp-admin/includes/plugin.php' );
if(is_plugin_active('gravityforms/gravityforms.php')) {
	array_push(Config::$styles,
		plugins_url( 'gravityforms/css/forms.css' )
	);
}

array_push(Config::$styles,
	get_bloginfo('stylesheet_url')
);

# Scripts (output in footer)
Config::$scripts = array(
	'//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1',
	THEME_JS_URL.'/script.min.js',
);

# Header Meta
Config::$metas = array(
	array('charset' => 'utf-8',),
);

if ($theme_options['gw_verify']){
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities($theme_options['gw_verify']),
	);
}

# Scripts in header
function jquery_in_header() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//code.jquery.com/jquery-2.2.4.min.js');
    wp_enqueue_script( 'jquery' );
}

add_action('wp_enqueue_scripts', 'jquery_in_header');
