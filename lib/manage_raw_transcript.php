<?php

/* This file handles transcript saving from the raw field, as well as the display of the transcript in the back end. */

include_once( 'save_sliced_transcript.php' );
add_action( 'save_post', function( $id ){
  if( get_post_type( $id ) !== 'interview' ) return;
  if( !isset( $_POST['acf'] ) || !isset( $_POST['acf']['update_from_raw'] ) || $_POST['acf']['update_from_raw'] == 0 || $_POST['acf']['update_from_fields'] ) return;

  update_field( 'update_from_raw', 0, $id );

  $old = get_field( 'transcript_file', $id );
  if( $old ){
    $old = $old['ID'];
    $delete = wp_delete_attachment( $old, true );
  }

  $transcript = $_POST['acf']['transcript_raw'];

  if( strlen( $transcript ) > 0){
    $title = preg_replace( '/[^a-zA-Z0-9\s]/', '', $_POST['post_title'] );
    $title = str_replace( ' ', '_', strtolower( $title ) );
    $file_temp = wp_upload_dir()['path'].'/'.$title.'_transcript.vtt';
    $file_put_contents = file_put_contents( $file_temp, stripslashes( $transcript ) );

    $attachment = [
      'post_mime_type' => 'text/vtt',
      'post_title'     => get_the_title( $id ).' Transcript (.vtt)',
      'post_content'   => '',
      'post_status'    => 'inherit'
    ];

    $attach = wp_insert_attachment( $attachment, $file_temp );
    update_field( 'transcript_file', $attach, $id );
    save_sliced_transcript( $id, true );
    save_txt_from_vtt( $transcript, $_POST['post_title'] );

  } else {
    update_field( 'transcript_contents', [], $id );
    update_field( 'transcript_file', false, $id );
  }

}, 30 );

add_filter( 'acf/load_field/key=transcript_raw', function( $field ){
  if( !is_admin() || !isset( $_GET['post'] ) ) return $field;
  if( get_post_type( $_GET['post'] ) !== 'interview' ) return $field;
  $transcript = new Transcript( $_GET['post'] );
  $field['value'] = $transcript->transcript;
  return $field;
} );
