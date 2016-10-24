<?php

/*
 * This file specifies the OpenGraph tags used on a page.
 */ 

function get_og(){

  if( is_home() ){
    $url = get_bloginfo('url');
    $title = get_bloginfo('name');
    $description = strip_tags( get_field( 'site_description', 'options' ) );
    $img = ''; // make this be the site logo
  } elseif( get_queried_object() && is_single() ) {
    $obj = get_queried_object();
    $url = get_the_permalink();
    $title = $obj->post_title;
    $description = $obj->post_excerpt;
    $img = ''; // make this be feat img
  } else {
    $url = '';
    $title = '';
    $description = '';
    $img = '';
  }

?>
  <meta property="og:url"                content="<?= $url; ?>" />
  <meta property="og:title"              content="<?= $title; ?>" />
  <meta property="og:description"        content="<?= $description; ?>" />
  <meta property="og:image"              content="<?= $img; ?>" />
<?php }
