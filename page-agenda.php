<?php get_header(); the_post();?>
	<?php
		$agendas = true;
		$minutes = false;
	?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<?=get_sidebar();?>
	</div>
<?php get_footer(); ?>
