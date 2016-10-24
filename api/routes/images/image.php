<?php

$route = new Route( '/images/(?P<id>\d+)/(?P<size>.*)', 'GET', function( $data ){
  $meta = get_post( $data['id'] );
  $caption = $meta->post_excerpt;
  $credit = [
    'author' => get_post_meta( $data['id'], 'photographer_name', true ),
    'src' => get_post_meta( $data['id'], 'photographer_url', true )
  ];
  return [
    'original' => wp_get_attachment_image_src( $data['id'], 'full' )[0],
    'requested' => wp_get_attachment_image_src( $data['id'], $data['size'] )[0],
    'caption' => $caption,
    'credit' => $credit,
    'alt' => get_post_meta( $data['id'], '_wp_attachment_image_alt', true)
  ];
} );
