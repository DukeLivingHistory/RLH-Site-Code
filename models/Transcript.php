<?php

class Transcript {

  function __construct( $interview_id ){
    $this->interview_id = $interview_id;
    $this->transcript = get_field( 'transcript', $interview_id ) ? get_field('transcript_raw', $interview_id ) : false;
    $this->description = get_field( 'description', $interview_id ) ? get_field('description_raw', $interview_id ) : false;
  }

  public function get_slices( $should_trim = false ){
    /*
    // first capture: optional section header (unused)
       (?:WEBVTT.*\s)?(?:Kind.*\s)?(?:Language.*\s)?\s?

    // second capture: required timestamp (beginning)
       (?:([^\d].+\s)?(?:([\d][\d:\.]+)

       [ \-\>]+

    // third capture: required timestamp (end) (unused)
       ([\d][\d:\.]+).*)\n)

    // fourth capture: optional speaker name (unused)
       (?:<v[ ]*(.*)>[ ]*\R?)?

    // fifth capture: required text contents
       ((?:(?!\s).*\s{0,1})*)

    // sixth capture : optional paragraph break (unused)
       (\n*NOTE\sparagraph\n*)?/'

    */
    $pattern = '/(?:WEBVTT.*\s)?(?:Kind.*\s)?(?:Language.*\s)?\s?(?:([^\d].+\s)?(?:([\d][\d:\.]+)[ \-\>]+([\d][\d:\.]+).*)\n)?[ ]*(?:<v[ ]*(.*?)>[ ]*\R?)?((?:(?!\s).*\s{0,1})*)(\n*NOTE\sparagraph\n*)?/';

    preg_match_all( $pattern, $this->transcript, $nodes );

    for( $i = 0; $i < count($nodes[0]); $i++ ){
      $timestamp = $nodes[2][$i];
      $caption = $nodes[5][$i];
      if( $should_trim ){ // limit captions to five words
        $caption = explode( ' ', $caption );
        if( count( $caption ) > 5 ){
          $caption = implode( ' ', array_slice( $caption, 0, 5 ) ).'&hellip;';
        } else {
          $caption = implode( ' ', $caption );
        }
      }
      $timestamps[$timestamp] = $caption;
    }
    return $timestamps;
  }

  public function get_slices_and_breaks($include_description){
    // first capture:    optional section header        ((?:\s*NOTE chapter )[^\d].+\s*)
    // second capture:   optional section header        ([^\d].+\s)
    // third capture:    required timestamp (beginning) ([\d][\d:\.]+)
    // fourth capture:   required timestamp (end)       ([\d][\d:\.]+)
    // fifth capture:    optional speaker name          (.*) in (?:<v[ ]*(.*)>[ ]*)
    // sixth capture:    required text contents         ((?:(?!\s).*\s{0,1})*)
    // seventh capture:  optional paragraph break       (\s*NOTE\sparagraph\s*)
    $pattern = '/(?:WEBVTT.*\s)?(?:Kind.*\s)?(?:Language.*\s)?\s?(?:(?:(?:\s*NOTE chapter )([^\d].+)\s*)?([^\d].+\s)?(?:([\d][\d:\.]+)[ \-\>]+([\d][\d:\.]+).*)\n)?[ ]*(?:<v[ ]*(.*?)>[ ]*\R?)?((?:(?!\s).*\s{0,1})*)(\n*NOTE paragraph\n*)?/i';

    // TODO: update indeces with new capture index

    // print '<pre>';
    // print_r( htmlspecialchars( $this->transcript ) );
    // print '</pre>'; die();

    preg_match_all( $pattern, $this->transcript, $nodes );

    $curr_speaker = '';
    $results = [];

    // type matches the acf layout as defined in the interview fields
    for( $i = 0; $i < count($nodes[0]); $i++ ){
      if( isset( $nodes[1][$i] ) && strlen( trim( $nodes[1][$i] ) ) > 0 ){
        $results[] = [
          'type' => 'section_break',
          'contents' => trim( $nodes[1][$i] ),
          'start' => trim( $nodes[3][$i] ),
          'end' => trim( $nodes[4][$i] ),
          'note_chapter' => true
        ];
      }
      if( isset( $nodes[2][$i] ) && strlen( trim( $nodes[2][$i] ) ) > 0 ){
        $results[] = [
          'type' => 'section_break',
          'contents' => trim( $nodes[2][$i] ),
          'start' => trim( $nodes[3][$i] ),
          'end' => trim( $nodes[4][$i] ),
          'note_chapter' => false
        ];
      }
      if( isset( $nodes[5][$i] ) && strlen( $nodes[5][$i] ) > 0  ){
        $contents = str_replace( '<v ', '', $nodes[5][$i] );
        $contents = str_replace( '>', '', $contents );
        if( strlen( $nodes[1][$i] ) > 0 || strlen( $nodes[2][$i] ) > 0 || $contents !== $curr_speaker ){ // don't repeat speakers UNLESS there's a section change
          $results[] = [
            'type' => 'speaker_break',
            'contents' => trim( $contents ),
            'start' => trim( $nodes[3][$i] ),
            'end' => trim( $nodes[4][$i] )
          ];
          $curr_speaker = $contents;
        }
      }
      if( isset( $nodes[6][$i] ) && strlen( $nodes[6][$i] ) > 0 && substr( $nodes[6][$i], 0, 4 ) !== 'NOTE' ){
        $results[] = [
          'type' => 'transcript_node',
          'contents' => trim( $nodes[6][$i] ),
          'start' => trim( $nodes[3][$i] ),
          'end' => trim( $nodes[4][$i] )
        ];
      }
      if( isset( $nodes[7][$i] ) && strlen( $nodes[7][$i] ) > 0  ){
        $results[] = [
          'type' => 'paragraph_break'
        ];
      }
    }

    if($include_description){
      preg_match_all($pattern, $this->description, $description_nodes);
      for( $i = 0; $i < count($description_nodes[0]); $i++ ){
        $insert = [];

        if( isset( $description_nodes[6][$i] ) && strlen( $description_nodes[6][$i] ) > 0){
          $start = trim($description_nodes[3][$i]);

          $insert[] = [
            'type'      => 'description',
            'contents'  => trim($description_nodes[6][$i]),
            'start'     => $start,
            'end'       => trim($description_nodes[4][$i]),
          ];

          foreach($results as $index => $result){
            if(sanitize_timestamp($result['start']) > sanitize_timestamp($start)){
              $offset = $index;
              break;
            }
          }

          array_splice($results, $offset, 0, $insert);
        }
      }
    }

    return count($results) ? $results : false;
  }

  public function get_caption( $timestamp ){
    return isset( $this->get_slices()[$timestamp] ) ? trim( $this->get_slices()[$timestamp] ) : false;
  }

  public function has_supp_at( $timestamp ){
    $supp_content = get_field( 'sc_row', $this->interview_id );
    foreach( $supp_content as $item ){
      if( $item['timestamp'] === $timestamp ){
        return true;
      }
    }
    return false;
  }

}
