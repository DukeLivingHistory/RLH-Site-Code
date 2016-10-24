<?php

/* This file registers timestamps from an interview as post meta attached
 * to a post, so that they can be used by supp cont.
 */

include_once( get_template_directory().'/models/Transcript.php' );

function save_timestamp( $id ){
  if( !isset( $_POST['acf'] ) || $_POST['_acfchanged'] == 0 || $_POST['acf']['sync'] == 0 ) return;
  update_field( 'sync', 0, $id );
  if( get_post_type( $id ) === 'interview' ){
    $transcript = new Transcript( $id );
    update_post_meta( $id, 'timestamps', $transcript->get_slices( true ) );
  } elseif( get_post_type( $id) === 'timeline' ){
    $events = $_POST['acf']['timeline_events'];
    foreach( $events as $event ){
      $event_dates[] = $event['timeline_date'];
    }
    update_post_meta( $id, 'timestamps', $event_dates );
  }
}
add_action( 'save_post', 'save_timestamp', 20 );
