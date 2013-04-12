<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.

/* Display */
function display_committee_list() {
	$committees = get_posts(array(
		'order'       => 'ASC',
		'orderby'     => 'title',
		'post_type'   => 'committee',
		'numberposts' => -1,
	));
	$orgs = array();
	ob_start();
	?>
	<ul class="committee-list">
	<?php foreach($committees as $committee):?>
		<?php if(stripos($committee->post_title, 'organization') === false): ?>
		<li><a href="<?=get_permalink($committee->ID)?>"><?=$committee->post_title?></a></li>
		<?php else: $orgs[] = $committee; endif; ?>
	<?php endforeach;?>
	</ul>
	
	<hr />
	<ul class="unstyled committee-list">
	<?php foreach($orgs as $org):?>
		<li><a href="<?=get_permalink($org->ID)?>"><?=$org->post_title?></a></li>
	<?php endforeach;?>
	</ul>
	<?
	echo ob_get_clean();
}

function display_next_meeting() {
	ob_start();
	?>
	<div id="next-meeting">
		<?php if( !is_null($meeting = get_next_meeting()) ):
			$meta  = get_post_custom($meeting->ID);
			$date  = strtotime($meta['meeting_date'][0]);
			
			$date      = date('F j, Y', $date);
			$start     = $meta['meeting_start_time'][0];
			$end       = $meta['meeting_end_time'][0];
			$location  = $meta['meeting_location'][0];
			$committee = ($meta['meeting_committee'][0]) ? get_post($meta['meeting_committee'][0]) : null;
		?>
		<span class="date"><?=$date?></span>
		<?php if($start and $end):?>
		<span class="time"><?=$start?> - <?=$end?></span>
		<?php endif;?>
		<?php if($location):?>
		<span class="location"><?=$location?></span>
		<?php endif;?>
		<?php if($committee):?>
		<span class="committee"><?=$committee->post_title?></span>
		<?php endif;?>
		<?php else:?>
		No upcoming meetings scheduled.
		<?php endif;?>
	</div>
	<?
	echo ob_get_clean();
}

function display_latest_minutes() {
	ob_start();
	?>
	<div id="latest-minutes">
		<?php
		if ( ($document = get_latest_minutes()) !== False){
			$url   = Document::get_url($document);
			$title = Document::get_meeting_title($document);
			$mime  = Document::get_mimetype(get_post($document));
			?>
			<a class="document <?php echo get_document_type($mime) ?>" href="<?php echo $url ?>"><?php echo $title ?></a>
			<?php
		}
		?>
	</div>
	<?
	echo ob_get_clean();
}

function display_latest_agenda() {
	ob_start();
	?>
	<div id="latest-minutes">
		<?php
		if ( ($document = get_latest_agenda()) !== False){
			$url   = Document::get_url($document);
			$title = Document::get_meeting_title($document);
			$mime  = Document::get_mimetype(get_post($document));
			?>
			<a class="document <?php echo get_document_type($mime) ?>" href="<?php echo $url ?>"><?php echo $title ?></a>
			<?php
		}
		?>
	</div>
	<?
	echo ob_get_clean();
}

/* Utility */
function get_meetings($committee=null, $year=null){
	global $wpdb;
	
	if(is_null($committee)) {
		$sql = "
			SELECT post.*
			FROM   $wpdb->posts post
			JOIN   $wpdb->postmeta meta
			ON     (
						post.ID = meta.post_id
						AND meta.meta_key = 'meeting_date'
					)
			WHERE  post.post_type = 'meeting'
			       AND post.post_status = 'publish'
			       AND post.ID NOT IN (
			             SELECT post_id
			             FROM   $wpdb->postmeta
			             WHERE  meta_key = 'meeting_committee')";
	} else {
		$sql = "
			SELECT post.*
			FROM   $wpdb->posts post
			JOIN   $wpdb->postmeta meta
			ON     (
						post.ID = meta.post_id
						AND meta.meta_key = 'meeting_committee'
			       		AND meta.meta_value = '$committee->ID'
					)
			JOIN   $wpdb->postmeta meta_1
			ON     (
						post.ID = meta_1.post_id
						AND meta_1.meta_key = 'meeting_date'

					)
			WHERE  post.post_type = 'meeting'
			       AND post.post_status = 'publish'";
	}
	
	if( !is_null($year) && 
		(int)$year != 0 && 
		($start_time = strtotime($year.'-01-01 00:00:00')) !== False && 
		($end_time   = strtotime($year.'-12-31 00:00:00')) !== False) {
		
		$start_date = date('Y-m-d', $start_time);
		$end_date   = date('Y-m-d', $end_time);
		
		$sql .= ' 
			AND (
				STR_TO_DATE('.(is_null($committee) ? '' : 'meta_1.').'meta_value, "%m/%e/%Y") >= STR_TO_DATE("'.$start_date.'", "%Y-%m-%d") 
				AND STR_TO_DATE('.(is_null($committee) ? '' : 'meta_1.').'meta_value, "%m/%e/%Y") <= STR_TO_DATE("'.$end_date.'", "%Y-%m-%d")
				)

		';
	}

	return $wpdb->get_results($sql);
}

function sort_meetings(&$meetings){
	usort($meetings, create_function('$a, $b', '
		$a_meeting_date       = get_post_meta($a->ID, "meeting_date", True);
		$b_meeting_date       = get_post_meta($b->ID, "meeting_date", True);
		$a_date = strtotime($a_meeting_date);
		$b_date = strtotime($b_meeting_date);
		return ($a_date < $b_date) ? -1 : 1;
	'));
}

function get_next_meeting($committee=null) {
	$meetings = get_meetings($committee);

	// If the meeting is today, it should still be considered
	// `the next meeting` for the entire day
	$now   = mktime(0, 0, 0);

	sort_meetings($meetings);

	foreach($meetings as $meeting) {
		$starting_date = get_post_meta($meeting->ID, 'meeting_date', True);
		$starting_time = get_post_meta($meeting->ID, 'meeting_start_time', True);
		$start_timestamp = 0;
		if( (($start_timestamp = strtotime($starting_date.' '.$starting_time)) !== False || ($start_timestamp = strtotime($starting_date) !== False)) && $start_timestamp > $now) {
			return $meeting;
		}
	}
	return null;
}

function get_next_agenda($committee = null) {
	if( !is_null($meeting = get_next_meeting($committee))
		&& ($file_id = get_post_meta($meeting->ID, 'meeting_agenda', True)) !== ""
		&& ($file = get_post($file_id)) !== False) {
		return $file;
	} else {
		return null;
	}
}

function get_latest_document($meta_key = NULL) {
	if(!is_null($meta_key)) {
		global $wpdb;

		$sql = "
			SELECT meta.meta_value AS document_id
			FROM  $wpdb->posts AS post
			JOIN  $wpdb->postmeta AS meta
			ON    
				(
					post.ID = meta.post_id
					AND meta.meta_key = '$meta_key'
					AND meta.meta_value != ''
				)
			JOIN  $wpdb->postmeta AS meta_1
			ON    (
					post.ID = meta_1.post_id
					AND meta_1.meta_key = 'meeting_date'
				)
			WHERE 
				post.post_type = 'meeting'
				AND post.post_status = 'publish'
				AND post.ID NOT IN (
					SELECT post_id 
					FROM   $wpdb->postmeta
					WHERE  meta_key = 'meeting_committee'
				)
				AND meta.meta_value IN (
					SELECT ID
					FROM   $wpdb->posts
				)
				AND STR_TO_DATE(meta_1.meta_value, '%m/%e/%Y') IS NOT NULL
			ORDER BY STR_TO_DATE(meta_1.meta_value, '%m/%e/%Y') DESC
		";

		$rows = $wpdb->get_results($sql);
		return (count($rows) > 0) ? $rows[0]->document_id : False;
	} else {
		return False;
	}
}

function get_latest_minutes() {
	return get_latest_document('meeting_minutes');
}

function get_latest_agenda() {
	return get_latest_document('meeting_agenda');
}


function get_document_type($mimetype){
	switch($mimetype){
		default:
			$type = 'unknown';
			break;
		case 'application/pdf':
			$type = 'pdf';
			break;
		case 'text/html':
			$type = 'html';
			break;
		case 'application/msword':
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			$type = 'word';
			break;
		case 'application/msexcel':
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			$type = 'excel';
			break;
		case 'application/vnd.ms-powerpoint':
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			$type = 'ppt';
			break;
	}
	return $type;
}

function get_content($id){
	$post    = get_post($id);
	$content = $post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

function meetings_prep(&$meetings){
	filter_meetings($meetings);
	sort_meetings($meetings);
}

function filter_meetings(&$meetings){
	$current_year = (int)date('Y');
	foreach ($meetings as $key=>$meeting){
		$date = strtotime($meeting->meta["meeting_date"][0] . " " . $meeting->meta["meeting_start_time"][0]);
		if($date === False) {
			$date = strtotime($meeting->meta["meeting_date"][0]);
		}
		$year = (int)date('Y', $date);
		if( ($current_year - $year > 1) || ($year - $current_year > 1) || $date === False){
			unset($meetings[$key]);
		}
	}
}
?>