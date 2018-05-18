		</div><?php //end #content ?>
	<?php if (is_singular('courses')) : ?>
		</div><?php //end courses non-sidebar wrapper ?>
   <div id="courses-right-sidebar" class="col m12 l3"></div>
    <?php endif; ?>
</div><?php //end #not-header-wrapper ?>
<div id="pre-footer-cta"><?php do_action('sttv_pre_footer'); ?></div>
    <footer>
        <small>©<?php echo date('Y'); ?> Supertutor Media, Inc. All Rights Reserved.</small>
    </footer>
    <hr/>
</div><?php //end main-wrapper ?>
<?php sttv_get_template('_modal','html'); ?>
<?php wp_footer(); ?>
</body>
</html>