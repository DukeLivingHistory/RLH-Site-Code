<?php

include_once('Content.php');

class Interview extends Content {
  function __construct( $id ){
    parent::__construct($id);
    $this->introduction = get_field( 'introduction', $id );
    $this->video_id = get_field( 'youtube_id', $id );
    $this->transcript_url = get_field( 'transcript', $id )['url'];
  }
}
