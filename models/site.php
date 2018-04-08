<?php

add_theme_support('post-thumbnails');

add_action( 'after_setup_theme',  'register_menus' );
function register_menus() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'primary' ) );
  register_nav_menu( 'utility', __( 'Utility Menu', 'utility' ) );
  register_nav_menu( 'research', __( 'Research Menu', 'research' ) );
  register_nav_menu( 'blog', __( 'Blog Menu', 'blog' ) );
  register_nav_menu( 'standard', __( 'Standard Menu', 'standard' ) );
  register_nav_menu( 'extra', __( 'Extra Menu', 'extra' ) );
}

add_action( 'admin_menu', 'add_collections_item' );
function add_collections_item(){
  add_menu_page( 'Collections', 'Collections', 'read', '/edit-tags.php?taxonomy=collection', null, 'dashicons-admin-post', 8 );
}

// add options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}
