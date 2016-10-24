<?php

add_action( 'acf/init', function(){
  acf_add_local_field_group( [
  	'key' => 'timeline_options',
  	'title' => 'Timeline Options',
  	'fields' => [
  		[
  			'key' => 'tab_basic',
  			'label' => 'Basic Information',
  			'name' => '',
  			'type' => 'tab',
  		],
  		[
  			'key' => 'timeline_introduction',
  			'label' => 'Introduction',
  			'name' => 'introduction',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 0
  		],
      [
        'key' => 'sync',
        'label' => 'Sync timestamps?',
        'name' => 'sync',
        'type' => 'true_false',
        'wrapper' => [ 'width' => '50' ],
  			'instructions' => 'If selected, upon saving the timestamps or event dates for this interview or timeline will be available to use with supporting content. This may erase existing timestamps or event dates that no longer exist in the transcript or timeline. Only select this when initially saving or if the transcript or timeline has changed.'
      ],
      [
        'key' => 'hide',
        'label' => 'Hide from feeds?',
        'name' => 'hide',
        'type' => 'true_false',
        'instructions' => 'If selected, this content will not be displayed on the home page or any archive pages. This content may still be referenced via Related Content relationships or through menus, such as the Research menu.'
      ],
  		[
  			'key' => 'tab_events',
  			'label' => 'Events',
  			'name' => '',
  			'type' => 'tab'
  		],
  		[
  			'key' => 'timeline_events',
  			'label' => 'Events',
  			'name' => 'events',
  			'type' => 'repeater',
  			'collapsed' => 'timeline_title',
  			'min' => 1,
  			'max' => 120,
  			'layout' => 'block',
  			'button_label' => 'Add Event',
  			'sub_fields' => [
  				[
  					'key' => 'timeline_title',
  					'label' => 'Title',
  					'name' => 'title',
  					'type' => 'text',
  					'required' => 1,
  					'wrapper' => [
  						'width' => 50,
  						'class' => '',
  						'id' => '',
  					],
  				],
          [
  					'key' => 'timeline_date',
  					'label' => 'Date',
  					'name' => 'event_date',
  					'type' => 'text',
  					'required' => 1,
  					'wrapper' => [
  						'width' => 50,
  						'class' => '',
  						'id' => '',
  					],
  				],
  				[
  					'key' => 'timeline_image',
  					'label' => 'Image',
  					'name' => 'image',
  					'type' => 'image',
  					'instructions' => 'This field is optional.',
  					'return_format' => 'id',
  					'preview_size' => 'thumbnail',
  					'library' => 'all',
            'wrapper' => ['width'=>50]
  				],
  				[
  					'key' => 'timeline_content',
  					'label' => 'Content',
  					'name' => 'content',
  					'type' => 'textarea',
  					'new_lines' => 'wpautop',
            'wrapper' => ['width'=>50],
            'rows' => 3
  				],
  				[
  					'key' => 'timeline_content_link',
  					'label' => 'Content Link',
  					'name' => 'content_link',
  					'type' => 'post_object',
  					'post_type' => [
  						0 => 'timeline',
  						1 => 'interview',
  					],
  					'allow_null' => 1,
  					'multiple' => 0,
  					'return_format' => 'id',
  					'ui' => 0,
  				],
  			],
  		],
  		[
  			'key' => 'timeline_supporting_content',
  			'label' => 'Supporting Content',
  			'name' => '',
  			'type' => 'tab',
  		],
      get_supp_cont_fields()
  	],
  	'location' => [
  		[
  			[
  				'param' => 'post_type',
  				'operator' => '==',
  				'value' => 'timeline',
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
} );
