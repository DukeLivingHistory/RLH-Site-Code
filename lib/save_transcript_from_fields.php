<?php

/* This file constructs a WebVTT file from the contents of the drag-and-drop. */

class Cue {

  function __construct( $section, $start, $end, $speaker, $text ){
    $this->section = $section ? $section : false;
    $this->start = $start;
    $this->end = $end;
    $this->speaker = $speaker ? $speaker : false;
    $this->text = $text;
  }

  public function content(){
    $content = "\n\n";
    if( $this->section ) $content .= $this->section."\n";
    $content .= $this->start.' --> '.$this->end."\n";
    if( $this->speaker ) $content .= "<v ".$this->speaker."> ";
    $content .= $this->text;
    return $content;
  }

}

function save_transcript_from_fields( $data, $postarr ){
  $id = $postarr['ID'];
  if( get_post_type( $id ) !== 'interview' ) return $data;
  if( !isset( $_POST['acf'] ) || $_POST['acf']['update_from_fields'] == 0 ) return $data;

  $contents = $_POST['acf']['transcript_contents'];
  if( !$contents ) return $data;

  $transcript = "WEBVTT\nKind: captions\nLanguage: en";
  $section = false;
  $speaker = false;

  foreach( $contents as $content ){
    if( $content['acf_fc_layout'] === 'section_break' ){
      $section = $content['section_break_title'];
    }
    if( $content['acf_fc_layout'] === 'speaker_break' ){
      $speaker = strlen( $content['speaker_name'] ) ? $content['speaker_name'] : $content['speaker_name_text'];
    }
    if( $content['acf_fc_layout'] === 'transcript_node' ){
      $start =  isset( $content['transcript_node_start'] ) ? $content['transcript_node_start'] : false;
      $end =    isset( $content['transcript_node_end'] ) ? $content['transcript_node_end'] : false;
      $text =   isset( $content['transcript_node_caption'] ) ? $content['transcript_node_caption'] : false;

      if( $start && $end && $text ){
        $cue = new Cue( $section, $start, $end, $speaker, $text );
        $transcript .= $cue->content();
      }

      $section = false;
      $speaker = false;
    }
    if( $content['acf_fc_layout'] === 'paragraph_break' ){
      $transcript .= "\n\nNOTE paragraph";
    }
  }

  $title = preg_replace( '/[^a-zA-Z0-9\s]/', '', $_POST['post_title'] );
  $title = str_replace( ' ', '_', strtolower( $title ) );
  save_vtt_from_fields( $id, $title, $transcript );

  // defer saving
  unset( $_POST['acf']['transcript_contents'] );
  wp_schedule_single_event( time(), 'save_vtt', [ $id, $title, $transcript ] );
  wp_schedule_single_event( time()+1, 'update_transcript', [ $id, $contents ] );
  return $data;
}
add_action( 'wp_insert_post_data', 'save_transcript_from_fields', 99, 2 );
