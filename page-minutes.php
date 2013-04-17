<?php get_header(); the_post();?>
	<?php
		$agendas = ($class == 'Agenda') ? True : False;
		$minutes = ($class == 'Minutes') ? True : False;
	?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<?=display_agenda_minutes_pages($post, $agendas, $minutes);?>
		<?=get_sidebar();?>
	</div>
<?php get_footer(); ?>