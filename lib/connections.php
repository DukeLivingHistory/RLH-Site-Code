<?php
/*
 * This file registers P2P relations used by the site.
 */

add_action('p2p_init', function(){
  p2p_register_connection_type([
      'title'       => 'Related Content (Two-way)',
      'name'        => 'content_bi',
      'from'        => [ 'interview', 'timeline'],
      'to'          => [ 'interview', 'timeline'],
      'reciprocal'  => true
  ]);

  p2p_register_connection_type([
      'title'       => 'Related Content One-way',
      'name'        => 'content_uni',
      'from'        => [ 'interview', 'timeline'],
      'to'          => [ 'interview', 'timeline', 'post' ],
      'reciprocal'  => false
  ]);

});

add_action('admin_head', function(){ ?>
  <style>
    #p2p-to-content_uni {
      display: none;
    }
  </style>
<?php });
