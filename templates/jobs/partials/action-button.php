<?php
    $action = current_user_can('edit_posts') ? 'edit' : 'apply';
?>
<a href="<?php echo site_url().$stjob->url.$action; ?>" class="jobs-action-button btn"><?php echo $action == 'apply' ? 'apply for':'edit';?> this job</a>