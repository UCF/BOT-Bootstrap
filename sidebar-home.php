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
						<p class="mb-0">
							<?php echo $next_meeting->metadata['ucf_meeting_location']; ?>
							<?php if ( isset( $next_meeting->metadata['ucf_meeting_special_name'] ) && ! empty( $next_meeting->metadata['ucf_meeting_special_name'] ) ) : ?>
								<br /><i><?php echo $next_meeting->metadata['ucf_meeting_special_name']; ?></i>
							<?php endif; ?>
						</p>
						<?php if ( $next_meeting->metadata['ucf_meeting_agenda'] ) : $file_url = wp_get_attachment_url( $next_meeting->metadata['ucf_meeting_agenda'] ); ?>
						<p class="mb-0"><a class="document" href="<?php echo $file_url; ?>">View Agenda</a></li>
						<?php endif ; ?>
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
						<p>
							<?php echo $special_meeting->metadata['ucf_meeting_location']; ?>
							<?php if ( isset( $special_meeting->metadata['ucf_meeting_special_name'] ) && ! empty( $special_meeting->metadata['ucf_meeting_special_name'] ) ) : ?>
								<br /><i><?php echo $special_meeting->metadata['ucf_meeting_special_name']; ?></i>
							<?php endif; ?>
						</p>
					</div>
				</div>
				<?php else: ?>
				<p>No Upcoming Special Meetings</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</aside>
