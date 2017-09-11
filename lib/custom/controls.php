<?php

###########################
##### CUSTOM CONTROLS #####
###########################

if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Class to create a tinyMCE editor control
 */
class Text_Editor_Custom_Control extends WP_Customize_Control
{
      /**
       * Render the content on the theme customizer page
       */
      public function render_content()
       {
            ?>
                <label>
                  <span class="customize-text_editor"><?php echo esc_html( $this->label ); ?></span>
                  <?php
                    $settings = array(
                      'textarea_name' => $this->id,
					  'media_buttons' => false,
					  'teeny' => true
                      );
					  
					//$this->filter_editor_setting_link();
                    wp_editor($this->value(), $this->id, $settings );
					
                  ?>
                </label>
            <?php
				
				do_action('admin_footer');
                do_action('admin_print_footer_scripts');
				
       }
	   
	   private function filter_editor_setting_link() {
		   add_filter( 'the_editor', function( $output ) { return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 ); } );
	}
	
	public function enqueue() {
		wp_enqueue_media();
	}
}
/* https://github.com/paulund/Wordpress-Theme-Customizer-Custom-Controls */