<?php
include_once(get_template_directory().'/models/ContentNode.php');
include_once("search-helpers.php");
include_once("query.php");
$search = new Route('/search/(?P<term>.*)/(?P<type>.*)', 'GET', function($data){
  global $wpdb;
  $args = $data->get_query_params();
  $term = str_replace('+', ' ', urldecode($data['term']));

  if($term === 'null') {
    $ignore = true;
    $term = '';
  }

  // Important!
  $flags = [];
  $flaggable = ['whole_word', 'case_sensitive'];
  foreach($flaggable as $flag) {
    if(isset($args[$flag])) $flags[] = $flag;
  }

  $config = [
    'types' => ['post', 'timeline', 'interactive', 'interview'],
    'primary_fields' => ['post_content', 'post_title'],
    'meta_fields' => ['transcript_raw', 'introduction', 'description_raw', 'supporting_content_raw'],
    'meta_like_fields' => ['events_%_content', 'events_%_title'],
    'terms' => ['collection'],
    'term_fields' => ['collection_description'],
  ];

  if($args['collection']) {
    $statement = generate_collection_query($config, $flags);
    $query = $wpdb->prepare(
      $statement,
      $term,
      $term,
      $term,
      $args['collection']
    );
  }
  elseif($data['type'] === 'blog') {
    $config['types'] = ['post', 'interactive'];
    $statement = generate_blog_query($config, $flags);
    $query = $wpdb->prepare(
      $statement,
      $term
    );
  } else {
    $statement = generate_global_query($config, $flags);
    $query = $wpdb->prepare(
      $statement,
      $term,
      $term,
      $term,
      $term,
      $term
    );
  }

  $query = str_replace(["<<'", "'>>"], '', $query);

  $results = $wpdb->get_results($query);

  $count = isset($args['count']) ? $args['count'] : false;
  $offset = isset($args['offset']) ? $args['offset'] : false;

  $total_results = count($results);
  $total_hits = 0;

  $fields = [
    'blog' => [
      'content' => function($id) {
        $content = get_post($id)->post_content;
        $lines = get_lines_from_sentences($content);
        return $lines;
      }
    ],
    'interactive' => [
      'introduction' => function($id) {
        $lines = get_lines_from_sentences(get_field('introduction', $id));
        return $lines;
      },
      'transcript_raw',
    ],
    'interview' => [
      'introduction' => function($id) {
        $lines = get_lines_from_sentences(get_field('introduction', $id));
        return $lines;
      },
      'transcript_raw',
      'description_raw',
      'supporting_content_raw' => function($id) {
        $raw = get_field('supporting_content_raw', $id);
        return $raw;
      }
     ],
    'timeline' => [
      'introduction' => function($id) {
        return get_lines_from_sentences(get_field('introduction', $id));
      },
      'content' => function($id) {
        $nodes = get_field('events', $id);
        $content = '';
        foreach($nodes as $node) {
          $content .= $node['title'] . ' - ' . strip_tags($node['content']);
        }
        return $content;
      }
    ],
    'collection' => [
      'collection_descripton' => function($term_id) {
        return get_lines_from_sentences(get_field('collection_description', 'collection_'.$term_id));
      }
    ]
  ];

  // Loop over all results
  $pattern = in_array('whole_word', $flags) ?
    "/((?:^${term})|(?<=\s)(?:${term}))(?=\s|[[:punct:]])/" :
    "/(${term})/";
  if(!in_array('case_sensitive', $flags)) $pattern .= 'i';

  foreach($results as $result) {
    $item = $result->type === 'term' ?
      new ContentNodeCollection($result->id) :
      new ContentNode($result->id);

    $type = $item->original_type ? $item->original_type : $item->type;

    if(
      $result->type === 'content' &&
      (get_post_status( $result->id ) !== 'publish' ||
      get_post_type( $result->id ) === 'revision')
    ) {
      continue;
    }

    if(!$ignore):
    $hits = [];
    if($fields[$type]) foreach($fields[$type] as $key => $field) {
      if(is_string($field)) {
        $value = clean_vtt(get_field($field, $item->id));
        $timestamp_method = $field;
      } else {
        $value = clean_vtt($field($item->id));
        $timestamp_method = $key;
      }

      $lines = get_matching_lines($value, $term, $timestamp_method, $flags);
      $hits = array_merge($hits, $lines);

      foreach($lines as $line) {
        $hit_count = preg_match_all($pattern, $hit['text']);
        $total_hits = $total_hits + $hit_count;
      }
    }

    $item->hits = $hits;
    $item->title = highlight_term($item->title, $term);
    $item->hit_count = 0;

    if(count($item->hits)) {
      foreach($item->hits as $hit) {
        $item->hit_count = $item->hit_count + preg_match_all("/content-search-result/", $hit['text']);
      }
      $total_hits = $total_hits + $item->hit_count;
    }
  endif;

    $returns['items'][] = $item;
  }

  if($count !== false && $offset !== false){
    $results = array_slice($results, $offset, $count);
  }

  if(!$ignore) {
    $returns['name'] = 'Search for '.$term;
    $returns['total_hits'] = $total_hits;
    $returns['results'] = $total_results;
  }

  if(count($returns['items'])) {
    usort($returns['items'], function($a, $b) {
      return strcmp($a->title, $b->title);
    });
  }

  return $returns;
});
