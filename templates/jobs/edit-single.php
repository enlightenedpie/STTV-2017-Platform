<?php
require 'object.php';
add_filter('wpseo_robots',function($robots) {
    return 'noindex, nofollow';
},99);
get_header();
?>
<section id="content-wrapper-full">
    <div id="jobs">
<?php include 'partials/edit-single-form.php'; ?>
    </div>
</section>
<?php
get_footer();
?>