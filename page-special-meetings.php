<?php get_header(); the_post();?>
<div class="container">
	<h1 class="page-title"><?php the_title(); ?></h1>
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content();?>
			<?php 
                $none_term = get_term_by( 'name', 'None', 'people_group' ); 
                $special_meetings = get_special_meetings_by_year_committee( $none_term );
				echo display_meetings_by_year( $special_meetings );
			?>
		</div>
		<div class="col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>
