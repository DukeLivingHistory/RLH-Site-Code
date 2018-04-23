<?php
/* This file lets us get the ACF fields needed to be registered by
 * both interviews and timelines from one place.
 */

function get_supp_cont_fields(){
  return [
		'key' => 'sc_row',
		'label' => 'Supporting Content',
		'name' => 'sc_row',
		'type' => 'repeater',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'collapsed' => 'sc_timestamp',
		'min' => '',
		'max' => '',
		'layout' => 'block',
		'button_label' => 'Add Item',
		'sub_fields' => [
			[
				'key' => 'sc_timestamp',
				'label' => 'Timestamp',
				'name' => 'timestamp',
				'type' => 'select',
        'allow_null' => 1,
        'wrapper' => [ 'width' => '80' ]
			],
      [
        'key' => 'sc_open',
        'label' => 'Open by default',
        'name' => 'open',
        'type' => 'true_false',
        'default' => 0,
        'wrapper' => [ 'width' => '20' ]
      ],
			[
				'key' => 'sc_content',
				'label' => 'Content',
				'name' => 'content',
				'type' => 'flexible_content',
				'button_label' => 'Select Type',
				'min' => 0,
				'max' => 1,
				'layouts' => [
					[
						'key' => 'sc_text',
						'name' => 'text',
						'label' => 'Text',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_text_content',
								'label' => 'Content',
                'type' => 'wysiwyg',
								'name' => 'content',
								'tabs' => 'all',
								'toolbar' => 'basic',
								'media_upload' => 0,
                'required' => 1
							]
						],
					],
					[
						'key' => 'sc_blockquote',
						'name' => 'blockquote',
						'label' => 'Blockquote',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_blockquote_quotetext',
								'label' => 'Quote Text',
								'name' => 'quote',
								'type' => 'textarea',
								'rows' => 4,
                'required' => 1
							],
							[
								'key' => 'sc_blockquote_attribution',
								'label' => 'Attribution',
								'name' => 'attribution',
								'type' => 'text'
							],
						]
					],
					[
						'key' => 'sc_image',
						'name' => 'image',
						'label' => 'Image',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_image_img',
								'label' => 'Image',
								'name' => 'sc_image_img',
								'type' => 'image',
								'preview_size' => 'thumbnail',
								'return_format' => 'array',
								'library' => 'all',
                'required' => 1
							],
						]
					],
					[
						'key' => 'sc_gallery',
						'name' => 'gallery',
						'label' => 'Gallery',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_gallery_title',
								'label' => 'Title',
								'name' => 'title',
								'type' => 'text',
                'required' => 1
							],
							[
								'key' => 'sc_gallery_description',
								'label' => 'Description',
								'name' => 'description',
								'type' => 'textarea',
								'rows' => 3
							],
              [
                'key' => 'sc_gallery_gallery',
                'label' => 'Gallery',
                'name' => 'gallery',
                'type' => 'post_object',
                'post_type' => ['gallery'],
                'return_format' => 'ID',
                'required' => 1
              ]
						]
					],
					[
						'key' => 'sc_externallink',
						'name' => 'externallink',
						'label' => 'External Link',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_externallink_title',
								'label' => 'Title',
								'name' => 'title',
								'type' => 'text',
                'required' => 1
							],
							[
								'key' => 'sc_externallink_description',
								'label' => 'Description',
								'name' => 'description',
								'type' => 'textarea',
								'rows' => 3
							],
              [
								'key' => 'sc_externallink_text',
								'label' => 'Link Text',
								'name' => 'text',
								'type' => 'text'
							],
              [
                'key' => 'sc_externallink_url',
                'label' => 'URL',
                'name' => 'url',
                'type' => 'url',
                'required' => 1
              ],
						]
					],
					[
						'key' => 'sc_internallink',
						'name' => 'internallink',
						'label' => 'Internal Link',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_internallink_to',
								'label' => 'Link To',
								'name' => 'link',
								'type' => 'post_object',
								'post_type' => [
									0 => 'interview',
									1 => 'timeline',
								],
                'required' => 1
							],
              [
                'key' => 'sc_internalink_timestamp',
                'label' => 'Timestamp',
                'name' => 'link_timestamp',
                'type' => 'text',
                'instructions' => 'The value below will be added to your internal link as a hash. If you know the hash you want, you can add it manually. Otherwise, you can use the picker below to select specific timestamps.'
              ],
              [
                'key' => 'sc_internalink_timestamp_picker',
                'label' => 'Timestamp Picker',
                'name' => 'link_timestamp_picker',
                'type' => 'select'
              ],
              [
                'key' => 'sc_internalink_label',
                'label' => 'Label',
                'name' => 'link_label',
                'type' => 'text',
                'instructions' => 'If not provided, the name of the linked content will be used.'
              ],
              [
                'key' => 'sc_internalink_description',
                'label' => 'Description',
                'name' => 'link_description',
                'type' => 'textarea',
                'rows' => 3
              ]
						]
					],
					[
						'key' => 'sc_file',
						'name' => 'file',
						'label' => 'File Download',
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'sc_file_file',
								'label' => 'File',
								'name' => 'file',
								'type' => 'file',
								'return_format' => 'array',
								'library' => 'all',
                'required' => 1
							],
							[
								'key' => 'sc_file_description',
								'label' => 'Description',
								'name' => 'description',
								'type' => 'textarea',
								'rows' => 3
							]
						]
					],
          [
            'key' => 'sc_video',
            'name' => 'video',
            'label' => 'Video',
            'display' => 'block',
            'sub_fields' => [
              [
                'key' => 'sc_video_title',
                'name' => 'name',
                'label' => 'Title',
                'type' => 'text',
                'required' => 1,
              ],
              [
                'key' => 'sc_video_iframe',
                'name' => 'iframe',
                'label' => 'iframe code',
                'type' => 'textarea',
                'required' => 1,
              ],
            ],
          ],
          [
            'key' => 'sc_map',
            'name' => 'map_location',
            'label' => 'Map Location',
            'display' => 'block',
            'sub_fields' => [
              [
                'key' => 'sc_map_title',
                'label' => 'Title',
                'name' => 'name',
                'type' => 'text',
                'required' => 1
              ],
              [
                'key' => 'sc_map_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'google_map',
                'required' => 1
              ],
              [
                'key' => 'sc_zoom_level',
                'label' => 'Zoom Level',
                'name' => 'zoom',
                'instructions' => 'Enter a zoom level between 1 and 18',
                'type' =>'text',
                'default' => '13'
              ]
            ]
          ]
				] // end layouts
			] // end sc_content
		] //end subfields
	];
}
