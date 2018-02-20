<?php
include_once(get_template_directory().'/models/RichText.php');
$route = new Route('/rich-text/(?P<id>\d+)/supp', 'GET', function($data){
  $rich_text = new RichText($data['id']);

  $supp = $rich_text->get_supp_cont();

  if(count($supp)){
    foreach($supp as &$item){
      $item['timestamp'] = $item['timestamp'] ? sanitize_timestamp($item['timestamp']) : $item['timestamp'];
    }
  }

  $vtt_file = get_field('transcript', $data['id']);
  $txt_file = get_posts([
    'post_per_page' => 1,
    'post_type'     => 'attachment',
    'name'          => get_the_title($data['id']).' Transcript (.txt)'
  ]);
  $supp_cont = get_field('supp_cont_file', $data['id']);

  if($vtt_file){
    $vtt_download = [
      'timestamp' => '',
      'type' => 'file',
      'data' => [
        'description' => $vtt_file['description'],
        'file' => $vtt_file['url'],
        'title' => $vtt_file['title']
      ]
    ];
    array_push($supp, $vtt_download);
  }

  if(count($txt_file)){
    $txt_file = $txt_file[0];
    $txt_download = [
      'timestamp' => '',
      'type' => 'file',
      'data' => [
        'description' => $txt_file->post_content,
        'file' => wp_get_attachment_url($txt_file->ID),
        'title' => $txt_file->post_title
      ]
    ];
    array_push($supp, $txt_download);
  }

  if($supp_cont){
    $supp_download = [
      'timestamp' => '',
      'type' => 'file',
      'data' => [
        'description' => $supp_cont['description'],
        'file' => $supp_cont['url'],
        'title' => $supp_cont['title']
      ]
    ];
    array_push($supp, $supp_download);
  }

  return $supp;

});
