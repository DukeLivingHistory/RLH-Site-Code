<?php
include_once( get_template_directory().'/models/Interview.php' );
$route = new Route( '/interviews/(?P<id>\d+)', 'GET', function( $data ){
  $interview = new Interview( $data['id'] );

  if( $interview->collections ){
    $i = 0;
    foreach( $interview->collections as $collection ){
      $collections_formatted[$i]['id'] = $collection;
      $collections_formatted[$i]['type'] = 'collection';
      $collections_formatted[$i]['link'] = get_term_link( $collection );
      $collections_formatted[$i++]['link_text'] = get_term( $collection )->name;
    }
    $interview->collections = $collections_formatted;
  }

  return $interview;
} );
