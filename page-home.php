<?php get_header(); the_post();?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span6">
			<?php the_content();?>
		</div>
		<div class="span3">
			<div class="highlight">
				<h3>Latest Board Minutes</h3>
				<div class="content">
				</div>
			</div>

			<?php if ( !is_null( get_next_agenda() ) ): ?>
			<div class="highlight">
				<h3>Next Board Agenda</h3>
				<div class="content">
				</div>
			</div>
			<?php endif; ?>

			<div class="highlight">
				<h3>Next Board Meeting</h3>
				<div class="content">
				</div>
			</div>
		</div>
		<div class="span3" id="sidebar">
			<div class="highlight">
				<h3>Search this Site</h3>
				<?php get_template_part('searchform'); ?>
			</div>
			<div class="highlight">
				<h3>Committees</h3>
			</div>
		</div>
	</div>
<?php get_footer();?>
