<?php

/*
 * This file runs on post save for interviews if "Update From Youtube" is selected.
 * It will prompty an oauth2 login that will grab the transcript from YouTube,
 * save it as the transcript file, and redirect back to the interview.
 * It does NOT trigger any transcript slicing.
 */

// make oauth2 request to youtube to get authentication key.
function oauth( $id ){
  if( get_post_type( $id ) !== 'interview' ) return;
  if( !isset( $_POST['acf'] ) || $_POST['_acfchanged'] == 0 || $_POST['acf']['update'] == 0 ) return;
  update_field( 'update', 0, $id  );
  $url =  'https://accounts.google.com/o/oauth2/auth?';
  $url .= 'client_id='.get_field( 'youtube_client_id', 'options').'&';
  $url .= 'redirect_uri='.urlencode( site_url().'/wp-admin/' ).'&';
  $url .= 'scope=https://www.googleapis.com/auth/youtube.force-ssl&';
  $url .= 'response_type=code&';
  $url .= 'state='.$id;
  header( 'Location: '.$url );
  die();
}
add_action('save_post', 'oauth', 20);

// receive code url param from oauth2 callback.
// use code to make request for authentication token.
// use authentication token to get caption id.
// use caption id to get caption.
function handle_response(){
  if( is_admin() && isset( $_GET['code'] ) ){ // code is a query param based back by the oauth2 redirect
    $post_id = $_GET['state']; // specified in oauth() above
    $get_token = curl_init( 'https://accounts.google.com/o/oauth2/token' );
    curl_setopt( $get_token, CURLOPT_HTTPHEADER, [
      'POST /o/oauth2/token HTTP/1.1',
      'Host: accounts.google.com',
      'Content-type: application/x-www-form-urlencoded',
    ] );
    $body =  'grant_type=authorization_code&';
    $body .= 'code='.$_GET['code'].'&';
    $body .= 'client_id='.get_field( 'youtube_client_id', 'options').'&';
    $body .= 'client_secret='.get_field( 'youtube_secret_id', 'options').'&';
    $body .= 'redirect_uri='.urlencode( site_url().'/wp-admin/' );
    curl_setopt( $get_token, CURLOPT_POSTFIELDS, $body );
    curl_setopt( $get_token, CURLOPT_RETURNTRANSFER, true );
    $token = json_decode( curl_exec( $get_token ) )->access_token;

    $get_transcript_id = curl_init( 'https://www.googleapis.com/youtube/v3/captions?part=snippet&videoId='.get_field( 'youtube_id', $post_id ) );
    curl_setopt( $get_transcript_id, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer '.$token
    ] );
    curl_setopt( $get_transcript_id, CURLOPT_RETURNTRANSFER, true );
    $transcript_id = json_decode( curl_exec( $get_transcript_id ) )->items[0]->id;

    $get_transcript = curl_init( 'https://www.googleapis.com/youtube/v3/captions/'.$transcript_id.'?tfmt=vtt' );
    curl_setopt( $get_transcript, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer '.$token
    ] );
    curl_setopt( $get_transcript, CURLOPT_RETURNTRANSFER, true );
    $transcript = curl_exec( $get_transcript );

    $old = get_field( 'transcript_file', $id );
    if( $old ){
      $old = $old['ID'];
      $delete = wp_delete_attachment( $old, true );
    }

    $title = preg_replace( '/[^a-zA-Z0-9\s]/', '', $_POST['post_title'] );
    $title = str_replace( ' ', '_', strtolower( $title ) );
    $file_temp = wp_upload_dir()['path'].'/'.$title.'_transcript.vtt';
    $file_put_contents = file_put_contents( $file_temp, stripslashes( $transcript ) );

    $attachment = [
    	'post_mime_type' => 'text/vtt',
    	'post_title'     => get_the_title( $post_id ).' Transcript (.vtt)',
    	'post_content'   => '',
    	'post_status'    => 'inherit'
    ];

    $attach = wp_insert_attachment( $attachment, $file_temp );
    update_field( 'transcript_file', $attach, $post_id );

    save_txt_from_vtt( $transcript, $_POST['post_title'] );

    header( 'Location: '.site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit' );
  }
}
add_action('init', 'handle_response', 20 );
