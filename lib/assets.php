<?php

/*
 * This file enqueues the scripts and stylesheets needed by the front end of the site.
 */

add_action( 'wp_enqueue_scripts', function(){
  wp_deregister_script('jquery');
  wp_dequeue_script( 'jquery' );
  wp_enqueue_script( 'jquery-cdn', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js', null, null, false );
  wp_enqueue_script( 'featherlight-js', get_stylesheet_directory_uri().'/assets/vendor/js/featherlight.min.js', null, null, true );
  wp_enqueue_script( 'main-js', get_stylesheet_directory_uri().'/assets/dist/js/bundle.js?update=5', null, null, true );
  wp_enqueue_style( 'main-css', get_stylesheet_directory_uri().'/assets/dist/css/styles.min.css?update=5', false, null );

  $menu = wp_get_nav_menu_items('interactive');
  wp_localize_script('main-js', 'interactive_menu', $menu);
} );
