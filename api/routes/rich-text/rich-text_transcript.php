<?php
$route = new Route('/rich-text/(?P<id>\d+)/transcript', 'GET', function($data){
  $transcript = new Transcript($data['id']);
  $sliced = $transcript->get_slices_and_breaks(true);
  if(!$sliced) return [];

  foreach($sliced as &$slice){
    if($slice['start']) $slice['start'] = sanitize_timestamp($slice['start']);
    if($slice['end'])   $slice['end']   = sanitize_timestamp($slice['end']);
  }
  return $sliced;
});
