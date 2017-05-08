<?php

function next_associative($array, $key){
  $is_match = false;
  foreach($array as $test => $val){
    if($is_match){
      return $test;
    }
    if($test == $key) $is_match = true;
  }
  return $key;
}

function last_associative($array){
  if(!is_array($array)) return;
  return end(array_keys($array));
}

function supp_cont_to_vtt($id, $supp_cont){
  if(!$supp_cont) return;
  $vtt = "WEBVTT\n\n";
  $timestamps = get_post_meta( $id, 'timestamps', $event_dates )[0];
  foreach($supp_cont as $item){
    $item_text = '';

    $open = $item['sc_open'];

    if($open){
      $item_text = "NOTE open by default\n\n";
    }

    $timestamp = $item['sc_timestamp'];

    if($timestamp){
      $item_text .= $timestamp;
      $item_text .= ' --> ';
      $item_text .= next_associative($timestamps, $timestamp);
    } else {
      $item_text .= 'NOTE Non-transcript supporting content.'."\n\n";
      $item_text .= last_associative($timestamps);
      $item_text .= ' --> ';
      $item_text .= last_associative($timestamps);
    }

    $content = $item['sc_content'][0];
    $type = $content['acf_fc_layout'];
    switch($type){
      case 'text':
        $item_text .= "\n";
        $item_text .= 'CONTENT ' . trim($content['sc_text_content']);
        break;
      case 'blockquote':
        $item_text .= "\n";
        $item_text .= 'QUOTE ' . trim($content['sc_blockquote_quotetext']);
        $item_text .= 'ATTRIBUTION ' . trim($content['sc_blockquote_attribution']);
        break;
      case 'image':
        $item_text .= "\n";
        $item_text .= 'IMAGE ' . trim(wp_get_attachment_url($content['sc_image_img']));
        break;
      //TODO: galleries?
      case 'externallink':
        $item_text .= "\n";
        $item_text .= 'TITLE ' . trim($content['sc_externallink_title']);
        $item_text .= "\n";
        $item_text .= 'DESCRIPTION ' . trim($content['sc_externallink_description']);
        $item_text .= "\n";
        $item_text .= 'URL ' . trim($content['sc_externallink_url']);
        if($content['sc_externallink_text']){
          $item_text .= "\n";
          $item_text .= 'LINK_TEXT ' . trim($content['sc_externallink_text']);
        }
        break;
      case 'internallink':
        $item_text .= "\n";
        $item_text .= 'URL ' . trim(get_permalink($content['sc_internallink_to']));
        if($content['sc_internalink_timestamp']){
          $item_text .= "\n";
          $item_text .= 'TIMESTAMP ' . trim($content['sc_internalink_timestamp']);
        }
        if($content['sc_internalink_label']){
          $item_text .= "\n";
          $item_text .= 'LABEL' . trim($content['sc_internalink_label']);
        }
        if($content['sc_internalink_description']){
          $item_text .= "\n";
          $item_text .= 'DESCRIPTION ' . trim($content['sc_internalink_description']);
        }
        break;
      case 'file':
        $item_text .= "\n";
        $item_text .= 'DESCRIPTION ' . trim($content['sc_file_description']);
        $item_text .= "\n";
        $item_text .= 'FILE ' . trim(wp_get_attachment_url($content['sc_file_file']));
        break;
      case 'map_location':
        $item_text .= "\n";
        $item_text .= 'TITLE ' . trim($content['sc_map_title']);
        $item_text .= "\n";
        $item_text .= 'ADDRESS ' . trim($content['sc_map_location']['address']);
        break;
      default:
        break;
    }

    $vtt .= $item_text . "\n\n";
  }

  return $vtt;
}

add_action('save_post', function($id){
  if(get_post_type($id) !== 'interview') return;
  if($_POST['acf']['save_from_supp_cont_raw']) return;
  $supp_cont = $_POST['acf']['sc_row'];
  $vtt = supp_cont_to_vtt($id, $supp_cont);
  update_field('supporting_content_raw', $vtt, $id);
});
