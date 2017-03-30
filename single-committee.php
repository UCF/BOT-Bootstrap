<?php get_header(); the_post();?>
<div class="container">
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span8">
			<h2 class="page-title"><?php the_title(); ?></h2>
			<div class="description">
				<?=get_post_meta($post->ID, 'committee_description', True)?>
			</div>
			<?php the_content();?>
		</div>
		<?=get_sidebar();?>
	</div>
</div>
<?php get_footer();?>
