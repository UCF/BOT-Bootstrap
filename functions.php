<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.
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
?>