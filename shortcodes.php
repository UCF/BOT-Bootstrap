<?php

/**
 * Outputs all meetings, broken down by type (board or committee) and then
 * committee name if applicable
 * 
 * [meetings] # output all meetings
 **/
function sc_meetings(){
	$committee = new Committee();
	$meeting   = new Meeting();
	
	$meetings = array_map(create_function('$m', '
		$meta    = get_post_custom($m->ID);
		$m->meta = $meta;
		return $m;
	'), $meeting->get_objects(array(
		'orderby' => 'title',
		'order'   => 'DESC',
	)));
	
	$map = array();
	foreach($meetings as $meeting){
		$committee         = @(int)$meeting->meta['meeting_committee'][0];
		$map[$committee][] = $meeting;
	}
	
	ksort($map);
	$map            = array_reverse($map, True);
	$board_meetings = array_pop($map);
	ob_start();
	?>
	
	<div class="meetings">
		<h3>Board Meetings</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Date</th>
					<th scope="col">Time</th>
					<th scope="col">Agenda/Minutes</th>
					<th scope="col">Location</th>
				</tr>
			</thead>
			<tbody>
				<?php meetings_prep($board_meetings); foreach($board_meetings as $i=>$meeting):?>
				<tr class="<?=($i % 2) ? 'even' : 'odd';?>">
					<td class="date"><?=date("F j, Y", strtotime($meeting->meta['meeting_date'][0]))?></td>
					<td class="time">
						<?php
							$start = get_post_meta($meeting->ID, 'meeting_start_time', True);
							$end   = get_post_meta($meeting->ID, 'meeting_end_time', True);
						?>
						<?php if($start and $end):?>
							<?=$start?> &ndash; <?=$end?>
						<?php elseif($start and !$end):?>
							<?=$start?>
						<?php endif;?>
					</td>
					<td class="documents">
						<?php
							$agenda_id = get_post_meta($meeting->ID, 'meeting_agenda', True);
							if ($agenda_id){
								$agenda = get_post($agenda_id);
								$url    = Document::get_url($agenda);
								$title  = Document::get_title($agenda);
								$mime   = Document::get_mimetype($agenda);
							}else{
								$agenda = null;
							}
						?>
						<?php if ($agenda):?>
						<a class="document <?=get_document_type($mime)?>" href="<?=$url?>">Agenda</a>
						<?php endif;?>
						<?php
							$minutes_id = get_post_meta($meeting->ID, 'meeting_minutes', True);
							if ($minutes_id){
								$minutes = get_post($minutes_id);
								
								$url     = Document::get_url($minutes);
								$title   = Document::get_title($minutes);
								$mime    = Document::get_mimetype($minutes);
							}else{
								$minutes = null;
							}
						?>
						<?php if ($minutes):?>
						<a class="document <?=get_document_type($mime)?>" href="<?=$url?>">Minutes</a>
						<?php endif;?>
					</td>
					<td class="location"><?=get_post_meta($meeting->ID, 'meeting_location', True)?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	
	<h3>Committee Meetings</h3>
	<? 
	return ob_get_clean().sc_committee_meetings(array('show_link'=>True));
	
}
add_shortcode('meetings', 'sc_meetings');


/**
 * Outputs committee meetings for all committees or a specified committee
 * 
 * [committee-meetings]
 * [committee-meetings name="Advancement"]
 **/
function sc_committee_meetings($attrs = array()) {
	global $wpdb, $post;
	
	$show_link      = isset($attrs['show_link']) ? (bool)$attrs['show_link'] : False;

	# Make sure this committee exists
	if($post->post_type == 'committee') {
		$committees = array($post);
	} else {
		$query_args = array(
			'post_type'   => 'committee',
			'numberposts' => -1,
			'orderby'     => 'post_title',
			'order'       => 'DESC');
		$committees = get_posts($query_args);
	}

	ob_start();
	foreach($committees as $committee) {
		if(!is_null($committee_name) && $committee->post_title != $committee_name) {
			continue;
		}

		$sql = "
		SELECT post.*
		FROM   $wpdb->posts post
		JOIN   $wpdb->postmeta meta
		ON     post.ID = meta.post_id
		WHERE
			post.post_type ='meeting'
			AND meta.meta_key = 'meeting_committee'
			AND meta.meta_value = $committee->ID";
		$meetings = $wpdb->get_results($sql);
		
		?><div class="meetings">
			<? if($show_link): ?>
			<h4><?php if($committee):?><a href="<?=get_permalink($committee->ID)?>"><?php endif;?><?=($committee) ? $committee->post_title : 'Non-committee Meetings'?><?php if($committee):?></a><?php endif;?></h3>
			<? else: ?>
			<h3>Meetings</h3>
		<? endif ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Date</th>
						<th scope="col">Time</th>
						<th scope="col">Documents</th>
						<th scope="col">Location</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($meetings) == 0): ?>
					<tr>
						<td colspan="4">No meetings scheduled.</td>
					</tr>
					<? else: ?>
					<?php meetings_prep($meetings); foreach($meetings as $i=>$meeting):?>
					<tr class="<?=($i % 2) ? 'even' : 'odd';?>">
						<td class="date"><?=date("F j, Y", strtotime(get_post_meta($meeting->ID, 'meeting_date', True)))?></td>
						<td class="time">
							<?php
								$start = get_post_meta($meeting->ID, 'meeting_start_time', True);
								$end   = get_post_meta($meeting->ID, 'meeting_end_time', True);
							?>
							<?php if($start and $end):?>
								<?=$start?> &ndash; <?=$end?>
							<?php elseif($start and !$end):?>
								<?=$start?>
							<?php endif;?>
						</td>
						<td class="documents">
							<?php
								$agenda_id = get_post_meta($meeting->ID, 'meeting_agenda', True);
								if ($agenda_id){
									$agenda = get_post($agenda_id);
									$url    = Document::get_url($agenda);
									$title  = Document::get_title($agenda);
									$mime   = Document::get_mimetype($agenda);
								} else {
									$agenda = null;
								}
							?>
							<?php if ($agenda):?>
							<a class="document <?=get_document_type($mime)?>" href="<?=$url?>">Agenda</a>
							<?php endif;?>
							<?php
								$minutes_id = get_post_meta($meeting->ID, 'meeting_minutes', True);
								if ($minutes_id){
									$minutes = get_post($minutes_id);
									$url     = Document::get_url($minutes);
									$title   = Document::get_title($minutes);
									$mime    = Document::get_mimetype($minutes);
								} else {
									$minutes = null;
								}
							?>
							<?php if ($minutes):?>
							<a class="document <?=get_document_type($mime)?>" href="<?=$url?>">Minutes</a>
							<?php endif;?>
						</td>
						<td class="location"><?=get_post_meta($meeting->ID, 'meeting_location', True)?></td>
					</tr>
					<?php endforeach;?>
					<? endif ?>
				</tbody>
			</table>
		</div><?
	}
	return ob_get_clean();
}
add_shortcode('committee-meetings', 'sc_committee_meetings');

/**
 * When used on committee page, this will output a link for the committee's 
 * charter document if available.
 **/
function sc_committee_charter($attr){
	global $post;
	$committees = get_custom_post_type('Committee', True);
	if(get_custom_post_type($post) == $committees->options('name')){
		$document_id = get_post_meta($post->ID, 'committee_charter', True);
		$document    = get_post($document_id);
		ob_start();?>
		<?php if($document):?>
		<ul class="nobullet">
			<li><a class="document <?=get_document_type(Document::get_mimetype($document))?>" href="<?=Document::get_url($document)?>"><?=Document::get_title($document)?></a></li>
		</ul>
		<?php else:?>
		<p>No charter for this committee.</p>
		<?php endif;?>
		<?php
		$html = ob_get_clean();
	}
	
	return $html;
}
add_shortcode('committee-charter', 'sc_committee_charter');


/**
 *
 * When used on committee page, this will output the members for that committee.
 *
 * Examples:
 *   [committee-members]
 *
 **/
function sc_committee_members($attr){
	$class = (isset($attr['class'])) ? $attr['class'] : 'inline-person-list';
	$class = str_replace('inline-person-list', 'person-list', $class);
	return sc_committee_people('members', $class);
}
add_shortcode('committee-members', 'sc_committee_members');
