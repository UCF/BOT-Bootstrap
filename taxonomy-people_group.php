<?php get_header(); $tax = $wp_query->get_queried_object(); ?>
<div class="row">
	<div class="col-md-8">
		<h1><?php echo $tax->name; ?></h1>
		<p class="lead"><?php echo $tax->description; ?></p>
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
