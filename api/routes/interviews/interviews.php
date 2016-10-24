<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$route = new Route( '/interviews/', 'GET', function( $data ){
  $args = $data->get_query_params();

  $query_args = [
    'post_type' => 'interview',
    'posts_per_page' => isset( $args['count'] ) ? $args['count'] : -1,
    'offset' => isset( $args['offset'] ) ? $args['offset'] : 0,
    'meta_query' => [
      [
        'key' => 'hide',
        'compare' => '!=',
        'value' => 1
      ]
    ],
    'fields' => 'ids'
  ];

  if( isset( $args['order'] ) && $args['order'] === 'abc' ){
    $query_args['orderby'] = 'meta_value';
    $query_args['meta_key'] = 'abc_term';
    $query_args['order'] = 'ASC';
  }

  $interviews = get_posts( $query_args );

  foreach( $interviews as $interview ){
    $returns[] = new ContentNode( $interview );
  }

  return [
    'items' => $returns,
    'image' => get_field( 'interviews_content_image', 'options' ),
    'name' => 'Interviews'
  ];

} );
