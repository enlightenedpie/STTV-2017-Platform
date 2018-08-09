</div><?php //end #not-header-wrapper ?>
<footer class="row">
    <section id="stFooter" class="row">
        <div id="stFooterInner" class="row">
            <div id="stFooterBadge" class="col s12 m4">
                <a class="footer-logo row" href="<?php echo site_url(); ?>">
                    <?php footer_image(); ?>
                </a>
                <span class="site-tagline row"><h5><?php echo get_bloginfo( 'description' ); ?></h5></span>
            </div>
            <div class="col s12 m4">Left Menu</div>
            <div class="col s12 m4">Right Menu</div>
        </div>
        <div class="stFooterBlockInner row">It's like having elite private tutoring at a fraction of the cost!</div>
    </section>
    <section id="stFooterTailights" class="row">
        <div id="stFooterTailightsInner">
            <div class="stSocialBlock col s12 m6 push-m6">
                <span class="stSocialMenu">
                    <a class="stSocialIcon yt" title="SupertutorTV Youtube channel" href="https://youtube.com/supertutortv">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a class="stSocialIcon fb" title="SupertutorTV Facebook page" href="https://facebook.com/supertutortv">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="stSocialIcon ig" title="SupertutorTV Instagram account" href="https://instagram.com/supertutortv">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="stSocialIcon tw" title="SupertutorTV Twitter account" href="https://twitter.com/supertutortv">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="stSocialIcon g+" title="SupertutorTV Google+ channel" href="https://plus.google.com/b/112536989461256452305/112536989461256452305">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                    <a class="stSocialIcon at" title="Send a message to SupertutorTV" href="<?php echo site_url('/contact'); ?>">
                        <i class="fas fa-at"></i>
                    </a>
                </span>
            </div>
            <div class="col s12 m6 pull-m6"><span>© <?php echo date('Y'); ?> Supertutor Media, Inc. All Rights Reserved.</span></div>
        </div>
    </section>
</footer>
<div id="stPostFooter" class="stStripedLine"></div>
<?php sttv_get_template('_modal','html'); ?>
<?php wp_footer(); ?>
</body>
</html>