<?php

/* This file registers our ACF site options. */

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
				'key' => 'show_roll_home',
        'label' => 'Show blog roll on homepage?',
        'name' => 'show_roll_home',
        'type' => 'true_false',
        'default' => 0,
			],
			[
				'key' => 'show_roll_blog',
        'label' => 'Show blog roll on blog?',
        'name' => 'show_roll_home',
        'type' => 'true_false',
        'default' => 0,
			],
  		[
  			'key' => 'youtube_client_id',
  			'label' => 'YouTube Client ID',
  			'name' => 'youtube_client_id',
  			'type' => 'password',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => [
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			],
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'readonly' => 0,
  			'disabled' => 0,
  		],
  		[
  			'key' => 'youtube_secret_id',
  			'label' => 'YouTube Secret ID',
  			'name' => 'youtube_secret_id',
  			'type' => 'password',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => [
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			],
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'readonly' => 0,
  			'disabled' => 0,
  		],
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

function acf_hide_drafts( $args ) {
  $args['post_status'] = 'publish';
  return $args;
}
add_filter('acf/fields/post_object/query', 'acf_hide_drafts', 10, 3);

function acf_hide_empty_tax( $args ) {
  $args['hide_empty'] = 1;
  return $args;
}
add_filter('acf/fields/taxonomy/query', 'acf_hide_empty_tax', 10, 2);
