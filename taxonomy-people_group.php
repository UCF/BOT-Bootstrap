<?php get_header(); $term = $wp_query->get_queried_object(); $today = new DateTime( 'now' ); ?>
<div class="row">
	<div class="col-md-8">
		<h1><?php echo $term->name; ?></h1>
		<p class="lead"><?php echo $term->description; ?></p>
		<h2>Meetings in <?php echo $today->format( 'Y' ); ?></h2>
		<?php $meetings = get_meetings_committee( $term ); ?>
		<?php echo display_meetings( $meetings ); ?>
		<h2>Committee Memebers</h2>
		<?php echo do_shortcode( '[people-list category="trustee" people_group="' . $tax->slug . '"]' ); ?>
		<h2>Commitee Staff</h2>
		<?php echo do_shortcode( '[people-list category="committee-staff" people_group="' . $tax->slug . '"]' ); ?>
	</div>
	<div class="col-md-4">
	<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
