<?php
extract(array_merge([
    'cols' => '12',
    'class' => 'default',
    'color' => 'azure',
    'title' => 'Callout',
    'link' => '/',
    'linktext' => 'Go to link'
],$atts)); ?>
<div class="stCallout col s12 m<?php echo $cols; ?>">
    <div class="stCalloutInner z-depth-4 col s12">
        <div class="stCalloutContent row">
            <div class="stCalloutIcon <?php echo $class; ?> z-depth-2"></div>
            <div class="stCalloutTitle"><h2><?php echo $title; ?></h2></div>
            <div class="stCalloutBlurb"><?php echo $content; ?></div>
        </div>
        <div class="stCalloutLink row">
            <a href="<?php echo $link; ?>"><?php echo $linktext; ?></a>
        </div>
    </div>
</div>