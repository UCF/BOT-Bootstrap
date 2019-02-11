<?php

/***************************************************************************
 * CLASSES
 *
 ***************************************************************************/

/**
 * The Config class provides a set of static properties and methods which store
 * and facilitate configuration of the theme.
 **/
class ArgumentException extends Exception{}
class Config{
	static
		$theme_settings    = array(), # Theme settings
		$custom_post_types = array(), # Custom post types to register
		$custom_taxonomies = array(), # Custom taxonomies to register
		$styles            = array(), # Stylesheets to register
		$scripts           = array(), # Scripts to register
		$links             = array(), # <link>s to include in <head>
		$metas             = array(); # <meta>s to include in <head>


	/**
	 * Creates and returns a normalized name for a resource url defined by $src.
	 **/
	static function generate_name($src, $ignore_suffix=''){
		$base = basename($src, $ignore_suffix);
		$name = slug($base);
		return $name;
	}


	/**
	 * Registers a stylesheet with built-in wordpress style registration.
	 * Arguments to this can either be a string or an array with required css
	 * attributes.
	 *
	 * A string argument will be treated as the src value for the css, and all
	 * other attributes will default to the most common values.  To override
	 * those values, you must pass the attribute array.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'theme-style',  # Wordpress uses this to identify queued files
	 *    'media' => 'all',          # What media types this should apply to
	 *    'admin' => False,          # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.css',
	 * );
	 **/
	static function add_css($attr){
		# Allow string arguments, defining source.
		if (is_string($attr)){
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}

		if (!isset($attr['src'])){
			throw new ArgumentException('add_css expects argument array to contain key "src"');
		}
		$default = array(
			'name'  => self::generate_name($attr['src'], '.css'),
			'media' => 'all',
			'admin' => False,
		);
		$attr = array_merge($default, $attr);

		$is_admin = (is_admin() or is_login());

		if (
			($attr['admin'] and $is_admin) or
			(!$attr['admin'] and !$is_admin)
		){
			wp_deregister_style($attr['name']);
			wp_enqueue_style($attr['name'], $attr['src'], null, null, $attr['media']);
		}
	}


	/**
	 * Functions similar to add_css, but appends scripts to the footer instead.
	 * Accepts a string or array argument, like add_css, with the string
	 * argument assumed to be the src value for the script.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'jquery',  # Wordpress uses this to identify queued files
	 *    'admin' => False,     # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.js',
	 * );
	 **/
	static function add_script($attr){
		# Allow string arguments, defining source.
		if (is_string($attr)){
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}

		if (!isset($attr['src'])){
			throw new ArgumentException('add_script expects argument array to contain key "src"');
		}
		$default = array(
			'name'  => self::generate_name($attr['src'], '.js'),
			'admin' => False,
		);
		$attr = array_merge($default, $attr);

		$is_admin = (is_admin() or is_login());

		if (
			($attr['admin'] and $is_admin) or
			(!$attr['admin'] and !$is_admin)
		){
			# Override previously defined scripts
			wp_deregister_script($attr['name']);
			wp_enqueue_script($attr['name'], $attr['src'], null, null, True);
		}
	}
}

class ThemeConfig {
	static
		$setting_defaults  = array(), # Default settings for theme mods
		$styles            = array(), # Stylesheets to register
		$scripts           = array(), # Scripts to register
		$links             = array(), # <link>s to include in <head>
		$metas             = array(); # <meta>s to include in <head>
	/**
	 * Creates and returns a normalized name for a resource url defined by $src.
	 * */
	static function generate_name( $src, $ignore_suffix='' ) {
		$base = basename( $src, $ignore_suffix );
		$name = slugify( $base );
		return $name;
	}
	/**
	 * Registers a stylesheet with built-in wordpress style registration.
	 * Arguments to this can either be a string or an array with required css
	 * attributes.
	 *
	 * A string argument will be treated as the src value for the css, and all
	 * other attributes will default to the most common values.  To override
	 * those values, you must pass the attribute array.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'theme-style',  # Wordpress uses this to identify queued files
	 *    'media' => 'all',          # What media types this should apply to
	 *    'admin' => False,          # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.css',
	 * );
	 * */
	static function add_css( $attr ) {
		// Allow string arguments, defining source.
		if ( is_string( $attr ) ) {
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}
		if ( !isset( $attr['src'] ) ) {
			throw new ArgumentException( 'add_css expects argument array to contain key "src"' );
		}
		$default = array(
			'name'  => self::generate_name( $attr['src'], '.css' ),
			'media' => 'all',
			'admin' => False,
		);
		$attr = array_merge( $default, $attr );
		$is_admin = ( is_admin() or is_login() );
		if (
			( $attr['admin'] and $is_admin ) or
			( !$attr['admin'] and !$is_admin )
		) {
			wp_deregister_style( $attr['name'] );
			wp_enqueue_style( $attr['name'], $attr['src'], null, null, $attr['media'] );
		}
	}
	/**
	 * Functions similar to add_css, but appends scripts to the footer instead.
	 * Accepts a string or array argument, like add_css, with the string
	 * argument assumed to be the src value for the script.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'jquery',  # Wordpress uses this to identify queued files
	 *    'admin' => False,     # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.js',
	 * );
	 * */
	static function add_script( $attr ) {
		// Allow string arguments, defining source.
		if ( is_string( $attr ) ) {
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}
		if ( !isset( $attr['src'] ) ) {
			throw new ArgumentException( 'add_script expects argument array to contain key "src"' );
		}
		$default = array(
			'name'  => self::generate_name( $attr['src'], '.js' ),
			'admin' => False,
		);
		$attr = array_merge( $default, $attr );
		$is_admin = ( is_admin() or is_login() );
		if (
			( $attr['admin'] and $is_admin ) or
			( !$attr['admin'] and !$is_admin )
		) {
			// Override previously defined scripts
			wp_deregister_script( $attr['name'] );
			wp_enqueue_script( $attr['name'], $attr['src'], null, null, True );
		}
	}
}

/**
 * Abstracted field class, all form fields should inherit from this.
 *
 * @package default
 * @author Jared Lang
 **/
abstract class Field{
	protected function check_for_default(){
		if ($this->value === null){
			$this->value = $this->default;
		}
	}

	function __construct($attr){
		$this->name        = @$attr['name'];
		$this->id          = @$attr['id'];
		$this->value       = @$attr['value'];
		$this->description = @$attr['description'];
		$this->default     = @$attr['default'];

		$this->check_for_default();
	}

	function label_html(){
		ob_start();
		?>
		<label class="block" for="<?=htmlentities($this->id)?>"><?=__($this->name)?></label>
		<?php
		return ob_get_clean();
	}

	function input_html(){
		return "Abstract Input Field, Override in Descendants";
	}

	function description_html(){
		ob_start();
		?>
		<?php if($this->description):?>
		<p class="description"><?=__($this->description)?></p>
		<?php endif;?>
		<?php
		return ob_get_clean();
	}

	function html(){
		$label       = $this->label_html();
		$input       = $this->input_html();
		$description = $this->description_html();

		return $label.$input.$description;
	}
}


/**
 * Abstracted choices field.  Choices fields have an additional attribute named
 * choices which allow a selection of values to be chosen from.
 *
 * @package default
 * @author Jared Lang
 **/
abstract class ChoicesField extends Field{
	function __construct($attr){
		$this->choices = @$attr['choices'];
		parent::__construct($attr);
	}
}


/**
 * TextField class represents a simple text input
 *
 * @package default
 * @author Jared Lang
 **/
class TextField extends Field{
	protected $type_attr = 'text';

	function input_html(){
		ob_start();
		?>
		<input type="<?=$this->type_attr?>" id="<?=htmlentities($this->id)?>" name="<?=htmlentities($this->id)?>" value="<?=htmlentities($this->value)?>" />
		<?php
		return ob_get_clean();
	}
}


/**
 * PasswordField can be used to accept sensitive information, not encrypted on
 * wordpress' end however.
 *
 * @package default
 * @author Jared Lang
 **/
class PasswordField extends TextField{
	protected $type_attr = 'password';
}


/**
 * TextareaField represents a textarea form element
 *
 * @package default
 * @author Jared Lang
 **/
class TextareaField extends Field{
	function input_html(){
		ob_start();
		?>
		<textarea id="<?=htmlentities($this->id)?>" name="<?=htmlentities($this->id)?>"><?=htmlentities($this->value)?></textarea>
		<?php
		return ob_get_clean();
	}
}


/**
 * Select form element
 *
 * @package default
 * @author Jared Lang
 **/
class SelectField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<select name="<?=htmlentities($this->id)?>" id="<?=htmlentities($this->id)?>">
			<?php foreach($this->choices as $key=>$value):?>
			<option<?php if($this->value == $value):?> selected="selected"<?php endif;?> value="<?=htmlentities($value)?>"><?=htmlentities($key)?></option>
			<?php endforeach;?>
		</select>
		<?php
		return ob_get_clean();
	}
}


/**
 * Radio form element
 *
 * @package default
 * @author Jared Lang
 **/
class RadioField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<ul class="radio-list">
			<?php $i = 0; foreach($this->choices as $key=>$value): $id = htmlentities($this->id).'_'.$i++;?>
			<li>
				<input<?php if($this->value == $value):?> checked="checked"<?php endif;?> type="radio" name="<?=htmlentities($this->id)?>" id="<?=$id?>" value="<?=htmlentities($value)?>" />
				<label for="<?=$id?>"><?=htmlentities($key)?></label>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		return ob_get_clean();
	}
}


/**
 * Checkbox form element
 *
 * @package default
 * @author Jared Lang
 **/
class CheckboxField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<ul class="checkbox-list">
			<?php $i = 0; foreach($this->choices as $key=>$value): $id = htmlentities($this->id).'_'.$i++;?>
			<li>
				<input<?php if(is_array($this->value) and in_array($value, $this->value)):?> checked="checked"<?php endif;?> type="checkbox" name="<?=htmlentities($this->id)?>[]" id="<?=$id?>" value="<?=htmlentities($value)?>" />
				<label for="<?=$id?>"><?=htmlentities($key)?></label>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		return ob_get_clean();
	}
}


/**
 * Convenience class to calculate total execution times.
 *
 * @package default
 * @author Jared Lang
 **/
class Timer{
	private $start_time  = null;
	private $end_time    = null;

	public function start_timer(){
		$this->start_time = microtime(True);
		$this->end_time   = null;
	}

	public function stop_timer(){
		$this->end_time = microtime(True);
	}

	public function clear_timer(){
		$this->start_time = null;
		$this->end_time   = null;
	}

	public function reset_timer(){
		$this->clear_timer();
		$this->start_timer();
	}

	public function elapsed(){
		if ($this->end_time !== null){
			return $this->end_time - $this->start_time;
		}else{
			return microtime(True) - $this->start_time;
		}
	}

	public function __toString(){
		return $this->elapsed;
	}

	/**
	 * Returns a started instance of timer
	 *
	 * @return instance of Timer
	 * @author Jared Lang
	 **/
	public static function start(){
		$timer_instance = new self();
		$timer_instance->start_timer();
		return $timer_instance;
	}
}




/***************************************************************************
 * DEBUGGING FUNCTIONS
 *
 * Functions to assist in theme debugging.
 *
 ***************************************************************************/

/**
 * Given an arbitrary number of arguments, will return a string with the
 * arguments dumped recursively, similar to the output of print_r but with pre
 * tags wrapped around the output.
 *
 * @return string
 * @author Jared Lang
 **/
function dump(){
	$args = func_get_args();
	$out  = array();
	foreach($args as $arg){
		$out[] = print_r($arg, True);
	}
	$out = implode("<br />", $out);
	return "<pre>{$out}</pre>";
}


/**
 * Will add a debug comment to the output when the debug constant is set true.
 * Any value, including null, is enough to trigger it.
 *
 * @return void
 * @author Jared Lang
 **/
if (DEBUG){
	function debug($string){ /*
		print "<!-- DEBUG: {$string} -->\n"; */
	}
}else{
	function debug($string){return;}
}


/**
 * Will execute the function $func with the arguments passed via $args if the
 * debug constant is set true.  Returns whatever value the called function
 * returns, or void if debug is not set active.
 *
 * @return mixed
 * @author Jared Lang
 **/
if (DEBUG){
	function debug_callfunc($func, $args){
		return call_user_func_array($func, $args);
	}
}else{
	function debug_callfunc($func, $args){return;}
}


/**
 * Indent contents of $html passed by $n indentations.
 *
 * @return string
 * @author Jared Lang
 **/
function indent($html, $n){
	$tabs = str_repeat("\t", $n);
	$html = explode("\n", $html);
	foreach($html as $key=>$line){
		$html[$key] = $tabs.trim($line);
	}
	$html = implode("\n", $html);
	return $html;
}



/***************************************************************************
 * GENERAL USE FUNCTIONS
 *
 * Theme-wide general use functions. (Alphabetized)
 *
 ***************************************************************************/

/**
 * Walker function to add Bootstrap classes to nav menus using wp_nav_menu()
 *
 * based on https://gist.github.com/1597994
 **/
function bootstrap_menus() {
	class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {


			function start_lvl( &$output, $depth ) {

				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";

			}

			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$li_attributes = '';
				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;


				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}

			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

				if ( !$element )
					return;

				$id_field = $this->db_fields['id'];

				//display this element
				if ( is_array( $args[0] ) )
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) )
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);

				$id = $element->$id_field;

				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}

				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}

				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);

			}

		}
}
add_action( 'after_setup_theme', 'bootstrap_menus' );


/**
 * Strings passed to this function will be modified under the assumption that
 * they were outputted by wordpress' the_output filter.  It checks for a handful
 * of things like empty, unnecessary, and unclosed tags.
 *
 * @return string
 * @author Jared Lang
 **/
function cleanup($content){
	# Balance auto paragraphs
	$lines = explode("\n", $content);
	foreach($lines as $key=>$line){
		$null = null;
		$found_closed = preg_match_all('/<\/p>/', $line, $null);
		$found_opened = preg_match_all('/<p[^>]*>/', $line, $null);

		$diff = $found_closed - $found_opened;
		# Balanced tags
		if ($diff == 0){continue;}

		# missing closed
		if ($diff < 0){
			$lines[$key] = $lines[$key] . str_repeat('</p>', abs($diff));
		}

		# missing open
		if ($diff > 0){
			$lines[$key] = str_repeat('<p>', abs($diff)) . $lines[$key];
		}
	}
	$content = implode("\n", $lines);

	#Remove incomplete tags at start and end
	$content = preg_replace('/^<\/p>[\s]*/i', '', $content);
	$content = preg_replace('/[\s]*<p>$/i', '', $content);
	$content = preg_replace('/^<br \/>/i', '', $content);
	$content = preg_replace('/<br \/>$/i', '', $content);

	#Remove paragraph and linebreak tags wrapped around shortcodes
	$content = preg_replace('/(<p>|<br \/>)\[/i', '[', $content);
	$content = preg_replace('/\](<\/p>|<br \/>)/i', ']', $content);

	#Remove empty paragraphs
	$content = preg_replace('/<p><\/p>/i', '', $content);

	return $content;
}


/**
 * Creates a string of attributes and their values from the key/value defined by
 * $attr.  The string is suitable for use in html tags.
 *
 * @return string
 * @author Jared Lang
 **/
function create_attribute_string($attr){
	$attr_string = '';
	foreach($attr as $key=>$value){
		$attr_string .= " {$key}='{$value}'";
	}
	return $attr_string;
}


/**
 * Creates an arbitrary html element.  $tag defines what element will be created
 * such as a p, h1, or div.  $attr is an array defining attributes and their
 * associated values for the tag created. $content determines what data the tag
 * wraps.  And $self_close defines whether or not the tag should close like
 * <tag></tag> (False) or <tag /> (True).
 *
 * @return string
 * @author Jared Lang
 **/
function create_html_element($tag, $attr=array(), $content=null, $self_close=True){
	$attr_str = create_attribute_string($attr);
	if ($content){
		$element = "<{$tag}{$attr_str}>{$content}</{$tag}>";
	}else{
		if ($self_close){
			$element = "<{$tag}{$attr_str}/>";
		}else{
			$element = "<{$tag}{$attr_str}></{$tag}>";
		}
	}

	return $element;
}


/**
 * When called, prevents direct loads of the value of $page.
 **/
function disallow_direct_load($page){
	if ($page == basename($_SERVER['SCRIPT_FILENAME'])){
		include(TEMPLATEPATH.'/404.php');
		die();
	}
}


/**
 * Returns the name of the custom post type defined by $obj
 *
 * @return string
 * @author Jared Lang
 **/
function get_custom_post_type($obj, $instance=False){
	if ($obj == null){return null;}

	$installed = installed_custom_post_types();

	if (is_string($obj)){
		foreach($installed as $custom_post_type){
			if (
					($obj == get_class($custom_post_type)) or
					($obj == $custom_post_type->name)
				){
				if ($instance){
					return $custom_post_type;
				}
				else{
					return $custom_post_type->name;
				}
			}
		}
		return null;
	}

	if (get_class($obj) == 'stdClass' || get_class($obj) == 'WP_Post'){
		foreach($installed as $custom_post_type){
			if ($obj->post_type == $custom_post_type->name){
				if ($instance){
					return $custom_post_type;
				}else{
					return $custom_post_type->name;
				}
			}
		}
		return null;
	}

	return null;
}

/**
 * Returns true if the current request is on the login screen.
 *
 * @return boolean
 * @author Jared Lang
 **/
function is_login(){
	return in_array($GLOBALS['pagenow'], array(
			'wp-login.php',
			'wp-register.php',
	));
}

/**
* Fetches objects defined by arguments passed, outputs the objects according
* to the objectsToHTML method located on the object. Used by the auto
* generated shortcodes enabled on custom post types. See also:
*
* CustomPostType::objectsToHTML
* CustomPostType::toHTML
*
* @return string
* @author Jared Lang
**/
function sc_object_list($attrs, $options = array()){
	if (!is_array($attrs)){return '';}

	$default_options = array(
		'default_content' => null,
		'sort_func' => null,
		'objects_only' => False
	);

	extract(array_merge($default_options, $options));

	# set defaults and combine with passed arguments
	$default_attrs = array(
		'type'    => null,
		'limit'   => -1,
		'join'    => 'or',
		'class'   => '',
		'orderby' => 'menu_order title',
		'order'   => 'ASC',
		'offset'  => 0
	);
	$params = array_merge($default_attrs, $attrs);

	# verify options
	if ($params['type'] == null){
		return '<p class="error">No type defined for object list.</p>';
	}
	if (!is_numeric($params['limit'])){
		return '<p class="error">Invalid limit argument, must be a number.</p>';
	}
	if (!in_array(strtoupper($params['join']), array('AND', 'OR'))){
		return '<p class="error">Invalid join type, must be one of "and" or "or".</p>';
	}
	if (null == ($class = get_custom_post_type($params['type']))){
		return '<p class="error">Invalid post type.</p>';
	}

	$class = new $class;

	# Use post type specified ordering?
	if(!isset($attrs['orderby']) && !is_null($class->default_orderby)) {
		$params['orderby'] = $class->orderby;
	}
	if(!isset($attrs['order']) && !is_null($class->default_order)) {
		$params['order'] = $class->default_order;
	}

	# get taxonomies and translation
	$translate = array(
		'tags' => 'post_tag',
		'categories' => 'category',
		'labels' => 'person_label'
	);
	$taxonomies = array_diff(array_keys($attrs), array_keys($default_attrs));

	# assemble taxonomy query
	$tax_queries = array();
	$tax_queries['relation'] = strtoupper($params['join']);

	foreach($taxonomies as $tax){
		$terms = $params[$tax];
		$terms = trim(preg_replace('/\s+/', ' ', $terms));
		$terms = explode(' ', $terms);

		if (array_key_exists($tax, $translate)){
			$tax = $translate[$tax];
		}

		$tax_queries[] = array(
			'taxonomy' => $tax,
			'field' => 'slug',
			'terms' => $terms,
		);
	}

	# perform query
	$query_array = array(
		'tax_query'      => $tax_queries,
		'post_status'    => 'publish',
		'post_type'      => $params['type'],
		'posts_per_page' => $params['limit'],
		'orderby'        => $params['orderby'],
		'order'          => $params['order'],
		'offset'         => $params['offset']
	);

	$query = new WP_Query($query_array);

	global $post;
	$objects = array();
	while($query->have_posts()){
		$query->the_post();
		$objects[] = $post;
	}

	# Custom sort if applicable
	if ($sort_func !== null){
		usort($objects, $sort_func);
	}

	wp_reset_postdata();

	if($objects_only) {
		return $objects;
	}

	if (count($objects)){
		$html = $class->objectsToHTML($objects, $params['class']);
	}else{
		$html = $default_content;
	}
	return $html;
}


/**
 * Sets the default values for any theme options that are not currently stored.
 *
 * @return void
 * @author Jared Lang
 **/
function set_defaults_for_options(){
	$values  = get_option(THEME_OPTIONS_NAME);
	if ($values === False or is_string($values)){
		add_option(THEME_OPTIONS_NAME);
		$values = array();
	}

	$options = array();
	foreach(Config::$theme_settings as $option){
		if (is_array($option)){
			$options = array_merge($option, $options);
		}else{
			$options[] = $option;
		}
	}

	foreach ($options as $option){
		$key = str_replace(
			array(THEME_OPTIONS_NAME, '[', ']'),
			array('', '', ''),
			$option->id
		);
		if ($option->default !== null and !isset($values[$key])){
			$values[$key] = $option->default;
			update_option(THEME_OPTIONS_NAME, $values);
		}
	}
}


/**
 * Runs as wordpress is shutting down.
 *
 * @return void
 * @author Jared Lang
 **/
function __shutdown__(){
	global $timer;
	$elapsed = round($timer->elapsed() * 1000);
	debug("{$elapsed} milliseconds");
}
add_action('shutdown', '__shutdown__');


/**
 * Will return a string $s normalized to a slug value.  The optional argument,
 * $spaces, allows you to define what spaces and other undesirable characters
 * will be replaced with.  Useful for content that will appear in urls or
 * turning plain text into an id.
 *
 * @return string
 * @author Jared Lang
 **/
function slug($s, $spaces='-'){
	$s = strtolower($s);
	$s = preg_replace('/[-_\s\.]/', $spaces, $s);
	return $s;
}




/***************************************************************************
 * HEADER AND FOOTER FUNCTIONS
 *
 * Functions that generate output for the header and footer, including
 * <meta>, <link>, body classes and Facebook OpenGraph
 * stuff.
 *
 ***************************************************************************/

/**
 * Header content
 *
 * @return string
 * @author Jared Lang
 **/
function header_($tabs=2){
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');

	ob_start();
	print header_meta()."\n";
	wp_head();
	print header_links()."\n";

	return indent(ob_get_clean(), $tabs);
}


/**
 * Footer content
 *
 * @return string
 * @author Jared Lang
 **/
function footer_($tabs=2){
	ob_start();
	wp_footer();
	$html = ob_get_clean();
	return indent($html, $tabs);
}

/**
 * Handles generating the meta tags configured for this theme.
 *
 * @return string
 * @author Jared Lang
 **/
function header_meta(){
	$metas     = Config::$metas;
	$meta_html = array();
	$defaults  = array();

	foreach($metas as $meta){
		$meta        = array_merge($defaults, $meta);
		$meta_html[] = create_html_element('meta', $meta);
	}
	$meta_html = implode("\n", $meta_html);
	return $meta_html;
}


/**
 * Handles generating the link tags configured for this theme.
 *
 * @return string
 * @author Jared Lang
 **/
function header_links(){
	$links      = Config::$links;
	$links_html = array();
	$defaults   = array();

	foreach($links as $link){
		$link         = array_merge($defaults, $link);
		$links_html[] = create_html_element('link', $link, null, True);
	}

	$links_html = implode("\n", $links_html);
	return $links_html;
}


/***************************************************************************
 * SETTINGS RETRIEVAL FUNCTIONS
 *
 ***************************************************************************/
/**
 * Returns the default value for a setting in ThemeConfig::$setting_defaults,
 * or $fallback if one is not available.
 **/
function get_setting_default( $setting, $fallback=null ) {
	return isset( ThemeConfig::$setting_defaults[$setting] ) ? ThemeConfig::$setting_defaults[$setting] : $fallback;
}
/**
 * Returns a theme mod, the theme mod's default defined in
 * ThemeConfig::$setting_defaults, or $fallback.
 **/
function get_theme_mod_or_default( $mod, $fallback='' ) {
	return get_theme_mod( $mod, get_setting_default( $mod, $fallback ) );
}

?>
