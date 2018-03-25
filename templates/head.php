<?php
  if( is_front_page() ){
    $title = get_bloginfo('name');
  } elseif ( is_tax() ) {
    $title = get_queried_object()->name;
  } elseif( is_archive() ){
    $title = ucfirst( get_post_type_object( get_post_type() )->rewrite['slug'] );
  } elseif( is_search() ) {
    $title = 'Search for '.( !isset( $_GET['s'] ) ?: $_GET['s'] );
  } else {
    $title = get_the_title();
  }
?>
<!doctype html>
<html class="no-js" lang="en-US">
  <head>
    <title><?= $title ; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php get_og(); ?>
    <link rel="alternate" type="application/rss+xml" title="<?= get_bloginfo('name'); ?> Feed" href="<?= esc_url(get_feed_link()); ?>">
    <link rel="apple-touch-icon-precomposed" href="/apple-icon-152x152.png">
    <?php wp_head(); ?>
  </head>
