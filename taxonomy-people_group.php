<?php get_header(); $term = $wp_query->get_queried_object(); $today = new DateTime( 'now' ); ?>
<div class="container">
	<h1><?php echo $term->name; ?></h1>
	<div class="row">
		<div class="col-md-9">
			<p class="lead"><?php echo $term->description; ?></p>
		<?php 
			$meetings = get_meetings_by_year_committee( $term );
					
			$term = get_queried_object();
			$show_videos = get_field('people_group_video_toggle', $term);
			echo display_meetings_by_year( $meetings, $show_videos );
			
			echo display_committee_members( $term );
			echo display_committee_staff( $term ); 
		?>
			<h2>Committee Charter</h2>
			<?php $charter = get_field( 'people_group_charter', 'people_group_' . $term->term_id ); ?>
			<a class="document" href="<?php echo $charter; ?>"><?php echo $term->name; ?> Committee Charter</a>
		</div>
		<div class="col-md-3">
		<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
