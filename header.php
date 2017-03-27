<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?="\n".header_()."\n"?>

		<?php if(GA_ACCOUNT or CB_UID):?>

		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>

			var GA_ACCOUNT  = '<?=GA_ACCOUNT?>';
			var _gaq        = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>

			var CB_UID      = '<?=CB_UID?>';
			var CB_DOMAIN   = '<?=CB_DOMAIN?>';
			<?php endif?>

		</script>
		<?php endif;?>

		<!--[if IE]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?  $post_type = get_post_type($post->ID);
			if(($stylesheet_id = get_post_meta($post->ID, $post_type.'_stylesheet', True)) !== False
				&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) { ?>
				<link rel='stylesheet' href="<?=$stylesheet_url?>" type='text/css' media='all' />
		<? } ?>

	</head>
	<body>
		<?php if ( is_home() || is_front_page() ) : $header = get_custom_header_extended(); ?>
		<header class="site-header header-image" style="background-image: url(<?php echo $header->url; ?>);" data-header-md="<?php echo $header->url; ?>" data-header-sm="<?php echo $header->mobile; ?>">
		<?php else: ?>
		<header class="site-header no-header-image">
		<?php endif; ?>
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<?php if ( is_home() || is_front_page() ) : ?>
							<h1><a href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></h1>
						<?php else: ?>
							<span class="h1"><a href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></span>
						<?php endif; ?>
					</div>
					<div class="col-md-8">
						<?php
							echo wp_nav_menu(array(
								'theme_location' => 'header-menu',
								'container'      => 'false',
								'menu_class'     => 'menu list-unstyled list-inline',
								'menu_id'        => 'header-menu',
								'walker'         => new Bootstrap_Walker_Nav_Menu()
							));
						?>
					</div>
				</div>
			</div>
		</header>
		<div class="container">
			
