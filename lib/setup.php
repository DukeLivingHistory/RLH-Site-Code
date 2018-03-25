<?php

// add widgets
add_action('widgets_init', function() {
  register_sidebar([
    'id' => 'blog',
    'name' => 'Blog',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
  register_sidebar([
    'id' => 'posts',
    'name' => 'Posts',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
});

if( function_exists('acf_add_local_field_group') ):
  acf_add_local_field_group(array(
  	'key' => 'blog',
  	'title' => 'Blog',
  	'fields' => array(
  		array(
  			'key' => 'hide_sidebar',
  			'label' => 'Hide Sidebar',
  			'name' => 'hide_sidebar',
  			'type' => 'true_false',
  		),
  	),
  	'location' => array(
  		array(
  			array(
  				'param' => 'post_type',
  				'operator' => '==',
  				'value' => 'post',
  			),
  		),
  	),
  ));
endif;

add_filter('excerpt_length', function() {
  return 70;
}, 999);
