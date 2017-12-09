<?php
require 'object.php';

add_filter('wpseo_robots',function($robots) {
    return 'noindex, nofollow';
},99);

add_filter('tiny_mce_before_init','add_mce_events',10,2);
function add_mce_events($mceInit, $editor_id){
    $mceInit['setup'] = 'function(ed) {
        ed.on("focus", _sted.handler)
        ed.on("blur", _sted.handler)
    }';
    return $mceInit;
}

add_action('wp_enqueue_scripts','job_edit_script');
function job_edit_script() {
    wp_enqueue_script('sttv-job-edit', get_stylesheet_directory_uri().'/s/job-edit.js','jquery',null,true);
}
get_header();
?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
    <div id="jobs" class="row">
        <div class="col s12" id="immutables">
            <div class="col s12 m6"><span>Job ID: <?php echo $stjob->id; ?></span></div>
            <div class="col s12 m6"><span>Slug: <?php echo $stjob->name; ?></span></div>
        </div>
        <?php include 'partials/edit-single-form.php'; ?>
    </div>
</section>
<?php
get_footer();
?>