<?php

/**
 * Abstract class for defining custom post types.  
 * 
 **/
abstract class CustomPostType{
	public 
		$name           = 'custom_post_type',
		$plural_name    = 'Custom Posts',
		$singular_name  = 'Custom Post',
		$add_new_item   = 'Add New Custom Post',
		$edit_item      = 'Edit Custom Post',
		$new_item       = 'New Custom Post',
		$public         = True,  # I dunno...leave it true
		$use_title      = True,  # Title field
		$use_editor     = True,  # WYSIWYG editor, post content field
		$use_revisions  = True,  # Revisions on post content and titles
		$use_thumbnails = False, # Featured images
		$use_order      = False, # Wordpress built-in order meta data
		$use_metabox    = False, # Enable if you have custom fields to display in admin
		$use_shortcode  = False, # Auto generate a shortcode for the post type
		                         # (see also objectsToHTML and toHTML methods)
		$taxonomies     = array('post_tag'),
		$built_in       = False,

		# Optional default ordering for generic shortcode if not specified by user.
		$default_orderby = null,
		$default_order   = null;
	
	
	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.  Returns an array of objects.
	 **/
	public function get_objects($options=array()){

		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options('name'),
		);
		$options = array_merge($defaults, $options);
		$objects = get_posts($options);
		return $objects;
	}
	
	
	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 **/
	public function get_objects_as_options($options=array()){
		$objects = $this->get_objects($options);
		
		if (!$objects) { return array('no objects returned'); }
		
		$opt     = array();
		foreach($objects as $o){
			switch(True){
				case $this->options('use_title'):
					$opt[$o->post_title] = $o->ID;
					break;
				default:
					$opt[$o->ID] = $o->ID;
					break;
			}
		}
		return $opt;
	}
	
	
	/**
	 * Return the instances values defined by $key.
	 **/
	public function options($key){
		$vars = get_object_vars($this);
		return $vars[$key];
	}
	
	
	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 **/
	public function fields(){
		return array();
	}
	
	
	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 **/
	public function supports(){
		#Default support array
		$supports = array();
		if ($this->options('use_title')){
			$supports[] = 'title';
		}
		if ($this->options('use_order')){
			$supports[] = 'page-attributes';
		}
		if ($this->options('use_thumbnails')){
			$supports[] = 'thumbnail';
		}
		if ($this->options('use_editor')){
			$supports[] = 'editor';
		}
		if ($this->options('use_revisions')){
			$supports[] = 'revisions';
		}
		return $supports;
	}
	
	
	/**
	 * Creates labels array, defining names for admin panel.
	 **/
	public function labels(){
		return array(
			'name'          => __($this->options('plural_name')),
			'singular_name' => __($this->options('singular_name')),
			'add_new_item'  => __($this->options('add_new_item')),
			'edit_item'     => __($this->options('edit_item')),
			'new_item'      => __($this->options('new_item')),
		);
	}
	
	
	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 **/
	public function metabox(){
		if ($this->options('use_metabox')){
			return array(
				'id'       => $this->options('name').'_metabox',
				'title'    => __($this->options('singular_name').' Fields'),
				'page'     => $this->options('name'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
			);
		}
		return null;
	}
	
	
	/**
	 * Registers metaboxes defined for custom post type.
	 **/
	public function register_metaboxes(){
		if ($this->options('use_metabox')){
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
			);
		}
	}
	
	
	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 **/
	public function register(){
		$registration = array(
			'labels'     => $this->labels(),
			'supports'   => $this->supports(),
			'public'     => $this->options('public'),
			'taxonomies' => $this->options('taxonomies'),
			'_builtin'   => $this->options('built_in')
		);
		
		if ($this->options('use_order')){
			$registration = array_merge($registration, array('hierarchical' => True,));
		}
		
		register_post_type($this->options('name'), $registration);
		
		if ($this->options('use_shortcode')){
			add_shortcode($this->options('name').'-list', array($this, 'shortcode'));
		}
	}
	
	
	/**
	 * Shortcode for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 **/
	public function shortcode($attr){
		$default = array(
			'type' => $this->options('name'),
		);
		if (is_array($attr)){
			$attr = array_merge($default, $attr);
		}else{
			$attr = $default;
		}
		return sc_object_list($attr);
	}
	
	
	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}
		
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		ob_start();
		?>
		<ul class="<?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li>
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
		$html = '<a href="'.get_permalink($object->ID).'">'.$object->post_title.'</a>';
		return $html;
	}
}

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
	
	static function get_meeting_doc_type($post_id) {
		global $wpdb;

		$sql = "
			SELECT post_id
			FROM   $wpdb->postmeta meta
			WHERE
				meta_key = 'meeting_minutes'
				AND meta_value = $post_id
		";

		$rows = $wpdb->get_results($sql);
		if(count($rows) > 0) {
			return 'Minutes';
		} else {
			$sql = "
				SELECT post_id
				FROM   $wpdb->postmeta meta
				WHERE
					meta_key = 'meeting_agenda'
					AND meta_value = $post_id
			";
			$rows = $wpdb->get_results($sql);
			if(count($rows) > 0) {
				return 'Agenda';
			} else {
				return False;
			}
		}
	}

	static function get_meeting($file, $doc_type) {
		$meta_key = '';
		switch($doc_type) {
			case 'Agenda':
				$meta_key = 'meeting_agenda';
				break;
			case 'Minutes':
				$meta_key = 'meeting_minutes';
				break;
		}
		$args = array(
			'post_type' => 'meeting',
			'numberposts' => 1,
			'meta_key'	=> $meta_key,
			'meta_value' => $file->ID,
		);
		$posts = get_posts($args);
		return ($posts) ? $posts[0] : False;
 	}

	static function get_document_application($form){
		return mimetype_to_application(self::get_mimetype($form));
	}
	
	
	static function get_mimetype($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = post_type($form);
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
	
	static function get_meeting_title($document){
		if (is_numeric($document)){
			$document = get_post($document);
		}

		if( ($doc_type = Document::get_meeting_doc_type($document->ID)) !== False
			&& ($meeting = Document::get_meeting($document, $doc_type)) !== False
			&& ($meeting_date = get_post_meta($meeting->ID, 'meeting_date', True)) !== False) {
				return date('F j, Y', strtotime($meeting_date));
		} else {
			return 'Unknown';
		}
	}

	static function get_url($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = get_post_type($form);
		
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

/**
 * Describes a staff member
 *
 * @author Chris Conover
 **/
class Person extends CustomPostType
{
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
		$taxonomies     = array('person_label');

		public function fields(){
			$fields = array(
				array(
					'name'    => __('Job Title'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_title',
					'type'    => 'text',
				),
				array(
					'name'    => __('Phone'),
					'desc'    => __('Separate multiple entries with commas.'),
					'id'      => $this->options('name').'_phone',
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
	
	/** 
	 * Get a list of posts, check them against the person_label provided,
	 * and return an array of field options
	 **/
	public function get_committee_options($person_label=''){
		$args = array(
			'numberposts' 	=> -1,
			'post_type' 	=> 'person',
			'orderby'       => 'title',
			'order'         => 'ASC',
		);
		$objects = get_posts($args);
		$opt = array();
		foreach($objects as $o){
			// We check here instead of doing a tax_query within the get_posts args
			// because it fails to obey the taxonomy term for whatever reason
			if (in_array($person_label, wp_get_post_terms($o->ID, 'person_label', array('fields' => 'slugs')))) {
				$opt[$o->post_title] = $o->ID;
			}
		}
		return $opt;
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
		ob_start();
		?>
		<ul class="person-list">
		<?php foreach($people as $person) { ?>
			<li>
			<div class="person" id="person-<?=$person->ID?>"><a href="<?=get_permalink($person->ID)?>">
					<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($person->ID), 'full');?>
					<div class="crop">
						<?php if($image[0]):?>
						<img src="<?=$image[0]?>" alt="<?=$person->post_title?>" />
						<?php else:?>
						<img src="<?=THEME_IMG_URL?>/no-photo.jpg" alt="no photo" />
						<?php endif;?>
					</div>
					<div class="information">
						<h3>
							<?php
								$title = get_post_meta($person->ID, 'person_title', true) ? get_post_meta($person->ID, 'person_title', true) : '&nbsp;';
								$phone = get_post_meta($person->ID, 'person_phone', true) ? get_post_meta($person->ID, 'person_phone', true) : '&nbsp;';
								$email = get_post_meta($person->ID, 'person_email', true) ? get_post_meta($person->ID, 'person_email', true) : '&nbsp;';
							?>
							<span class="name"><?=$person->post_title?></span>
							<span class="title"><?=$title?></span>
						</h3>
					</div>
				</a>
			<div class="ie-clear"><!-- --></div>
		</div>
		</li>
		<? } ?>
	</ul>
		<?php
		return ob_get_clean();
	}
} // END class 

class Committee extends CustomPostType{
	public
		$name           = 'committee',
		$plural_name    = 'Committees',
		$singular_name  = 'Committee',
		$add_new_item   = 'Add Committee',
		$edit_item      = 'Edit Committee',
		$new_item       = 'New Committee',
		$public         = True,
		$use_title      = True,
		$use_editor     = True,
		$use_metabox    = True;
	
	
	public function get_members($post, $type, $obj=False){
		if (!is_numeric($post)){
			$post = $post->ID;
		}
		$members = get_post_meta($post, $this->options('name').'_'.$type, True);
		if ($members == ''){
			return array();
		}else{
			$person = new Person();
			$people = $person->get_objects(array(
				'post_status' => 'publish',
			));
			$published = array_map(create_function('$t', 'return $t->ID;'), $people);
			
			# On some systems this sometimes comes out as a serialized string
			# not sure why that happens.  But this fixes the issue.
			if (gettype($members) == 'string'){$members = unserialize($members);}
			
			if($obj){
				$t = array();
				foreach($members as $member=>$role){
					$member = get_post($member);
					$member->position = $role;
					$t[] = $member;
				}
				$members = $t;
				usort($members, 'Committee::_sort_members');
			}
			return $members;
		}
	}
	
	static function _sort_members($a, $b){
		$titles = array('chair', 'vice chair', 'ex officio');
		if (!$a->position and !$b->position){
			$names_a = array_pop(explode(' ', $a->post_title));
			$names_b = array_pop(explode(' ', $b->post_title));
			
			if ($names_a < $names_b){
				return -1;
			}else{
				return 1;
			}
		}
		if (!$a->position and $b->position){
			return 1;
		}
		if ($a->position and !$b->position){
			return -1;
		}
		
		$a_index = array_search(strtolower($a->position), $titles);
		$b_index = array_search(strtolower($b->position), $titles);
		
		if ($a_index === $b_index){
			return 0;
		}
		if ($a_index === False){
			return 1;
		}
		if ($b_index === False){
			return -1;
		}
		return ($a_index < $b_index) ? -1 : 1 ;
	}
	
	
	public function documents(){
		$args = array(
			'post_type'     => 'attachment',
			'category_name' => 'charter',
			'numberposts'   => -1,
		);
		$medias = get_posts($args);
		$return = array();
		foreach($medias as $media){
			$return[$media->post_title] = $media->ID;
		}
		return $return;
	}
	
	
	public function fields(){
		$documents = new Document();
		return array(
			array(
				'name' => __('Description'),
				'desc' => __('Description of the committee\'s responsibilities and duties'),
				'id'   => $this->options('name').'_description',
				'type' => 'textarea',
			),
			array(
				'name'    => __('Committee Charter'),
				'desc'    => __('Document for this committee\'s charter'),
				'id'      => $this->options('name').'_charter',
				'type'    => 'select',
				'options' => $documents->get_objects_as_options(),
			),
			array(
				'name'    => __('Committee Members'),
				'desc'    => __('People tagged as "Trustee" who belong to this committee and the positions they hold within it.'),
				'id'      => $this->options('name').'_members',
				'type'    => 'members',
				'options' => Person::get_committee_options('trustee'),
			),
			array(
				'name'    => __('Committee Staff'),
				'desc'    => __('People tagged as "Staff" who belong to this committee and the positions they hold within it.'),
				'id'      => $this->options('name').'_staff',
				'type'    => 'staff',
				'options' => Person::get_committee_options('committee-staff'),
			),
		);
	}
}

abstract class CommitteeRelated extends CustomPostType{
	public
		$name           = 'committee_related',
		$public         = True,
		$use_editor     = True,
		$use_metabox    = True,
		$use_title      = True;
	
	public function fields(){
		$committees = new Committee();
		return array(
			array(
				'name'    => __('Committee'),
				'desc'    => __('The associated committee.'),
				'id'      => $this->options('name').'_committee',
				'type'    => 'select',
				'options' => $committees->get_objects_as_options(),
			),
		);
	}
}


class Meeting extends CommitteeRelated{
	public
		$name           = 'meeting',
		$plural_name    = 'Meetings',
		$singular_name  = 'Meeting',
		$add_new_item   = 'Add New Meeting',
		$edit_item      = 'Edit Meeting',
		$new_item       = 'New Meeting',
		$public         = True,
		$use_editor     = False,
		$use_metabox    = True;
	
	static $type_choices = array(
		'Regular'   => 0,
		'Committee' => 1,
	);
	
	public function fields(){
		$parent_fields = parent::fields();
		$committee     = array_shift($parent_fields);
		$committee['desc'] = __('The associated committee, if applicable.');
		$fields        = array(
			$committee,
			array(
				'name'    => __('Agenda'),
				'desc'    => __('Meeting agenda.'),
				'id'      => $this->options('name').'_agenda',
				'type'    => 'file',
			),
			array(
				'name'    => __('Minutes'),
				'desc'    => __('Meeting minutes.'),
				'id'      => $this->options('name').'_minutes',
				'type'    => 'file',
			),
			array(
				'name'    => __('Location'),
				'desc'    => __('Location of the meeting'),
				'id'      => $this->options('name').'_location',
				'type'    => 'text',
			),
			array(
				'name'    => __('Date'),
				'desc'    => __('Date of the meeting. Must be in MM/DD/YY format'),
				'id'      => $this->options('name').'_date',
				'type'    => 'date',
			),
			array(
				'name'    => __('Time Start'),
				'desc'    => __('Start time or TBA'),
				'id'      => $this->options('name').'_start_time',
				'type'    => 'time',
			),
			array(
				'name'    => __('Time End'),
				'desc'    => __('End time or TBA'),
				'id'      => $this->options('name').'_end_time',
				'type'    => 'time',
			),
			array(
				'name'    => __('Special Meeting Name'),
				'desc'    => __('(Optional) Add a special name for the meeting.'),
				'id'      => $this->options('name').'_special_meeting',
				'type'    => 'text',
			), 
		);
		$fields = array_merge(
			$parent_fields,
			$fields
		);
		return $fields;
	}
}
?>