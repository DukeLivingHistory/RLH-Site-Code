<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$route = new Route( '/timelines/', 'GET', function( $data ){
  $args = $data->get_query_params();

  $timelines = get_posts( [
    'post_type' => 'timeline',
    'posts_per_page' => isset( $args['count'] ) ? $args['count'] : -1,
    'offset' => isset( $args['offset'] ) ? $args['offset'] : 0,
    'meta_query' => [
      [
        'key' => 'hide',
        'compare' => '!=',
        'value' => 1
      ]
    ]
  ] );

  foreach( $timelines as $timeline ){
    $returns[] = new ContentNode( $timeline->ID );
  }

  return [
    'items' => $returns,
    'image' => get_field( 'timelines_content_image', 'options' ),
    'name' => 'Timelines'
  ];

} );
