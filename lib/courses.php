<?php

add_action( 'rest_api_init' , 'register_course_meta_endpoint' );
function register_course_meta_endpoint() {
	register_rest_route( STTV_REST_NAMESPACE , '/course_log', [
			'methods' => 'POST',
			'callback' => 'course_access_log',
			'permission_callback' => 'is_user_logged_in'
		]
	);

	register_rest_route( STTV_REST_NAMESPACE , '/course_data/(?P<id>[\d]+)', [
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'get_course_meta',
		'permission_callback' => 'course_permissions_check',
		'args' => [
			'id' => [
				'validate_callback' => 'absint'
			]
		]
	]);
}

function course_access_log( WP_REST_Request $request ) {
	$data = [
		date('c',time()),
		$_SERVER['REMOTE_ADDR']
	];
	$data = array_merge($data, json_decode($request->get_body(), true));
	$data = implode(' | ',$data);
	return file_put_contents(STTV_LOGS_DIR.get_current_user_id().'.log',PHP_EOL.$data.PHP_EOL,FILE_APPEND);
}

function get_course_alert_template($data) {
	$the_post = get_post($data['id']);
	ob_start();
	require(dirname(__DIR__).'/templates/courses/update_alert.php');
	return array('html'=>ob_get_clean(),'hashid'=>sttvhashit($the_post->post_title.'/'.STTV_VERSION.'/'.$the_post->ID));
}

function course_permissions_check($data) {
	$thepost = get_post($data['id']);
		$cap = str_replace(' ','_',strtolower($thepost->post_title));

	$legacy = array('_legacy_value_bundle','_legacy_official_guide','_legacy_red_book');

	foreach($legacy as $l) {
		if (current_user_can($l)){
			return current_user_can( $l );
		}
	}
	return current_user_can( $cap );
}

function get_course_meta($data) {
	
	$meta = get_post_meta( $data['id'], 'sttv_course_data' , true );
	
	$data = array(
		'id'=>$meta['id'],
		'name'=>$meta['name'],
		'slug'=>$meta['slug'],
		'link'=>$meta['link'],
		'test'=>strtolower($meta['test']),
		'intro'=>$meta['intro'],
		'version'=>STTV_VERSION
	);
	
	if (current_user_can($meta['cap'])) {
		$data['tl_content'] = $meta['tl_content'];
	}
	
	
	foreach ($meta['sections'] as $sec => $val) {
		$data['sections'][$sec] = array(
			'name' => $val['name'],
			'description' => $val['description'],
			'intro' => $val['intro'],
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

	private $introvid_album = false;
	
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

			add_meta_box(
				'course_introvid_album', // $id
				'Course Introvideo Album', // $title
				array($this , 'sttv_display_course_introvid_album'), // $callback
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
	
	public function sttv_display_course_introvid_album() { 
		global $post;
		?>
		<input type="text" name="course_introvid_album" value="<?php echo get_post_meta($post->ID,'course_introvid_album',true)?>"/>
	<?php }
	
	public function sttv_display_course_meta() { 
			global $post; 
			
			$data = get_post_meta($post->ID,'sttv_course_data',true);
			$intro = get_post_meta($post->ID,'course_introvid_album',true);

			//$this->album_video_select($intro);
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
        <div><label for="course_test">Intro Video: <?php print $this->album_video_select($intro,'courses[intro_vid]',$data['intro']); ?></label></div>
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
				$html .= "<label for='courses[sections][{$i}][title]'>Section name: ";
					$s_name = "courses[sections][{$i}][intro_vid]";
				$html .= $this->album_video_select($intro,$s_name,$val['intro']);
				$html .= "</label>&nbsp;";
				$html .= "<br/><hr/><br/><textarea placeholder='Section description' rows='10' cols='100' name='courses[sections][{$i}][desc]'>{$val['description']}</textarea>";
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
				$pract_intro = "courses[practice][{$a}][sections][{$b}][intro_vid]";
				$html .= "<div class='course_practice_test_inner'>";
				$html .= "<label for='{$pract_title}'>Section name: <input name='{$pract_title}' value='{$sec['title']}'/></label>&nbsp;";
				$html .= "<label for='{$pract_id}'>Intro video:&nbsp;";
				$html .= $this->album_video_select($intro,$pract_intro,$sec['intro']);
				$html .= "</label>&nbsp;";
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
<pre style="display:block;width:100%"><?php //print_r(json_encode($data,JSON_PRETTY_PRINT)); ?><?php //print STTV_CACHE_DIR; ?><?php //print_r($this->alb_cache); ?></pre>
		
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

		//save course intro album
		if ($_POST['course_introvid_album']) :
			update_post_meta($post_id, 'course_introvid_album', sanitize_text_field($_POST['course_introvid_album']));
		endif;
			
		//save course product page
		if ($_POST['product_page_dropdown']) :
			update_post_meta($post_id, 'course_product_page', sanitize_text_field($_POST['product_page_dropdown']));
		endif;
		
		if ($_POST['courses']) :
			$test = strtolower($_POST['courses']['test_abbrev']?:'act');
			$caps = array( //default caps for all courses
				'course_post_feedback',
				'course_post_reviews'
			);
		
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
			update_post_meta($post_id,'course_primary_cap',$data['cap']);
		
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
				
				$root_path = STTV_RESOURCE_DIR.strtolower($data['test']).'/'.$sec['title'].'/';
				$resources = array();
				$files = scandir($root_path);
				foreach ($files as $file) {
					if (is_file($root_path.$file)){
						$resources[$file] = md5_file($root_path.$file);
					}
				}
		
				$data['sections'][$sec['title']] = array(
					'name'=>ucfirst($sec['title']),
					'description'=>$sec['desc'],
					'intro'=>$sec['intro_vid'],
					'cap'=>$cap,
					'color'=>'#'.$color,
					'resources'=>$resources,
					'videos'=>new stdClass(),
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
						'intro'=>$v['intro_vid'],
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

	private function introvid_album_select($post_id=0,$album_id=0) {
		if (!get_post_meta($post_id,'introvid_album',true)){
			update_post_meta($post_id,'introvid_album',$album_id);
		}
	}
	
	private function get_cached_album($id) {

		$file = STTV_CACHE_DIR.$id.'.cache';
		$fcache = fopen($file,'r');
		$albs = fread($fcache,filesize($file));
		fclose($fcache);

		return json_decode($albs,true);
	}
	
	private function album_cache_ids(){
		
		$files = scandir(''.STTV_CACHE_DIR);
		foreach ($files as $file) {
			if (is_file(STTV_CACHE_DIR.$file)){
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

	private function album_video_select($id='',$name='',$s='') {
		$obj = $this->get_cached_album($id);
		$select = "<select name={$name}><option disabled selected value> -- Intro videos -- </option>";
		foreach ($obj[$id] as $o){
			$selected = ($o['ID'] != $s)?:'selected';
			$select .= "<option value='{$o['ID']}' {$selected}>{$o['name']}</option>";
		}
		$select .= "</select>";
		return $select;
	}

} //end class

new STTV_Courses_Admin();