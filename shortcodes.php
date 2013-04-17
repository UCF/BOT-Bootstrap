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


/**
 *
 * When used on committee page, this will output the staff for that committee.
 *
 * Examples:
 *   [committee-staff]
 *
 **/
function sc_committee_staff($attr){
	$class = (isset($attr['class'])) ? $attr['class'] : 'inline-person-list';
	$class = str_replace('inline-person-list', 'person-list notitle', $class);
	return sc_committee_people('staff', $class);
}
add_shortcode('committee-staff', 'sc_committee_staff');

function sc_committee_people($type, $class){
	ob_start();
	$committees = get_custom_post_type('Committee', True);
	global $post;
	if(get_custom_post_type($post) == $committees->options('name')){
		$people = $committees->get_members($post->ID, $type, True);
		
		if (count($people)):?>
		<ul class="<?=$class?>">
			<?php foreach($people as $person):?>
			<li>
				<div class="person" id="person-<?=$person->ID?>"><a href="<?=get_permalink($person->ID)?>">
					<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($person->ID), 'full');?>
					<div class="crop">
						<?php if($image[0]):?>
						<img src="<?=$image[0]?>" alt="<?=$person->post_title?>" />
						<?php else:?>
						<img src="<?=THEME_IMG_URL?>/no-photo.jpg" alt="no photo" />
						<?php endif;?>
					</div>
					<div class="information">
						<h3>
							<span class="name"><?=$person->post_title?></span>
							<?php if($person->position):?>
							<span class="title">
								<?=$person->position?>
							</span>
							<?php endif;?>
						</h3>
						<div class="bio"><?=get_content($person->ID);?></div>
						<div class="phone"><?=$meta['person_phone'][0]?></div>
						<div class="email"><?=$meta['person_email'][0]?></div>
					</div>
				</a></div>
			</li>
			<?php endforeach;?>
		</ul>
		<?php else:?>
			<p>There are no members for this committee.</p>
		<?php endif;?>
	<?php
	}
	return ob_get_clean();
}


/**
 * 
 * Displays the minutes and agendas for a particular committee,
 * defined by the committee page this shortcode is called on. An archive
 * list will be included.
 * 
 * Examples:
 *   [latest-minutes-and-agenda]
 **/
function sc_minutes_and_agendas($attrs){
	global $post;
	
	$today = getdate();
	$year  = isset($_GET['y']) ? $_GET['y'] : $today['year'];
	
	$agenda_archive_years  = get_agenda_archive_years($post);
	$minutes_archive_years = get_minutes_archive_years($post);

	$archive_years = ($agenda_archive_years > $minutes_archive_years) ? $agenda_archive_years : $minutes_archive_years;

	ob_start();
	?>
	<div class="row category-list">
		<div class="span5">
			<h3>Agenda <? if($year != $today['year']): ?> (<?=$year?>)<? endif ?></h3>
	<?
		$files = get_agendas($post, $year);
		$class = 'Agenda';
		include('includes/file-listing.php');
	?>
			<h3>Minutes <? if($year != $today['year']): ?> (<?=$year?>)<? endif ?></h3>
	<?
		$files = get_minutes($post, $year);
		$class = 'Minutes';
		include('includes/file-listing.php');
	?>
		</div>
		<div class="span2 offset1">
			<?php ?>
			<?php if (count($archive_years)):?>
			<h3>Archives</h3>
			<ul class="archives">
				<?php foreach($archive_years as $archive_year):?>
				<li><a href="?y=<?=$archive_year?>"<? if($archive_year == $year): ?> class="active"> &#9656; <? else: ?>><? endif ?><?=$archive_year?></a></li>
				<?php endforeach;?>
			</ul>
			<? else: ?>
			<p>There are no archives.</p>
			<?php endif;?>
		</div>
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('minutes-and-agendas', 'sc_minutes_and_agendas');
