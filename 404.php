<?php @header("HTTP/1.1 404 Not found", true, 404);?>
<?php disallow_direct_load('404.php');?>

<?php get_header(); the_post();?>
<div class="container">
	<div class="row">
		<div class="span12">
			<h2>Page Not Found</h2>
			<?php 
				$page = get_page_by_title('404');
				if($page){
					$content = $page->post_content;
					$content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]>', $content);
				}
			?>
			<?php if($content):?>
			<?=$content?>
			<?php else:?>
			<p>The page you requested doesn't exist.</p>
			<?php endif;?>
		</div>
	</div>
</div>
<?php get_footer();?>