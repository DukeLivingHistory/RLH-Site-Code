<?php
include_once( get_template_directory().'/models/Collection.php' );
$route = new Route( '/collections/(?P<id>\d+)', 'GET', function($data){
  $params = $data->get_query_params();
  $type = isset( $params['type'] ) ? $params['type'] : [ 'interview', 'timeline'];
  $search = isset( $params['s'] ) ? str_replace('+', ' ', urldecode($params['s'])) : false;
  $count = isset( $params['count'] ) ? $params['count'] : -1;
  $not = isset( $params['not'] ) ? $params['not'] : 0;
  $collection = new Collection( $data['id'] );
  $collection->content = $collection->get_content_by_type( $type, $search, $count, $not );
  usort($collection->content, function($a, $b) {
    $a_compare = $a->title;
    $b_compare = $b->title;
    if($a_sort_by = get_field('abc_term', $a->id)) $a_compare = $a_sort_by;
    if($b_sort_by = get_field('abc_term', $b->id)) $b_compare = $b_sort_by;
    return strcmp($a_compare, $b_compare);
  });
  return $collection;
} );
