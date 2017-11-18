<?php require 'object.php';
add_filter('wpseo_robots',function($robots) {
    return 'index, nofollow';
},99);
get_header();
?>
<?php get_template_part('templates/title'); ?>
<?php if (!is_404()) : ?>
<section id="content-wrapper-full">
    <div id="jobs">
        <?php require 'partials/meta.php'; ?>
        <div class="job-description col s12">
            <?php print wp_specialchars_decode($stjob->description); ?>
        </div>
        <div class="after-job-description col s12">
            <?php include 'partials/action-button.php'; ?>
        </div>
    </div>
</section>
<script>
$('.meta-location').on('blur',function(e){
    e.preventDefault()
    alert($(this).text())
})
</script>
<?php else : ?>
<?php endif; ?>
<?php
get_footer();
?>