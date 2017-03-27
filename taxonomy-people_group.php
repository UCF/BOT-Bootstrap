<?php get_header(); $tax = $wp_query->get_queried_object(); $tax = get_people_group_data( $tax ); ?>
<div class="row">
	<div class="col-md-8">
		<h1><?php echo $tax->name; ?></h1>
		<p class="lead"><?php echo $tax->description; ?></p>
		<h2>Meetings in <?php echo $tax->meetings->latest; ?></h2>
		<h2>Committee Memebers</h2>
		<?php foreach( $tax->members as $member ) : ?>
			<h3><?php echo $member->post_title; ?></h3>
		<?php endforeach; ?>
		<h2>Commitee Staff</h2>
		<?php foreach( $tax->staff as $staff ) : ?>
			<h3><?php echo $staff->post_title; ?></h3>
		<?php endforeach; ?>
	</div>
	<div class="col-md-4">

	</div>
</div>
<?php get_footer(); ?>
