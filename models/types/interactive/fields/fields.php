<?php

add_action( 'acf/init', function(){
  acf_add_local_field_group( [
  	'key' => 'interactive_options',
  	'title' => 'Interactive Page Options',
  	'fields' => [
  		[
  			'key' => 'tab_interactive',
  			'label' => 'Interactive Page Options',
  			'name' => '',
  			'type' => 'tab'
  		],
      [
        'key' => 'introduction',
        'label' => 'Introduction',
        'name' => 'introduction',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 0
      ],
      [
        'key' => 'transcript_file',
        'label' => 'Transcript',
        'name' => 'transcript',
        'type' => 'file',
        'instructions' => 'Transcripts should be uploaded in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '50' ]
      ],
      [
        'key' => 'supp_cont_file',
        'label' => 'Supporting Content',
        'name' => 'supp_cont_file',
        'type' => 'file',
        'instructions' => 'Transcripts should be uploaded in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '50' ]
      ],
      [
        'key' => 'tab_transcript_raw',
        'label' => 'Text (.vtt)',
        'name' => '',
        'type' => 'tab'
      ],
      [
        'key'   => 'transcript_raw',
        'label' => 'Text(.vtt)',
        'name'  => 'transcript_raw',
        'type'  => 'textarea',
        'instructions' => '<p>You may paste text here and press the button below to automatically insert timestamps. If there is an abbreviation that should not trigger a new timestamp, add it to the "Whitelisted Abbreviations" section<a href="/wp-admin/admin.php?page=acf-options">here</a>.</p><a href="#" id="js-format-interactive" class="button-primary">Format</a>',
        'rows'  => 100
      ],
  		[
  			'key' => 'tab_supporting_content',
  			'label' => 'Supp. Content',
  			'name' => '',
  			'type' => 'tab'
  		],
      get_supp_cont_fields(), // use function so fields can exist in multiple places
      [
        'key' => 'tab_supporting_content_raw',
        'label' => 'Supp. Content (.vtt)',
        'name' => '',
        'type' => 'tab'
      ],
      [
        'key' => 'save_from_supp_cont_raw',
        'label' => 'Update from raw supporting content?',
        'name' => 'save_from_supp_cont_raw',
        'instructions' => 'If selected, the content below will be used for supporting content for this rich text. Please note that this may remove existing images or other content from the supporting content.',
        'type' => 'true_false'
      ],
      [
        'key' => 'supporting_content_raw',
        'label' => 'Supporting Content (.vtt)',
        'name' => 'supporting_content_raw',
        'type' => 'textarea',
        'rows' => 100
      ]
    ],
  	'location' => [
  		[
  			[
  				'param' => 'post_type',
  				'operator' => '==',
  				'value' => 'interactive',
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
  ]);
}, 0);
