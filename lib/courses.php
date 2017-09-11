<?php

add_action( 'rest_api_init' , 'register_course_meta_endpoint' );
function register_course_meta_endpoint() {

	register_rest_route( 'sttv/v1', '/course_data/(?P<id>[\d]+)', array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'get_course_meta',
		'permission_callback' => function($data) {
			$thepost = get_post($data['id']);
				$cap = str_replace(' ','_',strtolower($thepost->post_title));
		
		$legacy = array('_legacy_value_bundle','_legacy_official_guide','_legacy_red_book');
		
		foreach($legacy as $l) {
			if (current_user_can($l)){
				return current_user_can( $l );
			}
		}
			return current_user_can( $cap );
		},
		'args' => array(
			'id' => array(
				'validate_callback' => 'absint'
			),
			'section' => array(
				'default' => false,
				'validate_callback' => 'sanitize_title'
			),
		)
	));
}

function get_course_meta($data) {
	
	$meta = get_post_meta( $data['id'], 'sttv_course_data' , true );
	
	$data = array(
		'id'=>$meta['id'],
		'name'=>$meta['name'],
		'slug'=>$meta['slug'],
		'link'=>$meta['link'],
		'test'=>strtolower($meta['test']),
		'intro'=>'188703514',
		'dashboard'=>true
	);
	
	if (current_user_can($meta['cap'])) {
		$data['tl_content'] = $meta['tl_content'];
	}
	
	
	foreach ($meta['sections'] as $sec => $val) {
		$data['sections'][$sec] = array(
			'name' => $val['name'],
			'color' => $val['color'],
			'videos' => $val['videos']
		);
		
		if (current_user_can($val['cap'])) {
			$data['sections'][$sec]['resources'] = $val['resources'];
			$data['sections'][$sec]['subsec'] = $val['subsec'];
		} else {
			$data['sections'][$sec]['restricted'] = 'Restricted access. This section will be available when you purchase the full course, or your trial period ends.';
		}
	}
	foreach ($meta['practice'] as $sec => $val) {
		$data['practice'][$sec] = array(
			'name'=> $val['name'],
			'description'=>$val['desc'],
			'color'=>'rgba(0,0,0,0.60)'
		);
		
		if (current_user_can($val['cap'])) {
				$data['practice'][$sec]['subsec'] = $val['sections'];
		} else {
			$data['practice'][$sec]['restricted'] = 'Restricted access. This practice section will be available when you purchase the full course, or your trial period ends.';
		}
	}
	
	$data['size'] = (mb_strlen(json_encode($data), '8bit')/1000).'KB';
	
	return $data;

}

function courses_res_download() {
	
}

/**
*
*
* STTV Courses Class
*
* A custom designed PHP class for SupertutorTV's test prep courses, with custom taxonomies and REST access.
*
* @package SupertutorTV Redesign 2017
* @since 1.0.0
*
*
**/
class STTV_Courses {
	
	public $course_id = 0;
	
	public function __construct($course = 0) {
		
			$this->course_id = $course;
		
	}
	
}

class STTV_Courses_Admin {
	
	private $alb_cache = array();
	
	public function __construct() {
		
		$this->album_cache_ids();
		
		add_action( 'init', array( $this, 'sttv_course_init'), 10, 0);
		add_action( 'init', array( $this, 'sttv_course_endpoints'), 10, 0);
		add_filter( 'query_vars', array( $this, 'sttv_course_query_vars'), 10, 1);
		add_action( 'edit_form_after_title', array( $this, 'course_meta_position' ));
		add_action( 'save_post_courses' , array($this,'save_course_meta'), 10, 2 );
		
	}
	
	public function sttv_course_init() {
		
		$labels = array(
			'name'	=>	'Courses'
		);
		
		$args = array(
			'labels'				=>	$labels,
			'description'			=>	'SupertutorTV courses',
			'menu_position'			=>	56,
			'menu_icon'				=> 'dashicons-welcome-learn-more',
			'public'				=>	true,
			'hierarchical'			=>	true,
			'exclude_from_search'	=>	true,
			'show_in_nav_menus'		=>	false,
			'show_in_rest'			=>	true,
			'rewrite'				=>	array(
				'with_front'	=>	true,
				'pages'			=>	false
			),
			'delete_with_user'		=>	false,
			'can_export'			=>	true,
			'supports'				=>	array('title', 'editor', 'comments', 'revisions', 'author', 'excerpt', 'thumbnail'),
			'register_meta_box_cb'	=> array( $this, 'sttv_add_course_meta' )
		);
		
		register_post_type( 'courses', $args );
	}
	
	public function sttv_course_endpoints() {
		
		add_rewrite_rule('^courses/(.*)/(.*)/(.*)/(.*)/(.*)?$','index.php?post_type=courses&name=$matches[1]&section=$matches[2]&subsection=$matches[3]&video=$matches[4]&q=$matches[5]','top' );
		add_rewrite_rule('^courses/(.*)/(.*)/(.*)/(.*)?$','index.php?post_type=courses&name=$matches[1]&section=$matches[2]&subsection=$matches[3]&video=$matches[4]','top' );
		add_rewrite_rule('^courses/(.*)/(.*)/(.*)?$','index.php?post_type=courses&name=$matches[1]&section=$matches[2]&subsection=$matches[3]','top' );
		add_rewrite_rule('^courses/(.*)/(.*)?$','index.php?post_type=courses&name=$matches[1]&section=$matches[2]','top' );
		add_rewrite_rule('^courses/(.*)?$','index.php?post_type=courses&name=$matches[1]','top' );
		
		add_rewrite_tag( '%section%', '([a-zA-Z0-9]+[_-])*', 'section=' );
		add_rewrite_tag( '%subsection%', '([a-zA-Z0-9]+[_-])*', 'subsection=' );
		add_rewrite_tag( '%video%', '([a-zA-Z0-9]+[_-])*', 'video=' );
		add_rewrite_tag( '%question%', '([a-zA-Z0-9]+[_-])*', 'q=' );
		
	}
	
	public function sttv_course_query_vars($vars) {
		$vars[] = 'section';
		$vars[] = 'subsection';
		$vars[] = 'video';
		$vars[] = 'q';
		return $vars;
	}
	
	public function sttv_add_course_meta() {
			add_meta_box(
				 'course_info', // $id
				 'Course Information', // $title
				 array($this , 'sttv_display_course_meta'), // $callback
				 'courses', // $post_type
				 'top', // $context
				 'high' // $priority
			);
		
			add_meta_box(
				 'course_product_page', // $id
				 'Course Product Page', // $title
				 array($this , 'sttv_display_course_product_page'), // $callback
				 'courses', // $post_type
				 'side', // $context
				 'low' // $priority
			);
			
			remove_meta_box('wpseo_meta', 'courses', 'normal');
			remove_meta_box('members-cp', 'courses', 'advanced');
		
	}
	
	public function sttv_display_course_product_page() { ?>
		<select name="product_page_dropdown"> 
		 <option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
		 <?php 
		global $post;
			$cpp = get_post_meta($post->ID,'course_product_page',true);
		  $pages = get_pages(); 
		  foreach ( $pages as $page ) :
			$selected = ($page->ID == $cpp) ? 'selected' : '';
			$option = '<option value="' . $page->ID . '" '.$selected.'>';
			$option .= $page->post_title;
			$option .= '</option>';
			echo $option;
		  endforeach;
		 ?>
		</select>
	<?php 
		wp_nonce_field('save_course_product_page_nonce','course_product_page_nonce');										   
	}
	
	
	public function sttv_display_course_meta() { 
			global $post; 
			
			$data = get_post_meta($post->ID,'sttv_course_data',true);
?>
<style type="text/css" scoped>
	* {
		box-sizing: border-box;
	}
	.row {
		width: 100%;
		display: block;
		clear: both;
		margin-bottom: 1em;
	}
	.row::after { 
	   content: " ";
	   display: block; 
	   height: 0; 
	   clear: both;
	}
	.col {
		width: 50%;
		float: left;
		min-height: 5em;
	}
	#sections_wrapper {
		width: 100%;
		padding: 5px;
	}
	.course_section, .course_practice {
		background-color: #f1f1f1;
		border: 1px solid #E0E0E0;
		width: 100%;
		margin-bottom: 1em;
		padding: 1em;
	}
	.course_section > button {
		font-size: 1.5em;
	}
	.course_subsec {
		border-top: 1px solid #E0E0E0;
		padding: 1em;
		width: 100%;
		margin-top: 1em;
	}
	.course_subsec_inner, .course_subsubsec_inner, .course_practice_test {
		margin-left: 2em;
	}
</style>
<div id="sttv_course_info" class="row">
	<h1>Main Course Info</h1>
    <div class="col s6">
    	<div>Course ID: <?php echo $post->ID; ?></div>
        <div>Course Title: <?php echo $post->post_title; ?></div>
        <div>Canonical URL: <?php echo get_post_permalink($post->ID);?></div>
        <div>Course Description: <?php echo $post->post_content; ?></div>
    </div>
    <div class="col s6">
    	<div><label for="course_test">Test abbreviation: <input type="text" name="courses[test_abbrev]" value="<?php echo $data['test']; ?>" /></label></div>
        <div><label for="course_test">Intro Video ID: <input type="text" name="courses[intro_vid]" value="<?php echo $data['intro']; ?>" /></label></div>
        <div><label for="course_test">Top Level Content ID: <input type="text" name="courses[tl_content]" value="<?php echo $data['tl_content']; ?>" /></label></div>
    </div>
</div>
<div id="sttv_course_sections" class="row">
	<div id="sections_title_h"><h3>Sections</h3></div>
    <div id="sections_wrapper">
    	<?php 
			$i = $s = 0;
			$html = '';
		
			if (empty($data['sections'])) {
				$data['sections'] = array(false=>false);
			}
			foreach ( $data['sections'] as $sec => $val ){
				$html .= "<div class='course_section'>";
				$html .= "<label for='courses[sections][{$i}][title]'>Section name: <input name='courses[sections][{$i}][title]' value='{$sec}'/></label>&nbsp;";
				$html .= "<label for='courses[sections][{$i}][id]'>Section ID: <input name='courses[sections][{$i}][id]' value='{$val['id']}' disabled/></label>&nbsp;";
				$html .= "<button class='add-section' href='/'>+</button> <button class='remove-section' href='/'>-</button><br/>";
				$html .= "<div class='course_subsec'>";
				
				if (empty($val['subsec'])) {
					$val['subsec'] = array(false=>false);
				}
				
				foreach ($val['subsec'] as $k => $v){
					$checked = '';
					$disabled = '';
					if (empty($v['subsec'])) {
						$v['subsec'] = array();
					} else {
						$checked = 'checked';
						$disabled = 'disabled';
					}
					$name_id = "courses[sections][{$i}][videos][{$s}][id]";
					$name_title = "courses[sections][{$i}][videos][{$s}][title]";
					$name_checksub = "courses[sections][{$i}][videos][{$s}][hassubsub]";
					$html .= "<div class='course_subsec_inner'>";
					$html .= "<label for='{$name_title}'>Sub section name: <input name='{$name_title}' value='{$k}'/></label>&nbsp;";
					$html .= "<label for='{$name_id}'>Video album ID:&nbsp;";
					$html .= $this->cached_album_select($name_id,$v['id'],$disabled);
					$html .= "</label>&nbsp;";
					$html .= "<button class='add-sub-section' href='/'>+</button>&nbsp;<button class='remove-section' href='/'>-</button>&nbsp;";
					$html .= "</div>";
					$s++;
				}
				
				$html .= "</div></div>";
				
				$i++;
				$s = 0;
			}
			print $html;
		?>
    </div>
</div>
<div id="sttv_course_practice" class="row">
	<div id="practice_title_h"><h3>Practice</h3></div>
    <div id="practice_wrapper">
    <?php
		$a = $b = 0;
		$html = '';
		if (empty($data['practice'])) {
			$data['practice'] = array(false=>false);
		}
		foreach ( $data['practice'] as $val ){
			$html .= "<div class='course_practice'>";
			$html .= "<label for='courses[practice][{$a}][title]'>Book/Test name: <input size='50' name='courses[practice][{$a}][title]' value='{$val['name']}'/></label>&nbsp;";
			$html .= "<button class='add-section' href='/'>+</button> <button class='remove-section' href='/'>-</button><br/>";
			$html .= "<label for='courses[practice][{$a}][description]'>Description: <textarea rows='5' cols='80' name='courses[practice][{$a}][description]'>{$val['desc']}</textarea></label>&nbsp;";
			$html .= "<div class='course_practice_test'>";
			
			if (empty($val['sections'])) {
				$val['sections'] = array(false=>false);
			}
			foreach ($val['sections'] as $sec) {
				
				$pract_id = "courses[practice][{$a}][sections][{$b}][id]";
				$pract_title = "courses[practice][{$a}][sections][{$b}][title]";
				$html .= "<div class='course_practice_test_inner'>";
				$html .= "<label for='{$pract_title}'>Section name: <input name='{$pract_title}' value='{$sec['title']}'/></label>&nbsp;";
				$html .= "<label for='{$pract_id}'>Video album ID:&nbsp;";
				$html .= $this->cached_album_select($pract_id,$sec['id'],$disabled);
				$html .= "</label>&nbsp;";
				$html .= "<button class='add-sub-sub-section' href='/'>+</button> <button class='remove-section' href='/'>-</button><br/>";
				$html .= "</div>";
				
				$b++;
			}
			$html .= "</div></div>";
			$a++;
			$b = 0;
		}
		print $html;
		
	?>
	</div>
</div>
<pre style="display:block;width:100%"><?php print_r(json_encode($data,JSON_PRETTY_PRINT)); ?><?php //print_r($data); ?></pre>
		
<?php }
	
	public function course_meta_position() {
		global $post, $wp_meta_boxes;

		do_meta_boxes(get_current_screen(), 'top', $post);

		unset($wp_meta_boxes[get_post_type($post)]['top']);
	}
	
	public function save_course_meta($post_id, $post) {
		// Stop WP from clearing custom fields on autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		// Prevent quick edit from clearing custom fields
		if (defined('DOING_AJAX') && DOING_AJAX)
			return;
			
		//save course product page
		if ($_POST['product_page_dropdown']) :
			update_post_meta($post_id, 'course_product_page', sanitize_text_field($_POST['product_page_dropdown']));
		endif;
		
		if ($_POST['courses']) :
			$test = strtolower($_POST['courses']['test_abbrev']?:'act');
			$caps = array();
		
			$data = array(
				'id'=>$post_id,
				'name'=>$post->post_title,
				'slug'=>$post->post_name,
				'link'=>get_post_permalink($post_id),
				'sales_page'=> get_permalink(get_post_meta($post_id, 'course_product_page',true)),
				'cap'=>"course_{$test}_full",
				'created'=>strtotime($post->post_date),
				'modified'=>strtotime($post->post_modified),
				'intro'=>$_POST['courses']['intro_vid']?:0,
				'test'=>strtoupper($test),
				'dashboard'=>$_POST['courses']['has_dash']?:'null',
				'description'=>$post->post_content,
				'tl_content'=>$_POST['courses']['tl_content'],
				'sections'=>array(),
				'practice'=>array()
			);
			$caps[]=$data['cap'];
		
			foreach($_POST['courses']['sections'] as $sec) :
				$i = 0;
				$sec['title'] = strtolower($sec['title']);
		
				$cap = "course_{$test}_{$sec['title']}";
				$caps[]=$cap;
		
				$albs = array();
		
				$color = '';
		
				foreach ($sec['videos'] as $sub) {
					$albs[sanitize_title_with_dashes($sub['title'])] = array();
					
					
						$calb = $this->get_cached_album($sub['id']);
						if (empty($color)) {
							$color = $calb['embedColor'];
						}
						$albs[sanitize_title_with_dashes($sub['title'])] = array(
							'id' => $sub['id'],
							'title'=>$sub['title'],
							'videos' => $calb[$sub['id']]
						);
				}
				
				//$ha = explode('/', $_SERVER['DOCUMENT_ROOT']);
				$root_path = $_SERVER['DOCUMENT_ROOT'].'/resources/'.strtolower($data['test']).'/'.$sec['title'].'/';
				$resources = array();
				$files = scandir($root_path);
				foreach ($files as $file) {
					if (is_file($root_path.$file)){
						$resources[$file] = md5_file($root_path.$file);
					}
				}
		
				$data['sections'][$sec['title']] = array(
					'name'=>ucfirst($sec['title']),
					'cap'=>$cap,
					'color'=>'#'.$color,
					'resources'=>$resources,
					'subsec'=>$albs
				);
				$i++;
			endforeach;
		
			foreach ($_POST['courses']['practice'] as $prac) :
		
				$title = sanitize_title_with_dashes($prac['title']);
		
				$sections = array();
				foreach ($prac['sections'] as $v) {
					$calb = $this->get_cached_album($v['id']);
					if (empty($color)) {
						$color = $calb['embedColor'];
					}
					
					$sections[sanitize_title_with_dashes($v['title'])] = array(
						'id'=>$v['id'],
						'album-name'=>$calb['albumName'],
						'title'=>$v['title'],
						'videos'=>$calb[$v['id']]
					);
				}
		
				$data['practice'][$title] = array(
					'name'=>$prac['title'],
					'desc'=>$prac['description'],
					'cap'=>"course_{$test}_practice_{$title}",
					'sections'=>$sections
				);
				$caps[]=$data['practice'][$title]['cap'];
		
			endforeach;
		
			$data['size'] = (mb_strlen(json_encode($data), '8bit')/1000).'KB';
		
			$data['allcaps'] = $caps;
			
			update_post_meta($post_id, 'sttv_course_data', $data);
		
			$admin = get_role('administrator');
			foreach ($caps as $c){
				$admin->add_cap($c);
			}
			
		endif;
	}
	
	private function get_cached_album($id) {
		//$h = explode('/', $_SERVER['DOCUMENT_ROOT']);
		//$root_path = $_SERVER['DOCUMENT_ROOT'].'/';

		$file = $_SERVER['DOCUMENT_ROOT'].'/vim/vcache/'.$id.'.cache';
		$fcache = fopen($file,'r');
		$albs = fread($fcache,filesize($file));
		fclose($fcache);

		return json_decode($albs,true);
	}
	
	private function album_cache_ids(){
		//$h = explode('/', $_SERVER['DOCUMENT_ROOT']);
		$root_path = $_SERVER['DOCUMENT_ROOT'].'/vim/vcache/';
		
		$files = scandir($root_path);
		foreach ($files as $file) {
			if (is_file($root_path.$file)){
				$this->alb_cache[] = $file;
			}
		}
	}
	
	private function cached_album_select($name = '', $s = '',$d = '') {
		
		$select = "<select name={$name} {$d}>";
		
		foreach ($this->alb_cache as $file) {
			$file = str_replace('.cache','',$file);
			$obj = $this->get_cached_album($file);
			$selected = ($file != $s)?:'selected';
			$select .= "<option value='{$file}' {$selected}>{$obj['albumName']}</option>";
		}
		
		$select .= '</select>';
		return $select;
	}

} //end class
new STTV_Courses_Admin();