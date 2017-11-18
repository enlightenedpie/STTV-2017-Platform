<div class="col s12 job-edit">
    <div class="col s12" id="immutables">
        <span>ID: <?php echo $stjob->id; ?></span><br/>
        <span>URL: <?php echo site_url().$stjob->url; ?></span><br/>
        <span>Slug: <?php echo $stjob->name; ?></span>
    </div>
    <div id="job-edit-inputs">
        <input name="title" placeholder="Title" value="<?php echo $stjob->title; ?>" />
        <?php wp_editor($stjob->description,'description'); ?>
    </div>
</div>