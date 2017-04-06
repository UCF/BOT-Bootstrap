<?php
/**
 * Returns a person list
 **/
function ucf_people_list_shortcode( $atts, $content='' ) {
	$atts = shortcode_atts(
		array(
			'people_group' => null,
			'category'     => null,
			'positions'    => false,
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

		// Create iterator for layout here.
	$i = 0;

	ob_start();

	if ( $atts['positions'] ) {
		$chair_id = get_theme_mod_or_default( 'board_chair' );
		$vice_chair_id = get_theme_mod_or_default( 'board_vice_chair' );

		$exlude = array();

		if ( $chair_id ) {
			$chair = get_post( $chair_id );
			$chair = UCF_People_PostType::append_metadata( $chair );
			$exclude[] = $chair->ID;
		}

		if ( $vice_chair_id ) {
			$vice_chair = get_post( $vice_chair_id );
			$vice_chair = UCF_People_PostType::append_metadata( $vice_chair );
			$exclude[] = $vice_chair->ID;
		}

		if ( count( $exclude ) > 0 ) {
			$args['post__not_in'] = $exclude;
		}
	}

	$people = get_posts( $args );
	$count = count( $people ) - 1;

	if ( $chair ) : 
?>
		<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
		<div class="col-md-4 col-sm-6">
			<?php echo get_person_markup( $chair, 'Board Chairman' ); ?>
		</div>
		<?php if ( $i % 3 === 2 ) : ?></div><?php endif; $i++; $count++; ?>
<?php
	endif;

	if ( $vice_chair ) :
?>
		<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
		<div class="col-md-4 col-sm-6">
			<?php echo get_person_markup( $vice_chair, 'Board Vice Chairman' ); ?>
		</div>
		<?php if ( $i % 3 === 2 ) : ?></div><?php endif; $i++; $count++; ?>
<?php
	endif;

	foreach( $people as $person ) : 
		$person = UCF_People_PostType::append_metadata( $person );
?>
	<?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
	<div class="col-md-4 col-sm-6">
		<?php echo get_person_markup( $person ); ?>
	</div>
	<?php if ( $i % 3 === 2  || $i === $count ) : ?></div><?php endif; $i++; ?>
<?php
	endforeach;
	return ob_get_clean();
}

add_shortcode( 'people-list', 'ucf_people_list_shortcode' );

function ucf_people_group_charter_list_shortcode( $atts, $content="" ) {
	$none_term = term_exists( 'None', 'people_group' );
	$terms = get_terms( array(
		'taxonomy' => 'people_group',
		'exclude'  => array( $none_term )
	) );
	ob_start();
?>
	<ul class="list-unstyled">
	<?php foreach( $terms as $term ) : $charter = get_field( 'people_group_charter', 'people_group_' . $term->term_id ); ?>
		<li><a class="document" href=<?php echo $charter; ?>><?php echo $term->name; ?> Committee Charter</a></li>
	<?php endforeach; ?>
	</ul>
<?php
	return ob_get_clean();
}

add_shortcode( 'charter-list', 'ucf_people_group_charter_list_shortcode' );

?>
