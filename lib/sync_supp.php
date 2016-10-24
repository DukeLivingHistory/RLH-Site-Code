<?php

/* This file makes the results of save_timestamp available for use with supporting content. */

function add_choices( $field ){
  if( !is_admin() || !isset( $_GET['post'] ) || !isset(get_post_meta( $_GET['post'], 'timestamps' )[0]) ) return $field;
  $timestamps = get_post_meta( $_GET['post'], 'timestamps' )[0]; // set in save_timestamp
  if( !$timestamps ) return;
  foreach( $timestamps as $timestamp => $label ){
    if( get_post_type( $_GET['post'] ) === 'interview' ){
      $field['choices'][$timestamp] = $timestamp.' '.$label;
    } elseif( get_post_type( $_GET['post'] ) === 'timeline' ){
      $field['choices'][$label] = $label;
    }
  }
  return $field;
}
add_filter( 'acf/load_field/key=sc_timestamp', 'add_choices');
