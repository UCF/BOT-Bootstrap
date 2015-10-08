<?php disallow_direct_load('sidebar.php');?>

<div class="span3 offset1" id="sidebar">
	<div class="highlight">
		<h3>Latest Board Minutes</h3>
		<div class="content">
			<?php display_latest_minutes() ?>
		</div>
	</div>
	<div class="highlight">
		<h3>Next Board Agenda</h3>
		<div class="content">
			<?php display_latest_agenda() ?>
		</div>
	</div>
	<div class="highlight">
		<h3>Next Board Meeting</h3>
		<div class="content">
			<?php display_next_meeting() ?>
		</div>
	</div>
	<div class="highlight">
		<h3>Special Meeting</h3>
		<div class="content">
			<?php display_special_meeting() ?>
		</div>
	</div>
	<div class="highlight">
		<h3>Search this Site</h3>
		<?php get_template_part('searchform'); ?>
	</div>
	<div class="highlight">
		<h3>Committees</h3>
		<?php display_committee_list() ?>
	</div>
</div>