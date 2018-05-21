<?php

/* This file registers our ACF site options. */
function acf_hide_drafts( $args ) {
  $args['post_status'] = 'publish';
  return $args;
}
add_filter('acf/fields/post_object/query', 'acf_hide_drafts', 10, 3);

function acf_hide_empty_tax( $args ) {
  $args['hide_empty'] = 1;
  return $args;
}
add_filter('acf/fields/taxonomy/query', 'acf_hide_empty_tax', 10, 2);

function acf_load_menus( $field ) {
  $terms = get_terms([
    'taxonomy' => 'nav_menu'
  ]);
  foreach($terms as $menu) {
    $slug = str_replace('-', '_', $menu->slug);
    $menu_options[$slug] = $menu->name;
  }
  $field['choices'] = $menu_options;
  return $field;
}

add_filter('acf/load_field/name=show_menu', 'acf_load_menus');
