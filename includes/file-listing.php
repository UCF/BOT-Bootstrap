<?=disallow_direct_load('file-listing.php')?>
<?php if(count($files) > 0):?>
<table class="document-table">
	<thead>
		<tr>
			<th scope="col">Document</th>
			<th scope="col">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0; foreach($files as $file): ?>
		<?php
			$title   = Document::get_meeting_title($file);
			$url     = Document::get_url($file);
			$mime    = Document::get_mimetype($file);
		?>
		<tr class="<?=($i % 2) ? 'even' : 'odd';?>">
			<td class="document <?=get_document_type($mime)?>"><?=$title?></td>
			<td class="actions"><a href="<?=$url?>">view document</a></td>
		</tr>
		<?php 
			$i++;
			endforeach;
		?>
	</tbody>
</table>
<?php else:?>
<p>No <?=($class == 'Minutes') ? 'minutes' : 'agendas' ?> have been published for <?=$year?>, check back later or view the archives.</p>
<?php endif;?>