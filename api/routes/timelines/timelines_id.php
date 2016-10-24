<?php
include_once( get_template_directory().'/models/ContentNode.php' );
include_once( get_template_directory().'/models/Timeline.php' );
$route = new Route( '/timelines/(?P<id>\d+)', 'GET', function($data){
  $timeline =  new Timeline( $data['id'] );

  if( $timeline->events ){
    foreach( $timeline->events as &$event ){
      if( $event['content_link'] ){
        $link_id = $event['content_link'];
        $event['content_link_id'] = $link_id;
        $event['content_link'] = get_permalink( $link_id );
        $event['content_link_text'] = get_the_title( $link_id );
        $event['content_link_type'] = get_post_type( $link_id );
      }
    }
  }

  if( $timeline->collections ){
    $i = 0;
    foreach( $timeline->collections as &$collection ){
      $collections_formatted[$i]['id'] = $collection;
      $collections_formatted[$i]['type'] = 'collection';
      $collections_formatted[$i]['link'] = get_term_link( $collection );
      $collections_formatted[$i++]['link_text'] = get_term( $collection )->name;
    }
    $timeline->collections = $collections_formatted;
  }

  return $timeline;
} );
