<?php get_header(); the_post();?>
<?php get_sidebar( 'home' ); ?>
<?php $cta = get_homepage_cta_object(); ?>
<div class="container">
<?php $committees = get_terms( array( 'people_group' ) ); ?>
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content();?>
		</div>
		<div class="col-md-3" id="sidebar">
			<?php if ( $cta->show && $cta->has_content ) : ?>
			<style>
				.cta-background-override { background-color: <?php echo $cta->bg_color; ?> }
				.cta-color-override { color: <?php echo $cta->txt_color; ?> }
				.cta-btn-background-override { background-color: <?php echo $cta->btn_bg_color; ?> }
				.cta-btn-text-override { color: <?php echo $cta->btn_txt_color; ?> }
			</style>
			<aside class="homepage-cta cta-background-override">
				<div class="homepage-cta-content-wrap">
				<?php if ( ! empty( $cta->title ) ) : ?>
					<h2 class="homepage-cta-title cta-color-override"><?php echo $cta->title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $cta->content ) ) : ?>
					<p class="homepage-cta-content cta-color-override"><?php echo $cta->content; ?></p>
				<?php endif; ?>
				</div>
				<div class="homepage-cta-footer">
				<?php if ( $cta->has_button ) : ?>
					<a class="btn btn-ucf-inverse homepage-cta-btn cta-btn-background-override cta-btn-text-override" href="<?php echo $cta->btn_url; ?>">
						<?php echo $cta->btn_text; ?>
					</a>
				<?php endif; ?>
				</div>
			</aside>
			<?php endif; ?>
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
</div>
<?php get_footer();?>
