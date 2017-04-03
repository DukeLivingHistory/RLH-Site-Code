<?php

function is_timestamp($line){
  $pattern = "/([^\d].+\s)?(?:([\d][\d:\.]+)[ \-\>]+([\d][\d:\.]+).*)/";
  return preg_match($pattern, $line);
}

function get_formatted_supp_cont_cues($vtt){
  $lines = explode("\n", $vtt);
  $cues = [];
  $cue = [];
  foreach($lines as $line){
    $sep = explode(' ', $line, 2);
    $keyword = trim(isset($sep[0]) ? $sep[0] : $line);
    $value = strstr($line, ' ');
    if(is_timestamp($line)){
      $cues[] = $cue;
      $cue = [
        'timestamp' => $keyword
      ];
      continue;
    }
    switch($keyword){
      case 'CONTENT': // text
        $cue['type'] = 'text';
        $cue['content'] = $value;
        break;
      case 'QUOTE': // blockquote
        $cue['type'] = 'blockquote';
        $cue['blockquote'] = $value;
        break;
      case 'ATTRIBUTION': // blockquote
        $cue['attribution'] = $value;
        break;
      case 'IMAGE'; // image
        $cue['type'] = 'image';
        break;
      case 'TITLE': // gallery, map
        $cue['title'] = $value;
        break;
      case 'DESCRIPTION': // gallery, ext link, file
        $cue['description'] = $value;
        break;
      case 'GALLERY': // gallery
        $cue['type'] = 'gallery';
        break;
      case 'URL': // ext link, int link
        $cue['type'] = 'internallink'; // default to internal
        $cue['url'] = $value;
        break;
      case 'LINK_TEXT': // ext link
        $cue['type'] = 'externallink'; // external if provided
        $cue['link_text'] = $value;
        break;
      case 'TIMESTAMP': // int link
        $cue['link_timestamp'] = $value;
        break;
      case 'FILE': // file
        $cue['type'] = 'file';
        break;
      case 'ADDRESS': // map location
        $cue['type'] = 'map_location';
        $cue['address'] = $value;
        break;
    }
  }
  $cues[] = $cue;
  foreach($cues as $cue => $value){
    if(!isset($cues[$cue]['type'])){
      unset($cues[$cue]);
    }
  }

  return $cues;
}

add_action('save_post', function( $id ){
  if(get_post_type($id) !== 'interview') return;
  if(!$_POST['acf']['save_from_supp_cont_raw']) return;
  update_field('save_from_supp_cont_raw', 0, $id );

  $supporting_content = $_POST['acf']['supporting_content_raw'];
  $formatted = get_formatted_supp_cont_cues($supporting_content);
  $insert = [];
  //print '<pre>'; print_r($formatted); print '</pre>'; die();
  if($formatted){
    foreach($formatted as $slice){
      $insert[] = [
        'timestamp' => $slice['timestamp'],
        'sc_content'   => [
          [
            'acf_fc_layout'  => $slice['type'],
            'content'        => $slice['content'],
            'text'           => $slice['link_text'],
            'quote'          => $slice['blockquote'],
            'attribution'    => $slice['attribution'],
            'title'          => $slice['title'],
            'description'    => $slice['description'],
            'url'            => $slice['url'],
            'link'           => url_to_postid($slice['url']),
            'link_timestamp' => $slice['link_timestamp'],
            'name'           => $slice['title'],
            'location'       => [ // TODO: fix this
              'address' => $slice['address'],
              'lat'     => '36.000180',
              'lng'     => '-78.897299'
            ]
          ]
        ]
      ];
    }
    update_field('sc_row', $insert, $id);
  }
  //die();
});