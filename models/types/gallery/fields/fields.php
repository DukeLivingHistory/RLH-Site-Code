<?php

add_action( 'acf/init', function(){
  acf_add_local_field_group( [
  	'key' => 'gallery_options',
  	'title' => 'Gallery Options',
  	'fields' => [
  		[
  			'key' => 'gallery_contents',
  			'label' => 'Gallery',
        'name' => 'gallery_contents',
  			'type' => 'gallery'
  		]
    ],
  	'location' => [
  		[
  			[
  				'param' => 'post_type',
  				'operator' => '==',
  				'value' => 'gallery',
  			],
  		],
  	]
  ] );
}, 0 );
