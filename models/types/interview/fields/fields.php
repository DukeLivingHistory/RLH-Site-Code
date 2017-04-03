<?php

add_action( 'acf/init', function(){
  acf_add_local_field_group( [
  	'key' => 'interview_options',
  	'title' => 'Interview Options',
  	'fields' => [
      // [
      //   'key' => 'is_transcript_processing',
      //   'label' => 'Processing',
      //   'name' => 'is_transcript_processing',
      //   'type' => 'true_false'
      // ],
      // [
      //   'key' => 'processing_message',
      //   'label' => '',
      //   'name' => '',
      //   'type' => 'message',
      //   'message' => 'NOTICES:<br/>1. Please wait. Tabs will take a few seconds to load.<br/>2. If you\'ve just hit "Publish" or "Update", changes are still processing. This typically takes a few minutes but may take longer. Reload page to check.',
      //   'new_lines' => '',
      //   'esc_html' => 0,
      //   'conditional_logic' => [
      //     [
      //       [
      //         'field' => 'is_transcript_processing',
      //         'operator' => '==',
      //         'value' => '1'
      //       ]
      //     ]
      //   ]
      // ],
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
        'instructions' => 'Transcripts should be uploaded in WebVTT format. If pulling from YouTube, the caption track will be converted to WebVTT for you.',
        'return_format' => 'array',
        'mime_types' => ''
      ],
  		[
  			'key' => 'transcript_utilities',
  			'label' => 'Transcript Utilities',
  			'name' => 'transcript_utilities',
  			'type' => 'message'
  		],
      [
        'key' => 'speaker_list',
        'label' => 'Speaker List',
        'name' => 'speaker_list',
        'type' => 'textarea',
        'rows' => 3,
        'instructions' => 'Enter the names of the speakers that are featured in this video, the way you\'d like them to appear in labels. Separate names with semicolons. You may enter multiple names for the same speaker as multiple entries (e.g. a full name for the first use and just a last name for subsequent uses).'
      ],
      [
        'key' => 'update',
        'label' => 'Pull transcript from YouTube?',
        'name' => 'update',
        'type' => 'true_false',
        'wrapper' => [ 'width' => '50' ],
        'instructions' => 'If selected, upon saving this interview you\'ll be asked to authenticate with the YouTube account hosting the video. After authenticating, the YouTube caption track will be saved to this post and you\'ll be redirected back to this page. Please note that this will erase existing content breaks in a track.'
      ],
      [
        'key' => 'sync',
        'label' => 'Sync',
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
        'key' => 'abc_term',
        'label' => 'Sort Term',
        'name' => 'abc_term',
        'type' => 'text',
        'required' => 1,
        'instructions' => 'Enter the term (i.e. last name) you want used when this content is sorted alphabetically.'
      ],
  		// [
  		// 	'key' => 'tab_transcript',
  		// 	'label' => 'Transcript',
  		// 	'name' => '',
  		// 	'type' => 'tab',
  		// 	'instructions' => '',
  		// 	'required' => 0,
  		// 	'placement' => 'top',
  		// 	'endpoint' => 0,
  		// ],
      // [
      //   'key' => 'update_from_fields',
      //   'label' => 'Update here?',
      //   'name' => 'update_from_fields',
      //   'type' => 'true_false',
      //   'instructions' => 'If selected, the WebVTT file will be reconstructed from the contents here.<input name="save" type="submit" class="button button-primary button-large" id="publish" value="Validate and save">'
      // ],
  		// [
  		// 	'key' => 'transcript_contents',
  		// 	'label' => 'Transcript',
  		// 	'name' => 'transcript_contents',
  		// 	'type' => 'flexible_content',
      //   'instructions' => 'Changes here will be reflected in the transcript file.',
  		// 	'required' => 0,
  		// 	'conditional_logic' => 0,
  		// 	'button_label' => 'Add Break',
  		// 	'min' => '',
  		// 	'max' => '',
  		// 	'layouts' => [
  		// 		[
  		// 			'key' => 'transcript_node',
  		// 			'name' => 'transcript_node',
  		// 			'label' => 'Transcript Node',
  		// 			'display' => 'table',
  		// 			'sub_fields' => [
  		// 				[
  		// 					'key' => 'transcript_node_start',
  		// 					'label' => 'Timestamp',
  		// 					'name' => 'transcript_node_start',
  		// 					'type' => 'text',
      //           'wrapper' => ['width'=>15],
      //           'required' => 1
  		// 				],
      //         [
      //           'key' => 'transcript_node_end',
      //           'label' => 'Timestamp',
      //           'name' => 'transcript_node_end',
      //           'type' => 'text',
      //           'wrapper' => ['width'=>15],
      //           'required' => 1
      //         ],
  		// 				[
  		// 					'key' => 'transcript_node_caption',
  		// 					'label' => 'Caption',
  		// 					'name' => 'transcript_node_caption',
  		// 					'type' => 'textarea',
  		// 					'instructions' => '',
      //           'rows' => 2,
      //           'required' => 1
  		// 				],
  		// 			],
  		// 			'min' => '',
  		// 			'max' => '',
  		// 		],
  		// 		[
  		// 			'key' => 'section_break',
  		// 			'name' => 'section_break',
  		// 			'label' => 'Section Break',
  		// 			'display' => 'table',
  		// 			'sub_fields' => [
      //         [
      //           'key' => 'transcript_node_timestamp',
      //           'label' => 'Timestamp',
      //           'name' => 'transcript_node_timestamp',
      //           'type' => 'text',
      //           'wrapper' => ['width'=>10]
      //         ],
  		// 				[
  		// 					'key' => 'section_break_title',
  		// 					'label' => 'Section Title',
  		// 					'name' => 'section_break_title',
  		// 					'type' => 'text',
  		// 					'instructions' => '',
      //           'required' => 1
  		// 				],
  		// 			],
  		// 			'min' => '',
  		// 			'max' => '',
  		// 		],
  		// 		[
  		// 			'key' => 'speaker_break',
  		// 			'name' => 'speaker_break',
  		// 			'label' => 'Speaker Break',
  		// 			'display' => 'block',
  		// 			'sub_fields' => [
  		// 				[
  		// 					'key' => 'speaker_name',
  		// 					'label' => 'Speaker Name',
  		// 					'name' => 'speaker_name',
  		// 					'type' => 'radio',
  		// 					'instructions' => 'Choose a speaker. Note that this interview must be saved with the Speaker List filled out for this field to be used.',
  		// 					'required' => 0,
  		// 					'conditional_logic' => 0,
  		// 					'choices' => [],
      //           'wrapper' => ['width'=>50],
  		// 					'other_choice' => 0,
  		// 					'save_other_choice' => 0,
  		// 					'default_value' => '',
  		// 					'layout' => 'horizontal',
  		// 				],
      //         [
      //           'key' => 'speaker_name_text',
      //           'label' => '',
      //           'name' => 'speaker_name_text',
      //           'type' => 'text',
      //           'instructions' => 'You can also manually set this.',
      //           'wrapper' => ['width'=>50]
      //         ]
  		// 			],
  		// 		],
  		// 		[
  		// 			'key' => 'paragraph_break',
  		// 			'name' => 'paragraph_break',
  		// 			'label' => 'Paragraph Break',
  		// 			'display' => 'block',
  		// 			'sub_fields' => [
  		// 				[
  		// 					'key' => 'paragraph_break_message',
  		// 					'label' => '',
  		// 					'name' => '',
  		// 					'type' => 'message',
  		// 					'instructions' => '',
  		// 					'required' => 0,
  		// 					'conditional_logic' => 0,
  		// 					'message' => 'Insert a paragraph before and after transcript nodes you\'d like sectioned into a paragraph.',
  		// 					'new_lines' => '',
  		// 					'esc_html' => 0,
  		// 				],
  		// 			],
  		// 			'min' => '',
  		// 			'max' => '',
  		// 		],
  		// 	],
  		// ],
      [
        'key' => 'tab_transcript_raw',
        'label' => 'Transcript (Raw)',
        'name' => '',
        'type' => 'tab'
      ],
      // [
      //   'key' => 'update_from_raw',
      //   'label' => 'Update here?',
      //   'name' => 'update_from_raw',
      //   'type' => 'true_false',
      //   'instructions' => 'If selected, the WebVTT file will be reconstructed from the contents here. (If this is checked for the drag-and-drop interface, the drag-and-drop interface will take precedence.)'
      // ],
      [
        'key' => 'transcript_raw',
        'label' => 'Transcript (Raw)',
        'name' => 'transcript_raw',
        'type' => 'textarea',
        'instructions' => 'Changes here will be reflected in the transcript file.',
        'rows' => 100
      ],
  		[
  			'key' => 'tab_supporting_content',
  			'label' => 'Supporting Content',
  			'name' => '',
  			'type' => 'tab'
  		],
      get_supp_cont_fields(), // use function so fields can exist in multiple places
      [
        'key' => 'tab_supporting_content_raw',
        'label' => 'Supporting Content (Raw)',
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
        'label' => 'Supporting Content (Raw)',
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

// use contents of speakers list for speaker name
function add_speakers( $field ){
  if( !is_admin() || !isset( $_GET['post'] ) ) return $field;
  $speakers = get_field( 'speaker_list', $_GET['post'] );
  $speakers = explode( ';', $speakers );
  foreach( $speakers as $speaker ){
    $speaker = trim( $speaker );
    $field['choices'][$speaker] = $speaker;
  }
  return $field;
}
add_filter( 'acf/load_field/key=speaker_name', 'add_speakers');
