<?php
function is_timestamp($line){
  $pattern = "/([^\d].+\s)?(?:([\d][\d:\.]+)[ \-\>]+([\d][\d:\.]+).*)/";
  return preg_match($pattern, $line);
}

function is_open($string){
  $string = trim($string);
  return strpos($string, 'NOTE open by default') !== false;
}

function get_image_id_from_url($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
  return $attachment[0];
}

function get_formatted_supp_cont_cues($vtt){
  $lines = explode("\n", $vtt);
  $cues = [];
  $cue = [];
  $is_open = false;
  foreach($lines as $line){
    $sep = explode(' ', $line, 2);
    $keyword = trim(isset($sep[0]) ? $sep[0] : $line);
    $value = trim(strstr($line, ' '));
    if(is_open($line)){
      $is_open = true;
    }
    if(is_timestamp($line)){
      $cues[] = $cue;
      $cue = [
        'timestamp' => $keyword,
        'open' => $is_open
      ];
      $is_open = false;
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
        $cue['image'] = get_image_id_from_url($value);
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
      case 'INTERNAL_URL': // ext link, int link
        $cue['type'] = 'internallink'; // default to internal
        $cue['url'] = $value;
        break;
      case 'URL': // ext link
        $cue['type'] = 'externallink'; // external if provided
        $cue['url'] = $value;
        break;
      case 'LINK_TEXT':
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

  if($formatted){
    foreach($formatted as $slice){
      $insert[] = [
        'timestamp' => $slice['timestamp'],
        'open' => $slice['open'],
        'sc_content'   => [
          [
            'acf_fc_layout'    => $slice['type'],
            'content'          => $slice['content'],
            'text'             => $slice['link_text'],
            'quote'            => $slice['blockquote'],
            'attribution'      => $slice['attribution'],
            'title'            => $slice['title'],
            'description'      => $slice['description'],
            'link_description' => $slice['description'],
            'link_label'       => $slice['title'],
            'url'              => $slice['url'],
            'link'             => url_to_postid($slice['url']),
            'link_timestamp'   => $slice['link_timestamp'],
            'name'             => $slice['title'],
            'sc_image_img'     => $slice['image'],
            'location'         => [ // TODO: fix this
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

  if( strlen( $supporting_content ) > 0){
    $title = preg_replace( '/[^a-zA-Z0-9\s]/', '', $_POST['post_title'] );
    $title = str_replace( ' ', '_', strtolower( $title ) );
    $file_temp = wp_upload_dir()['path'].'/'.$title.'_supporting_content.vtt';
    $file_put_contents = file_put_contents( $file_temp, stripslashes( $supporting_content ) );

    $attachment = [
      'post_mime_type' => 'text/vtt',
      'post_title'     => get_the_title($id).' Supporting Content (.vtt)',
      'post_content'   => '',
      'post_status'    => 'inherit'
    ];

    $attach = wp_insert_attachment( $attachment, $file_temp );
    update_field('supp_cont_file', $attach, $id);
  } else {
    update_field('supp_cont_file', false, $id);
  }
});
