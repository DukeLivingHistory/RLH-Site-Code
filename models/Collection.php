<?php

class Collection {
  function __construct( $collection_id ){
    $this->id = $collection_id;
    $this->name = get_term( $collection_id )->name;
    $this->image = get_field( 'collection_img', 'collection_'.$collection_id );
    $this->description = get_field( 'collection_description', 'collection_'.$collection_id );
    $this->link = get_bloginfo('url').'/collections/'.get_term( $collection_id )->slug;
  }

  public function get_content_by_type( $type, $search, $count, $not = []){
    $results = array_merge(
      // Posts with search term accessible by regular WordPress Search
      // Cache value so it can be used to exclude other posts (prevent duplicates)
      $search_posts = get_posts([
        'post_type' => $type,
        'posts_per_page' => $count,
        'tax_query' => [
          [
            'taxonomy' => 'collection',
            'field' => 'term_id',
            'terms' => $this->id
          ]
        ],
        's' => $search,
        'post__not_in' => [$not]
      ]),
      // Posts with search term in meta fields
      get_posts([
        'post_type' => $type,
        'posts_per_page' => $count,
        'tax_query' => [
          [
            'taxonomy' => 'collection',
            'field' => 'term_id',
            'terms' => $this->id
          ]
        ],
        'meta_query' => [
          'relation' => 'OR',
          [
            'key'     => 'transcript_raw',
            'value'   => $search,
            'compare' => 'LIKE'
          ],
          [
            'key'     => 'description_raw',
            'value'   => $search,
            'compare' => 'LIKE'
          ],
          [
            'key'     => 'supporting_content_raw',
            'value'   => $search,
            'compare' => 'LIKE'
          ]
        ],
        'post__not_in' => array_merge([$not], array_reduce($search_posts, function($excluded_posts = [], $post) {
          $excluded_posts[] = $post->ID;
          return $excluded_posts;
        }, []))
      ])
    );


    if($count > 0) {
      $results = array_slice($results, 0, $count);
    }

    foreach($results as $result){
      $result_item = [
        'id' => $result->ID,
        'link'=> get_permalink( $result->ID ),
        'type' => get_post_type( $result->ID ),
        'title' => $result->post_title,
        'img' => get_post_thumbnail_id( $result->ID ),
        'excerpt' => $result->post_excerpt
      ];
      $results_array[] = $result_item;
    }

    return isset( $results_array ) ? $results_array : false;
  }
}
