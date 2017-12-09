<?php 

/* Template Name: Jobs Template */

add_action('sttv_stage_section','jobs_page_hero_img');
function jobs_page_hero_img() { ?>
    <span id="post-<?php the_ID(); ?>-thumb" class="post-thumbnail post-thumb-jobs">
        <?php print get_the_post_thumbnail(); ?>
    </span>
<?php }

get_header();
global $wp_query;

$jobs = (new STTV_Jobs)->get_jobs();
?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
<?php if (current_user_can('edit_posts')){ ?>
    <div class="add-job col s12"><a href="#!">add job</a></div>
<?php } ?>
    <div id="jobs">
<?php foreach ($jobs as $job) { ?>
    <div class="single-job row">
        <div class="single-job-title col s9"><h3><a href="<?php echo site_url().trailingslashit($job->url); ?>"><?php echo $job->title; ?></a></h3></div>
        <div class="single-job-button col s3"><a href="<?php echo site_url().trailingslashit($job->url); ?>" class="jobs-action-button btn">Apply</a></div>
        <div class="single-jobs-meta col s12">
            <span class="meta"><?php echo $job->location; ?></span>
            <span class="meta"><?php echo $job->category; ?></span>
            <span class="meta"><?php echo $job->job_type; ?></span>
        </div>
    </div>
<?php } ?>
    </div>
</section>
<?php get_footer(); ?>