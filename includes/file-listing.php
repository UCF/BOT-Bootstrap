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
			$class 	 = Document::get_meeting_doc_type($file->ID);
			$title   = Document::get_meeting_title($file, $class);
			$url     = wp_get_attachment_url($file->ID);
			$mime    = get_document_type(get_post_mime_type($file->ID));
		?>
		<tr class="<?=($i % 2) ? 'even' : 'odd';?>">
			<td class="document <?=$mime?>"><?=$title?></td>
			<td class="actions"><a href="<?=$url?>">view document</a></td>
		</tr>
		<?php 
			$i++;
			endforeach;
		?>
	</tbody>
</table>
<?php else:?>
<p>No <?=($minutes) ? 'minutes' : 'agendas' ?> have been published for <?=$year?>, check back later or view the archives.</p>
<?php endif;?>