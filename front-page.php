<?php get_header(); the_post();?>
</div><!-- break .container -->
<?php get_sidebar( 'home' ); ?>
<div class="container">
<?php $committees = get_terms( array( 'people_group' ) ); ?>
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content();?>
		</div>
		<div class="col-md-3" id="sidebar">
			<aside>
				<h2>Committees</h2>
				<ul class="list-gold-arrow">
				<?php foreach( $committees as $committee ) : ?>
					<li><a href="<?php echo get_committee_url( $committee ); ?>"><?php echo $committee->name; ?></a></li>
				<?php endforeach; ?>
				</ul>
			</aside>
		</div>
	</div>
<?php get_footer();?>
