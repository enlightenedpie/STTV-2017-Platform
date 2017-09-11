<?php

/* Template Name: Courses Template */

?>
<?php

	if (!is_user_logged_in()) :

	endif;

global $post, $wp;

$post_name = $post->post_name;

?>
<?php get_header(); ?>

<script async>
	var courseData = {
		curl : stajax.rest.url,
		request : function(cdata,method) {
			method = method || 'GET';
			cdata = cdata || '';
			$.ajax({
				url : this.curl,
				data : cdata,
				type : method,
				processData : false,
				headers : {
					'X-WP-Nonce' : stajax.rest.nonce
				},
				success : function(response) {
					localStorage.setItem('course_data',response);
				},
				error : function(xhr,status,error) {
					localStorage.setItem('course_data',xhr.responseText);
				}
			});
		},
		tester : function() {
			console.log(this.curl);
		}
	};
	
	if (localStorage.getItem('course_data') === null) {
		localStorage.removeItem('course_data');
		courseData.request();
	}
	courseData.tester();
	
	var theData = localStorage.getItem('course_data');
</script>
<?php get_template_part('templates/title'); ?>
	<section id="content-wrapper">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    	<article id="post-<?php the_ID(); ?>" class="post single-post">
       		<script>
				document.write(theData);
			</script>
       	<?php echo get_query_var('shmoop','default'); ?><br><?php echo $wp->matched_query; ?>
        	<?php the_content(); ?>
        </article>
    <?php endwhile; endif; ?>
	</section>
		<?php get_sidebar(); ?>
        <?php get_template_part('templates/related-posts'); ?>
<?php get_footer(); ?>