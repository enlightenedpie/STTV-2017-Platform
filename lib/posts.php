<?php 

####################################
##### ARCHIVE POSTS PAGINATION #####
####################################

function sttv_pagination() {
	global $wp_query;
	$args = [
		'format' => 'page/%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'type' => 'array',
		'mid_size' => 3,
		'end_size' => 10,
		'prev_next' => true,
		'prev_text' => '<i class="material-icons">chevron_left</i>',
		'next_text' => '<i class="material-icons">chevron_right</i>'
	];
	
	$pages = paginate_links($args);
	
	if (is_array($pages)) {
	$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<ul class="pagination">';
			foreach ( $pages as $page ) {
				echo "<li class='waves-effect'>$page</li>";
			}
		echo '</ul></div>';
	}
}

######################
##### POSTS META #####
######################

add_action('add_meta_boxes', 'add_course_meta');
function add_course_meta() {
	global $post;

    add_meta_box(
		'album_id', // $id
		'Page template file name', // $title
		'sttv_display_album_id', // $callback
		[ 'post', 'page' ], // $post_type
		'side', // $context
		'high' // $priority
	);

	add_meta_box(
		'yt_link', // $id
		'Youtube Video ID', // $title
		'sttv_youtube_link', // $callback
		[ 'post', 'page' ], // $post_type
		'side', // $context
		'high' // $priority
	);

	if ( 'mu-signup.php' == get_post_meta( $post->ID, '_wp_page_template', true ) )
		add_meta_box(
			'mu_teacher', // $id
			'Multiuser Teacher', // $title
			'sttv_mu_teacher', // $callback
			[ 'page' ], // $post_type
			'side', // $context
			'high' // $priority
		);
}

function sttv_youtube_link()
{
    global $post;  
        $meta = get_post_meta($post->ID, 'yt_link', true);
        
        echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
        
        echo '<input name="yt_link" class="widefat" type="text" value="'.$meta.'" /><br/><small>***Paste the video ID only, not the full link***</small><br/><small>***Remove anything after the ID, like \'&t=x\'***</small>';
}

function sttv_display_album_id()
{
	global $post; echo get_post_meta( $post->ID, '_wp_page_template', true );
}

function sttv_mu_teacher() {
	$users = get_users( [ 'role__in' => [ 'teacher', 'administrator' ] ] );
	$uselect = '<option value="" disabled selected>Select Teacher</option>';
	foreach ( $users as $user ) {
		$uselect .= "<option value='{$user->ID}'>{$user->user_email}</option>";
	}
	$html = <<<HTML
	<select name="mu_teacher">$uselect</select>
HTML;
	print $html;
}

function save_album_meta($post_id) {
        
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    if (isset($_POST['album_id'])) {
		$old = get_post_meta($post_id, 'album_id', true);
		
		$new = $_POST['album_id'];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, 'album_id', $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, 'album_id', $old);
		}
	}
	
	if (isset($_POST['yt_link'])) {
		$yt_root = array('https://youtu.be/','https://www.youtube.com/watch?v=');
		$ytlink = str_replace($yt_root,'',$_POST['yt_link']);
		$yt = strstr($ytlink, '&', true) ?: $ytlink;
		update_post_meta($post_id, 'yt_link', $yt);
	} else {
		update_post_meta($post_id, 'yt_link', '');
	}
}
add_action('save_post', 'save_album_meta');

########################
##### POST COLUMNS #####
########################

add_filter('manage_posts_columns', 'sttv_post_columns');
add_action('manage_posts_custom_column', 'sttv_post_columns_content', 10, 2);

function sttv_post_columns($cols) {
	$new_col = array('post_slug'=>'Slug');
	$cols = array_slice( $cols, 0, 2, true ) + $new_col + array_slice( $cols, 2, count( $cols ) - 1, true );
	
	return $cols;
}
function sttv_post_columns_content($column_name, $post_ID) {
	
	switch($column_name){
		case 'post_slug':
			echo get_post_field('post_name',$post_ID);
			break;
	}
}