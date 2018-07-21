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
	 * Footer Logo Customizer
	 *
	 *
	**/
		
	$wp_customize->add_section( 'footer_image', [
			'title' => 'Footer Image',
			'description' => 'Select/Upload an image to display in the site footer',
			'priority' => 61
			]
	);
	
	$wp_customize->add_setting( 'footer_image_setting' );
	
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'footer_image_select', [
		  'label' => 'Footer Image',
		  'section' => 'footer_image',
		  'settings' => 'footer_image_setting',
		  'mime_type' => 'image',
		] ) );
	
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
		
		$wp_customize->add_panel( 'front_page_panel', [
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
			  'theme_supports' => '',
			  'title'          => 'Front Page',
			  'description'    => 'Customize the front page of the website from this panel',
			]);
		
		// Parallax section
		$wp_customize->add_section( 'parallax_block1', [
			'title' => 'Parallax Block 1',
			'description' => 'Change the background parallax scrolling image on the front page',
			'priority' => 10,
			'panel'  => 'front_page_panel'
		]);
		// Billboard sections
		$wp_customize->add_section( 'parallax_block2', [
			'title' => 'Parallax Block 2',
			'description' => 'Change the background parallax scrolling image on the front page',
			'priority' => 20,
			'panel'  => 'front_page_panel'
		]);
		$wp_customize->add_section( 'parallax_block3', [
			'title' => 'Parallax Block 3',
			'description' => 'Change the background parallax scrolling image on the front page',
			'priority' => 30,
			'panel'  => 'front_page_panel'
		]);
		
		$wp_customize->add_setting( 'parallax_block1_img' );
		$wp_customize->add_setting( 'parallax_block1_title' );
		$wp_customize->add_setting( 'parallax_block1_text' );

		$wp_customize->add_setting( 'parallax_block2_img' );
		$wp_customize->add_setting( 'parallax_block2_title' );
		$wp_customize->add_setting( 'parallax_block2_text' );

		$wp_customize->add_setting( 'parallax_block3_img' );
		$wp_customize->add_setting( 'parallax_block3_title' );
		$wp_customize->add_setting( 'parallax_block3_text' );
		
		// Panes
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'parallax_block1_image', [
		  'label' => 'Background Image',
		  'section' => 'parallax_block1',
		  'settings' => 'parallax_block1_img',
		  'mime_type' => 'image'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block1_title', [
		  'label' => 'Block Title',
		  'section' => 'parallax_block1',
		  'settings' => 'parallax_block1_title',
		  'type' => 'text'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block1_text', [
		  'label' => 'Block Text',
		  'section' => 'parallax_block1',
		  'settings' => 'parallax_block1_text',
		  'type' => 'textarea'
		]));

		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'parallax_block2_image', [
		  'label' => 'Background Image',
		  'section' => 'parallax_block2',
		  'settings' => 'parallax_block2_img',
		  'mime_type' => 'image'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block2_title', [
		  'label' => 'Block Title',
		  'section' => 'parallax_block2',
		  'settings' => 'parallax_block2_title',
		  'type' => 'text'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block2_text', [
		  'label' => 'Block Text',
		  'section' => 'parallax_block2',
		  'settings' => 'parallax_block2_text',
		  'type' => 'textarea'
		]));

		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'parallax_block3_image', [
		  'label' => 'Background Image',
		  'section' => 'parallax_block3',
		  'settings' => 'parallax_block3_img',
		  'mime_type' => 'image'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block3_title', [
		  'label' => 'Block Title',
		  'section' => 'parallax_block3',
		  'settings' => 'parallax_block3_title',
		  'type' => 'text'
		]));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_block3_text', [
		  'label' => 'Block Text',
		  'section' => 'parallax_block3',
		  'settings' => 'parallax_block3_text',
		  'type' => 'textarea'
		]));
}