<div class="meta-bar row valign-wrapper">
    <div class="col s12 m8">
        <span class="meta meta-location"><?php echo $stjob->location; ?></span>
        <?php
            if ($stjob->is_remote) {
                echo '<span class="meta meta-remote"><small><i class="material-icons">wifi</i>&nbsp;Remote</small></span>';
            }
        ?>
        <span class="meta meta-category"><?php echo $stjob->category; ?></span>
        <span class="meta meta-job-type">Full-time<?php //echo $stjob->location; ?></span>
    </div>
    <div class="col s12 m4 meta-apply-button">
        <?php 
            if (!get_query_var('job-action')) {
                include 'action-button.php';
            }
        ?>
    </div>
</div>