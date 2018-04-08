<?php

/*
 * This file adds the body attributes needed by the application portion of the site.
 * data-endpoint = the type of contents
 * data-id = the id of the object being displayed OR "archive" for a get_resource_type
 * data-search = the search term (for search page only)
 */

function body_attr(){

  if( is_front_page() ){
    return 'class="home"';
  }

  // sanitize the slug for "collection"
  $request = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : false;
  $is_collection_archive = $request === '/collections' ||  $request === '/collections/';

  $attr = 'data-endpoint="';
  if( $is_collection_archive ){
    $attr .= 'collections';
  } elseif( is_search() ){
    $attr .= 'search';
    if( $_GET['type'] ) {
      $attr .= '" data-type="'.$_GET['type'];
    }
  } elseif( is_tax() ) {
    $attr .= get_taxonomy( get_queried_object()->taxonomy )->rewrite['slug'];
  } elseif( is_singular() || is_archive() ){
    $attr .= get_post_type_object( get_post_type() )->rewrite['slug'];
  }
  $attr .= '" ';
  if( is_singular() || is_tax() ){
    $attr .=  'data-id="';
    $attr .= is_tax() ? get_queried_object()->term_id : get_the_ID(); // if tax, return term_id. else return id.
    $attr .= '" ';
  } elseif( is_archive() || is_search() || $is_collection_archive ){
    $attr .= 'data-id="archive"';
  }
  if( is_search() ){
    $attr .= ' data-search="'.( !isset( $_GET['s'] ) ?: urlencode($_GET['s']) ).'"';
  }

  return $attr;
}
