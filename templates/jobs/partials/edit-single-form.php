<div class="col s12 job-edit">
    <div class="job-edit-saved">Saved</div>
    <div class="col s12" id="job-edit-inputs">
        <div class="row">
            <div class="col s12 job-input" id="title" contenteditable="true"><?php echo $stjob->title; ?></div>
        </div>
        <div class="row">
            <?php wp_editor(wp_specialchars_decode($stjob->description),'description',array('media_buttons'=>false, 'editor_height'=>400, 'editor_class'=>'job-input', 'quicktags'=>false)); ?>
        </div>
        <div class="row">
            <div class="col s6 job-input" id="location" contenteditable="true"><div><?php echo $stjob->location ?: 'location'; ?></div></div>
            <div class="col s6 job-input" id="is_remote"><input type="checkbox" id="is_remote_check" <?php echo !$stjob->is_remote ?: 'checked'; ?>/><label for="is_remote_check">Remote?</label></div>
        </div>
        <div class="row">
            <div class="col s6 job-input" id="category" contenteditable="true"><div><?php echo $stjob->category ?: 'category'; ?></div></div>
            <div class="col s6 job-input" id="job_type" contenteditable="true"><div><?php echo $stjob->job_type ?: 'job type'; ?></div></div>
        </div>
        <input type="hidden" id="id" value="<?php echo $stjob->id; ?>"/>
    </div>
</div>