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
		
		<?  $post_type = get_post_type($post->ID);
			if(($stylesheet_id = get_post_meta($post->ID, $post_type.'_stylesheet', True)) !== False
				&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) { ?>
				<link rel='stylesheet' href="<?=$stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
	</head>
	<body>
		<div class="container">
			<div class="row"  id="header">
				<h1 class="span9"><a href="<?=bloginfo('url')?>"><?=bloginfo('name')?></a></h1>
				<div class="span3">
					<?php $options = get_option(THEME_OPTIONS_NAME);?>
					<?php if($options['facebook_url'] or $options['twitter_url']):?>
						<ul class="social">
							<?php if($options['facebook_url']):?>
							<li><a class="ignore-external facebook" href="<?=$options['facebook_url']?>">Facebook</a></li>
							<?php endif;?>
							<?php if($options['twitter_url']):?>
							<li><a class="ignore-external twitter" href="<?=$options['twitter_url']?>">Twitter</a></li>
							<?php endif;?>
						</ul>
					<?php endif;?>
				</div>
			</div>
			<?=wp_nav_menu(array(
				'theme_location' => 'header-menu', 
				'container'      => 'false', 
				'menu_class'     => 'nav nav-pills', 
				'menu_id'        => 'header-menu', 
				'walker'         => new Bootstrap_Walker_Nav_Menu()
				));
			?>