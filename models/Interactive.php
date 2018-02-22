<?php

include_once('Content.php');

class Interactive extends Content {
  function __construct($id){
    parent::__construct($id);
    $this->introduction    = get_field('introduction', $id);
    $this->transcript_url  = get_field('transcript', $id)['url'];
  }
}
