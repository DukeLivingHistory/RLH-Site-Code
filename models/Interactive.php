<?php

include_once('Content.php');

class Interactive extends Content {
  function __construct($id){
    parent::__construct($id);
    $this->introduction    = get_field('introduction', $id);
    $this->instructions    = get_field('interactive_instructions', 'option');
    $this->transcript_url  = get_field('transcript', $id)['url'];
  }
}
