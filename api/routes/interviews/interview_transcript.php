<?php
$route = new Route( '/interviews/(?P<id>\d+)/transcript', 'GET', function( $data ){
  $transcript = get_field( 'transcript', $data['id'] )['url'];
  if( isset( $data->get_query_params()['return'] ) && $data->get_query_params()['return'] === 'file_contents' ){

    return file_get_contents( $transcript );

  } elseif( isset( $data->get_query_params()['return'] ) && $data->get_query_params()['return'] === 'transcript_contents' ){

    $nodes = get_field( 'transcript_contents', $data['id'] );
    if( !$nodes ) return false;
    
    foreach( $nodes as $node ){
      $type = $node['acf_fc_layout'];
      $content = [];
      $content['type'] = $type;
      switch( $type ){
        case 'transcript_node':
          $content['start'] = sanitize_timestamp( $node['transcript_node_start'] );
          $content['end'] = sanitize_timestamp( $node['transcript_node_end'] );
          $content['text'] = $node['transcript_node_caption'];
          break;
        case 'speaker_break':
          $content['text'] = strlen( $node['speaker_name'] > 0 ) ? $node['speaker_name'] : $node['speaker_name_text'];
          break;
        case 'section_break':
          $content['timestamp'] = sanitize_timestamp( $node['transcript_node_timestamp'] );
          $content['text'] = $node['section_break_title'];
          break;
      }
      $contents[] = $content;
    }

    return $contents;
  }
  return $transcript;
} );
