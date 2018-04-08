<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$route = new Route( '/collections/', 'GET', function($data){
  $collections = get_terms( 'collection' );

  $timelines = get_posts([
    'post_type' => 'timeline',
    'posts_per_page' => -1,
    'meta_query' => [
      'relation' => 'AND',
      [
        'key' => 'hide',
        'compare' => '!=',
        'value' => 1
      ]
    ]
  ]);

  foreach( $collections as $collection ){
    $returns[] = new ContentNodeCollection( $collection->term_id, true );
  }

  foreach( $timelines as $timeline ) {
    $returns[] = new ContentNode($timeline->ID, false);
  }

  usort($returns, function($a, $b) {
    return strcasecmp($a->title, $b->title);
  });

  return [
    'items' => $returns,
    'image' => get_field( 'collections_content_image', 'options' ),
    'name' => 'Collections'
  ];

} );
