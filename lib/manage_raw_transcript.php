<?php

/* This file handles transcript and description saving from the raw field, as well as the display of the transcript in the back end.
*/

function handle_save($alias){

  $old = get_field("{$alias}_file", $id );
  if( $old ){
    $old = $old['ID'];
    $delete = wp_delete_attachment( $old, true );
  }

  $contents = $_POST['acf']["{$alias}_raw"];

  if(strlen( $contents ) > 0){
    $title             = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['post_title']);
    $title             = stripslashes(str_replace(' ', '_', strtolower($title )));
    $file_temp         = wp_upload_dir()['path'].'/'.$title.'_'.$alias.'.vtt';
    $file_put_contents = file_put_contents($file_temp, stripslashes($contents));

    $attachment = [
      'post_mime_type' => 'text/vtt',
      'post_title'     => get_the_title($id).' '.$alias.' (.vtt)',
      'post_content'   => '',
      'post_status'    => 'inherit'
    ];

    $attach = wp_insert_attachment( $attachment, $file_temp );

    update_field( $alias.'_file', $attach, $id );
    save_txt_from_vtt($contents, $_POST['post_title'], $alias);

  } else {
    update_field("{$alias}_file", false, $id);
  }
}

add_action('save_post', function($id){
  if(
    get_post_type($id) === 'interview' ||
    get_post_type($id) === 'interactive'
  ) {
    handle_save('transcript');
    handle_save('description');
  }
  if(get_post_type($id) === 'interactive') {
    handle_save('transcript');
  }
}, 30);

add_filter( 'acf/load_field/key=transcript_raw', function( $field ){
  if(!$id = $_GET['post']) return $field;

  $transcript = new Transcript($_GET['post']);
  $field['value'] = $transcript->transcript;
  return $field;
} );
