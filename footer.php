</div><?php //end #content ?>
</div><?php //end #not-header-wrapper ?>
<div id="pre-footer-cta" class="row z-depth-1"><?php
    $msg = is_page('subscribe') ? 'Is it really the "best" ACT prep course ever?' : 'Join our mailing list for exclusive offers!&nbsp;';
    $link = is_page('subscribe') ? '/the-best-act-prep-course-ever' : '/subscribe';
    $txt = is_page('subscribe') ? 'Find out!' : 'Subscribe!';
?>
    <div id="footer-subscribe-cta" class="row">
        <h4><?php echo $msg; ?></h4>
        <a class="pmt-button btn waves-effect waves-dark" href="<?php echo site_url().$link; ?>"><strong><?php echo $txt; ?></strong></a>
    </div>
</div>
<footer class="row">
    <section class="row">
        <a class="footer-logo row" href="<?php echo site_url(); ?>">
            <?php footer_image(); ?>
        </a>
        <span class="site-tagline row"><h5><?php echo get_bloginfo( 'description' ); ?></h5></span>
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
<?php sttv_get_template('_modal','html'); ?>
<?php wp_footer(); ?>
</body>
</html>