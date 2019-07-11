<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
		<?php $post_type = ( $post ) ? get_post_type( $post->ID ) : null;
			if( $post_type && ( $stylesheet_id = get_post_meta( $post->ID, $post_type . '_stylesheet', true ) ) !== false
				&& ( $stylesheet_url = wp_get_attachment_url( $stylesheet_id ) ) !== false ) : ?>
				<link rel='stylesheet' href="<?php echo $stylesheet_url; ?>" type='text/css' media='all' />
		<?php endif; ?>

	</head>
	<body>
		<header class="site-header">
			<?php
				if ( ( is_home() || is_front_page() ) &&
					( get_theme_mod_or_default( 'header_copy' ) || get_theme_mod_or_default( 'header_button_copy' ) ) ) :
					$header = get_custom_header_extended();
					echo get_header_media($header);
			?>
			<?php
				else:
					echo '<div class="sub-page-nav">' . get_header_menu() . '</div>';
				endif;
			?>
		</header>
