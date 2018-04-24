<?php
include_once(get_template_directory().'/models/ContentNode.php');
include_once("search-helpers.php");
$search = new Route('/search/(?P<term>.*)/(?P<type>.*)', 'GET', function($data){
  $args = $data->get_query_params();
  $term = str_replace('+', ' ', urldecode($data['term']));

  if(strlen($term) < 4) {
    return [
      "term" => $term,
      "error" => "Search term too short.",
      "message" => "Try a longer search."
    ];
  }

  function get_collection_arg($args) {
    if(isset($args['collection'])) {
      return [
        [
          'taxonomy' => 'collection',
          'field' => 'term_id',
          'terms' => $args['collection']
        ]
      ];
    }
    else return null;
  }

  $blog_search = array_merge(
    $orig_search = get_posts([
      'post_type' => [ 'post', 'interactive' ],
      'posts_per_page' => -1,
      's' => $term,
    ]),
    get_posts([
      'post_type' => [ 'interactive' ],
      'posts_per_page' => -1,
      'meta_query' => [
        'relation' => 'OR',
        [
          'key' => 'transcript_raw',
          'value' => $term,
          'compare' => 'LIKE'
        ],
      ],
      // Prevent duplicate terms from previous query
      'exclude' => array_reduce($orig_search, function($excluded_posts, $post) {
        $excluded_posts[] = $post->ID;
        return $excluded_posts;
      }, [])
    ])
  );

  if( $data['type'] === 'blog') {
    $results = $blog_search;
  } else {
    $results = array_merge(
      $blog_search,
      $posts_search = get_posts([
        'post_type' => [ 'timeline', 'interview' ],
        'posts_per_page' => -1,
        's' => $term,
        'tax_query' => get_collection_arg($args)
      ]),
      get_posts([
        'post_type' => [ 'timeline', 'interview' ],
        'posts_per_page' => -1,
        'suppress_filters' => false,
        'tax_query' => get_collection_arg($args),
        'meta_query' => [
          'relation' => 'OR',
          [
            'key' => 'transcript_raw',
            'value' => $term,
            'compare' => 'LIKE'
          ],
          [
            'key' => 'description_raw',
            'value' => $term,
            'compare' => 'LIKE'
          ],
          [
            'key' => 'supporting_content_raw',
            'value' => $term,
            'compare' => 'LIKE'
          ],
          [
            'key' => 'events_$_title',
            'value' => $term,
            'compare' => 'LIKE'
          ],
          [
            'key' => 'events_$_content',
            'value' => $term,
            'compare' => 'LIKE'
          ]
        ],
        // Prevent duplicate terms from previous query
        'exclude' => array_reduce($posts_search, function($excluded_posts, $post) {
          $excluded_posts[] = $post->ID;
          return $excluded_posts;
        }, [])
      ]),
      $terms_search = (!isset($args['collection']) ? get_terms([
        'number' => 0,
        'search' => $term,
        'taxonomy' => 'collection',
      ]) : []),
      (!isset($args['collection']) ? get_terms([
        'taxonomy' => 'collection',
        'meta_query' => [
          [
            'key' => 'collection_description',
            'value' => $term,
            'compare' => 'LIKE'
          ]
        ],
        // Prevent duplicate terms from previous search
        'exclude' => array_reduce($terms_search, function($excluded_terms , $term) {
          $excluded_terms[] = $term->term_id;
          return $excluded_terms;
        }, [])
      ]) : [])
    );
  }

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
  foreach($results as $result) {
    if(isset($result->ID)){
      $item = new ContentNode($result->ID);
    } else {
      $item = new ContentNodeCollection($result->term_id);
    }

    $hits = [];

    if($fields[$item->type]) foreach($fields[$item->type] as $key => $field) {
      if(is_string($field)) {
        $value = clean_vtt(get_field($field, $item->id));
        $timestamp_method = $field;
      } else {
        $value = clean_vtt($field($item->id));
        $timestamp_method = $key;
      }
      $lines = get_matching_lines($value, $term, $timestamp_method);
      $hits = array_merge($hits, $lines);

      foreach($lines as $line) {
        $hit_count = preg_match_all("/$term/i", $hit['text']);
        $total_hits = $total_hits + $hit_count;
      }
    }

    $item->hits = $hits;
    $item->title = highlight_term($item->title, $term);
    $item->hit_count = 0;

    if(count($item->hits)) {
      foreach($item->hits as $hit) {
        $item->hit_count = $item->hit_count + preg_match_all("/$term/i", $hit['text']);
      }
      $total_hits = $total_hits + $item->hit_count;
    }

    $returns['items'][] = $item;
  }

  if($count !== false && $offset !== false){
    $results = array_slice($results, $offset, $count);
  }

  $returns['name'] = 'Search for '.$term;
  $returns['total_hits'] = $total_hits;
  $returns['results'] = $total_results;
  return $returns;
});
