<?php
include_once( get_template_directory().'/models/Interview.php' );
$route = new Route( '/timelines/(?P<id>\d+)/supp', 'GET', function( $data ){
  $timeline = new Timeline( $data['id'] );

  if( $timeline->get_supp_cont() ){
    $supp = $timeline->get_supp_cont();
    return $supp;
  }
  return false;

} );
