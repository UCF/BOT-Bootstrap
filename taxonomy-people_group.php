<?php get_header(); $term = $wp_query->get_queried_object(); $today = new DateTime( 'now' ); ?>
<div class="row">
	<div class="col-md-8">
		<h1><?php echo $term->name; ?></h1>
		<p class="lead"><?php echo $term->description; ?></p>
		<?php $meetings = get_meetings_by_year_committee( $term ); ?>
		<?php echo display_meetings_by_year( $meetings ); ?>
		<h2>Committee Members</h2>
		<?php echo do_shortcode( '[people-list category="trustee" people_group="' . $tax->slug . '"]' ); ?>
		<h2>Commitee Staff</h2>
		<?php echo do_shortcode( '[people-list category="committee-staff" people_group="' . $tax->slug . '"]' ); ?>
	</div>
	<div class="col-md-4">
	<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
