<?php

class Taxonomy {
  function __construct( $name, $plural = false, $describes = [] ){
    $this->name = $name;
    $this->plural = $plural ? $plural : $name.'s';
    $this->describes = is_string( $describes ) ? [ $describes ] : $describes;
    add_action( 'init', function(){
      $labels = [
        'name'                       => _x( $this->plural, 'Taxonomy General Name', 'text_domain' ),
    		'singular_name'              => _x( $this->name, 'Taxonomy Singular Name', 'text_domain' ),
    		'menu_name'                  => __( $this->plural, 'text_domain' ),
        'all_items'                  => __( 'All '.$this->plural, 'text_domain' ),
    		'new_item_name'              => __( 'New '.$this->name.' Name', 'text_domain' ),
    		'add_new_item'               => __( 'Add New '.$this->name, 'text_domain' ),
    		'edit_item'                  => __( 'Edit '.$this->name, 'text_domain' ),
    		'update_item'                => __( 'Update '.$this->name, 'text_domain' ),
    		'view_item'                  => __( 'View '.$this->name, 'text_domain' ),
    		'separate_items_with_commas' => __( 'Separate '.strtolower( $this->plural ).' with commas', 'text_domain' ),
    		'add_or_remove_items'        => __( 'Add or remove '.strtolower( $this->plural ), 'text_domain' ),
    		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
    		'popular_items'              => __( 'Popular '.$this->plural, 'text_domain' ),
    		'search_items'               => __( 'Search '.$this->plural, 'text_domain' ),
    		'no_terms'                   => __( 'No'.$this->plural, 'text_domain' ),
    		'items_list'                 => __( $this->plural.' list', 'text_domain' ),
    		'items_list_navigation'      => __( $this->plural.' list navigation', 'text_domain' ),
      ];
      $args = [
        'labels'                     => $labels,
    		'hierarchical'               => true,
    		'public'                     => true,
        'rewrite'                    => [ 'slug' => strtolower( $this->plural ) ],
    		'show_ui'                    => true,
    		'show_admin_column'          => true,
    		'show_in_nav_menus'          => true,
    		'show_tagcloud'              => true
      ];
      register_taxonomy( strtolower( $this->name ), $this->describes, $args );
    }, 0 );
    add_action( 'acf/init', function(){
      acf_add_local_field_group([
        'key' => strtolower( $this->name ).'_img_group',
        'title' => 'Collection Options',
        'fields' => [
          [
            'key' => strtolower( $this->name ).'_description',
            'type' => 'wysiwyg',
            'name' => strtolower( $this->name ).'_description',
            'label' => 'Description',
            'media_upload' => 0
          ],
          [
            'key' => strtolower( $this->name ).'_img',
            'label' => 'Featured Image',
            'name' => 'featured_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => '',
            ],
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all'
          ],
        ],
        'location' => [
          [
            [
              'param' => 'taxonomy',
              'operator' => '==',
              'value' => strtolower( $this->name ),
            ],
          ],
        ]
      ] );
    } );
  }
}
