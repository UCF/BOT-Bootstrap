<?php disallow_direct_load('home-sidebar.php');?>
<aside class="sidebar-home well">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<h3>Next Board Meeting</h3>
				<?php $next_meeting = get_next_meeting(); if ( $next_meeting ) : ?>
				<div class="row">
					<div class="col-xs-1">
						<span class="fa fa-calendar"></span>
					</div>
					<div class="col-xs-10">
						<h4><?php echo $next_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
						<time><?php echo $next_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $next_meeting->metadata['ucf_meeting_end_time']; ?></time>
						<p><?php echo $next_meeting->metadata['ucf_meeting_location']; ?></p>
					</div>
				</div>
				<?php else: ?>
				<p>No Upcoming Meetings</p>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<h3>Latest Board Minutes</h3>
				<?php $minutes = get_latest_meeting_minutes(); if ( $minutes ) : ?>
					<a href="<?php echo $minutes['file']; ?>" class="document latest-board-minutes"><?php echo $minutes['name']; ?></a>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<h3>Special Meeting</h3>
				<?php $special_meeting = get_next_special_meeting(); if ( $special_meeting ) : ?>
				<div class="row">
					<div class="col-xs-1">
						<span class="fa fa-calendar"></span>
					</div>
					<div class="col-xs-10">
						<h4><?php echo $special_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
						<time><?php echo $special_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $special_meeting->metadata['ucf_meeting_end_time']; ?></time>
						<p><?php echo $special_meeting->metadata['ucf_meeting_location']; ?></p>
					</div>
				</div>
				<?php else: ?>
				<p>No Upcoming Special Meetings</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</aside>
