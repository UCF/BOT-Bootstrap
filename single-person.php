<?php disallow_direct_load('single-person.php');?>
<?php get_header(); the_post(); $post = UCF_People_PostType::append_metadata( $post ); ?>
<div class="container">
	<div class="page-content person-profile">
		<div class="row">
			<div class="col-md-3 details">
				<img class="img-fluid rounded-circle" src="<?php echo isset( $post->metadata['thumbnail_url'] ) ? $post->metadata['thumbnail_url'] : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
			</div>
			<div class="col-md-6 mb-5">
				<h1 class="mt-0 h2 mb-4"><?php echo $post->post_title; ?></h1>
				<?php if ( isset( $post->metadata['person_job_title'] ) ) : ?>
				<p class="lead mb-4"><?php echo $post->metadata['person_job_title']; ?></p>
				<?php endif; ?>
				<?php if ( $post->post_content ) : the_content(); ?>
				<?php else: ?>
					<p><em>No biography available.</em></p>
				<?php endif; ?>
			</div>
			<div class="col-md-3">
				<?php get_sidebar();?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();?>
