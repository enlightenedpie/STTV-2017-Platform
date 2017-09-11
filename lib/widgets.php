<?php

// Sensei 'My Courses' Widget

add_action( 'widgets_init', 'sttv_custom_widgets_init' );
 
function sttv_custom_widgets_init() {
    //register_widget( 'sttv_products_widget' );
	register_widget( 'sttv_subscribe_widget' );
}

class sttv_subscribe_widget extends WP_Widget
{
 
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'sttv_subscribe_widget',
            'description' => 'STTV Email Subscribe Form'
        );
 
        parent::__construct( 'sttv_subscribe_widget', 'STTV Email Subscribe Form', $widget_details );
 
    }
 
    public function form( $instance ) {
        $defaults = array(
			'sub_title' => 'Subscribe Now!',
			'sub_desc' => 'This is some text that goes above the form'
		);
		$title = ($instance[ 'sub_title' ]) ?: $defaults['sub_title'];
		$desc = ($instance[ 'sub_desc' ]) ?: $defaults['sub_desc'];
		?>
        <p>
        	<label for="<?php echo $this->get_field_id( 'sub_title' ); ?>">Title: </label>
        	<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'sub_title' ); ?>" name="<?php echo $this->get_field_name( 'sub_title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'sub_desc' ); ?>">Intro text: </label>
        	<textarea class="widefat" id="<?php echo $this->get_field_id( 'sub_desc' ); ?>" name="<?php echo $this->get_field_name( 'sub_desc' ); ?>"><?php echo esc_attr( $desc ); ?></textarea>
        </p>
        <?php
	
	}
 
    public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance[ 'sub_title' ] = strip_tags( $new_instance[ 'sub_title' ] );
		$instance[ 'sub_desc' ] = $new_instance[ 'sub_desc' ];
		return $instance;
    }
 
    public function widget( $args, $instance ) {
		// construct the shortcode
		$shortcode = "[subscribe-form context=\"sidebar\" title=\"{$instance['sub_title']}\"]{$instance['sub_desc']}[/subscribe-form]";
		
		print do_shortcode($shortcode);
	
    }
 
}


/**
 * Extend Recent Posts Widget 
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */

class STTV_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

	function widget($args, $instance) {
	
		extract( $args );
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
				
		if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
			$number = 5;
					
		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if( $r->have_posts() ) :
			
			echo $before_widget;
			if( $title ) echo '<h5>'.$before_title . $title . $after_title.'</h5>'; ?>
			<ul id="recentposts" class="collection">
				<?php while( $r->have_posts() ) : $r->the_post(); ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >	
                    <li class="collection-item avatar">
                        <?php print wp_get_attachment_image(get_post_thumbnail_id(),'thumbnail','',array( "class" => "circle" )); ?>
                        <span class="title"><?php the_title(); ?></span>
                    </li>
                </a>
				<?php endwhile; ?>
			</ul>
			 
			<?php
			echo $after_widget;
		
		wp_reset_postdata();
		
		endif;
	}
}
function my_recent_widget_registration() {
  unregister_widget('WP_Widget_Recent_Posts');
  register_widget('STTV_Recent_Posts_Widget');
}
add_action('widgets_init', 'my_recent_widget_registration');