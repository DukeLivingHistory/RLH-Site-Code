<?php

// add widgets
add_action('widgets_init', function() {
  register_sidebar([
    'id' => 'blog',
    'name' => 'Blog',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
  register_sidebar([
    'id' => 'posts',
    'name' => 'Posts',
    'before_widget' => '<div class="blog-sidebar">',
    'after_widget' => '</div>'
  ]);
});
