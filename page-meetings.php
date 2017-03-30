<?php get_header(); the_post();?>
<div class="container">
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-8">
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php the_content();?>
			<?php $meetings = UCF_Meeting::group_by_year(); ?>
			<?php foreach( $meetings as $year=>$posts ) : ?>
			<h3>Meetings in <?php echo $year; ?></h3>
			<?php echo display_meetings( $posts ); ?>
			<?php endforeach; ?>
		</div>
		<div class="col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
<?php get_footer();?>
