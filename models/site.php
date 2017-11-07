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
      ],
      [
        'key'   => 'chapter_message',
        'label' => 'Chapter Indicator Options',
        'name'  => 'chapter_message',
        'type'  => 'message',
        'text'  => 'The following options affect indicator dots added to video seekbars for section breaks added using the "NOTE Chapter" syntax.'
      ],
      [
        'key' => 'chapter_color',
        'label' => 'Chapter Marker Color',
        'name' => 'chapter_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'chapter_width',
        'label' => 'Chapter Marker Width',
        'name' => 'chapter_width',
        'type' => 'text',
        'instructions' => 'Width should be specified as a number of pixels.',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'chapter_height',
        'label' => 'Chapter Marker Height',
        'name' => 'chapter_height',
        'type' => 'text',
        'instructions' => 'Height should be specified as a number of pixels. This value will only be used if display is set to "Line."',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'chapter_display',
        'label' => 'Chapter Marker Display',
        'name' => 'chapter_display',
        'type' => 'select',
        'choices' => [
          'line' => 'Line',
          'dot' => 'Dot'
        ],
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key'   => 'heading_message',
        'label' => 'Header Indicator Options',
        'name'  => 'heading_message',
        'type'  => 'message',
        'text'  => 'The following options affect indicator dots added to video seekbars for section breaks added using the header syntax.'
      ],
      [
        'key' => 'heading_color',
        'label' => 'Heading Marker Color',
        'name' => 'heading_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'heading_width',
        'label' => 'Heading Marker Width',
        'name' => 'heading_width',
        'type' => 'text',
        'instructions' => 'Width should be specified as a number of pixels.',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'heading_height',
        'label' => 'Heading Marker Height',
        'name' => 'heading_height',
        'type' => 'text',
        'instructions' => 'Height should be specified as a number of pixels."',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'heading_display',
        'label' => 'Heading Marker Display',
        'name' => 'heading_display',
        'type' => 'select',
        'choices' => [
          'line' => 'Line',
          'dot' => 'Dot'
        ],
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key'   => 'search_message',
        'label' => 'Search Indicator Options',
        'name'  => 'search_message',
        'type'  => 'message',
        'text'  => 'The following options affect search result markers for Able Player.'
      ],
      [
        'key' => 'search_color',
        'label' => 'Search Marker Color',
        'name' => 'search_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'search_width',
        'label' => 'Search Marker Width',
        'name' => 'search_width',
        'type' => 'text',
        'instructions' => 'Width should be specified as a number of pixels.',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'search_height',
        'label' => 'Search Marker Height',
        'name' => 'search_height',
        'type' => 'text',
        'instructions' => 'Height should be specified as a number of pixels. This value will only be used if display is set to "Line."',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'search_display',
        'label' => 'Search Marker Display',
        'name' => 'search_display',
        'type' => 'select',
        'choices' => [
          'line' => 'Line',
          'dot' => 'Dot'
        ],
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key'   => 'audio_message',
        'label' => 'Audio Description Indicator Options',
        'name'  => 'audio_message',
        'type'  => 'message',
        'text'  => 'The following options affect audio description search result markers for Able Player.'
      ],
      [
        'key' => 'audio_color',
        'label' => 'Audio Description Marker Color',
        'name' => 'audio_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'audio_width',
        'label' => 'Audio Description Marker Width',
        'name' => 'audio_width',
        'type' => 'text',
        'instructions' => 'Width should be specified as a number of pixels.',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'audio_height',
        'label' => 'Audio Description Marker Height',
        'name' => 'audio_height',
        'type' => 'text',
        'instructions' => 'Height should be specified as a number of pixels. This value will only be used if display is set to "Line."',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'audio_display',
        'label' => 'Audio Description Marker Display',
        'name' => 'audio_display',
        'type' => 'select',
        'choices' => [
          'line' => 'Line',
          'dot' => 'Dot'
        ],
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key'   => 'supp_cont_message',
        'label' => 'Supporting Content Indicator Options',
        'name'  => 'supp_cont_message',
        'type'  => 'message',
        'text'  => 'The following options affect audio description search result markers for Able Player.'
      ],
      [
        'key' => 'supp_cont_color',
        'label' => 'Supporting Content Marker Color',
        'name' => 'supp_cont_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'supp_cont_width',
        'label' => 'Supporting Content Marker Width',
        'name' => 'supp_cont_width',
        'type' => 'text',
        'instructions' => 'Width should be specified as a number of pixels.',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'supp_cont_height',
        'label' => 'Supporting Content Marker Height',
        'name' => 'supp_cont_height',
        'type' => 'text',
        'instructions' => 'Height should be specified as a number of pixels. This value will only be used if display is set to "Line."',
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'supp_cont_display',
        'label' => 'Supporting Content Marker Display',
        'name' => 'supp_cont_display',
        'type' => 'select',
        'choices' => [
          'line' => 'Line',
          'dot' => 'Dot'
        ],
        'wrapper' => [ 'width' => '25%' ]
      ],
      [
        'key' => 'highlight_color',
        'label' => 'Highlight Color',
        'name' => 'highlight_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '25%' ]
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
