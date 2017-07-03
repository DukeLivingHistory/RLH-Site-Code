<?php

add_theme_support('post-thumbnails');

add_action( 'after_setup_theme',  'register_menus' );
function register_menus() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'primary' ) );
  register_nav_menu( 'utility', __( 'Utility Menu', 'utility' ) );
  register_nav_menu( 'research', __( 'Research Menu', 'research' ) );
  register_nav_menu( 'blog', __( 'Blog Menu', 'blog' ) );
  register_nav_menu( 'standard', __( 'Standard Menu', 'standard' ) );
  register_nav_menu( 'extra', __( 'Extra Menu', 'extra' ) );
}

add_action( 'admin_menu', 'add_collections_item' );
function add_collections_item(){
  add_menu_page( 'Collections', 'Collections', 'read', '/edit-tags.php?taxonomy=collection', null, 'dashicons-admin-post', 8 );
}

// add options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

if( function_exists('acf_add_local_field_group') ){

  acf_add_local_field_group( [
  	'key' => 'acf-options',
  	'title' => 'Site Settings',
  	'fields' => [
  		[
  			'key' => 'youtube_client_id',
  			'label' => 'YouTube Client ID',
  			'name' => 'youtube_client_id',
  			'type' => 'password'
  		],
  		[
  			'key' => 'youtube_secret_id',
  			'label' => 'YouTube Secret ID',
  			'name' => 'youtube_secret_id',
  			'type' => 'password'
  		],
      [
        'key' => 'fb_client_id',
        'label' => 'Facebook Client ID',
        'name' => 'fb_client_id',
        'type' => 'password'
      ],
      [
        'key' => 'maps_client_id',
        'label' => 'Google Maps Client ID',
        'name' => 'maps_client_id',
        'type' => 'password'
      ],
      [
        'key' => 'interview_instructions',
        'label' => 'Interview Instructions',
        'name' => 'interview_instructions',
        'type' => 'textarea'
      ]
    ],
  	'location' => [
  		[
  			[
  				'param' => 'options_page',
  				'operator' => '==',
  				'value' => 'acf-options',
  			],
  		],
  	],
  	'menu_order' => 0,
  	'position' => 'normal',
  	'style' => 'default',
  	'label_placement' => 'top',
  	'instruction_placement' => 'label',
  	'hide_on_screen' => '',
  	'active' => 1,
  	'description' => '',
  ] );

}
