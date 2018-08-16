<?php

if (is_page_template([
    'fullwidth-bareheader.php',
    'fullwidth-fullheader.php',
    'fullwidth-stripped.php'
])) { ?>
<h1><?php the_title(); ?></h1>
<span><?php the_excerpt(); ?></span>
<?php } else { ?>
<div id="stStagePageLeft" class="col s12 l6">
    <h1><?php the_title(); ?></h1>
    <span><?php the_excerpt(); ?></span>
</div>
<div id="stStagePageRight" class="col s12 l6">right</div>
<?php }