<?php

add_action( 'acf/init', function(){
  acf_add_local_field_group( [
  	'key' => 'interview_options',
  	'title' => 'Interview Options',
  	'fields' => [
  		[
  			'key' => 'tab_interview',
  			'label' => 'Interview Options',
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
        'key' => 'youtube_id',
        'label' => 'YouTube Video ID',
        'name' => 'youtube_id',
        'type' => 'text',
        'maxlength' => '11'
      ],
      [
        'key' => 'transcript_file',
        'label' => 'Transcript',
        'name' => 'transcript',
        'type' => 'file',
        'instructions' => 'Transcripts should be uploaded in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '33' ]
      ],
      [
        'key'    => 'description_file',
        'label'  => 'Description',
        'name'   => 'description',
        'type'   => 'file',
        'instructions' => 'Descriptions should be uploaded in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '33' ]
      ],
      [
        'key' => 'supp_cont_file',
        'label' => 'Supporting Content',
        'name' => 'supp_cont_file',
        'type' => 'file',
        'instructions' => 'Transcripts should be uploaded in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '33' ]
      ],
  		[
  			'key' => 'transcript_utilities',
  			'label' => 'Transcript Utilities',
  			'name' => 'transcript_utilities',
  			'type' => 'message'
  		],
      // @deprecated
      // [
      //   'key' => 'update',
      //   'label' => 'Pull transcript from YouTube?',
      //   'name' => 'update',
      //   'type' => 'true_false',
      //   'wrapper' => [ 'width' => '50' ],
      //   'instructions' => 'If selected, upon saving this interview you\'ll be asked to authenticate with the YouTube account hosting the video. After authenticating, the YouTube caption track will be saved to this post and you\'ll be redirected back to this page. Please note that this will erase existing content breaks in a track.'
      // ],
      // [
      //   'key' => 'sync',
      //   'label' => 'Sync',
      //   'name' => 'sync',
      //   'type' => 'true_false',
      //   'wrapper' => [ 'width' => '50' ],
  		// 	'instructions' => 'If selected, upon saving the timestamps or event dates for this interview or timeline will be available to use with supporting content. This may erase existing timestamps or event dates that no longer exist in the transcript or timeline. Only select this when initially saving or if the transcript or timeline has changed.'
      // ],
      [
        'key' => 'hide',
        'label' => 'Hide from feeds?',
        'name' => 'hide',
        'type' => 'true_false',
        'instructions' => 'If selected, this content will not be displayed on the home page or any archive pages. This content may still be referenced via Related Content relationships or through menus, such as the Research menu.'
      ],
      [
        'key' => 'abc_term',
        'label' => 'Sort Term',
        'name' => 'abc_term',
        'type' => 'text',
        'required' => 1,
        'instructions' => 'Enter the term (i.e. last name) you want used when this content is sorted alphabetically.'
      ],
      [
        'key' => 'interview_date',
        'label' => 'Interview Date',
        'name' => 'interview_date',
        'type' => 'date_picker',
        'display_format' => 'F d, Y',
        'required' => 1,
        'instructions' => 'Enter the date of the interview.'
      ],
      [
        'key' => 'tab_transcript_raw',
        'label' => 'Transcript (.vtt)',
        'name' => '',
        'type' => 'tab'
      ],
      [
        'key'   => 'transcript_raw',
        'label' => 'Transcript (.vtt)',
        'name'  => 'transcript_raw',
        'type'  => 'textarea',
        'instructions' => 'Changes here will be reflected in the transcript file.',
        'rows'  => 100
      ],
      [
        'key'   => 'tab_description_raw',
        'label' => 'Description (.vtt)',
        'name'  => '',
        'type'  => 'tab'
      ],
      [
        'key'   => 'description_raw',
        'label' => 'Description (.vtt)',
        'name'  => 'description_raw',
        'type'  => 'textarea',
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
        'instructions' => 'If selected, the content below will be used for supporting content for this interview. Please note that this may remove existing images or other content from the supporting content.',
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
  				'value' => 'interview',
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
}, 0 );
