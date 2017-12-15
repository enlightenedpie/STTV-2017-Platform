<?php 

/* Template Name: Jobs Template */

add_action('sttv_stage_section','jobs_page_hero_img');
function jobs_page_hero_img() { ?>
    <span id="post-<?php the_ID(); ?>-thumb" class="post-thumbnail post-thumb-jobs">
        <?php print get_the_post_thumbnail(); ?>
    </span>
<?php }
$editlink = '<div></div>';
$deleter = '';
if (current_user_can('edit_posts')){
    add_action('wp_enqueue_scripts','job_add_script');
    $editlink = '<div class="add-job col s12"><a class="add-job-link" href="#job_modal">add job</a></div>';
}
function job_add_script() {
    wp_enqueue_script('sttv-job-add', get_stylesheet_directory_uri().'/s/job-add.js','jquery',null,true);
}

get_header();
global $wp_query;

$jobs = (new STTV_Jobs)->get_jobs();
?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
<?php print $editlink; ?>
    <div id="jobs">
        <div id="jobs-blurb" class="row">
            <div class="col s12"><?php the_content(); ?></div>
        </div>
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
    </div>
</section>
<?php get_footer(); ?>