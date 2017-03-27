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

?>
