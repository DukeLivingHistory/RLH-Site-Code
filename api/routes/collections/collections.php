<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$route = new Route( '/collections/', 'GET', function($data){
  $collections = get_terms( 'collection' );

  foreach( $collections as $collection ){
    $node = new ContentNodeCollection( $collection->term_id, true );
    $timelines = get_posts([
      'post_type' => 'timeline',
      'posts_per_page' => -1,
      'tax_query' => [
        [
          'taxonomy' => 'collection',
          'field' => 'id',
          'terms' => $collection->term_id,
        ]
      ],
      'meta_query' => [
        [
          'key' => 'hide',
          'compare' => '!=',
          'value' => 1
        ]
      ]
    ]);
    foreach( $timelines as $timeline ) {
      $node->children[] = new ContentNode($timeline->ID, false);
    }

    $returns[] = $node;
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
