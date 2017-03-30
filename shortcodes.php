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
		'order'          => 'ASC',
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
	foreach( $people as $i=>$person ) :
		$person = UCF_People_PostType::append_metadata( $person );
?>
	<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
	<div class="col-md-4 col-sm-6">
		<figure class="figure person-figure">
			<a href="<?php echo get_permalink( $person->ID ); ?>">
				<img class="img-responsive" src="<?php echo $person->metadata['thumbnail_url']; ?>" alt="<?php echo $person->post_title; ?>">
				<figcaption class="figure-caption"><?php echo $person->post_title; ?></figcaption>
			</a>
		</figure>
	</div>
	<?php if ( $i % 3 === 2  || $i == count( $people ) - 1 ) : ?></div><?php endif; ?>
<?php
	endforeach;
	return ob_get_clean();
}

add_shortcode( 'people-list', 'ucf_people_list_shortcode' );
?>
