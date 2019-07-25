<?php disallow_direct_load('home-sidebar.php');?>
<aside class="sidebar-home well py-4 bg-faded">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<h3 class="text-uppercase h6 underline-gold mb-3">Next Board Meeting</h3>
				<?php $next_meeting = get_next_meeting(); if ( $next_meeting ) : ?>
				<div class="row">
					<div class="col-1">
						<span class="fa fa-calendar"></span>
					</div>
					<div class="col-10 mt-1">
						<h4 class="h6"><?php echo $next_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
						<time class="small"><?php echo $next_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $next_meeting->metadata['ucf_meeting_end_time']; ?></time>
						<p class="my-1 small"><?php echo $next_meeting->metadata['ucf_meeting_location']; ?></p>
						<?php if ( $next_meeting->metadata['ucf_meeting_agenda'] ) : $file_url = wp_get_attachment_url( $next_meeting->metadata['ucf_meeting_agenda'] ); ?>
						<p class="mb-0 small"><a class="document" href="<?php echo $file_url; ?>" target="_blank">View Agenda</a></li>
						<?php endif ; ?>
						<?php if ( $next_meeting->metadata['ucf_meeting_video'] ) : ?>
						<p class="mt-1 mb-0 small"><a class="document" href="<?php echo $next_meeting->metadata['ucf_meeting_video']; ?>" target="_blank">View Livestream</a></p>
						<?php endif; ?>
					</div>
				</div>
				<?php else: ?>
				<p class="mb-0 small">No Upcoming Meetings</p>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<h3 class="text-uppercase h6 underline-gold mb-3">Latest Board Minutes</h3>
				<?php $minutes = get_latest_meeting_minutes(); if ( $minutes && ! empty( $minutes['file'] ) ) : ?>
					<a href="<?php echo $minutes['file']; ?>" class="document latest-board-minutes"><?php echo $minutes['name']; ?></a>
				<?php else : ?>
					<p class="mb-0 small">No Minutes Available for Latest Meeting</p>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<h3 class="text-uppercase h6 underline-gold mb-3">Special Meeting</h3>
				<?php $special_meeting = get_next_special_meeting(); if ( $special_meeting ) : ?>
				<div class="row">
					<div class="col-1">
						<span class="fa fa-calendar"></span>
					</div>
					<div class="col-10 mt-1">
						<h4 class="h6"><?php echo $special_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
						<time class="small"><?php echo $special_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $special_meeting->metadata['ucf_meeting_end_time']; ?></time>
						<p class="my-1 small"><?php echo $special_meeting->metadata['ucf_meeting_location']; ?></p>
						<?php if ( isset( $special_meeting->metadata['ucf_meeting_special_name'] ) && ! empty( $special_meeting->metadata['ucf_meeting_special_name'] ) ) : ?>
							<p class="my-1"><em><?php echo $special_meeting->metadata['ucf_meeting_special_name']; ?></em></p>
						<?php endif; ?>
						<?php if ( isset( $special_meeting->metadata['ucf_meeting_agenda'] ) && ! empty( $special_meeting->metadata['ucf_meeting_agenda'] ) ) :
							$special_meeting_agenda = wp_get_attachment_url( $special_meeting->metadata['ucf_meeting_agenda'] );
						?>
							<p class="mb-0 small"><a class="document" href="<?php echo $special_meeting_agenda; ?>" target="_blank">View Agenda</a></p>
						<?php endif; ?>
						<?php if ( isset( $special_meeting->metadata['ucf_meeting_video'] ) && ! empty( $special_meeting->metadata['ucf_meeting_video'] ) ) : ?>
							<p class="mt-1 mb-0 small"><a class="document" href="<?php echo $special_meeting->metadata['ucf_meeting_video']; ?>" target="_blank">View Livestream</a></p>
						<?php endif; ?>
					</div>
				</div>
				<?php else: ?>
				<p class="mb-0 small">No Upcoming Special Meetings</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</aside>
