<?php

class Document extends CustomPostType{
	public
		$name           = 'document',
		$plural_name    = 'Documents',
		$singular_name  = 'Document',
		$add_new_item   = 'Add New Document',
		$edit_item      = 'Edit Document',
		$new_item       = 'New Document',
		$use_title      = True,
		$use_editor     = False,
		$use_shortcode  = True,
		$use_metabox    = True;
	
	public function fields(){
		$fields   = parent::fields();
		$fields[] = array(
			'name' => __('URL'),
			'desc' => __('Associate this document with a URL.  This will take precedence over any uploaded file, so leave empty if you want to use a file instead.'),
			'id'   => $this->options('name').'_url',
			'type' => 'text',
		);
		$fields[] = array(
			'name'    => __('File'),
			'desc'    => __('Associate this document with an already existing file.'),
			'id'      => $this->options('name').'_file',
			'type'    => 'file',
		);
		return $fields;
	}
	
	
	static function get_document_application($form){
		return mimetype_to_application(self::get_mimetype($form));
	}
	
	
	static function get_mimetype($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix   = post_type($form);
		$document = get_post(get_post_meta($form->ID, $prefix.'_file', True));
		
		$is_url = get_post_meta($form->ID, $prefix.'_url', True);
		
		return ($is_url) ? "text/html" : $document->post_mime_type;
	}
	
	
	static function get_title($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = post_type($form);
		
		return $form->post_title;
	}
	
	static function get_url($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = post_type($form);
		
		$x = get_post_meta($form->ID, $prefix.'_url', True);
		$y = wp_get_attachment_url(get_post_meta($form->ID, $prefix.'_file', True));
		
		if (!$x and !$y){
			return '#';
		}
		
		return ($x) ? $x : $y;
	}
	
	
	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}
		
		$class_name = get_custom_post_type($objects[0]->post_type);
		$class      = new $class_name;
		
		ob_start();
		?>
		<ul class="nobullet <?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li class="document <?=$class_name::get_document_application($o)?>">
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	
	
	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$title = Document::get_title($object);
		$url   = Document::get_url($object);
		$html = "<a href='{$url}'>{$title}</a>";
		return $html;
	}
}


class Video extends CustomPostType{
	public 
		$name           = 'video',
		$plural_name    = 'Videos',
		$singular_name  = 'Video',
		$add_new_item   = 'Add New Video',
		$edit_item      = 'Edit Video',
		$new_item       = 'New Video',
		$public         = True,
		$use_editor     = False,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True;
	
	public function get_player_html($video){
		return sc_video(array('video' => $video));
	}
	
	public function metabox(){
		$metabox = parent::metabox();
		
		$metabox['title']   = 'Videos on Media Page';
		$metabox['helptxt'] = 'Video icon will be resized to width 210px, height 118px.';
		return $metabox;
	}
	
	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'URL',
				'desc' => 'YouTube URL pointing to video.<br>  Example: http://www.youtube.com/watch?v=IrSeMg7iPbM',
				'id'   => $prefix.'url',
				'type' => 'text',
				'std'  => ''
			),
			array(
				'name' => 'Video Description',
				'desc' => 'Short description of the video.',
				'id'   => $prefix.'description',
				'type' => 'textarea',
				'std'  => ''
			),
			array(
				'name' => 'Shortcode',
				'desc' => 'To include this video in other posts, use the following shortcode:',
				'id'   => 'video_shortcode',
				'type' => 'shortcode',
				'value' => '[video name="TITLE"]',
			),
		);
	}
}


class Publication extends CustomPostType{
	public 
		$name           = 'publication',
		$plural_name    = 'Publications',
		$singular_name  = 'Publication',
		$add_new_item   = 'Add New Publication',
		$edit_item      = 'Edit Publication',
		$new_item       = 'New Publication',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True;
	
	public function toHTML($pub){
		return sc_publication(array('pub' => $pub));
	}
	
	public function metabox(){
		$metabox = parent::metabox();
		
		$metabox['title']   = 'Publications on Media Page';
		$metabox['helptxt'] = 'Publication cover icon will be resized to width 153px, height 198px.';
		return $metabox;
	}
	
	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name'  => 'Publication URL',
				'desc' => 'Example: <span style="font-family:monospace;font-weight:bold;color:#21759B;">http://publications.ucf.edu/publications/admissions-viewbook/</span>',
				'id'   => $prefix.'url',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => 'Shortcode',
				'desc' => 'To include this publication in other posts, use the following shortcode: <input disabled="disabled" type="text" value="[publication name=]" />',
				'id'   => 'publication_shortcode',
				'type' => 'help',
				'value' => '[publication name="TITLE"]',
			),
		);
	}
}

class Page extends CustomPostType {
	public
		$name           = 'page',
		$plural_name    = 'Pages',
		$singular_name  = 'Page',
		$add_new_item   = 'Add New Page',
		$edit_item      = 'Edit Page',
		$new_item       = 'New Page',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$built_in       = True;

	public function fields() {
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Hide Lower Section',
				'desc' => 'This section normally contains the Flickr, News and Events widgets. The footer will not be hidden',
				'id'   => $prefix.'hide_fold',
				'type' => 'checkbox',
			),
				array(
					'name' => 'Stylesheet',
					'desc' => '',
					'id' => $prefix.'stylesheet',
					'type' => 'file',
				),
		);
	}
}

/**
 * Describes a staff member
 *
 * @author Chris Conover
 **/
class Person extends CustomPostType
{
	/*
	The following query will pre-populate the person_orderby_name
	meta field with a guess of the last name extracted from the post title.
	
	>>>BE SURE TO REPLACE wp_<number>_... WITH THE APPROPRIATE SITE ID<<<
	
	INSERT INTO wp_29_postmeta(post_id, meta_key, meta_value) 
	(	SELECT	id AS post_id, 
						'person_orderby_name' AS meta_key, 
						REVERSE(SUBSTR(REVERSE(post_title), 1, LOCATE(' ', REVERSE(post_title)))) AS meta_value
		FROM		wp_29_posts AS posts
		WHERE		post_type = 'person' AND
						(	SELECT meta_id 
							FROM wp_29_postmeta 
							WHERE post_id = posts.id AND
										meta_key = 'person_orderby_name') IS NULL)
	*/

	public
		$name           = 'person',
		$plural_name    = 'People',
		$singular_name  = 'Person',
		$add_new_item   = 'Add Person',
		$edit_item      = 'Edit Person',
		$new_item       = 'New Person',
		$public         = True,
		$use_shortcode  = True,
		$use_metabox    = True,
		$use_thumbnails = True,
		$use_order      = True,
		$taxonomies     = array('org_groups', 'category');

		public function fields(){
			$fields = array(
				array(
					'name'    => __('Title Prefix'),
					'desc'    => '',
					'id'      => $this->options('name').'_title_prefix',
					'type'    => 'text',
				),
				array(
					'name'    => __('Title Suffix'),
					'desc'    => __('Be sure to include leading comma or space if neccessary.'),
					'id'      => $this->options('name').'_title_suffix',
					'type'    => 'text',
				),
				array(
					'name'    => __('Job Title'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_jobtitle',
					'type'    => 'text',
				),
				array(
					'name'    => __('Phone'),
					'desc'    => __('Separate multiple entries with commas.'),
					'id'      => $this->options('name').'_phones',
					'type'    => 'text',
				),
				array(
					'name'    => __('Email'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_email',
					'type'    => 'text',
				),
				array(
					'name'    => __('Order By Name'),
					'desc'    => __('Name used for sorting. Leaving this field blank may lead to an unexpected sort order.'),
					'id'      => $this->options('name').'_orderby_name',
					'type'    => 'text',
				),
			);
			return $fields;
		}

	public function get_objects($options=array()){
		$options['order']    = 'ASC';
		$options['orderby']  = 'person_orderby_name';
		$options['meta_key'] = 'person_orderby_name';
		return parent::get_objects($options);
	}

	public static function get_name($person) {
		$prefix = get_post_meta($person->ID, 'person_title_prefix', True);
		$suffix = get_post_meta($person->ID, 'person_title_suffix', True);
		$name = $person->post_title;
		return $prefix.' '.$name.' '.$suffix;
	}

	public static function get_phones($person) {
		$phones = get_post_meta($person->ID, 'person_phones', True);
		return ($phones != '') ? explode(',', $phones) : array();
	}

	public function objectsToHTML($people, $css_classes) {
		ob_start();?>
		<div class="row">
			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col" class="name">Name</th>
							<th scope="col" class="job_title">Title</th>
							<th scope="col" class="phones">Phone</th>
							<th scope="col" class="email">Email</th>
						</tr>
					</thead>
					<tbody>
				<?
				foreach($people as $person) { 
					$email = get_post_meta($person->ID, 'person_email', True); 
					$link = ($person->post_content == '') ? False : True; ?>
						<tr>
							<td class="name">
								<?if($link) {?><a href="<?=get_permalink($person->ID)?>"><?}?>
									<?=$this->get_name($person)?>
								<?if($link) {?></a><?}?>
							</td>
							<td class="job_title">
								<?if($link) {?><a href="<?=get_permalink($person->ID)?>"><?}?>
								<?=get_post_meta($person->ID, 'person_jobtitle', True)?>
								<?if($link) {?></a><?}?>
							</td> 
							<td class="phones"><?php if(($link) && ($this->get_phones($person))) {?><a href="<?=get_permalink($person->ID)?>">
								<?php } if($this->get_phones($person)) {?>
									<ul class="unstyled"><?php foreach($this->get_phones($person) as $phone) { ?><li><?=$phone?></li><?php } ?></ul>
								<?php } if(($link) && ($this->get_phones($person))) {?></a><?php }?></td>
							<td class="email"><?=(($email != '') ? '<a href="mailto:'.$email.'">'.$email.'</a>' : '')?></td>
						</tr>
				<? } ?>
				</tbody>
			</table> 
		</div>
	</div><?
	return ob_get_clean();
	}
} // END class 

class Post extends CustomPostType {
	public
		$name           = 'post',
		$plural_name    = 'Posts',
		$singular_name  = 'Post',
		$add_new_item   = 'Add New Post',
		$edit_item      = 'Edit Post',
		$new_item       = 'New Post',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$taxonomies     = array('post_tag', 'category'),
		$built_in       = True;

	public function fields() {
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Hide Lower Section',
				'desc' => 'This section normally contains the Flickr, News and Events widgets. The footer will not be hidden',
				'id'   => $prefix.'hide_fold',
				'type' => 'checkbox',
			),
				array(
					'name' => 'Stylesheet',
					'desc' => '',
					'id' => $prefix.'stylesheet',
					'type' => 'file',
				),
		);
	}
}

?>