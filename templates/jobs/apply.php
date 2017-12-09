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
            <form id="job-apply-form">
                <fieldset>
                    <legend><h4>Your information</h4></legend>
                    <input type="text" name="fullname" placeholder="Full Name (required)" required/>
                    <input type="email" name="email" placeholder="Email address (required)" required/>
                    <input type="tel" name="phone" placeholder="Phone number"/>
                </fieldset>
                <input type="hidden" name="job_id" value="<?php echo $stjob->id; ?>" />
            </form>
        </div>
    </div>
</section>
<?php
get_footer();
?>