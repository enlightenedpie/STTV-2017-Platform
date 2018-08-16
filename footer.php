</div><?php //end #not-header-wrapper 
if (!is_page('subscribe')) { ?>
<div id="stPreFooterCTA" class="row z-depth-1">
    <div id="stPreFooterCTAInner" class="row">
        <h4>Join our mailing list for exclusive offers!</h4>
        <a class="pmt-button btn waves-effect waves-dark" href="<?php echo site_url().'/subscribe'; ?>"><strong>Subscribe!</strong></a>
    </div>
</div>
<?php } ?>
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
            <div class="col s12 m6 push-m6">
                <?php sttv_get_template('stSocial','shortcodes'); ?>
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