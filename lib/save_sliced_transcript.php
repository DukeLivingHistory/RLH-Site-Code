<?php

/*
 * This file saves transcript contents to the drag-and-drop interface
 * from either the raw interface or the file directly.
 */

/*
 * This function has been deprecated.
 */


//
// include_once( get_template_directory().'/models/Transcript.php' );
//
// function save_sliced_transcript( $id, $override = false ){ // override lets you call this even if you normally wouldn't be able to
//
//   if( get_post_type( $id ) !== 'interview' ) return;
//   if( !isset( $_POST['acf'] ) || $_POST['_acfchanged'] == 0 || $_POST['acf']['sync'] == 0 && !$override ) return;
//   update_field( 'sync', 0, $id );
//
//   $transcript = new Transcript( $id );
//   $slices = $transcript->get_slices_and_breaks();
//   $insert = [];
//
//   if( $slices ){
//     foreach( $slices as $slice ){
//       if( $slice['type'] === 'paragraph_break' ){
//         $insert[] = [
//           'acf_fc_layout' => $slice['type']
//         ];
//       } else {
//         $insert[] = [
//           'transcript_node_start' => $slice['start'],
//           'transcript_node_timestamp' => $slice['start'],
//           'transcript_node_end' => $slice['end'],
//           'transcript_node_caption' => $slice['contents'],
//           'section_break_title' => $slice['contents'],
//           'speaker_name_text' => $slice['contents'],
//           'acf_fc_layout' => $slice['type']
//         ];
//       }
//     }
//   }
//
//   update_field( 'is_transcript_processing', 1, $id );
//   wp_schedule_single_event( time()+1, 'update_transcript', [ $id, $insert ] );
//
// }
// add_action( 'save_post', 'save_sliced_transcript', 20 );
