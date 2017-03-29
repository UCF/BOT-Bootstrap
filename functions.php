<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

// Add theme-specific functions here.

/**
 * Updates the people_group custom taxonomies labels
 **/
function people_group_labels( $labels ) {
	$labels['singular'] = 'Committee';
	$labels['plural'] = 'Committees';
	$labels['slug'] = 'committees';

	return $labels;
}

add_filter( 'ucf_people_group_labels', 'people_group_labels', 10, 1 );

function format_meeting_metadata( $metadata ) {
	if ( isset( $metadata['ucf_meeting_date'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_date'] );
		$metadata['ucf_meeting_date'] = $date;
	}

	if ( isset( $metadata['ucf_meeting_start_time'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_start_time'] );
		$metadata['ucf_meeting_start_time'] = $date->format( 'g:i a' );
	}

	if ( isset( $metadata['ucf_meeting_end_time'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_end_time'] );
		$metadata['ucf_meeting_end_time'] = $date->format( 'g:i a' );
	}

	return $metadata;
}

add_filter( 'ucf_meeting_format_metadata', 'format_meeting_metadata', 10, 1 );

function get_committee_url( $term ) {
	return get_site_url( null, '/committees/' . $term->slug );
}

function get_people_group_data( $tax_term ) {
	$tax_term->members = get_posts(
		array(
			'post_type'        => 'person',
			'posts_per_page'   => -1,
			'category_name'    => 'trustee',
			'order'            => 'ASC',
			'orderby'          => 'post_title',
			'tax_query'        => array(
				array(
					'taxonomy' => 'people_group',
					'field'    => 'slug',
					'terms'    => $tax_term->slug
				)
			)
		)
	);

	$tax_term->staff = get_posts(
		array(
			'post_type'        => 'person',
			'posts_per_page'   => -1,
			'category_name'    => 'committee-staff',
			'order'            => 'ASC',
			'orderby'          => 'post_title',
			'tax_query'        => array(
				array(
					'taxonomy' => 'people_group',
					'field'    => 'slug',
					'terms'    => $tax_term->slug
				)
			)
		)
	);

	return $tax_term;
}

function get_custom_header_extended() {
	$retval = get_custom_header();
	$mobile = wp_get_attachment_image_src( $retval->attachment_id, 'header-mobile' );
	$retval->mobile = $mobile[0];

	return $retval;
}

/**
 * Displays meetings
 * Note: REQUIRES meetings to be pulled using the UCF_Meeting class
 * @author Jim Barnes
 * @param $meetings Array<WP_Post>
 **/
function display_meetings( $meetings ) {
	ob_start();
?>
	<table class="table">
		<thead>
			<tr>
				<th>Meeting</th>
				<th>Agenda</th>
				<th>Minutes</th>
			</tr>
		</thead>
		<tbody>
	<?php foreach( $meetings as $post ) : ?>
			<tr>
				<td><?php echo $post->post_title; ?></td>
				<td>
					<?php if ( isset( $post->metadata['ucf_meeting_agenda'] ) ) : ?>
					<a href="<?php echo wp_get_attachment_url( $post->metadata['ucf_meeting_agenda'] ); ?>">Agenda</a>
					<?php else: ?>
					<p>No Agenda</p>
					<?php endif; ?>
				</td>
				<td>
					<?php if ( isset( $post->metadata['ucf_meeting_minutes'] ) ) : ?>
					<a href="<?php echo wp_get_attachment_url( $post->metadata['ucf_meeting_minutes'] ); ?>">Minutes</a>
					<?php else: ?>
					<p>No Minutes</p>
					<?php endif; ?>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
<?php
	return ob_get_clean();
}

function get_meetings_committee( $committee, $args=array() ) {
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id
		)
	);

	return UCF_Meeting::all( $args );
}

function get_latest_meeting_minutes( $committee='None', $args=array() ) {
	$retval = null;

	$today = date('Y-m-d H:i:s');
	$committee = term_exists( $committee, 'people_group' );

	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATETIME',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '<=',
				'type'    => 'DATETIME'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'ucf_meeting_minutes',
				'compare' => 'EXISTS'
			)
		)
	);

	$meetings = UCF_Meeting::all( $args );
	$meeting = $meetings[0];

	if ( $meeting ) {

		$retval = array(
			'name' => $meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ),
			'file' => wp_get_attachment_url( $meeting->metadata['ucf_meeting_minutes'] )
		);
	}

	return $retval;
}

function get_next_meeting( $committee='None', $args=array() ) {
	$today = date('Y-m-d H:i:s');
	$committee = term_exists( $committee, 'people_group' );

	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATETIME',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATETIME'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => '='
			)
		)
	);

	$meetings = UCF_Meeting::all( $args );
	$meeting = $meetings[0];

	return $meeting;
}

function get_next_special_meeting( $committee='None', $args=array() ) {
	$today = date('Y-m-d H:i:s');
	$committee = term_exists( $committee, 'people_group' );

	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATETIME',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query' => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATETIME'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => '='
			),
			array(
				'key'     => 'ucf_meeting_special_meeting',
				'value'   => '1',
				'compare' => '=='
			)
		)
	);

	$meetings = UCF_Meeting::all( $args );
	$meeting = $meetings[0];

	return $meeting;
}

?>
