<?php get_header(); the_post();?>
<?php
	$two_column = False;
	$page       = get_page(get_the_ID());
	$parent     = get_page($page->post_parent);
	$children   = get_posts(array(
		'post_type'   => get_custom_post_type('Committee'),
		'numberposts' => -1,
		'orderby'     => 'title',
		'order'       => 'ASC',
	));
	$middle     = floor(count($children)/2);
	$left_col   = array_slice($children, 0, $middle);
	$right_col  = array_slice($children, $middle);
	global $orgs;
	$orgs = array();
	
	function display_column($committees){
		global $orgs;
	?>
		
		<ul>
		<?php foreach($committees as $committee):
			if(stripos($committee->post_title, 'organization') === false): ?>
			<li class="committee box"><a href="<?=get_permalink($committee->ID)?>">
				<h3 class="name"><?=$committee->post_title?></h3>
				<div class="description">
					<?=get_post_meta($committee->ID, 'committee_description', True);?>
				</div>
			</a></li>
		<?php 
			else: 
				$orgs[] = $committee;
			endif; 
		endforeach;
		?>
		</ul>
		<?php
	}
?>
<div class="row page-content" id="<?=$post->post_name?>">
	<div class="span8">
		<h2 class="page-title"><?php the_title(); ?></h2>
		<?php the_content();?>
		<div class="row">
			<div class="span4 left-col">
				<?php display_column($left_col)?>
			</div>
			<div class="span4 last right-col">
				<?php display_column($right_col)?>
			</div>
		</div>
		
		<hr/>
		
		<ul>
		<?php foreach($orgs as $org):?>
			<li class="committee box"><a href="<?=get_permalink($org->ID)?>">
				<h3 class="name"><?=$org->post_title?></h3>
				<div class="description">
					<?=get_post_meta($org->ID, 'committee_description', True);?>
				</div>
			</a></li>
		<?php endforeach;?>
		</ul>
		
	</div>
	<?=get_sidebar()?>
</div>

<?php get_footer(); ?>