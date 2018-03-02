<?php

/* This file makes the timestamps present in a piece of content available to supporting content */

include_once(get_stylesheet_directory() . '/models/Transcript.php');

function add_choices($field){
  if(!$id = $_GET['post']) return;

  if(get_post_type($id) === 'interview' || get_post_type($id) === 'interactive') {
    $transcript = new Transcript($id);
    $contents = $transcript->get_slices_and_breaks(false);

    if($contents) foreach($contents as $timestamp) {
      if($timestamp['type'] === 'transcript_node') {
        $start = $timestamp['start'];
        $label = $timestamp['contents'];
        $field['choices'][$start] = $start.' '.$label;
      }
    }
  }
  elseif(get_post_type($id) === 'timeline') {
    $contents = get_field('events');
    if($contents) foreach($contents as $timestamp) {
      $date = $timestamp['date'];
      $field['choices'][$date] = $date;
    }
  }
  return $field;
}
add_filter('acf/load_field/key=sc_timestamp', 'add_choices');
