<?php disallow_direct_load('single-person.php');?>
<?php get_header(); the_post();?>
	<div class="page-content person-profile">
		<div class="row">
			<div class="span2 details">
			<?
				$title = get_post_meta($post->ID, 'person_title', True);
				$image_url = get_featured_image_url($post->ID);
				$email = get_post_meta($post->ID, 'person_email', True);
				$phones = Person::get_phones($post);
			?>
			<img src="<?=$image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
			<? if(count($phones)) { ?>
			<ul class="phones unstyled">
				<? foreach($phones as $phone) { ?>
				<li><?=$phone?></li>
				<? } ?>
			</ul>
			<? } ?>
			<? if($email != '') { ?>
			<hr />
			<a class="email" href="mailto:<?=$email?>"><?=$email?></a>
			<? } ?>
			</div>
			<div class="span6">
				<h2><?=$post->post_title?></h2>
				<?php if ($title) { ?><h3 class="page-subtitle"><?=$title?></h3><? } ?>
				<?php if ($post->post_content) { echo the_content(); }
					  else { ?><p><em>No biography available.</em></p><?php } ?>
			</div>
			<?=get_sidebar();?>
		</div>
	</div>
<?php get_footer();?>