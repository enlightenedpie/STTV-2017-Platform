<?php 

/* Template Name: Jobs Template */

$editlink = '<div></div>';
$deleter = '';
if (current_user_can('edit_posts')){
    add_action( 'wp_enqueue_scripts', 'job_add_script' );
    $editlink = '<div class="add-job col s12"><a class="add-job-link" href="#job_modal">add job</a></div>';
}
function job_add_script() {
    wp_enqueue_script('sttv-job-add', get_stylesheet_directory_uri().'/s/job-add.js','jquery',null,true);
}

add_filter( 'sttv_byline', function() {
    return '<span class="byline">Ready to help us change some lives?</span><br/><a href="#jobs" class="st-scroll btn">Open Positions</a>';
});

add_action( 'sttv_stage_extras', function(){
    global $post;
	$thumb = get_the_post_thumbnail_url($post->ID);
	if ( $thumb ) {
		print 'style="background-image:url('.$thumb.');background-position:top;box-shadow:inset 0 0 0 10000px rgba(0,0,0,0.5)"';
	}
});

get_header();

global $wp_query;

$jobs = (new STTV_Jobs)->get_jobs(); ?>
<section id="content-wrapper" class="row">
    <div><?php the_excerpt(); ?></div>
</section>
<section id="jobs-content" class="row"></section>
<section id="jobs-testimonial" class="row z-depth-1">
    <h2>Open Positions</h2>
</section>
<section id="jobs" class="row">
    <?php print $editlink; ?>
    <div id="jobs-inner" class="row">
        <?php if (!$jobs) {
            echo 'There are currently no open positions.';
        } else { ?>
        <?php foreach ($jobs as $job) { ?>
            <div class="single-job row">
                <div class="single-job-title col s9"><h3><a href="<?php echo site_url().trailingslashit($job->url); ?>"><?php echo $job->title; ?></a></h3></div>
                <div class="single-job-button col s3">
                    <?php if (current_user_can('edit_posts')){
                        echo $deleter = '<a href="#!delete" data-id="'.$job->id.'" class="job-deleter"><i class="material-icons">delete</i></a>';
                    }
                    ?>
                    <a href="<?php echo site_url().trailingslashit($job->url); ?>" class="jobs-action-button btn">Apply</a>
                </div>
                <div class="single-jobs-meta col s12">
                    <span class="meta"><?php echo $job->location; ?></span>
                    <span class="meta"><?php echo $job->category; ?></span>
                    <span class="meta"><?php echo $job->job_type; ?></span>
                </div>
            </div>
        <?php } //foreach
        } //else ?>
    </div><?php //jobs-inner ?>
</section>
<?php get_footer(); ?>