<?php
if( function_exists('acf_add_local_field_group') ):


acf_add_local_field_group(array(
	'key' => 'home_options',
	'title' => 'Home Options',
	'fields' => array(
		array(
      'type' => 'message',
			'key' => 'homepage_instructions',
			'message' => '<strong>The following fields control what content appears at the top of the homepage.</strong>',
		),
		array(
			'key' => 'home_type',
			'label' => 'Featured Home Content Type',
			'name' => 'home_type',
			'type' => 'radio',
      'wrapper' => array(
        'width' => 30,
        'class' => '',
      ),
			'instructions' => 'Select the type of the content you\'d like to use on the homepage.',
			'choices' => array(
				'collection' => 'Collection',
				'interview' => 'Interview',
				'timeline' => 'Timeline',
        'blog' => 'Blog',
			),
		),
		array(
			'key' => 'home_collection',
			'label' => 'Featured Home Collection',
			'name' => 'home_collection',
			'type' => 'taxonomy',
			'instructions' => 'Featured Collection',
			'taxonomy' => 'collection',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'home_type',
						'operator' => '==',
						'value' => 'collection',
					),
				),
			),
      'wrapper' => array(
        'width' => 70,
        'class' => '',
      ),
			'taxonomy' => 'collection',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 0,
			'save_terms' => 0,
		),
		array(
			'key' => 'home_interview',
			'label' => 'Featured Home Interview',
			'name' => 'home_interview',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 1,
			'post_type' => array(
				0 => 'interview',
			),
      'wrapper' => array(
        'width' => 70,
        'class' => '',
      ),
			'conditional_logic' => array(
				array(
					array(
						'field' => 'home_type',
						'operator' => '==',
						'value' => 'interview',
					),
				),
			),
			'post_type' => array(
				0 => 'interview',
			),
			'return_format' => 'id',
		),
		array(
			'key' => 'home_timeline',
			'label' => 'Featured Home Timeline',
			'name' => 'home_timeline',
			'type' => 'post_object',
			'required' => 1,
			'post_type' => array(
				0 => 'timeline',
			),
      'wrapper' => array(
        'width' => 70,
        'class' => '',
      ),
			'conditional_logic' => array(
				array(
					array(
						'field' => 'home_type',
						'operator' => '==',
						'value' => 'timeline',
					),
				),
			),
		),
    array(
      'key' => 'home_blog',
      'label' => 'Featured Home Blog',
      'name' => 'home_blog',
      'type' => 'post_object',
      'required' => 1,
      'post_type' => array(
        0 => 'post',
      ),
      'wrapper' => array(
        'width' => 70,
        'class' => '',
      ),
      'conditional_logic' => array(
        array(
          array(
            'field' => 'home_type',
            'operator' => '==',
            'value' => 'blog',
          ),
        ),
      ),
    ),
    [
      'key' => 'show_roll_home',
      'label' => 'Show content roll on homepage?',
			'instructions' => 'If selected, homepage will show eight extra boxes of content',
      'name' => 'show_roll_home',
      'type' => 'true_false',
      'default' => 0,
      'wrapper' => [ 'width' => '50' ]
    ],
		array(
			'key' => 'curated_home_content',
			'label' => 'Curated Home Content',
			'name' => 'curated_home_content',
			'type' => 'repeater',
			'instructions' => 'Select between zero and seven pieces of content to feature on the homepage. (If less than 7 are selected, the homepage will be padded out with the most recently published content.)',
			'min' => 0,
			'max' => 7,
			'layout' => 'block',
			'button_label' => 'Add Item',
			'sub_fields' => array(
				array(
					'key' => 'type',
					'label' => 'Type',
					'name' => 'type',
					'type' => 'radio',
					'wrapper' => array(
						'width' => 20,
						'class' => '',
					),
					'choices' => array(
						'collection' => 'Collection',
						'interview' => 'Interview',
						'timeline' => 'Timeline',
            'blog' => 'Blog'
					),
					'layout' => 'vertical',
					'return_format' => 'value',
				),
				array(
					'key' => 'curated_collection',
					'label' => 'Collection',
					'name' => 'curated_collection',
					'type' => 'taxonomy',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'type',
								'operator' => '==',
								'value' => 'collection',
							),
						),
					),
					'wrapper' => array(
						'width' => 80,
						'class' => '',
					),
					'taxonomy' => 'collection',
					'field_type' => 'select',
					'return_format' => 'id',
				),
				array(
					'key' => 'curated_timeline',
					'label' => 'Timeline',
					'name' => 'curated_timeline',
					'type' => 'post_object',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'type',
								'operator' => '==',
								'value' => 'timeline',
							),
						),
					),
          'wrapper' => array(
            'width' => 80,
            'class' => '',
          ),
					'post_type' => array(
						0 => 'timeline',
					),
					'return_format' => 'id',
				),
				array(
					'key' => 'curated_interview',
					'label' => 'Interview',
					'name' => 'curated_interview',
					'type' => 'post_object',
					'instructions' => '',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'type',
								'operator' => '==',
								'value' => 'interview',
							),
						),
					),
          'wrapper' => array(
            'width' => 80,
            'class' => '',
          ),
					'post_type' => array(
						0 => 'interview',
					),
					'return_format' => 'id',
				),
        array(
					'key' => 'curated_blog',
					'label' => 'Blog',
					'name' => 'curated_blog',
					'type' => 'post_object',
					'instructions' => '',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'type',
								'operator' => '==',
								'value' => 'blog',
							),
						),
					),
          'wrapper' => array(
            'width' => 80,
            'class' => '',
          ),
					'post_type' => array(
						0 => 'post',
					),
					'return_format' => 'id',
				),
			),
		),
		array(
			'key' => 'description_instructions',
			'type' => 'message',
			'message' => '<strong>The following fields affect the description area of the homepage.</strong>',
		),
		array(
			'key' => 'site_description_header',
			'label' => 'Site Description Header',
			'name' => 'site_description_header',
			'type' => 'text',
			'required' => 1,
		),
		array(
			'key' => 'site_description',
			'label' => 'Site Description',
			'name' => 'site_description',
			'type' => 'textarea',
			'required' => 1,
			'rows' => 4,
			'new_lines' => 'wpautop',
		),
		array(
			'key' => 'site_description_links',
			'label' => 'Site Description Links',
			'name' => 'site_description_links',
			'type' => 'repeater',
			'required' => 1,
			'collapsed' => 'link',
			'min' => 1,
			'layout' => 'block',
			'button_label' => 'Add Link',
			'sub_fields' => array(
				array(
					'key' => 'link',
					'label' => 'Link',
					'name' => 'link',
					'type' => 'post_object',
					'post_type' => array(
						0 => 'page',
					),
					'return_format' => 'id',
				),
			),
		),
		array(
			'key' => 'bucket_instructions',
			'type' => 'message',
			'message' => '<strong>The following fields affect the content on the homepage buckets.</strong>',
		),
		array(
			'key' => 'collections_content_description',
			'label' => 'Collections Content Description',
			'name' => 'collections_content_description',
			'type' => 'textarea',
			'instructions' => 'Please enter text explaining what collections are and how they relate to other content. Please limit this to three or four sentences.',
			'required' => 1,
			'rows' => 3,
			'wrapper' => array(
				'width' => 50,
				'class' => '',
				'id' => '',
			),
		),
		array(
			'key' => 'collections_content_image',
			'label' => 'Collections Content Image',
			'name' => 'collections_content_image',
			'type' => 'image',
			'wrapper' => array(
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'return_format' => 'id',
			'preview_size' => 'medium',
		),
		array(
			'key' => 'interviews_content_description',
			'label' => 'Interviews Content Description',
			'name' => 'interviews_content_description',
			'type' => 'textarea',
			'instructions' => 'Please enter text explaining what interviews are and how they relate to other content. Please limit this to three or four sentences.',
			'required' => 1,
			'wrapper' => array(
				'width' => 50,
			),
			'rows' => 3,
		),
		array(
			'key' => 'interviews_content_image',
			'label' => 'Interviews Content Image',
			'name' => 'interviews_content_image',
			'type' => 'image',
			'wrapper' => array(
				'width' => 50,
			),
			'return_format' => 'id',
			'preview_size' => 'medium',
		),
		array(
			'key' => 'timelines_content_description',
			'label' => 'Timelines Content Description',
			'name' => 'timelines_content_description',
			'type' => 'textarea',
			'instructions' => 'Please enter text explaining what timelines are and how they relate to other content. Please limit this to three or four sentences.',
			'required' => 1,
			'wrapper' => array(
				'width' => 50,
			),
			'rows' => 3,
		),
		array(
			'key' => 'timelines_content_image',
			'label' => 'Timelines Content Image',
			'name' => 'timelines_content_image',
			'type' => 'image',
			'wrapper' => array(
				'width' => 50,
			),
			'return_format' => 'id',
			'preview_size' => 'medium',
		),
		array(
			'key' => 'blog_content_description',
			'label' => 'Blog Content Description',
			'name' => 'blog_content_description',
			'type' => 'textarea',
			'instructions' => 'Please enter text explaining what blog posts are and how they relate to other content. Please limit this to three or four sentences.',
			'required' => 1,
			'wrapper' => array(
				'width' => 50,
			),
			'rows' => 3,
		),
		array(
			'key' => 'blog_content_image',
			'label' => 'Blog Content Image',
			'name' => 'blog_content_image',
			'type' => 'image',
			'wrapper' => array(
				'width' => 50,
			),
			'return_format' => 'id',
			'preview_size' => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
));

acf_add_local_field_group(array(
	'key' => 'blog_options',
	'title' => 'Blog Options',

	'fields' => array(
		array(
      'type' => 'message',
			'key' => 'homepage_instructions',
			'message' => '<strong>The following fields control what content appears on the blog page.</strong>',
		),
    [
      'key' => 'show_roll_blog',
      'label' => 'Show content roll on blog?',
			'instructions' => 'If selected, blog will show eight extra boxes of content',
      'name' => 'show_roll_blog',
      'type' => 'true_false',
      'default' => 0,
      'wrapper' => [ 'width' => '100' ]
    ],
		array(
			'key' => 'curated_blog_content',
			'label' => 'Curated Blog Content',
			'name' => 'curated_blog_content',
			'type' => 'repeater',
			'instructions' => 'Select between zero and seven pieces of content to feature on the blog. (If less than 7 are selected, the blog will be padded out with the most recently published content.)',
			'min' => 0,
			'max' => 7,
			'layout' => 'block',
			'button_label' => 'Add Item',
			'sub_fields' => array(
        array(
					'key' => 'curated_blog',
					'label' => 'Blog',
					'name' => 'curated_blog',
					'type' => 'post_object',
					'instructions' => '',
					'post_type' => array(
						0 => 'post',
					),
					'return_format' => 'id',
				),
			),
		),
	),
	'menu_order' => 2,
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
));

acf_add_local_field_group(array(
	'title' => 'Branding',
	'fields' => array(
		array(
			'key' => 'primary_brand_logo',
			'label' => 'Primary Brand Logo',
			'name' => 'primary_brand_logo',
			'type' => 'image',
			'required' => 1,
			'return_format' => 'array',
			'preview_size' => 'full',
		),
		array(
			'key' => 'primary_brand_logo_alt',
			'label' => 'Primary Brand Logo (alt)',
			'name' => 'primary_brand_logo_alt',
			'type' => 'image',
			'instructions' => 'Vertically condensed version of logo (for footer and interior pages).',
			'required' => 1,
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
		),
		array(
			'key' => 'secondary_brand_logo',
			'label' => 'Secondary Brand Logo',
			'name' => 'secondary_brand_logo',
			'type' => 'image',
			'required' => 1,
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
		),
		array(
			'key' => 'address',
			'label' => 'Address',
			'name' => 'address',
			'type' => 'textarea',
			'instructions' => 'Use {{year}} in place of year.',
			'required' => 1,
			'new_lines' => 'wpautop',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
	'menu_order' => 10000,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

  acf_add_local_field_group( [
  	'key' => 'acf-options',
  	'title' => 'Site Settings',
		'menu_order' => 3,
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
        'key' => 'search_highlight_color',
        'label' => 'Search Highlight Color',
        'name' => 'search_highlight_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '50%' ]
      ],
      [
        'key' => 'highlight_color',
        'label' => 'Highlight Color',
        'name' => 'highlight_color',
        'type' => 'color_picker',
        'wrapper' => [ 'width' => '50%' ]
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
  ] );

	acf_add_local_field_group(array(
		'key' => 'functional',
		'title' => 'Functional',
		'fields' => array(
			array(
				'key' => 'whitelisted_abbreviations',
				'label' => 'Whitelisted Abbreviations',
				'name' => 'whitelisted_abbreviations',
				'type' => 'textarea',
				'instructions' => 'Abbreviations that will not be flagged as sentence delimiters by the formatting tool for Rich Text Content. Separate each with a comma.',

				'default_value' => 'Mr.,Mrs.,Dr.,A.S.A.P',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options',
				),
			),
		),
		'menu_order' => 100,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;
