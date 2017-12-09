<div class="meta-bar row valign-wrapper">
    <div class="col s12">
        <span class="meta meta-location"><?php echo $stjob->location; ?></span>
        <?php
            if ($stjob->is_remote) {
                echo '<span class="meta meta-remote"><small><i class="material-icons">wifi</i>&nbsp;Remote</small></span>';
            }
        ?>
        <span class="meta meta-category"><?php echo $stjob->category; ?></span>
        <span class="meta meta-job-type"><?php echo $stjob->job_type; ?></span>
    </div>
</div>