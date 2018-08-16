<?php

/* Template Name: Tutoring Main Page */

$id = get_the_ID();
$ytlink = get_post_meta($id,'yt_link',true);

$children = get_children([
    'post_parent' => $id,
	'post_type'   => 'page', 
	'numberposts' => -1,
    'post_status' => 'publish',
    'order' => 'ASC',
    'orderby' => 'menu_order'
], ARRAY_A);

get_header(); ?>
<section id="stContentWrapper" class="row">
    <div id="stTutoringIntro" class="row">
        <div id="stTutoringIntroInner" class="stBlockCentered">
            <div class="stHeroLeft col s12 l6">
                <h1><?php the_title(); ?></h1>
                <span><?php the_content(); ?></span>
            </div>
            <div class="stHeroRight col s12 l6">
            <?php if (!empty($ytlink)) { ?>
                <iframe class="stHeroVideo" type="text/html" src="https://www.youtube.com/embed/<?php print $ytlink; ?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent&playsinline=1" allowfullscreen="true" style="border:0;"></iframe>
                <script>
                    var vid = document.querySelector('.stHeroVideo')
                    vid.height = vid.offsetWidth * (9/16)
                </script>
            <?php } else { ?>
                <img class="stHeroImg" src="<?php print get_the_post_thumbnail_url( $post->ID, 'full' ); ?>" />
            <?php } ?>
            </div>
        </div>
    </div><?php foreach ($children as $name => $obj) { ?>
        <div id="stTutoring<?php echo stCamelCase($obj['post_name']); ?>" class="stTutoringSection row">
            <div class="stBlockCentered">
                <h2><?php echo $obj['post_title']; ?></h2>
                <div class="stInner">
                    <?php echo do_shortcode($obj['post_content']); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
<?php get_footer();