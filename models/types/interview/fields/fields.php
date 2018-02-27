<?php

function get_cond_logic() {
  return [
    [
      [
        'field' => 'no_media',
        'operator' => '!=',
        'value' => '1'
      ]
    ]
  ];
}

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
        'key' => 'no_media',
        'label' => 'No Media',
        'name' => 'no_media',
        'type' => 'true_false',
        'instructions' => 'Select this box for interviews that do not have a video.',
        'default' => 0,
      ],
  		[
  			'key' => 'transcript_utilities',
  			'label' => 'Transcript Utilities',
  			'name' => 'transcript_utilities',
  			'type' => 'message'
  		],
      [
        'key' => 'youtube_id',
        'label' => 'YouTube Video ID',
        'name' => 'youtube_id',
        'type' => 'text',
        'maxlength' => '11',
        'conditional_logic' => get_cond_logic(),
        'wrapper' => [ 'width' => '33' ]
      ],
      [
        'key' => 'abc_term',
        'label' => 'Sort Term',
        'name' => 'abc_term',
        'type' => 'text',
        'required' => 1,
        'instructions' => 'Enter the term (i.e. last name) you want used when this content is sorted alphabetically.',
        'wrapper' => [ 'width' => '33' ]
      ],
      [
        'key' => 'interview_date',
        'label' => 'Interview Date',
        'name' => 'interview_date',
        'type' => 'date_picker',
        'display_format' => 'F d, Y',
        'required' => 1,
        'instructions' => 'Enter the date of the interview.',
        'wrapper' => [ 'width' => '33' ]
      ],
  		[
  			'key' => 'transcript_files',
  			'label' => 'Transcript Files',
  			'name' => 'transcript_files',
  			'type' => 'message'
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
        'wrapper' => [ 'width' => '33' ],
        'conditional_logic' => get_cond_logic()
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
        'key' => 'hide',
        'label' => 'Hide from feeds?',
        'name' => 'hide',
        'type' => 'true_false',
        'instructions' => 'If selected, this content will not be displayed on the home page or any archive pages. This content may still be referenced via Related Content relationships or through menus, such as the Research menu.',
        'wrapper' => [ 'width' => '33' ]
      ],
      [
        'key' => 'tab_transcript_raw',
        'label' => 'Transcript (.vtt)',
        'name' => '',
        'type' => 'tab'
      ],
      [
        'key'   => 'transcript_raw',
        'label' => 'Text(.vtt)',
        'name'  => 'transcript_raw',
        'type'  => 'textarea',
        'instructions' => "
          <p style=\"width: calc(100% - 180px - 1em); padding-right: 1em; display: inline-block; margin-top: 0;\">The \"Format No-Media Text\" button inserts dummy timecodes to enable supporting content and direct links in text that's not connected to audio/video. The related Whitelisted Abbreviations control is <a href=\"/wp-admin/admin.php?page=acf-options\"> here</a>.</p>
          <div style=\"display: inline-block; text-align-right; vertical-align: top;\">
            <a href=\"#\" id=\"js-format-interactive\" class=\"button-primary\">
              Format No-Media Text
            </a>
          </div>",
        'rows'  => 100
      ],
      [
        'key'   => 'tab_description_raw',
        'label' => 'Description (.vtt)',
        'name'  => '',
        'type'  => 'tab',
        'conditional_logic' => get_cond_logic()
      ],
      [
        'key'   => 'description_raw',
        'label' => 'Description (.vtt)',
        'name'  => 'description_raw',
        'type'  => 'textarea',
        'rows'  => 100,
        'conditional_logic' => get_cond_logic()
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
