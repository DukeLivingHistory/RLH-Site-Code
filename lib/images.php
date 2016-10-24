<?php

/* This file registers custom image sizes based on the contents of /img/ */

function registerSizes(){
	$sizes = file_get_contents( get_stylesheet_directory().'/lib/img/sizes.json' );
  $sizes = json_decode( $sizes );
	$sets = $sizes->sets;
  $cases = $sizes->cases;

	foreach( $sets as $set => $set_sizes ) {
		foreach( $set_sizes as $breakpoint => $dimensions ){
			add_image_size( $set.'_'.$breakpoint, $dimensions->w, $dimensions->h, true );
		}
	}

  foreach( $cases as $case => $dimensions ) {
    if( isset( $dimensions->w ) && isset( $dimensions->h ) ){
			add_image_size( $case, $dimensions->w, $dimensions->h, true );
		} elseif( isset( $dimensions->w ) ){
			add_image_size( $case, $dimensions->w, false) ;
		} elseif( isset( $dimensions->h ) ){
			add_image_size( $case, 9999, $dimensions->h, false);
		}
  }

}
add_action( 'init', 'registerSizes' );

function removeDefaults( $sizes) {
  unset( $sizes['thumbnail'] );
  unset( $sizes['medium'] );
  unset( $sizes['medium_large'] );
  unset( $sizes['large'] );
  return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'removeDefaults');
