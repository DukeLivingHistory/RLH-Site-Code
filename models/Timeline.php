<?php

include_once('Content.php');

class Timeline extends Content {

  function __construct( $id ){
    parent::__construct( $id );
    $this->events = $this->get_events_by_id( $id );
    $this->intro = get_field( 'timeline_introduction', $id );
  }

  private function get_events_by_id( $id ){
    $events = get_field( 'events', $id );
    return $events;
  }

}
