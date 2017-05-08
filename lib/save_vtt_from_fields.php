<?php

/*
 * This function is deprecated.
 */

// function save_vtt_from_fields( $id, $title, $vtt ){
//
//   update_field( 'update_from_fields', 0, $id );
//
//   $old = get_field( 'transcript_file', $id );
//   if( $old ){
//     $old = $old['ID'];
//     $delete = wp_delete_attachment( $old, true );
//   }
//
//   $file_temp = wp_upload_dir()['path'].'/'.$title.'_transcript.vtt';
//   $file_put_contents = file_put_contents( $file_temp, stripslashes( $vtt ) );
//
//   $attachment = [
//     'post_mime_type' => 'text/vtt',
//     'post_title'     => get_the_title( $id ).' Transcript (.vtt)',
//     'post_content'   => '',
//     'post_status'    => 'inherit'
//   ];
//
//   $attach = wp_insert_attachment( $attachment, $file_temp );
//   update_field( 'transcript_file', $attach, $id );
//   save_txt_from_vtt( $vtt, $title );
// }
// add_action( 'save_vtt', 'save_vtt_from_fields', 5, 3 );
