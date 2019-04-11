<?php

/**
 * Meta tags to insert into the document head.
 **/
function add_meta_tags() {
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php
	$gw_verify = get_theme_mod( 'gw_verify' );

	if ( $gw_verify ):
	?>
		<meta name="google-site-verification" content="<?php echo htmlentities( $gw_verify ); ?>">
	<?php endif; ?>
	<?php
}

add_action( 'wp_head', 'add_meta_tags', 1 );


/**
 * Adds Google Analytics script to the document head.  Note that, if a Google
 * Tag Manager ID is provided in the customizer, this hook will have no effect.
 **/
function ucfwp_add_google_analytics() {
	$ga_account = get_theme_mod( 'ga_account' );

	if ( $ga_account ):
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_account; ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '<?php echo $ga_account; ?>');
		</script>
		<?php
	endif;
}

add_action( 'wp_head', 'ucfwp_add_google_analytics' );


/**
 * Removed unneeded meta tags generated by WordPress.
 * Some of these may already be handled by security plugins.
 **/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

?>
