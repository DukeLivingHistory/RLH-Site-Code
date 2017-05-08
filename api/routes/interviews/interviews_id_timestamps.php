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
  $i = 0;
  if( $type === 'interview' ){
    $transcript_timestamps = get_post_meta($id, 'timestamps')[0];
    foreach($transcript_timestamps as $time => $text){
      $hash = $i++;
      $timestamp = new Timestamp($hash, $text);
      array_push($timestamps, $timestamp);
    }
    return $timestamps;
  } elseif($type === 'timeline') {
    $timeline = get_field('events', $id);
    foreach($timeline as $timeline_item){
      $hash = $i++;
      $text = $timeline_item['title'];
      $timestamp = new Timestamp($hash, $text);
      array_push($timestamps, $timestamp);
    }
  }

  return $timestamps;

} );
