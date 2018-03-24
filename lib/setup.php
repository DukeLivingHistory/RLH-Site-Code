<?php

// add widgets
add_action('widgets_init', function() {
  register_sidebar([
    'name' => 'Blog',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
  register_sidebar([
    'name' => 'Posts',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
});
