<?php get_header(); the_post();?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span8">
			<h2 class="page-title"><?php the_title(); ?></h2>
			<?php the_content();?>
		</div>
		<?=get_sidebar();?>
	</div>
<?php get_footer();?>