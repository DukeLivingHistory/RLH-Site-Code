<?php
include_once(get_template_directory().'/models/Interactive.php');
$route = new Route('/interactives/(?P<id>\d+)', 'GET', function($data){
  $rich_text = new Interactive($data['id']);

  if($rich_text->collections){
    $i = 0;
    foreach($rich_text->collections as $collection){
      $collections_formatted[$i]['id'] = $collection;
      $collections_formatted[$i]['type'] = 'collection';
      $collections_formatted[$i]['link'] = get_term_link($collection);
      $collections_formatted[$i++]['link_text'] = get_term($collection)->name;
    }
    $rich_text->collections = $collections_formatted;
  }
  $author = get_post_field('post_author', $data['id']);
  if($author){
    $rich_text->author = array(
      'name' => get_author_name($author),
      'link' => get_author_posts_url($author),
      'avatar' => get_avatar($author),
      'bio' => get_the_author_meta('user_description', $author),
    );
  }

  $rich_text->show_menu = get_field('show_menu', $data['id']);

  return $rich_text;
});
