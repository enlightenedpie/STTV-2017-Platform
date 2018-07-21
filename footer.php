</div><?php //end #content ?>
</div><?php //end #not-header-wrapper ?>
<div id="pre-footer-cta"><?php do_action('sttv_pre_footer'); ?></div>
<footer class="row">
    <div class="col s12 m6 offset-m3">
        <a class="footer-logo row" href="<?php echo site_url(); ?>">
            <?php footer_image(); ?>
        </a>
        <span class="site-tagline row"><h5><?php echo get_bloginfo( 'description' ); ?></h5></span>
        <span class="site-social row">
            <a title="SupertutorTV Youtube channel" href="https://youtube.com/supertutortv">
                <i class="fab fa-youtube"></i>
            </a>
            <a title="SupertutorTV Facebook page" href="https://facebook.com/supertutortv">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a title="SupertutorTV Instagram account" href="https://instagram.com/supertutortv">
                <i class="fab fa-instagram"></i>
            </a>
            <a title="SupertutorTV Twitter account" href="https://twitter.com/supertutortv">
                <i class="fab fa-twitter"></i>
            </a>
            <a title="SupertutorTV Google+ channel" href="https://plus.google.com/b/112536989461256452305/112536989461256452305">
                <i class="fab fa-google-plus-g"></i>
            </a>
            <a title="Send a message to SupertutorTV" href="<?php echo site_url('/contact'); ?>">
                <i class="fas fa-at"></i>
            </a>
        </span>
        <small class="row">©<?php echo date('Y'); ?> Supertutor Media, Inc. All Rights Reserved.</small>
    </div>
</footer>
<?php sttv_get_template('_modal','html'); ?>
<?php wp_footer(); ?>
</body>
</html>