<?php
    if (current_user_can('edit_posts')) { ?>
        <a href="<?php echo site_url().trailingslashit($stjob->url); ?>/edit" class="jobs-action-button btn">edit this job</a>
<?php } else { ?>
    <span class="apply">To apply for this job, send your cover letter and resume to <a href="mailto:jobs@supertutortv.com?subject=<?php echo $stjob->title; ?>">jobs@supertutortv.com</a>. Please reference "<?php echo $stjob->title; ?>" in your subject line.</span>
<?php }