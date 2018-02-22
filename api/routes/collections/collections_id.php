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
  return $collection;
} );
