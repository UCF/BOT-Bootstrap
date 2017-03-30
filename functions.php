<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

// Add theme-specific functions here.

function get_header_menu() {
	ob_start();
?>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<?php if ( is_home() || is_front_page() ) : ?>
					<h1><a href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></h1>
				<?php else: ?>
					<span class="h1"><a href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></span>
				<?php endif; ?>
			</div>
			<div class="col-md-8">
				<?php
					echo wp_nav_menu(array(
						'theme_location' => 'header-menu',
						'container'      => 'false',
						'menu_class'     => 'menu list-unstyled list-inline',
						'menu_id'        => 'header-menu',
						'walker'         => new Bootstrap_Walker_Nav_Menu()
					));
				?>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}

/**
 * Returns the markup for the header with media
 * @author RJ Bruneel
 **/
function get_header_media($header) {
	ob_start();
?>
	<div class="media-header">
		<div class="media-header-callout">
			<picture>
				<!--[if IE 9]><video style="display: none;"><![endif]-->
				<source class="callout-media-src" srcset="<?php echo $header->url; ?>" media="(min-width: 768px)">
				<!--[if IE 9]></video><![endif]-->
				<img class="callout-media-src" srcset="<?php echo $header->mobile; ?>" alt="">
			</picture>
		</div>
		<div class="media-header-content">
			<?php echo get_header_menu(); ?>
			<div class="media-header-copy-wrapper">
				<div class="container-wrapper">
					<div class="container">
						<div class="row">
							<div class="col-md-8">
								<?php if ( get_theme_mod_or_default( 'header_copy' ) ) :?>
									<p class="media-header-copy child"><?php echo get_theme_mod_or_default( 'header_copy' ) ?></p>
								<?php endif; // End header copy if ?>
							</div>
							<div class="col-md-3 col-md-offset-1">
								<?php if ( get_theme_mod_or_default( 'header_button_copy' ) && get_theme_mod_or_default( 'header_button_link' ) ) :?>
									<a href="<?php echo get_theme_mod_or_default( 'header_button_link' ) ?>" class="btn btn-ucf">
										<?php echo get_theme_mod_or_default( 'header_button_copy' ) ?>
									</a>
								<?php endif; // End Header button if ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}

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

function format_people_metadata( $metadata ) {
	if ( isset( $metadata['_thumbnail_id'] ) ) {
		$src = wp_get_attachment_image_src( $metadata['_thumbnail_id'], 'people-portrait' );
		$metadata['thumbnail_url'] = $src[0];
	}

	return $metadata;
}

add_filter( 'ucf_people_format_metadata', 'format_people_metadata', 10, 1 );

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

function display_meetings_by_year( $years ) {
	ob_start();
	reset( $years );
	$first_year = key( $years );
?>
	<div class="row">
		<div class="col-md-8">
			<h2>Meetings in <span id="meeting-year"><?php echo $first_year; ?></span></h2>
		</div>
		<div class="col-md-4">
			<label for="year_select">Select Year</label>
			<select id="year_select" class="dropdown">
			<?php foreach ( array_keys( $years ) as $year ) :?>
				<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="tab-content">
	<?php foreach( $years as $year=>$meetings ) : ?>
		<div role="tabpanel" class="tab-pane<?php echo ($first_year === $year) ? ' active' : ''; ?>" id="panel_<?php echo $year; ?>">
			<?php echo display_meetings( $meetings ); ?>
		</div>
	<?php endforeach; ?>
	</div>
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

function get_meetings_by_year_committee( $committee, $args=array() ) {
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id
		)
	);

	return UCF_Meeting::group_by_year( $args );
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

function get_person_markup( $person, $title=null ) {
	ob_start();
?>
	<figure class="figure person-figure">
		<a href="<?php echo get_permalink( $person->ID ); ?>">
			<img clas="img-responsive" src="<?php echo $person->metadata['thumbnail_url']; ?>" alt="<?php echo $person->post_title; ?>">
			<figcaption class="figure-caption">
				<?php echo $person->post_title; ?>
				<?php if ( $title ) : ?>
				<p class="text-muted"><?php echo $title; ?></p>
				<?php endif; ?>
			</figcaption>
		</a>
	</figure>
<?php
	return ob_get_clean();
}

function display_committee_members( $people_group ) {
	$people_group_id = $people_group->term_id;

	$chair = UCF_People_PostType::append_metadata( get_field( 'people_group_chair', 'people_group_' . $people_group_id ) );
	$vice_chair = UCF_People_PostType::append_metadata( get_field( 'people_group_vice_chair', 'people_group_' . $people_group_id ) );
	$ex_officio = UCF_People_PostType::append_metadata( get_field( 'people_group_ex_officio', 'people_group_' . $people_group_id ) );

	$exclude = array( $chair->ID, $vice_chair->ID, $ex_officio->ID );

	// Remove the committee officers from the rest of the memebers.
	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'post__not_in'   => $exclude,
		'order'          => 'ASC',
		'orderby'        => 'post_title',
		'category_name'  => 'trustee',
		'tax_query'      => array(
			array(
				'taxonomy' => 'people_group',
				'field'    => 'id',
				'terms'    => $people_group_id
			)
		)
	);

	$people = get_posts( $args );

	ob_start();
?>
	<div class="row">
		<div class="col-md-4">
			<?php echo get_person_markup( $chair, 'Chair' ); ?>
		</div>
		<div class="col-md-4">
			<?php echo get_person_markup( $vice_chair, 'Vice Chair' ); ?>
		</div>
		<div class="col-md-4">
			<?php echo get_person_markup( $ex_officio, 'Ex Officio' ); ?>
		</div>
	</div>
<?php foreach( $people as $i=>$person ) : $person = UCF_People_PostType::append_metadata( $person ); ?>
	<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
	<div class="col-md-4 col-sm-6">
		<?php echo get_person_markup( $person ); ?>
	</div>
	<?php if ( $i % 3 === 2  || $i == count( $people ) - 1 ) : ?></div><?php endif; ?>
<?php 
	endforeach;
	return ob_get_clean();
}

function display_committee_staff( $people_group ) {
	$people_group_id = $people_group->term_id;

	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'orderby'        => 'post_title',
		'category_name'  => 'committee-staff',
		'tax_query'      => array(
			array(
				'taxonomy' => 'people_group',
				'field'    => 'id',
				'terms'    => $people_group_id
			)
		)
	);

	$people = get_posts( $args );

	ob_start();
	foreach( $people as $i=>$person ) : $person = UCF_People_PostType::append_metadata( $person ); ?>
	<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
	<div class="col-md-4 col-sm-6">
		<?php echo get_person_markup( $person ); ?>
	</div>
	<?php if ( $i % 3 === 2  || $i == count( $people ) - 1 ) : ?></div><?php endif; ?>
<?php 
	endforeach;
	return ob_get_clean();
}

?>
