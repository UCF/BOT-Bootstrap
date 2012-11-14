<?php get_header(); the_post();?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span6">
			<?php the_content();?>
		</div>
		<div class="span3">
			<div class="highlight">
				<h3>Latest Board Minutes</h3>
				<div class="content">
					<a href="">June 25, 2012</a>
				</div>
			</div>
			<div class="highlight">
				<h3>Next Board Agenda</h3>
				<div class="content">
					<a href="">November 15, 2012</a>
				</div>
			</div>
			<div class="highlight">
				<h3>Next Board Meeting</h3>
				<div class="content">
					November 15, 2012<br />
					8:00 am - 5:00 pm<br />
					Live Oak Center
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
				<?php display_committee_list() ?>
			</div>
		</div>
	</div>
<?php get_footer();?>