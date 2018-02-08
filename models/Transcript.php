<?php

class Transcript {

  private function sanitize_file_paths($data, $key = 'url') {
    if(!$data) return false;
    return file_get_contents(str_replace(site_url(), ABSPATH, $data[$key]));
  }

  function __construct($id){
    $this->transcript = $this->sanitize_file_paths(get_field('transcript', $id));
    $this->description = $this->sanitize_file_paths(get_field('description', id));
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

        if( // If description chunk has text
          isset($description_nodes[6][$i]) &&
          strlen($description_nodes[6][$i]) > 0
        ){
          $start = trim($description_nodes[3][$i]);
          $end = trim($description_nodes[4][$i]);

          $insert[] = [
            'type'      => 'description',
            'contents'  => trim($description_nodes[6][$i]),
            'start'     => $start,
            'end'       => $end, // End
          ];

          // Place result in the correct place in the transcript
          foreach($results as $index => $result){ // Index = array key
            // Identifies the last transcript node before timestamp
            if(sanitize_timestamp($result['start']) > sanitize_timestamp($start)){
              $offset = $index;
              break; // Exit
            }
          }

          array_splice($results, $offset, 0, $insert);
        }
      }
    }

    return count($results) ? $results : false;
  }
}
