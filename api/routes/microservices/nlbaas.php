<?php
// Negative Lookbehind as as service
$route = new Route('/microservices/nlbaas/', 'POST', function($data){
  $params = $data->get_json_params();
  $pattern = $params['pattern'];
  $target = $params['text'];
  preg_match_all("/{$pattern}/s", $target, $matches);
  return $matches[0];
});
