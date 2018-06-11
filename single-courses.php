<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

/**
 * Let's check that our current user is logged in and has access capabilities. If not, we redirect to the sales page with a query variable to be used on the sales page for alerts.
**/
// !current_user_can(get_post_meta($post->ID,'course_primary_cap',true))
if (!is_user_logged_in() || !current_user_can(get_post_meta($post->ID,'course_primary_cap',true))) :
	wp_redirect( esc_url( add_query_arg( 'access', time(), get_permalink($cpp) ) ) );
	exit;
endif;

$cpp = get_post_meta($post->ID,'course_product_page',true);

$section = get_query_var('section');
$subsec = get_query_var('subsection');
$video = get_query_var('video');
$q = get_query_var('q');

$student = get_user_by('id',get_current_user_id());

/**
 * Let's output all the course JS; this function prints the code in the head
**/
function sttv_course_js_object() {
	global $section, $subsec, $video, $q, $post, $student, $cpp;

?>
<script src= <?php echo get_stylesheet_directory_uri() . "/s/single-courses.bundle.js" ?>></script>
<?php

}
add_action( 'wp_head', 'sttv_course_js_object', 11 );

/**
 * Let's set the stage, literally... Initializes our iframe in the stage area
**/
function courses_vid_setup() { ?>
<span class="sttv-embed-video">
	<iframe class="sttv-course-player" src="https://player.vimeo.com/video/188703514?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&autoplay=0" width="1920" height="1080" frameborder="0" title="" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
</span>
<?php }
add_action('sttv_stage_section','courses_vid_setup');
//add_action('sttv_stage_section','courses_apologies',999);
function courses_apologies() {
	print '<div class="azure-bg" style="width:100%;padding:1em;color:white;text-align:center">Thank you for your patience. We are still working out some bugs in the system. Apologies if the course acts wonky at any point.</div>';
}

add_action('sttv_after_body_tag','sttv_course_preloader');
function sttv_course_preloader() {
	print '<div class="course-preloader"><div style="text-align:center"><img src="'.get_stylesheet_directory_uri().'/i/sttv-spinner.gif"><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>';
}

/**
 * Okay, let's do this thang! Start outputting the page.
**/
get_header();

get_template_part('templates/title'); ?>
<noscript>
	All SupertutorTV courses require Javascript to be enabled. Please enable Javascript in your browser to use this course properly.
	<style type="text/css">.course-contentarea, .course-preloader { display: none; } </style>
</noscript>
<div id="course-after-title"><h2>&nbsp;</h2></div>
<section class="course-contentarea course-<?php the_ID(); ?> row" id="content-wrapper-full">
<div id="course-content-hitbox-container" class="row">
	<div id="course-resource-bar" class="col s12 m3 z-depth-1">
		<div class="col s12 user-bug">
			<div class="col s12"><span>Hi there!</span></div>
		</div>
		<div class="resource-links">
			<div class="chevron"></div>
			<a href="#!" class="course-resource-link course-rating"><i class="material-icons">rate_review</i>Rate This Course</a>
			<a href="#!" class="course-resource-link course-feedback"><i class="material-icons">send</i>Leave Feedback</a>
			<a href="#!" class="course-resource-link course-updater"><i class="material-icons">refresh</i>Update Course</a>
			<a href="#!" class="course-resource-link course-version">Version <?php echo STTV_VERSION; ?></a>
		</div>
	</div>
	<div id="course-nav-container" class="col s12 m9"></div>
</div>
</section>
<a id="dwnld" style="display:block;height:1px;width:1px" title=""></a>
<?php get_footer(); ?>
