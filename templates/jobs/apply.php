<?php
require 'object.php';
add_filter('wpseo_robots',function($robots) {
    return 'noindex, nofollow';
},99);
get_header();
?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
    <div id="jobs">
        <?php require 'partials/meta.php'; ?>
        <div class="job-apply">
            <span>Application form</span>
        </div>
    </div>
</section>
<?php
get_footer();
?>