<?php

###############################
##### THEME CUSTOMIZATION #####
###############################

require_once dirname(__FILE__) . '/custom/controls.php';

add_action( 'customize_register', 'sttv_customize_register' );
function sttv_customize_register( $wp_customize ) {
	
	/**
	 *
	 *
	 * 404 Page Customizer
	 *
	 *
	**/
		
	$wp_customize->add_section( '404_stage_image', array(
		'title' => __('404 Stage Image','wordpress-seo'),
		'description' => 'Select/Upload an image to display in the stage for the 404 page',
		'priority' => 130
	));
	
	$wp_customize->add_setting( '404_stage_setting' );
	
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, '404_stage_image_select', array(
		  'label' => __( '404 Image', 'wordpress-seo' ),
		  'section' => '404_stage_image',
		  'settings' => '404_stage_setting',
		  'mime_type' => 'image',
		) ) );
		
		
		/**
		 *
		 *
		 * Front Page Customizer
		 *
		 *
		**/
		
		$wp_customize->add_panel( 'front_page_panel', array(
			 'priority'       => 10,
			  'capability'     => 'edit_theme_options',
			  'theme_supports' => '',
			  'title'          => __('Front Page', 'wordpress-seo'),
			  'description'    => __('Customize the front page of the website from this panel', 'wordpress-seo'),
			) );
		
		// Triptych section
		$wp_customize->add_section( 'triptych_edit', array(
			'title' => __('Triptych','wordpress-seo'),
			'description' => 'Customize the three calls to action right below the stage area',
			'priority' => 10,
			'panel'  => 'front_page_panel'
		));
		// Billboard sections
		$wp_customize->add_section( 'billboard_one', array(
			'title' => __('Billboard 1','wordpress-seo'),
			'description' => 'Customize the first billboard pane on the front page',
			'priority' => 20,
			'panel'  => 'front_page_panel'
		));
		$wp_customize->add_section( 'billboard_two', array(
			'title' => __('Billboard 2','wordpress-seo'),
			'description' => 'Customize the second billboard pane on the front page',
			'priority' => 30,
			'panel'  => 'front_page_panel'
		));
		$wp_customize->add_section( 'billboard_three', array(
			'title' => __('Billboard 3','wordpress-seo'),
			'description' => 'Customize the third billboard pane on the front page',
			'priority' => 40,
			'panel'  => 'front_page_panel'
		));
		
		$wp_customize->add_setting( 'triptych_section_left_img' );
		$wp_customize->add_setting( 'triptych_section_left_text' );
		$wp_customize->add_setting( 'triptych_section_center_img' );
		$wp_customize->add_setting( 'triptych_section_center_text' );
		$wp_customize->add_setting( 'triptych_section_right_img' );
		$wp_customize->add_setting( 'triptych_section_right_text' );
		
		$wp_customize->add_setting( 'billboard_one_mce' );
		$wp_customize->add_setting( 'billboard_one_img' );
		$wp_customize->add_setting( 'billboard_two_mce' );
		$wp_customize->add_setting( 'billboard_two_img' );
		$wp_customize->add_setting( 'billboard_three_mce' );
		$wp_customize->add_setting( 'billboard_three_img' );
		
		// Triptych controls
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_left_img', array(
		  'label' => __( 'Left Panel Image', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_left_img'
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_left_text', array(
		  'label' => __( 'Left Panel Text', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_left_text',
		  'type' => 'textarea',
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_center_img', array(
		  'label' => __( 'Center Panel Image', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_center_img'
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_center_text', array(
		  'label' => __( 'Center Panel Text', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_center_text',
		  'type' => 'textarea',
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_right_img', array(
		  'label' => __( 'Right Panel Image', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_right_img'
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'triptych_right_text', array(
		  'label' => __( 'Right Panel Text', 'wordpress-seo' ),
		  'section' => 'triptych_edit',
		  'settings' => 'triptych_section_right_text',
		  'type' => 'textarea',
		) ) );
		
		// Panes
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'billboard_one_left_pane', array(
		  'label' => __( 'Panel Text', 'wordpress-seo' ),
		  'section' => 'billboard_one',
		  'settings' => 'billboard_one_mce',
		  'type' => 'textarea'
		) ) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'billboard_one_right_img', array(
		  'label' => __( 'Panel Image', 'wordpress-seo' ),
		  'section' => 'billboard_one',
		  'settings' => 'billboard_one_img',
		  'mime_type' => 'image'
		) ) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'billboard_two_right_pane', array(
		  'label' => __( 'Panel Text', 'wordpress-seo' ),
		  'section' => 'billboard_two',
		  'settings' => 'billboard_two_mce',
		  'type' => 'textarea'
		) ) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'billboard_two_left_img', array(
		  'label' => __( 'Panel Image', 'wordpress-seo' ),
		  'section' => 'billboard_two',
		  'settings' => 'billboard_two_img',
		  'mime_type' => 'image'
		) ) );
}