<?php
include_once(get_template_directory().'/models/ContentNode.php');
$route = new Route('/rich-text/', 'GET', function($data){
  $args = $data->get_query_params();

  $query_args = [
    'post_type' => 'rich-text',
    'posts_per_page' => isset($args['count']) ? $args['count'] : -1,
    'offset' => isset($args['offset']) ? $args['offset'] : 0,
    'meta_query' => [
      [
        'key' => 'hide',
        'compare' => '!=',
        'value' => 1
      ]
    ],
    'fields' => 'ids'
  ];

  if(isset($args['order'])){
    $key = explode('_', $args['order'])[0];
    $order = explode('_', $args['order'])[1];

    switch($key){
      case 'abc':
        $query_args['meta_key'] = 'abc_term';
        $query_args['orderby'] = 'meta_value';
        break;
      case 'publish':
        $query_args['orderby'] = 'date';
        break;
      default:
        break;
    }

    $query_args['order'] = isset($order) ? $order : 'ASC';
  }

  $rich_texts = get_posts($query_args);

  foreach($rich_texts as $rich_text){
    $returns[] = new ContentNode($rich_text);
  }

  return [
    'items' => $returns,
    // TODO: Add field for this.
    'image' => get_field('rich_texts_content_image', 'options'),
    'name' => 'Rich Text'
  ];

});
