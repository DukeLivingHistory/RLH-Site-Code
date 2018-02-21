<?php

class Type {
  function __construct($name, $plural = false, $supports = ['title']){
    $this->name = $name;
    $this->plural = $plural ? $plural : $name.'s';
    $this->lower = str_replace(' ', '-', strtolower($this->name));
    $this->supports = $supports;
    $this->slug = str_replace(' ', '-', strtolower($this->plural));

    if($name === 'No Media VTT') { // HACK
      $this->slug = 'rich-text';
    }

    add_action('init', function(){
      $labels = [
        'name'                  => _x($this->plural, 'Post Type General Name', 'text_domain'),
    		'singular_name'         => _x($this->name, 'Post Type Singular Name', 'text_domain'),
    		'menu_name'             => __($this->plural, 'text_domain'),
    		'name_admin_bar'        => __($this->name, 'text_domain'),
    		'archives'              => __($this->name.'Archives', 'text_domain'),
    		'parent_item_colon'     => __('Parent '.$this->name.':', 'text_domain'),
    		'all_items'             => __('All '.$this->plural, 'text_domain'),
    		'add_new_item'          => __('Add New '.$this->name, 'text_domain'),
    		'add_new'               => __('Add '.$this->name, 'text_domain'),
    		'new_item'              => __('New '.$this->name, 'text_domain'),
    		'edit_item'             => __('Edit '.$this->name, 'text_domain'),
    		'update_item'           => __('Update '.$this->name, 'text_domain'),
    		'view_item'             => __('View '.$this->name, 'text_domain'),
    		'search_items'          => __('Search', 'text_domain'),
    		'insert_into_item'      => __('Insert into '.$this->lower, 'text_domain'),
    		'uploaded_to_this_item' => __('Uploaded to this '.$this->lower, 'text_domain'),
    		'items_list'            => __($this->plural.' list', 'text_domain'),
    		'items_list_navigation' => __($this->plural.' list navigation', 'text_domain'),
    		'filter_items_list'     => __('Filter '.$this->lower.'s list', 'text_domain')
      ];

      $args = [
        'label'                 => __($this->name, 'text_domain'),
    		'description'           => __($this->name.' Description', 'text_domain'),
    		'labels'                => $labels,
    		'supports'              => $this->supports,
    		'hierarchical'          => false,
    		'public'                => true,
    		'show_ui'               => true,
    		'show_in_menu'          => true,
    		'menu_position'         => 5,
    		'show_in_admin_bar'     => true,
    		'show_in_nav_menus'     => true,
    		'has_archive'           => $this->slug,
    		'publicly_queryable'    => true,
        'rewrite'               => [ 'slug' => $this->slug ]
      ];

      register_post_type($this->lower, $args);
    }, 0);

    if(file_exists(get_template_directory().'/models/types/'.$this->lower.'/fields/fields.php')){
      include_once(get_template_directory().'/models/types/'.$this->lower.'/fields/fields.php');
    }
  }
}
