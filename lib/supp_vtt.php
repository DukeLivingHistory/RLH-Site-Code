<?php

// Given an array of values with possible duplicates, returns the next by value
function next_by_val($array, $val){
  $is_match = false;
  foreach($array as $key => $_val){
    if($is_match && $_val !== $val){
      return $_val;
    }
    if($_val == $val) $is_match = true;
  }
  return $val;
}

function supp_cont_to_vtt($id, $supp_cont){
  if(!$supp_cont) return;

  $transcript = new Transcript($id);
  $timestamps = $transcript->get_slices_and_breaks(true);

  $timestamps = array_reduce($timestamps, function($timestamps, $timestamp) {
    if(strlen($timestamp['start'])) $timestamps[] = $timestamp['start'];
    return $timestamps;
  }, []);

  $vtt = "WEBVTT\n\n";
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
      $item_text .= next_by_val($timestamps, $timestamp);
    } else {
      $item_text .= 'NOTE Non-transcript supporting content.'."\n\n";
      $item_text .= end($timestamps);
      $item_text .= ' --> ';
      $item_text .= end($timestamps);
    }

    $content = $item['sc_content'][0];

    if(!isset($content['acf_fc_layout'])) continue;

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
        if($content['sc_internalink_label']){
          $item_text .= "\n";
          $item_text .= 'TITLE ' . trim($content['sc_internalink_label']);
        }
        if($content['sc_internalink_description']){
          $item_text .= "\n";
          $item_text .= 'DESCRIPTION ' . trim($content['sc_internalink_description']);
        }
        $item_text .= 'URL ' . trim(get_permalink($content['sc_internallink_to']));
        if($content['sc_internalink_timestamp']){
          $item_text .= "\n";
          $item_text .= 'TIMESTAMP ' . trim($content['sc_internalink_timestamp']);
        }
        break;
      case 'video':
        $item_text .= "\n";
        $item_text .= 'TITLE ' . trim($content['sc_video_title']);
        $item_text .= 'VIDEO ' . trim($content['sc_video_iframe']);
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

  if(strlen($vtt) > 0){
    $title = preg_replace( '/[^a-zA-Z0-9\s]/', '', $_POST['post_title'] );
    $title = str_replace( ' ', '_', strtolower( $title ) );
    $file_temp = wp_upload_dir()['path'].'/'.$title.'_supporting_content.vtt';

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

  // This is a hack. TODO: Figure out why this is necesary.
  $sc_rows = $_POST['acf']['sc_row'];
  if($sc_rows) foreach($sc_rows as $index => $content) {
    $meta_key = 'sc_row_'.$index.'_timestamp';
    update_post_meta($id, $meta_key, $content['sc_timestamp']);
  }

}, 100); // Must run after manage_raw_transcript.php
