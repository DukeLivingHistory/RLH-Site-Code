<?php

function save_txt_from_vtt( $transcript, $title ){

  $body = '';

  $pattern = '/(?:WEBVTT.*\s)?(?:Kind.*\s)?(?:Language.*\s)?\s?(?:([^\d].+\s)?(?:([\d][\d:\.]+)[ \-\>]+([\d][\d:\.]+).*)\n)?[ ]*(?:<v[ ]*(.*)>[ ]*\R?)?((?:(?!\s).*\s{0,1})*)(\n*NOTE\sparagraph\n*)?/';

  preg_match_all( $pattern, $transcript, $nodes );

  for( $i = 0; $i < count($nodes[0]); $i++ ){
    $section = $nodes[1][$i];
    $speaker = $nodes[4][$i];
    $speaker = str_replace( '<v ', '', $speaker );
    $speaker = str_replace( '>', '', $speaker );
    $caption = $nodes[5][$i];

    if( strlen( $section ) ) $body .= trim($section)."\r\n\r\n";
    if( strlen( $speaker ) ) $body .= trim($speaker)."\r\n";
    $body .= trim( $caption )."\n\n";
  }

  // locate previous txt transcript
  $old = new WP_Query([
    'post_per_page' => 1,
    'post_type'     => 'attachment',
    'name'          => $title.' Transcript (.txt)',
  ]);

  // remove old txt transcript if it exists
  if(count($old->posts)){
    $old = $old->posts[0];
    wp_delete_attachment($old->ID, true);
  }

  // add temp file to uploads directory
  $filename = str_replace(' ', '_', strtolower( $_POST['post_title']));
  $file_temp = wp_upload_dir()['path'].'/'.$filename.'_transcript.txt';
  $file_put_contents = file_put_contents( $file_temp, stripslashes( $body ) );

  //upload file to media library
  $attachment = [
    'post_mime_type' => 'text/txt',
    'post_title'     => $title.' Transcript (.txt)',
    'post_content'   => '',
    'post_status'    => 'inherit'
  ];
  $attach = wp_insert_attachment( $attachment, $file_temp );

}
