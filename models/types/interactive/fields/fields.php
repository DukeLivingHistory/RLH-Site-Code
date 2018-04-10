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
        'instructions' => 'Paste into Transcript (.vtt) window or upload in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '50' ]
      ],
      [
        'key' => 'supp_cont_file',
        'label' => 'Supporting Content',
        'name' => 'supp_cont_file',
        'type' => 'file',
        'instructions' => 'Paste into Supp. Content (.vtt) window or upload in WebVTT format.',
        'return_format' => 'array',
        'mime_types' => '',
        'wrapper' => [ 'width' => '50' ]
      ],
      [
        'key' => 'hide_from_blog',
        'label' => 'Hide from blog?',
        'name' => 'hide_from_blog',
        'type' => 'true_false',
        'instructions' => 'If checked, this interactive page will not display in blog feed.',
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
