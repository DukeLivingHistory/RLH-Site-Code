<?php
include_once(get_template_directory().'/models/RichText.php');
$route = new Route('/rich-text/(?P<id>\d+)', 'GET', function($data){
  $rich_text = new RichText($data['id']);

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

  return $rich_text;
});
