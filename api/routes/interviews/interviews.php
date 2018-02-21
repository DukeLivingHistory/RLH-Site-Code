<?php
include_once(get_template_directory().'/models/ContentNode.php');
$route = new Route('/interviews/', 'GET', function($data){
  $args = $data->get_query_params();

  $query_args = [
    'post_type' => 'interview',
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

  if(isset($args['include'])) {
    switch($args['include']) {
      case 'media':
        $query_args['post_type'] = ['interview'];
        break;
      case 'no-media':
        $query_args['post_type'] = ['rich-text']; // TODO: Rename
        break;
      default:
        $query_args['post_type'] = ['interview', 'rich-text']; // TODO: Rename
    }
  }

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
      case 'date':
        $query_args['meta_key'] = 'interview_date';
        $query_args['meta_type'] = 'DATETYPE';
        $query_args['orderby'] = 'meta_value';
        break;
      default:
        break;
    }

    $query_args['order'] = isset($order) ? $order : 'ASC';
  }

  $interviews = get_posts($query_args);

  foreach($interviews as $interview){
    $returns[] = new ContentNode($interview);
  }

  return [
    'items' => $returns,
    'image' => get_field('interviews_content_image', 'options'),
    'name' => 'Interviews'
  ];

});
