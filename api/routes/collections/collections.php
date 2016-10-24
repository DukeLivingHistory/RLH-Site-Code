<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$route = new Route( '/collections/', 'GET', function($data){
  $collections = get_terms( 'collection' );
  foreach( $collections as $collection ){
    $returns[] = new ContentNodeCollection( $collection->term_id, true );
  }

  return [
    'items' => $returns,
    'image' => get_field( 'collections_content_image', 'options' ),
    'name' => 'Collections'
  ];

} );
