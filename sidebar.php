<?php disallow_direct_load('sidebar.php');?>
<aside class="sidebar">
	<?php get_search_form(); ?>
	<div class="well">
		<h3>Latest Board Minutes</h3>
		<?php $minutes = get_latest_meeting_minutes(); if ( $minutes ) : ?>
			<a href="<?php echo $minutes['file']; ?>" class="document"><?php echo $minutes['name']; ?></a>
		<?php endif; ?>
	</div>
	<div class="well">
		<h3>Next Board Meeting</h3>
		<?php $next_meeting = get_next_meeting(); if ( $next_meeting ) : ?>
		<div class="row">
			<div class="col-md-1">
				<span style="margin-top: 10px;" class="fa fa-calendar"></span>
			</div>
			<div class="col-md-10">
				<h4><?php echo $next_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
				<time><?php echo $next_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $next_meeting->metadata['ucf_meeting_end_time']; ?></time>
				<p><?php echo $next_meeting->metadata['ucf_meeting_location']; ?></p>
			</div>
		</div>
		<?php else: ?>
		<p>No Upcoming Meetings</p>
		<?php endif; ?>
	</div>
	<div class="well">
		<h3>Special Meeting</h3>
		<?php $special_meeting = get_next_special_meeting(); if ( $special_meeting ) : ?>
		<div class="row">
			<div class="col-md-1">
				<span style="margin-top: 10px;" class="fa fa-calendar"></span>
			</div>
			<div class="col-md-10">
				<h4><?php echo $special_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
				<time><?php echo $special_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $special_meeting->metadata['ucf_meeting_end_time']; ?></time>
				<p><?php echo $special_meeting->metadata['ucf_meeting_location']; ?></p>
			</div>
		</div>
		<?php else: ?>
		<p>No Upcoming Special Meetings</p>
		<?php endif; ?>
	</div>
	<?php $committees = get_terms( array( 'people_group' ) ); ?>
	<h2>Committees</h2>
	<ul class="list-gold-arrow">
	<?php foreach( $committees as $committee ) : ?>
		<li><a href="<?php echo get_committee_url( $committee ); ?>"><?php echo $committee->name; ?></a></li>
	<?php endforeach; ?>
	</ul>
</aside>
