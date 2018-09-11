<?php

/*
 * This file enqueues the scripts and stylesheets needed by the front end of the site.
 */

add_action( 'wp_enqueue_scripts', function(){
  wp_deregister_script('jquery');
  wp_dequeue_script( 'jquery' );
  wp_enqueue_script( 'jquery-cdn', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js', null, null, false );
  wp_enqueue_script( 'featherlight-js', get_template_directory_uri().'/assets/vendor/js/featherlight.min.js', null, null, true );
  wp_enqueue_script( 'main-js', get_template_directory_uri().'/assets/dist/js/bundle.js?update=5', null, null, true );
  wp_enqueue_style( 'main-css', get_template_directory_uri().'/assets/dist/css/styles.min.css?update=5', false, null );

  $terms = get_terms([
    'taxonomy' => 'nav_menu'
  ]);
  foreach($terms as $menu) {
    $slug = str_replace('-', '_', $menu->slug);
    $menu_items = wp_get_nav_menu_items($menu->slug);
    wp_localize_script('main-js', "menu_$slug", $menu_items);
  }
} );
