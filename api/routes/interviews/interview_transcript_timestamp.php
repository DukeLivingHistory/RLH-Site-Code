<?php
include_once( get_template_directory().'/models/Transcript.php' );

$route = new Route( '/interviews/(?P<id>\d+)/transcript/(?P<timestamp>[\d][\d:\.]+)', 'GET', function( $data ){
  $transcript = new Transcript( $data['id'] );
  $caption = new stdClass();
  $caption->timestamp = $data['timestamp'];
  $caption->caption = $transcript->get_caption( $data['timestamp'] );
  $caption->has_supp = $transcript->has_supp_at( $data['timestamp'] );
  return $caption;
} );
