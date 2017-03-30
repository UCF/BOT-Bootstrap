<?php get_header(); the_post();?>
<div class="container">
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="col-md-8">
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php the_content();?>
		</div>
		<div class="col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>
