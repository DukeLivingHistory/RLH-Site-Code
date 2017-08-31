<?php
$route = new Route('/interviews/(?P<id>\d+)/description', 'GET', function($data){
  return [
    'description' => get_field('description_raw', $data['id'])
  ];
});
