<?php

class Collection {
  function __construct( $collection_id ){
    $this->id = $collection_id;
    $this->name = get_term( $collection_id )->name;
    $this->image = get_field( 'collection_img', 'collection_'.$collection_id );
    $this->description = get_field( 'collection_description', 'collection_'.$collection_id );
    $this->link = get_bloginfo('url').'/collections/'.get_term( $collection_id )->slug;
  }

  public function get_content_by_type( $type, $search, $count, $not ){
    $results = get_posts( [
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
      'post__not_in' => [ $not ]
    ] );

    foreach( $results as $result ){
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
