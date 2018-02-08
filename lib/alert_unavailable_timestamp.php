<?php
// @deprecated
function alert_unavailable_timestamp(){

  if( !is_admin() || !isset( $_GET['post'] ) );
  if( !get_post_type( $_GET['post'] ) === 'timeline' || !get_post_type( $_GET['post'] ) === 'interview' ) return $field;

  if( get_post_type( $_GET['post'] ) === 'timeline' ){
    $available = get_field( 'events', $_GET['post'] );
    if( !$available ) return;
    foreach( $available as $available_item ){
      $available_timestamps[] = $available_item['event_date'];
    }
  } elseif( get_post_type( $_GET['post'] ) === 'interview' ){
    $available = get_field( 'transcript_contents', $_GET['post'] );
    if( !$available ) return;
    foreach( $available as $available_item ){
      if( isset( $available_item['transcript_node_start'] ) ) {
        $available_timestamps[] = $available_item['transcript_node_start'];
      }
    }
  }
  if( !$available_timestamps || !count( array_filter( $available_timestamps, 'strlen' ) ) ) return;

  $unavailable = [];
  $sc_items = get_field( 'sc_row', $_GET['post'] );
  if( !$sc_items ) return;
  foreach( $sc_items as $sc_item ){
    if( !$sc_item['timestamp'] ) break;
    if( !in_array( $sc_item['timestamp'], $available_timestamps ) ){
      if( $unavailable && in_array( $sc_item['timestamp'], $unavailable ) ) break;
      $unavailable[] = $sc_item['timestamp'];
    }
  }

  if( $unavailable && count( array_filter( $unavailable, 'strlen' ) ) ){
    foreach( $unavailable as $unavailable_item ){
      $unavailable_list .= '<li>'.$unavailable_item.'</li>';
    }
    echo '<div class="error">The following timestamps are used for supporting content, but aren\'t present in the transcript or timeline:<ul>'.$unavailable_list.'</ul></div>';
  }

}
add_filter( 'edit_form_after_title', 'alert_unavailable_timestamp');
