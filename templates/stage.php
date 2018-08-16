<section id="stStage" class="row">
    <div id="stStageInner"><?php
    foreach([
        'is_page',
        'is_archive',
        'is_404',
        'is_single'
    ] as $temp) {
        if ($temp()) {
            $temp = str_replace('is_','-',$temp);
            sttv_get_template("stage{$temp}",'stage');
            break;
        }
    }
    do_action('st_stage_bottom');
?></div>
</section>
<?php do_action('sttv_after_stage');