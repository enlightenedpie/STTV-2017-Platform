<?php
extract(array_merge([
    'cols' => '12',
    'class' => 'default',
    'title' => ''
],$atts)); ?>
<div class="stContainer col s12 m<?php echo $cols.' '.$class;?>">
    <?php if ($title) : ?><div class="stContainerTitle row"><h3><?php echo $title; ?></h3></div><?php endif; ?>
    <div class="stContainerText row"><?php echo $content; ?></div>
</div>