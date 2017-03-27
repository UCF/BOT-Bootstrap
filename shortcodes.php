<?php
/**
 * Returns a person list
 **/
function ucf_people_list_shortcode( $atts, $content='' ) {
	$atts = shortcode_atts(
		array(
			'people_group' => null,
			'category'     => null,
			'limit'        => -1
		),
		$atts
	);

	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => (int)$atts['limit'],
		'order'          => 'DESC',
		'orderby'        => 'post_title'
	);

	if ( $atts['category'] ) {
		$args['category_name'] = $atts['category'];
	}

	if ( $atts['people_group'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'people_group',
				'field'    => 'slug',
				'terms'    => $atts['people_group']
			)
		);
	}

	$people = get_posts( $args );

	ob_start();
?>
	<div class="row">
<?php
	foreach( $people as $person ) :
		$person = UCF_People_PostType::append_metadata( $person );
?>
	<div class="col-md-3 col-sm-4">
		<figure class="figure person-figure">
			<img class="img-responsive" src="<?php echo $person->metadata['thumbnail_url']; ?>" alt="<?php echo $person->post_title; ?>">
			<figcaption class="figure-caption"><?php echo $person->post_title; ?></figcaption>
		</figure>
	</div>
<?php
	endforeach;
?>
	</div>
<?php
	return ob_get_clean();
}

add_shortcode( 'people-list', 'ucf_people_list_shortcode' );
?>
