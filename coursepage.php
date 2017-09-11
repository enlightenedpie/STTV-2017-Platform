<?php
/**

Template Name: Course Landing

**/
?>

<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper">
<div class="row">
	<?php
    global $post;
    $args = array(
        'post_type' => 'page',
        'post_parent' => $post->ID
    );
    $pg_chl = get_children($args);
    foreach ($pg_chl as $child) : ?>
    <article class="course-section subject">
    <div class="col l6 m12">
        <div class="card sticky-action">
        	<div class="card-image waves-effect waves-block waves-light"><?php print get_the_post_thumbnail($child->ID,'full',array('class'=>'responsive-img activator')); ?></div>
    
            <div class="card-content">
            	<span class="card-title activator">ACT <?php print $child->post_title; ?><i class="material-icons right">more_vert</i></span>
            </div>
        
            <div class="card-reveal">
            	<span class="card-title">ACT <?php print $child->post_title; ?><i class="material-icons right">close</i></span>
            </div>
        </div>
    </div>
    </article>
    <?php endforeach; ?>
</div>
</section>
<section id="practice">
	<div class="row">
    	<div class="col l4 m12">
        	<div class="card sticky-action">
        	<div class="card-image waves-effect waves-block waves-light">g</div>
    
            <div class="card-content">
            	<span class="card-title activator">The Official ACT Prep Guide 2016-2017<i class="material-icons right">more_vert</i></span>
            </div>
        
            <div class="card-reveal">
            	<span class="card-title">The Official ACT Prep Guide 2016-2017<i class="material-icons right">close</i></span>
            </div>
        </div>
        </div>
        <div class="col l4 m12">
        </div>
        <div class="col l4 m12">
        </div>
        
        <div class="col l4 m12">
        	<div class="card sticky-action">
        	<div class="card-image waves-effect waves-block waves-light">g</div>
    
            <div class="card-content">
            	<span class="card-title activator">The Real ACT Prep Guide<i class="material-icons right">more_vert</i></span>
            </div>
        
            <div class="card-reveal">
            	<span class="card-title">The Real ACT Prep Guide<i class="material-icons right">close</i></span>
            </div>
        </div>
        </div>
        <div class="col l4 m12">
        </div>
        <div class="col l4 m12">
        </div>
        
        <div class="col l4 m12">
        	<div class="card sticky-action">
        	<div class="card-image waves-effect waves-block waves-light">g</div>
    
            <div class="card-content">
            	<span class="card-title activator">Free ACT Practice Test<i class="material-icons right">more_vert</i></span>
            </div>
        
            <div class="card-reveal">
            	<span class="card-title">Free ACT Practice Test<i class="material-icons right">close</i></span>
            </div>
        </div>
        </div>
        <div class="col l4 m12">
        </div>
        <div class="col l4 m12">
        </div>
    </div>
</section>
<?php get_footer(); ?>