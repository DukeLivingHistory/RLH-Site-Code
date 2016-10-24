<?php

Class Timestamp {

  function __construct( $hash, $text ){
    $this->hash = $hash;
    $this->text = $text;
  }

}

$route = new Route( 'content/(?P<id>\d+)/timestamps', 'GET', function( $data ){

  $id = $data['id'];
  $type = get_post_type( $id );
  $timestamps = [];

  if( $type === 'interview' ){
    $transcript = get_field( 'transcript_contents', $id );
    foreach( $transcript as $transcript_item ){
      if( $transcript_item['acf_fc_layout'] === 'section_break' ){
        $hash = sanitize_timestamp( $transcript_item['transcript_node_timestamp'] );
        $text = $transcript_item['section_break_title'];
        $timestamp = new Timestamp( $hash, $text );
        array_push( $timestamps, $timestamp );
      }
    }
  } elseif( $type === 'timeline' ) {
    $timeline = get_field( 'events', $id );
    $i = 0;
    foreach( $timeline as $timeline_item ){
      $hash = $i++;
      $text = $timeline_item['title'];
      $timestamp = new Timestamp( $hash, $text );
      array_push( $timestamps, $timestamp );
    }
  }

  return $timestamps;

} );
