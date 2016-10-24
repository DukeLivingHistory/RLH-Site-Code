<?php
include_once( get_template_directory().'/models/ContentNode.php' );
$search = new Route( '/search/(?P<term>.*)', 'GET', function( $data ){
  $args = $data->get_query_params();

  $content = get_posts( [
    'post_type' => [ 'timeline', 'interview' ],
    'posts_per_page' => -1,
    's' => $data['term']
  ] );

  $terms = get_terms( 'collection', [
    'number' => 0,
    'search' => $data['term']
  ] );

  $results = array_merge( $content, $terms );

  $count = isset( $args['count'] ) ? $args['count'] : false;
  $offset = isset( $args['offset'] ) ? $args['offset'] : false;

  if( $count !== false && $offset !== false ){
    $results = array_slice( $results, $offset, $count );
  }
  //print_r( $results );

  foreach( $results as $result ){
    if( isset( $result->ID ) ){
      $returns['items'][] = new ContentNode( $result->ID );
    } else {
      $returns['items'][] = new ContentNodeCollection( $result->term_id );
    }
  }

  $returns['name'] = 'Search for '.$data['term'];

  return $returns;
} );
